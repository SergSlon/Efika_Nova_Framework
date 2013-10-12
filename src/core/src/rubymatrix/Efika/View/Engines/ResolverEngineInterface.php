<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View\Engines;

/**
 * Resolves a path to a view
 */
interface ResolverEngineInterface
{

    /**
     * resolves template in path
     * @abstract
     * @param $viewModel
     * @return mixed
     */
    public function resolve($viewModel);
}
