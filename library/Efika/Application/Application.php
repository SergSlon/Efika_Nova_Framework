<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

use Efika\Common\Logger;
use Efika\Common\SingletonTrait;
use Efika\EventManager\EventInterface;
use Efika\EventManager\EventManagerTrait;
use Efika\EventManager\EventResponse;
use Efika\EventManager\Exception;
use Exception as PhpException;
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
     *
     */
    private function __construct()
    {
        $this->setLogger(Logger::getInstance()->scope(self::LOGGER_SCOPE));
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
    public function getEventObjects()
    {
        return $this->eventObjects;
    }

    /**
     * @param $event
     * @return bool
     */
    public function hasEventObject($event)
    {
        return array_key_exists($event, $this->getEventObjects());
    }

    /**
     * init config
     * @param string $config
     * @return $this
     * @throws \Exception
     */
    public function configure($config)
    {
        if($this->getStatus() == self::STATUS_FRESH){
            $this->getLogger()->addMessage('configure application');

        }else{
            throw new PhpException('Status is not fresh');
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

//    /**
//     * trigger events
//     * @param null | callable $callback
//     * @return mixed
//     */
//    public function execute($callback = null)
//    {
//        if (!$this->getIsExecuted()) {
//
//            /**
//             * @var EventResponse
//             */
//            $previousEventResponse = new EventResponse();
//            $application = $this;
//
//            if ($callback === null || !is_callable($callback)) {
//                //stop propagantion when error occurs
//                /**
//                 * @param EventResponse $response
//                 * @return bool
//                 */
//                $callback = function ($response) use ($application) {
//                    return !($response->hasEvent() && !array_key_exists('errors', $response->getEvent()->getArguments()));
//                };
//            }
//
//            foreach ($this->getEventHandlers() as $event => $handlers) {
//
//                $eventObject = $this->getEventObject($event);
//
//                if ($previousEventResponse !== null && $previousEventResponse->hasEvent()) {
//                    $args = $previousEventResponse->getEvent()->getArguments();
//                } else {
//                    $args = null;
//                }
//
//                $eventObject->setName($event);
//                $eventObject->setTarget($this);
//                $eventObject->setArguments($args);
//
//                $previousEventResponse = $this->triggerEvent($eventObject, $args, $callback);
//            }
//
//            $this->executed();
//            Logger::getInstance()->scope(self::LOGGER_SCOPE)->addMessage('application successful executed');
//            return true;
//        }
//
//        Logger::getInstance()->scope(self::LOGGER_SCOPE)->addMessage('application unsuccessful executed');
//        return false;
//    }

    /**
     * @param callable $callback
     * @throws \Exception
     * @internal param array $args
     * @return $this
     */
    public function init(callable $callback)
    {
        if($this->getStatus() == self::STATUS_CONFIGURED){
            $this->getLogger()->addMessage('initialize application');
            $this->getLogger()->addMessage('execute initialize events');

            $event = self::ON_INIT;
            $eventObject = $this->getEventObject($event);

            $eventObject->setName($event);
            $eventObject->setTarget($this);
            $eventObject->setArguments($this->getApplicationConfig());

            $this->setPreviousEventResponse($this->triggerEvent(self::ON_INIT,$callback));

        }else{
            throw new PhpException('Status is not configured');
        }

        $this->setStatus(self::STATUS_INITIALIZED);
        return $this;
    }

    /**
     * @param callable $callback
     * @throws \Exception
     * @return $this
     */
    public function process(callable $callback)
    {
        if($this->getStatus() == self::STATUS_INITIALIZED){

            $this->getLogger()->addMessage('process application');
        }else{
            throw new PhpException('Status is not initialized');
        }

        $this->setStatus(self::STATUS_PROCESSED);
        return $this;
    }

    /**
     * @param callable $callback
     * @throws \Exception
     * @return $this
     */
    public function complete(callable $callback)
    {
        if($this->getStatus() == self::STATUS_PROCESSED){

            $this->getLogger()->addMessage('complete application');
        }else{
            throw new PhpException('Status is not processed');
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
                $this->getLogger()->addMessage('abord application execution');
            }

        } catch (PhpException $e) {
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
     * @return null
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
