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
 * @TODO inject real EventResponse and Event class
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
    protected $eventClass = 'Efika\EventManager\Event';

    /**
     * Default Response class
     * @var string
     */
    protected $responseClass = 'Efika\EventManager\EventResponse';

    /**
     * Attach an handler to an event or attach an event aggregate
     * @param string|\Efika\EventManager\EventHandlerAggregateInterface $event
     * @param $callback
     * @return mixed
     */
    public function attachEventHandler($event, $callback)
    {
        if ($event instanceof EventHandlerAggregateInterface) {
            return $this->attachEventHandlerAggregate($event);
        }

        if (is_array($event)) {
            foreach ($event as $name) {
                $this->attachEventHandler($name, $callback);
            }
        } else {
            $this->eventHandlers[$event] = $callback;
        }
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

    public function detachEventHandler($event)
    {
        if (!is_array($event))
            $event = [$event];
        $handlers = [];
        foreach ($this->eventHandlers as $handler => $callback) {
            if (!in_array($handler, $event))
                $handlers[$handler] = new EventHandlerCallback($callback);
        }
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
            $event = (new $this->eventClass)
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

        $responses = new $this->responseClass();
        $responses->setEvent($e);

        foreach($this->getEventHandler($e->getName()) as $handler){
            if($handler instanceof EventHandlerCallback){
                $responses->push($handler->execute($e));
            }

            if($e->isPropagationStopped()){
                $responses->setStopped(true);
                break;
            }

            if($callback && call_user_func($callback, $responses->last())){
                $responses->setStopped(true);
                break;
            }
        }

        return $responses;

    }

    public function setResponseClass(EventResponseInterface $responseClass)
    {
        $this->responseClass = $responseClass;
    }

    public function getResponseClass()
    {
        return $this->responseClass;
    }

    public function setEventClass($eventClass)
    {
        $this->eventClass = $eventClass;
    }


    public function getEventClass()
    {
        return $this->eventClass;
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

}
