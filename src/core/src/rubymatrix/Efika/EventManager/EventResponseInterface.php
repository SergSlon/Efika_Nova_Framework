<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

use ArrayAccess;
use Countable;
use Iterator;
use Traversable;

interface EventResponseInterface extends ArrayAccess, Countable, Traversable, Iterator
{

    /**
     * Event which has passed to event response
     * @abstract
     * @param $requestedEvent
     * @return mixed
     */
    public function setEvent($requestedEvent);

    /**
     * Returns passed event
     * @abstract
     * @return mixed
     */
    public function getEvent();

    /**
     *
     * @abstract
     * @param $flag
     * @return mixed
     */
    public function stop($flag=true);

    /**
     * @abstract
     * @return mixed
     */
    public function isStopped();
}
