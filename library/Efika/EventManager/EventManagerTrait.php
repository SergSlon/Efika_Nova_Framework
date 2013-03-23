<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * manage events in a single object
 * <br />
 * Example Usage:
 * <br />
 * <code>
 *      class Object {
 *          use Efika\EventManager\EventManagerTrait;
 *
 *          public function execute(){
 *              $this->triggerEvent('onExecute');
 *          }
 *      }
 *
 *      $eventObject = new Object();
 *      $eventObject->attach('onExecute',
 *          function(){
 *              echo 'Start Execution';
 *          }
 *      )
 * </code>
 */

/**
 * This trait provide reusable event manager. this event manager is based on observer pattern
 */
trait EventManagerTrait
{

    /**
     * Handler array
     * @var array
     */
    protected $eventHandlers = [];

    /**
     * Default event class
     * @var string
     */
    protected $defaultEventClass = 'Efika\EventManager\Event';

    /**
     * Default Response class
     * @var string
     */
    protected $defaultEventResponseClass = 'Efika\EventManager\EventResponse';
    /**
     * event class object
     * @var null|object
     */
    protected $eventObject = null;

    /**
     * Response object
     * @var string
     */
    protected $eventResponseObject = null;

    /**
     * @var array
     */
    protected $defaultEvents = [];

    /**
     * Attach an handler to an event or attach an event aggregate
     * @param string|\Efika\EventManager\EventHandlerAggregateInterface $event
     * @param $callback
     * @return EventManagerTrait
     */
    public function attachEventHandler($event, $callback)
    {
        if ($event instanceof EventHandlerAggregateInterface) {
            return $this->attachEventHandlerAggregate($event);
        }

        if(!($callback instanceof EventHandlerCallback))
            $callback = new EventHandlerCallback($callback);

        if (is_array($event)) {
            foreach ($event as $name) {
                $this->attachEventHandler($name, $callback);
            }
        } else {
            $this->eventHandlers[$event][] = $callback;
        }

        return $this;
    }

    /**
     * Attach an event handler aggregate
     * @param \Efika\EventManager\EventHandlerAggregateInterface $aggregate
     * @return mixed
     */
    public function attachEventHandlerAggregate(EventHandlerAggregateInterface $aggregate)
    {
        return $aggregate->attach($this);
    }

    /**
     * Detach an event handler.
     * @param $event
     * @return EventManagerTrait
     */
    public function detachEventHandler($event)
    {
        if (!is_array($event))
            $event = [$event];
        $handlers = [];
        foreach ($this->eventHandlers as $handler => $callback) {
            if (!in_array($handler, $event))
                $handlers[$handler] = new EventHandlerCallback($callback);
        }

        return $this;
    }

    /**
     * Trigger an event.
     * @param string|EventInterface $event
     * @param array $args an array with arguments which will passed to event class
     * @param null|callable $callback
     * @return \Efika\EventManager\EventResponse
     * @throws \Efika\EventManager\Exception
     */
    public function triggerEvent($event, $args=[], callable $callback = null)
    {
        if (is_string($event)) {
            $eventName = $event;
            $event = $this->getEventObject()
            ->setName($eventName)
            ->setTarget($this)
            ->setArguments($args);
        }else if($event instanceof EventInterface){
            if(is_null($event->getTarget()))
                $event->setTarget($this);

            if(is_null($event->getName()) || !is_string($event->getName())){
                throw new Exception('Event must be not null and a valid string!');
            }
        }else{
            throw new Exception('Event must be a string or instance of Efika\EventManager\EventInterface');
        }

        return $this->triggerHandlers($event,$callback);
    }

    /**
     * Trigger handlers of given event
     * @param EventInterface $e
     * @param $callback
     * @return mixed
     */
    protected function triggerHandlers(EventInterface $e, $callback)
    {

        $responses = $this->getEventResponseObject();
        $responses->setEvent($e);

        if($this->hasEventHandler($e->getName())){

            foreach($this->getEventHandler($e->getName()) as $handler){
                if($handler instanceof EventHandlerCallback){
                    $responses->push($handler->execute($e));
                }

                if(
                    $e->isPropagationStopped() ||
                    (
                        $callback &&
                        call_user_func($callback, $responses)
                    )
                ){
                    $responses->stop(true);
                    break;
                }
            }
        }

        return $responses;

    }

    /**
     * Set an instance of EventResponseInterface
     * @param EventResponseInterface $object
     * @return \Efika\EventManager\EventManagerTrait
     */
    public function setEventResponseObject(EventResponseInterface $object)
    {
        $this->eventResponseObject = $object;
        return $this;
    }

    /**
     * Return an instance of EventResponseInterface
     * @return EventResponseInterface
     */
    public function getEventResponseObject()
    {
        if(!is_object($this->eventResponseObject)){
            $class = $this->defaultEventResponseClass;
            $this->eventResponseObject = new $class;
        }
        return $this->eventResponseObject;
    }


    /**
     * Set an instance of EventInterface
     * @param EventInterface $object
     * @return \Efika\EventManager\EventManagerTrait
     */
    public function setEventObject(EventInterface $object)
    {
        $this->eventObject = $object;
        return $this;
    }

    /**
     * Return an instance of EventInterface
     * @return EventInterface
     */
    public function getEventObject()
    {
        if(!is_object($this->eventObject)){
            $class = $this->defaultEventClass;
            $this->eventObject = new $class;
        }
        return $this->eventObject;
    }

    /**
     * Return an event handler of an event
     * @param $event
     * @return mixed
     * @throws Exception
     */
    public function getEventHandler($event)
    {
        if(!$this->hasEventHandler($event))
            throw new Exception('Unknown EventHandler: ' . $event);
        return $this->eventHandlers[$event];
    }

    /**
     * Check if event has handler
     * @param $event
     * @return bool
     */
    public function hasEventHandler($event){
        return array_key_exists($event,$this->eventHandlers);
    }

    /**
     * Return all event handlers
     * @return array
     */
    public function getEventHandlers()
    {
        return $this->eventHandlers;
    }

    /**
     * @param string $defaultEventResponseClass
     */
    public function setDefaultEventResponseClass($defaultEventResponseClass)
    {
        $this->defaultEventResponseClass = $defaultEventResponseClass;
    }

    /**
     * @return string
     */
    protected function getDefaultEventResponseClass()
    {
        return $this->defaultEventResponseClass;
    }

    /**
     * @param string $defaultEventClass
     */
    protected function setDefaultEventClass($defaultEventClass)
    {
        $this->defaultEventClass = $defaultEventClass;
    }

    /**
     * @return string
     */
    public function getDefaultEventClass()
    {
        return $this->defaultEventClass;
    }

    /**
     * @param array $defaultEvents
     */
    protected function setDefaultEvents($defaultEvents)
    {
        $this->defaultEvents = $defaultEvents;
    }

    /**
     * @return array
     */
    public function getDefaultEvents()
    {
        return $this->defaultEvents;
    }

}
