<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands\Plugins;


use Efika\Application\Dispatcher\DispatchableInterface;

class FilterPlugin implements PluginInterface{

    private $command = null;

    /**
     * @param DispatchableInterface $command
     * @return mixed
     */
    public function setCommand(DispatchableInterface $command)
    {
        $this->command = $command;
    }

    /**
     * @return DispatchableInterface
     */
    public function getCommand()
    {
        return $this->command;
    }
}