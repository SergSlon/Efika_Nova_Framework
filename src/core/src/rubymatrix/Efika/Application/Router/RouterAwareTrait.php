<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;


trait RouterAwareTrait {

    protected $router = null;

    /**
     * @param \Efika\Application\Router\Router|null $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return null
     */
    public function getRouter()
    {
        return $this->router;
    }
}