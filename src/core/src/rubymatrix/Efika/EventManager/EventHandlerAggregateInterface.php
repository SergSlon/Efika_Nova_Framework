<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * Allows to manage a bunch of event handlers
 */
interface EventHandlerAggregateInterface
{

    /**
     * Attach events to parent event observer
     * @abstract
     *
     * @param $parent
     *
     * @return mixed
     */
    public function attach($parent);

    /**
     * Detach events from parent event observer
     * @abstract
     *
     * @param $parent
     *
     * @return mixed
     */
    public function detach($parent);
}
