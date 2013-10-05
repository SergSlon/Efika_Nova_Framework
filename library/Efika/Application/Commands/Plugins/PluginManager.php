<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands\Plugins;

use Efika\Di\DiContainer;
use Efika\Di\DiServiceInterface;

class PluginManager {

    const PLUGIN_INTERFACE = 'Efika\Application\Commands\Plugins\PluginInterface';

    private $plugins = [];

    public function register($name, $callback){
        $di = DiContainer::getInstance();
        $service = $di->getClassAsService($name,$callback);
        if(!$service->getReflection()->implementsInterface(self::PLUGIN_INTERFACE)){
            throw new InvalidPluginException('Plugin need to implement %s', self::PLUGIN_INTERFACE);
        }
        $this->plugins[$name] = $service;
    }

    public function exists($name){
        return array_key_exists($name, $this->plugins);
    }

    /**
     * @param $name
     * @throws InvalidPluginException
     * @return bool|DiServiceInterface
     */
    protected function get($name){
        if(!$this->exists($name)) {
            throw new InvalidPluginException(sprintf('Unknown plugin "%s"', $name));
        }
        return $this->plugins[$name];
    }

    public function call($name,$args = []){
        $plugin = $this->get($name);
        return $plugin->makeInstance($args);
    }

    public function __call($name, $args = []){
        return $this->call($name, $args);
    }

}