<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


interface ViewEngineAwareInterface {

    /**
     * @param ViewResolverInterface|ViewRendererInterface $engine
     * @return null
     */
    public function setEngine($engine);

    /**
     * @return ViewResolverInterface|ViewRendererInterface|null
     */
    public function getEngine();
}