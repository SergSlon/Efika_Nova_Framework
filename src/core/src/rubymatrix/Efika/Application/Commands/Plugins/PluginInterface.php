<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands\Plugins;


use Efika\Application\Dispatcher\DispatchableInterface;

interface PluginInterface {

    /**
     * @param DispatchableInterface $command
     * @return mixed
     */
    public function setCommand(DispatchableInterface $command);

    /**
     * @return DispatchableInterface
     */
    public function getCommand();
}