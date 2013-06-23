<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Exception;

class ViewModel implements ViewModelInterface{

    /**
     * @var ViewVarCollection
     */
    private $vars = null;

    /**
     * Path to view file
     * @var string|null
     */
    private $viewPath = null;

    /**
     * nome of view with or without extension
     * @var null
     */
    private $view = null;


    /**
     * Uses ViewResolverInterface to resolve und set the view path
     * @param $path
     * @return mixed
     */
    public function setViewPath($path)
    {
        $this->viewPath = $path;
    }

    /**
     * @return mixed
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Set a template. Name represents path/to/filename starting with view-path
     * and without file-extension
     * @param $file
     * @return mixed
     */
    public function setView($file)
    {
        $this->view = $file;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Assign a var to current template
     * @param $name
     * @param $value
     * @return mixed
     */
    public function assignVar($name, $value)
    {
        $this->getVarCollection()->offsetSet($name, $value);
    }

    /**
     * Revoke var from Template
     * @param $name
     * @return mixed
     */
    public function revokeVar($name)
    {
        $this->getVarCollection()->offsetUnset($name);
    }

    /**
     * @param $object
     */
    public function setVarCollection($object)
    {
        $this->vars = $object;
    }

    /**
     * If no varCollection has been set, the varCollection will get by DI
     * @return ViewVarCollection
     */
    public function getVarCollection()
    {
        if($this->vars == null){
            //TODO: add DI Support vor View collection
            $this->vars = new ViewVarCollection();
        }

        return $this->vars;
    }

    /**
     * Add a child model and name with an id
     * @param ViewModelInterface $model
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function addChildren($id, ViewModelInterface $model){
        $this->getVarCollection()->addChildren($id,$model);
    }

    public function __call($name, $arguments)
    {
        // TODO: Call view helper
    }

    public function __get($name)
    {
        return $this->getVarCollection()->offsetGet($name);
    }

    public function __set($name, $value)
    {
        $this->assignVar($name,$value);
    }


}