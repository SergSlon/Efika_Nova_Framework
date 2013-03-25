<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * This trait provide reusable event manager. this event manager is based on observer pattern
 */
use Efika\Common\Logger;
use SplPriorityQueue;

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
trait EventManagerTrait
{

    private $loggerScope = 'event.manager';

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
     *
     * Possible kinds of attaching:
     *
     * 1. attach aggregate if event is an instance of EventHandlerAggregateInterface
     *
     * 2. attach many callbacks to an event if callback is an array and not callable
     *
     * 3. attach multiple events. as key-value-pair. key will be event identifier and value
     * will be a single valid callback or a array with many callbacks multiple aggegate
     * attachment won't be possible
     *
     * @param string|\Efika\EventManager\EventHandlerAggregateInterface $id
     * @param null | EventHandlerCallback | array | callable $callback
     * @param int $priority
     * @return EventManagerTrait
     */
    public function attachEventHandler($id, $callback = null, $priority=1000)
    {
        //attach aggregate if event is an instance of EventHandlerAggregateInterface
        if ($id instanceof EventHandlerAggregateInterface) {
            return $this->attachEventHandlerAggregate($id);
        }

        //attach many callbacks to an event if callback is an array and not callable
        if(is_array($callback) && !is_callable($callback)){
            foreach($callback as $callbackItem){
                $this->attachEventHandler($id, $callbackItem);
            }

            return $this;
        }

        //attach multiple events. as key-value-pair. key will be event identifier and value
        //will be a single valid callback or a array with many callbacks
        //multiple aggegate attachment won't be possible
        if(is_array($id)){

            foreach($id as $id => $callback){
                $this->attachEventHandler($id, $callback);
            }

            return $this;
        }

        if(!($callback instanceof EventHandlerCallback) && $callback !== null)
            $callback = new EventHandlerCallback($callback);

        $this->setEventHandler($id,$callback,$priority);

        Logger::getInstance()->scope($this->getLoggerScope())->addMessage('(attach to) ' . $id);

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
     * Detach an event handler or an array of eventhandkers by event handler identifiers.
     * @param $id
     * @return EventManagerTrait
     */
    public function detachEventHandler($id)
    {
        if (!is_array($id))
            $id = [$id];
        $handlers = [];
        foreach ($this->eventHandlers as $handler => $callback) {
            if (!in_array($handler, $id))
                $handlers[$handler] = new EventHandlerCallback($callback);
        }

        return $this;
    }

    /**
     * Trigger an event.
     * @param \Efika\EventManager\EventInterface|string $id
     * @param array $args an array with arguments which will passed to event class
     * @param null|callable $callback
     * @throws Exception
     * @return EventResponse
     */
    public function triggerEvent($id, $args=[], callable $callback = null)
    {

        $event = null;
        if (is_string($id)) {
            $event = $this->getEventObject()
            ->setName($id)
            ->setTarget($this)
            ->setArguments($args);
        }else if($id instanceof EventInterface){
            $event = $id;
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
     * Trigger handlers of given event. Stop propagation when callback returns true
     * @param EventInterface $e
     * @param $callback
     * @return mixed
     */
    protected function triggerHandlers(EventInterface $e, $callback = null)
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

            Logger::getInstance()->scope($this->getLoggerScope())->addMessage('(trigger) ' . $e->getName() . ' (Elements: ' . count($responses) . ')' ,$e);
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
     * @return EventResponse
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
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getEventHandler($id)
    {
        if(!$this->hasEventHandler($id))
            throw new Exception('Unknown EventHandler: ' . $id);
        return $this->eventHandlers[$id];
    }

    /**
     * Return an event handler of an event
     * @param string $id
     * @param \Efika\EventManager\EventHandlerCallback|null $callback
     * @param int $priority
     * @return mixed
     */
    public function setEventHandler($id,EventHandlerCallback $callback,$priority=1000)
    {
        if(!$this->hasEventHandler($id))
            $this->eventHandlers[$id] = new SplPriorityQueue();
        $this->eventHandlers[$id]->insert($callback,$priority);
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

    public function getLoggerScope()
    {
        return $this->loggerScope;
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
