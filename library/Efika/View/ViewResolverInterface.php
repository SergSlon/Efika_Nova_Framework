<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;

/**
 * Resolves a path to a view
 */
interface ViewResolverInterface
{

    /**
     * resolves template in path
     * @abstract
     * @param $view
     * @param $path
     * @return mixed
     */
    public function resolve($view,$path);
}
