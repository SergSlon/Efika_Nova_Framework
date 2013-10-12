<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * Delivers an empty instance of event manager.
 */
class EventManager implements EventManagerInterface
{
    use EventManagerTrait;
}
