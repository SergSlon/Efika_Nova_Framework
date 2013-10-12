<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;


trait DiContainerAwareTrait {

    private $diContainer = null;

    /**
     * @return DiContainer
     */
    public function getDiContainer()
    {
        if($this->diContainer === null){
            $this->diContainer = DiContainer::getInstance();
        }
        return $this->diContainer;
    }

}