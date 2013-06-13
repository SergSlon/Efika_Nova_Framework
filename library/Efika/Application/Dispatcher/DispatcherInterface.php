<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;


use Efika\Application\Router\RouterInterface;

interface DispatcherInterface {

    public function execute();
    public function getRouter();
    public function setRouter(RouterInterface $router);

}