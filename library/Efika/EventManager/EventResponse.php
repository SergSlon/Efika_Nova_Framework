<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

use ArrayObject;
use SplStack;

/**
 * Collection of response for triggering event
 */
class EventResponse extends SplStack implements EventResponseInterface
{

    /**
     * @var null
     */
    private $event = null;

    /**
     * @var bool
     */
    private $stopped = false;

    /**
     * Returns if response has event
     * @return bool
     */
    public function hasEvent()
    {
        return $this->event instanceof EventInterface;
    }


    /**
     * Set current event object
     * @TODO add check whether object is an instance of EventInterface
     * @param $requestedEvent
     * @return mixed|void
     */
    public function setEvent($requestedEvent)
    {
        $this->event = $requestedEvent;
    }

    /**
     * Get current event
     * @return mixed|null
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Stop event trigger
     * @param boolean $flag
     * @return mixed|void
     */
    public function stop($flag=true)
    {
        $this->stopped = (boolean)$flag;
    }

    /**
     * returns true if triggering has been stopped otherwise false
     * @return bool|mixed
     */
    public function isStopped()
    {
        return $this->stopped;
    }

    /**
     * Returns first element of response
     * @return mixed
     */
    public function first()
    {
        return parent::bottom();
    }

    /**
     * Returns last element of response
     * @return mixed
     */
    public function last()
    {
        return parent::top();
    }


}
