<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands\Plugins;


interface PluginAwareInterface {
    public function setPluginManager(PluginManager $pluginManager);
    public function getPluginManager();
}