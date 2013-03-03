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

    use \Efika\Common\SingletonTrait;

    /**
     * init config
     * @param $config
     */
    public function construct($config);

    /**
     * trigger events
     * @abstract
     * @return mixed
     */
    public function execute();
}
