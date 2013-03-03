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

    private $isConstructed = false;
    private $isExecuted = false;

    private $eventTriggers = [
        'onInit' => '\Efika\EventManager\Event',
        'onPreProcess' => '\Efika\EventManager\Event',
        'onProcess' => '\Efika\EventManager\Event',
        'onPostProcess' => '\Efika\EventManager\Event',
        'onComplete' => '\Efika\EventManager\Event'
    ];

    public function getEventTriggers()
    {
        return $this->eventTriggers;
    }

    /**
     * init config
     * @param $config
     */
    public function construct($config)
    {
        if (!$this->getIsConstructed()) {

            $this->constructed();
        }
    }

    public function getIsConstructed()
    {
        return $this->isConstructed;
    }

    public function getIsExecuted()
    {
        return $this->isExecuted;
    }

    protected function constructed()
    {
        $this->isConstructed = true;
    }

    protected function executed()
    {
        $this->isExecuted = true;
    }

    /**
     * @param string $handler
     * @param \Efika\EventManager\EventInterface $object
     * @return mixed
     */
    public function setEventTrigger($handler, \Efika\EventManager\EventInterface $object)
    {
        $this->eventTriggers[$handler] = $object;
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

            foreach ($this->getEventTriggers() as $event => $eventObject) {

                if(!is_object($eventObject))
                    $eventObject = new $eventObject;

                if ($previousEventResponse == null) {
                    $args = null;
                }else{
                    $args = $previousEventResponse->getEvent()->getArguments();
                }

                $eventObject->setName($event);
                $eventObject->setTarget($this);
                $eventObject->setArguments($args);

                $previousEventResponse = $this->triggerEvent($eventObject,[],$callback);
            }

            $this->executed();
        }
    }
}
