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
     * trigger init event
     * @return mixed
     */
    public function onInit();

    /**
     * trigger preProcess event
     * @return mixed
     */
    public function onPreProcess();

    /**
     * trigger process event
     * @return mixed
     */
    public function onProcess();

    /**
     * trigger postProcess event
     * @return mixed
     */
    public function onPostProcess();

    /**
     * trigger complete event
     * @return mixed
     */
    public function onComplete();

    /**
     * trigger events
     * @abstract
     * @return mixed
     */
    public function execute();
}
