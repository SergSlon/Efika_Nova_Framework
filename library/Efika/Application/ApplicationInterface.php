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

    /**
     * init config
     * @param $config
     */
    public function configure($config);

    /**
     * trigger events
     * @abstract
     * @param null | callable $callback
     * @return mixed
     */
    public function execute($callback=null);
}
