<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

use Efika\Common\SingletonTrait;

/**
 * empty singleton event manager
 */
class StaticEventManager
{
    /**
     * provide event manager
     */
    use EventManagerTrait;

    /**
     * provide singleton pattern
     */
    use SingletonTrait;
}
