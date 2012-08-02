<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

use ArrayObject;
use SplStack;

class EventResponse extends SplStack implements EventResponseInterface
{

    private $event = null;
    private $stopped = false;

    public function hasEvent()
    {
        return $this->event instanceof EventInterface;
    }

    public function setEvent($requestedEvent)
    {
        $this->event = $requestedEvent;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setStopped($flag)
    {
        $this->stopped = (bool)$flag;
    }

    public function isStopped()
    {
        return $this->stopped;
    }

    public function first()
    {
        return parent::bottom();
    }

    public function last()
    {
        return parent::top();
    }


}
