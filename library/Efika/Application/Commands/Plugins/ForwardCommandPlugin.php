<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands\Plugins;


use Efika\Application\Dispatcher\DispatchableInterface;

class ForwardCommandPlugin implements PluginInterface{

    /**
     * @param DispatchableInterface $command
     * @return mixed
     */
    public function setCommand(DispatchableInterface $command)
    {
        // TODO: Implement setCommand() method.
    }

    /**
     * @return DispatchableInterface
     */
    public function getCommand()
    {
        // TODO: Implement getCommand() method.
    }
}