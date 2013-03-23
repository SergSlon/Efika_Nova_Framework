<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

interface EventManagerInterface
{
    /**
     * Attach an handler to an event or attach an event aggregate
     * @param string|\Efika\EventManager\EventHandlerAggregateInterface $event
     * @param callable | null $callback
     * @return EventManagerTrait
     */
    public function attachEventHandler($event, $callback=null);

    /**
     * Detach an event handler.
     * @param $event
     * @return EventManagerTrait
     */
    public function detachEventHandler($event);

    /**
     * Trigger an event.
     * @param string|EventInterface $event
     * @param array $args an array with arguments which will passed to event class
     * @param null|callable $callback
     * @return \Efika\EventManager\EventResponse
     * @throws \Efika\EventManager\Exception
     */
    public function triggerEvent($event, $args = [], callable $callback = null);
}
