<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


interface ViewModelAwareInterface {
    public function setViewModel(ViewModelInterface $model);
}