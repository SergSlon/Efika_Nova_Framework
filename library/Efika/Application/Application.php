<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

use Efika\Common\Logger;
use Efika\Common\SingletonTrait;
use Efika\Config\Config;
use Efika\Di\DiContainer;
use Efika\EventManager\EventManagerTrait;
use Efika\EventManager\EventResponse;
use Efika\EventManager\EventResponseInterface;
use InvalidArgumentException;

/**
 * Class Application
 * @package Efika\Application
 */
class Application implements ApplicationInterface
{

    use EventManagerTrait;

    use SingletonTrait {
        SingletonTrait::getInstance as TRAITgetInstance;
    }

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
    private $config = [];

    /**
     * @var array
     */
    private $applicationConfig = [];

    /**
     * @return Application
     */
    public static function getInstance()
    {
        return self::TRAITgetInstance();
    }

    /**
     *
     */
    private function __construct()
    {
        $this->setLogger(Logger::getInstance()->scope(self::OBJECT_ID));
        $this->setDefaultEventClass('Efika\Application\ApplicationEvent');
        $di = DiContainer::getInstance();
        $eventObject = $di->getClassAsService('Efika\Application\ApplicationEvent')->applyInstance();
        $this->setEventObject($eventObject);

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
     * @param $requiredStatus
     * @param $nextStatus
     * @param callable $taskCallback
     * @throws ApplicationException
     * @return $this
     */
    protected function executeApplicationTask($requiredStatus, $nextStatus, $taskCallback)
    {
        if ($this->getStatus() == $requiredStatus) {

            $taskCallback();

        } else {
            throw new ApplicationException(sprintf('Unexpected Status "%s"! Status "%s" required!', $requiredStatus, $this->getStatus()));
        }

        $this->setStatus($nextStatus);
        return $this;
    }

    /**
     * @param $id
     * @param $arguments
     * @param $callback
     */
    public function executeApplicationEvent($id, $arguments, $callback)
    {

        $previousEventResponse = $this->getPreviousEventResponse();
        $eventObject = $previousEventResponse !== null && $previousEventResponse instanceof EventResponseInterface ? $previousEventResponse->getEvent() : $this->getEventObject();

        $eventObject->setName($id);
        $eventObject->setTarget($this);
        $eventObject->setArguments($arguments);

        $this->setPreviousEventResponse($this->triggerEvent($eventObject, $callback));
    }


    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \Efika\Common\Logger $logger
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * init config
     * @throws ApplicationException
     * @return $this
     */
    public function configure()
    {
        return $this->executeApplicationTask(self::STATUS_FRESH, self::STATUS_CONFIGURED, function() {
            $this->getLogger()->info('configure application');
            $config = $this->getConfig();

            if ($config->offsetExists('events')) {
                $this->attachEventHandler($config->offsetGet('events')->toArray());
            }
        });
    }

    /**
     * @param callable $eventCallback
     * @throws ApplicationException
     * @internal param array $args
     * @return $this
     */
    public function init(callable $eventCallback)
    {
        return $this->executeApplicationTask(self::STATUS_CONFIGURED, self::STATUS_INITIALIZED, function() use($eventCallback) {
            $this->getLogger()->info('initialize application');
            $this->getLogger()->info('execute initialize events');

            $this->executeApplicationEvent(self::ON_INIT, ['config' => $this->getConfig()], $eventCallback);
        });
    }

    /**
     * @param callable $eventCallback
     * @throws ApplicationException
     * @internal param callable $callback
     * @return $this
     */
    public function route(callable $eventCallback)
    {

        return $this->executeApplicationTask(self::STATUS_INITIALIZED, self::STATUS_ROUTED, function() use($eventCallback) {
            $this->getLogger()->info('route application');
            $this->executeApplicationEvent(self::ON_ROUTE, [], $eventCallback);
        });

    }

    /**
     * @param callable $eventCallback
     * @throws ApplicationException
     * @return $this
     */
    public function dispatch(callable $eventCallback)
    {

        return $this->executeApplicationTask(self::STATUS_ROUTED, self::STATUS_DISPATCHED, function() use($eventCallback) {
            $this->getLogger()->info('dispatch application');
            $this->executeApplicationEvent(self::ON_DISPATCH, [], $eventCallback);
        });

    }

    /**
     * triggers application.complete event handler
     * @param callable $eventCallback
     * @throws ApplicationException
     * @return $this
     */
    public function complete(callable $eventCallback)
    {

        return $this->executeApplicationTask(self::STATUS_DISPATCHED, self::STATUS_COMPLETED, function() use($eventCallback) {
            $this->getLogger()->info('complete application');
            $this->executeApplicationEvent(self::ON_COMPLETE, [], $eventCallback);
        });
    }

    /**
     *
     */
    public function execute()
    {
        $executionResult = false;

        $this->getLogger()->info('start application execution');

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
                $this->route($callback);
                $this->dispatch($callback);
                $this->complete($callback);

                $this->getLogger()->info('finalize application execution');
                $executionResult = true;
            } else {
                $this->getLogger()->info('abort application execution');
            }

        } catch (ApplicationException $e) {
            $this->getLogger()->info('Exception: ' . $e->getMessage());
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
     * @return Config
     */
    public function getApplicationConfig()
    {
        return $this->applicationConfig;
    }

    /**
     * @param Config $appConfig
     */
    protected function setApplicationConfig($appConfig)
    {
        $this->applicationConfig = $appConfig;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (!($this->config instanceof Config)) {
            $this->setConfig();
        }
        return $this->config;
    }

    /**
     * @param null $config
     * @return mixed|void
     */
    public function setConfig(array $config = [])
    {
        if (!($config instanceof Config)) {
            $this->config = new Config($config);
        } else {
            $this->config = $config;
        }
    }
}
