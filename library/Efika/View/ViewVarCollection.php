<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Exception;

/**
 * Class ViewVarCollection
 * @package Efika\View
 * TODO: Add validate methods to validate reserved names
 */
class ViewVarCollection extends \ArrayObject implements ViewVarCollectionInterface{

    /**
     * @param $id
     * @param ViewModelInterface $model
     * @return $this
     * @throws \Exception
     */
    public function addChildren($id, ViewModelInterface $model){
        if(!is_string($id) || is_numeric($id)){
            throw new Exception(sprintf('Given id ""'));
        }

        if(!$this->offsetExists('children')){
            $this->offsetSet('children', new \ArrayObject());
        }

        $this->offsetGet('children')->offsetSet($id,$model);
        return $this;
    }

}