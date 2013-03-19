<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

use Efika\Common\SingletonTrait;
use Efika\EventManager\EventInterface;
use Efika\EventManager\EventManagerTrait;

class Application implements ApplicationInterface
{

    use EventManagerTrait;
    use SingletonTrait;

    /**
     * @var bool
     */
    private $isConfigured = false;
    /**
     * @var bool
     */
    private $isExecuted = false;

    /**
     * @var array
     */
    private $eventObjects = [];

    /**
     * @var array
     */
    private $services = [];

    /**
     * @var callable
     */
    private $executeCallback = null;

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

    public function connectService($id){
        if (array_key_exists($id, $this->getServices())) {
            $this->services[$id]->connect();
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
     * @param $executeCallback
     */
    public function setExecuteCallback($executeCallback)
    {
        $this->executeCallback = $executeCallback;
    }

    /**
     * @return null
     */
    public function getExecuteCallback()
    {
        return $this->executeCallback;
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
     * @param $event
     * @param \Efika\EventManager\EventInterface $object
     * @internal param string $handler
     * @return mixed
     */
    public function setEventObject($event, EventInterface $object)
    {
        $this->eventObjects[$event] = $object;
    }

    /**
     * @param $event
     * @return \Efika\EventManager\EventInterface
     */
    public function getEventObject($event)
    {
        $default = $this->getDefaultEventClass();
        return $this->hasEventObject($event) ? $this->eventObjects[$event] : new $default;
    }


    /**
     * init config
     * @param $config
     */
    public function configure($config)
    {
        if (!$this->getIsConfigured()) {
//            foreach ($config as $type => $data) {
//                if ($type == 'events') {
//                    print_r($data);
//                    $this->attachEventHandler($data, null);
//                }
//            }


            $this->configured();
        }
    }

    /**
     * @return bool
     */
    public function getIsConfigured()
    {
        return $this->isConfigured;
    }

    /**
     * @return bool
     */
    public function getIsExecuted()
    {
        return $this->isExecuted;
    }

    /**
     *
     */
    protected function configured()
    {
        $this->isConfigured = true;
    }

    /**
     *
     */
    protected function executed()
    {
        $this->isExecuted = true;
    }

    /**
     * trigger events
     * @param null | callable $callback
     * @return mixed
     */
    public function execute($callback = null)
    {
        if (!$this->getIsExecuted()) {

            /**
             * @var EventResponse
             */
            $previousEventResponse = null;
            $application = $this;

            var_dump(__FILE__ . __LINE__);
            var_dump($callback === null || !is_callable($callback));
            var_dump($callback === null);
            var_dump(!is_callable($callback));

//            if ($callback === null || !is_callable($callback)) {
//                //stop propagantion when error occurs
//                $callback = function ($response) use ($application) {
//                    $condition = !($response->hasEvent() && !array_key_exists('errors', $response->getEvent()->getArguments()));
//                    var_dump(__FILE__ . __LINE__);
//                    var_dump(!$condition);
//
//                };
//            }
//
            //Bug: Callback will executed without any arguments
            $callback = function () use ($application) {
                var_dump(func_get_args());
                return false;
            };

            foreach ($this->getEventHandlers() as $event => $handlers) {

                $eventObject = $this->getEventObject($event);

                if ($previousEventResponse == null) {
                    $args = null;
                } else {
                    $args = $previousEventResponse->getEvent()->getArguments();
                }

                $eventObject->setName($event);
                $eventObject->setTarget($this);
                $eventObject->setArguments($args);

                $previousEventResponse = $this->triggerEvent($eventObject, $args, $callback);
            }

            $this->executed();
            var_dump(__FILE__ . __LINE__);
            return true;
        }

        return false;
    }
}
