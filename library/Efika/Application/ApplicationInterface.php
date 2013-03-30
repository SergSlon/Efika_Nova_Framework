<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

/**
 * Application
 */
interface ApplicationInterface extends \Efika\EventManager\EventManagerInterface
{

    const ON_INIT = 'application.init';
    const ON_PREPROCESS = 'application.preprocess';
    const ON_PROCESS = 'application.process';
    const ON_POSTPROCESS = 'application.postprocess';
    const ON_COMPLETE = 'application.complete';
    const LOGGER_SCOPE = 'application';
    const STATUS_FRESH = 0;
    const STATUS_CONFIGURED = 2;
    const STATUS_INITIALIZED = 4;
    const STATUS_PROCESSED = 8;
    const STATUS_COMPLETED = 16;

    /**
     * init config
     * @param $config
     */
    public function configure($config);

    /**
     * trigger events
     * @abstract
     * @return bool
     */
    public function execute();
}
