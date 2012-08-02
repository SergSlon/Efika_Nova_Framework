<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

interface EventResponseInterface
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
    public function setStopped($flag);

    /**
     * @abstract
     * @return mixed
     */
    public function isStopped();
}
