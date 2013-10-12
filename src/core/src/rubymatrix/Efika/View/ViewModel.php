<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\Common\Collection;
use Efika\Common\CollectionInterface;
use Efika\Di\DiContainer;
use Efika\Di\DiException;
use Efika\View\Helper\ViewHelperException;
use Exception;

class ViewModel implements ViewModelInterface
{

    const VIRTUAL_VAR_COLLECTION_ID = 'Efika\View\ViewVarCollection';

    /**
     * @var CollectionInterface
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
     * @var null
     */
    private $viewFileExtension = null;

    /**
     * @var \Efika\Common\Collection|null
     */
    private $childs = null;

    private $renderedContent = '';

    private $resolvedViewPath = null;

    public function __construct()
    {
        $di = DiContainer::getInstance();
        $id = self::VIRTUAL_VAR_COLLECTION_ID;
        try {
            $service = $di->getService($id);
        } catch (DiException $e) {
            try {
                $service = $di->createService(new Collection(), $id);
            } catch (DiException $e) {
                throw new ViewHelperException(sprintf('Unable to load Collection "%s"', $id), 0, $e);
            }
        }
        $this->vars = $service->makeInstance();
        $this->childs = new Collection();
    }

    public function viewHelper($id)
    {
        $di = DiContainer::getInstance();
        try {
            $service = $di->getService($id);
            if(!$service->getReflection()->implementsInterface(View::VIEW_HELPER_INTERFACE)){
                throw new ViewHelperException(sprintf('Helper "%s" needs to implement %s', $id, View::VIEW_HELPER_INTERFACE));
            }
            return $service->applyInstance();
        } catch (DiException $e) {
            throw new ViewHelperException(sprintf('Unable to load Helper "%s"', $id), 0, $e);
        }
    }

    /**
     * Uses ResolverEngineInterface to resolve und set the view path
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
     * @param string $extension
     * @return mixed
     */
    public function setView($file, $extension = ViewInterface::DEFAULT_VIEW_FILE_EXTENSION)
    {
        $this->view = $file;
        $this->viewFileExtension = $extension;
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
        $this->getVarCollection()->set($name, $value);
    }

    /**
     * Revoke var from Template
     * @param $name
     * @return mixed
     */
    public function revokeVar($name)
    {
        $this->getVarCollection()->remove($name);
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
     * @return Collection
     */
    public function getVarCollection()
    {
        return $this->vars;
    }

    /**
     * Add a child model and name with an id
     * @param ViewModelInterface $model
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function addChildren($id, ViewModelInterface $model)
    {
        if($model->getViewPath() === null){
            $model->setViewPath($this->getViewPath());
        }

        if($model->getView() === null){
            throw new ViewException(sprintf('Viewmodel %s child\'s need to have specific view!', $id));
        }
        $this->childs->set($id,$model);
    }

    /**
     * @return array
     */
    public function getChilds(){
        return $this->childs->getAll();
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        // TODO: Call view helper
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getVarCollection()->get($name);
    }

//    /**
//     * @param $name
//     * @param $value
//     */
//    public function __set($name, $value)
//    {
//        $this->assignVar($name,$value);
//    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getVarCollection()->getAll();
    }

    /**
     * @param null $resolvedViewPath
     */
    public function setResolvedViewPath($resolvedViewPath)
    {
        $this->resolvedViewPath = $resolvedViewPath;
    }

    /**
     * @return null
     */
    public function getResolvedViewPath()
    {
        return $this->resolvedViewPath;
    }

    /**
     * @param null $renderedContent
     */
    public function setRenderedContent($renderedContent)
    {
        $this->renderedContent = $renderedContent;
    }

    /**
     * @return string
     */
    public function getRenderedContent()
    {
        return $this->renderedContent;
    }

    /**
     * @return string
     */
    public function toString(){
        return $this->getRenderedContent();
    }

    public function __toString(){
        return $this->toString();
    }

}