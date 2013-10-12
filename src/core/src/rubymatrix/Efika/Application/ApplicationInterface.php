<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

use Efika\EventManager\EventManagerInterface;

/**
 * Application
 */
/**
 * Class ApplicationInterface
 * @package Efika\Application
 */
interface ApplicationInterface extends EventManagerInterface
{

    /**
     *
     */
    const ON_INIT = 'application.init';
    /**
     *
     */
    const ON_ROUTE = 'application.route';
    /**
     *
     */
    const ON_DISPATCH = 'application.dispatch';
    /**
     *
     */
    const ON_COMPLETE = 'application.complete';
    /**
     *
     */
    const OBJECT_ID = 'application';
    /**
     *
     */
    const STATUS_FRESH = 0;
    /**
     *
     */
    const STATUS_CONFIGURED = 2;
    /**
     *
     */
    const STATUS_INITIALIZED = 4;
    /**
     *
     */
    const STATUS_ROUTED = 8;
    /**
     *
     */
    const STATUS_DISPATCHED = 16;
    /**
     *
     */
    const STATUS_COMPLETED = 32;

    /**
     * init config
     * @return
     */
    public function configure();

    /**
     * @param $config
     * @return mixed
     */
    public function setConfig(array $config = []);

    /**
     * trigger events
     * @abstract
     * @return bool
     */
    public function execute();
}
