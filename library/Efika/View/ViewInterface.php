<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;
use Efika\EventManager\EventManagerInterface;

/**
 * Factory for view management
 */
interface ViewInterface extends EventManagerInterface
{

    public function addRenderStrategy($callable);
    public function addResponseStrategy($callable);
    public function render(ViewModelInterface $model);

}
