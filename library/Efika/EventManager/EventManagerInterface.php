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
     * @param string|\Efika\EventManager\EventHandlerAggregateInterface $id
     * @param callable | null $callback
     * @return EventManagerTrait
     */
    public function attachEventHandler($id, $callback=null);

    /**
     * Detach an event handler.
     * @param $id
     * @return EventManagerTrait
     */
    public function detachEventHandler($id);

    /**
     * Trigger an event.
     * @param string|EventInterface $id
     * @param array $args an array with arguments which will passed to event class
     * @param null|callable $callback
     * @return \Efika\EventManager\EventResponse
     * @throws \Efika\EventManager\Exception
     */
    public function triggerEvent($id, $args = [], callable $callback = null);
}
