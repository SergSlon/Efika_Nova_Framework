<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;


/**
 * Class RouterInterface
 * @package Efika\Application\Router
 */
interface RouterInterface {
    /**
     * @param string $wantedRoute
     * @return mixed
     */
    public function match($wantedRoute);
}