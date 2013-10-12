<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Modules;


trait ModuleAwareTrait {


    /**
     * @var array
     */
    private $modules = [];

    /**
     * @param $id
     * @param \Efika\Application\Modules\ModuleInterface $instance
     * @param array $attributes
     * @return bool
     */
    public function registerModule($id, ModuleInterface $instance, $attributes = [])
    {

        if ($this->hasModule($id)) {
            return false;
        }

        $instance->register($this, $attributes);
        $this->modules[$id] = $instance;
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function connectModule($id)
    {
        return $this->executeModuleTask($id, function(ModuleInterface $module){
            $module->connect();
        });
    }

    public function disconnectModule($id)
    {
        return $this->executeModuleTask($id, function(ModuleInterface $module){
            $module->disconnect();
        });
    }

    protected function executeModuleTask($id, callable $callback = null){
        $module = $this->getModule($id);

        if($module === null){
            return false;
        }

        if(is_callable($callback)){
            $callback($module);
        }

        return true;
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param $id
     * @return null|ModuleInterface
     */
    public function getModule($id){
        return $this->hasModule($id) ? $this->modules[$id] : null;
    }

    public function hasModule($id){
        return array_key_exists($id, $this->getModules());
    }
}