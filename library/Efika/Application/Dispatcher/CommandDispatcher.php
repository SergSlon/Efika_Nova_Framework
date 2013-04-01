<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;

use Efika\Application\Router\RouterInterface;

class CommandDispatcher implements DispatcherInterface{

    private $router = null;

    public function dispatch()
    {
        // TODO: Implement dispatch() method.
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}