<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

use Efika\Common\Logger;
use Efika\Common\SingletonTrait;
use Efika\EventManager\EventManagerTrait;
use Efika\EventManager\EventResponse;
use InvalidArgumentException;

class Application implements ApplicationInterface
{

    use EventManagerTrait;
    use SingletonTrait;

    /**
     * @var int
     */
    private $status = self::STATUS_FRESH;

    /**
     * @var array
     */
    private $services = [];

    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @var EventResponse
     */
    private $previousEventResponse = null;

    /**
     * @var array
     */
    private $applicationConfig = [];

    /**
     * @var array
     */
    private $customEventObjects = [];

    /**
     *
     */
    private function __construct()
    {
        $this->setLogger(Logger::getInstance()->scope(self::LOGGER_SCOPE));
        $this->setDefaultEventClass('Efika\Application\ApplicationEvent');
    }

    /**
     * @param $id
     * @param \Efika\Application\ApplicationServiceInterface $instance
     * @param array $attributes
     * @return bool
     */
    public function registerService($id, ApplicationServiceInterface $instance, $attributes = [])
    {

        if (!array_key_exists($id, $this->getServices())) {
            $instance->register($this, $attributes);
            $this->services[$id] = $instance;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function connectService($id)
    {
        if (array_key_exists($id, $this->getServices())) {

            /**
             * @var ApplicationServiceInterface
             */
            $service = $this->services[$id];
            $service->connect();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return array
     */
    public function getCustomEventObjects()
    {
        return $this->customEventObjects;
    }

    /**
     * @param $event
     * @return bool
     */
    public function hasCustomEventObject($event)
    {
        return array_key_exists($event, $this->getCustomEventObjects());
    }

    /**
     * @param $event
     * @param \Efika\Application\ApplicationEvent $object
     * @internal param string $handler
     * @return mixed
     */
    public function addCustomEventObject($event, ApplicationEvent $object)
    {
        $this->customEventObjects[$event] = $object;
    }

    /**
     * @param $id
     * @param $arguments
     * @param $callback
     */
    public function executeApplicationEvent($id, $arguments, $callback){

        $default = $this->getDefaultEventClass();
        $eventObject = $this->hasCustomEventObject($id) ? $this->customEventObjects[$id] : new $default;

        $eventObject->setName($id);
        $eventObject->setTarget($this);
        $eventObject->setArguments($arguments);

        $this->setPreviousEventResponse($this->triggerEvent($eventObject,$callback));
    }

    /**
     * init config
     * @param string $config
     * @throws ApplicationException
     * @return $this
     */
    public function configure($config)
    {
        if($this->getStatus() == self::STATUS_FRESH){
            $this->getLogger()->addMessage('configure application');

        }else{
            throw new ApplicationException('Status is not fresh');
        }

        $this->setStatus(self::STATUS_CONFIGURED);
        return $this;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param callable $callback
     * @throws ApplicationException
     * @internal param array $args
     * @return $this
     */
    public function init(callable $callback)
    {
        if($this->getStatus() == self::STATUS_CONFIGURED){
            $this->getLogger()->addMessage('initialize application');
            $this->getLogger()->addMessage('execute initialize events');

            $this->executeApplicationEvent(self::ON_INIT,$this->getApplicationConfig(),$callback);

        }else{
            throw new ApplicationException('Status is not configured');
        }

        $this->setStatus(self::STATUS_INITIALIZED);
        return $this;
    }

    /**
     * @param callable $callback
     * @throws ApplicationException
     * @return $this
     */
    public function process(callable $callback)
    {
        if($this->getStatus() == self::STATUS_INITIALIZED){

            $this->getLogger()->addMessage('post-process application');
            $this->executeApplicationEvent(self::ON_PREPROCESS,[],$callback);

            $this->getLogger()->addMessage('process application');
            $this->executeApplicationEvent(self::ON_PROCESS,[],$callback);

            $this->getLogger()->addMessage('pre-process application');
            $this->executeApplicationEvent(self::ON_POSTPROCESS,[],$callback);
        }else{
            throw new ApplicationException('Status is not initialized');
        }

        $this->setStatus(self::STATUS_PROCESSED);
        return $this;
    }

    /**
     * triggers application.complete event handler
     * @param callable $callback
     * @throws ApplicationException
     * @return $this
     */
    public function complete(callable $callback)
    {
        if($this->getStatus() == self::STATUS_PROCESSED){

            $this->getLogger()->addMessage('complete application');
            $this->executeApplicationEvent(self::ON_COMPLETE,[],$callback);
        }else{
            throw new ApplicationException('Status is not processed');
        }

        $this->setStatus(self::STATUS_COMPLETED);
        return $this;
    }

    /**
     *
     */
    public function execute()
    {
        $executionResult = false;

        $this->getLogger()->addMessage('start application execution');

        try {
            if ($this->getStatus() == self::STATUS_CONFIGURED) {
                $application = $this;

                /**
                 * stop propagantion when error occurs
                 * @param EventResponse $response
                 * @return bool
                 */
                $callback = function ($response) use ($application) {
                    return !($response->hasEvent() && !array_key_exists('errors', $response->getEvent()->getArguments()));
                };

                $this->init($callback);
                $this->process($callback);
                $this->complete($callback);

                $this->getLogger()->addMessage('finalize application execution');
                $executionResult = true;
            } else {
                $this->getLogger()->addMessage('abort application execution');
            }

        } catch (ApplicationException $e) {
            $this->getLogger()->addMessage('Exception: ' . $e->getMessage());
        }

        return $executionResult;


    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @throws \InvalidArgumentException
     */
    protected function setStatus($status)
    {
        if (!$this->isValidStatus($status)) {
            throw new InvalidArgumentException('Invalid status');
        }
        $this->status = $status;
    }

    /**
     * @param $status
     * @return bool
     */
    public function isValidStatus($status)
    {
        return
            $status % 2 === 0 &&
            $status > $this->getStatus() &&
            $status <= self::STATUS_COMPLETED;
    }

    /**
     * @return EventResponse
     */
    public function getPreviousEventResponse()
    {
        return $this->previousEventResponse;
    }

    /**
     * @param $previousEventResponse
     */
    protected function setPreviousEventResponse($previousEventResponse)
    {
        $this->previousEventResponse = $previousEventResponse;
    }

    /**
     * @return null
     */
    public function getApplicationConfig()
    {
        return $this->applicationConfig;
    }

    /**
     * @param $appConfig
     */
    protected function setApplicationConfig($appConfig)
    {
        $this->applicationConfig = $appConfig;
    }
}
