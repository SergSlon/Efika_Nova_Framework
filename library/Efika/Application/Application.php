<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

class Application implements ApplicationInterface
{

    use \Efika\EventManager\EventManagerTrait;
    use \Efika\Common\SingletonTrait;

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
     * @return array
     */
    public function getEventObjects()
    {
        return $this->eventObjects;
    }

    public function hasEventObject($event){
        return array_key_exists($event, $this->getEventObjects());
    }

    /**
     * @param string $handler
     * @param \Efika\EventManager\EventInterface $object
     * @return mixed
     */
    public function setEventObject($event, \Efika\EventManager\EventInterface $object)
    {
        $this->eventObjects[$event] = $object;
    }

    /**
     * @param $event
     * @return \Efika\EventManager\EventInterface
     */
    public function getEventObject($event)
    {
        return $this->hasEventObject($event) ? $this->eventObjects[$event] : new $this->getDefaultEventClass();
    }


    /**
     * init config
     * @param $config
     */
    public function configure($config)
    {
        if (!$this->getIsConfigured()) {

            foreach($this->getEventObjects() as $event => $object){
                $this->attachEventHandler($event,function(){});
            }

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
     * @return mixed
     */
    public function execute()
    {
        if (!$this->getIsExecuted()) {
            $previousEventResponse = null;
            $application = $this;

            //stop propagantion when error occurs
            $callback = function($response) use ($application) {
                return false;
            };

            foreach ($this->getEventHandlers() as $event => $handlers) {

                $eventObject = $this->getEventObject($event);

                if ($previousEventResponse == null) {
                    $args = null;
                }else{
                    $args = $previousEventResponse->getEvent()->getArguments();
                }

                $eventObject->setName($event);
                $eventObject->setTarget($this);
                $eventObject->setArguments($args);

                $previousEventResponse = $this->triggerEvent($event,$args);
            }

//            var_dump($this->getEventHandlers());

            $this->executed();

            return true;
        }

        return false;
    }
}
