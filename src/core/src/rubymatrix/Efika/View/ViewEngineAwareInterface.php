<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\View\Engines\RendererEngineInterface;
use Efika\View\Engines\ResolverEngineInterface;

interface ViewEngineAwareInterface {

    /**
     * @param ResolverEngineInterface|RendererEngineInterface $engine
     * @return null
     */
    public function setEngine($engine);

    /**
     * @return ResolverEngineInterface|RendererEngineInterface|null
     */
    public function getEngine();
}