<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;

interface RouterAwareInterface {

    /**
     * @param \Efika\Application\Router\RouterInterface $router
     */
    public function setRouter(RouterInterface $router);

    /**
     * @return \Efika\Application\Router\RouterInterface
     */
    public function getRouter();
}