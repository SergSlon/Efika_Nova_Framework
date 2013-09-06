<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;

/**
 * View helper
 */
interface ViewHelperInterface
{
    public function init($args);
    public function execute();
    public function __invoke();
}
