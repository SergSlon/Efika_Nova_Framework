<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;

/**
 * renders given view
 */
interface ViewRendererInterface
{

    public function setResolvedView($view);

    /**
     * Renders given view
     * @abstract
     * @return mixed
     */
    public function render();

}
