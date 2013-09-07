<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\EventManager\Exception;

class ViewResolver implements ViewResolverInterface, ViewEngineAwareInterface{

    /**
     * @var null|ViewResolverInterface
     */
    private $engine = null;

    public function setEngine($engine){
        if(!($engine instanceof ViewResolverInterface)){
            throw new Exception(sprintf('given engine needs to be an instance of %s\\ViewResolverInterface', __NAMESPACE__));
        }
        $this->engine = $engine;
    }

    /**
     * @return null|ViewResolverInterface
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param $viewModel ViewModel
     * @return string
     * @throws ViewResolverException
     */
    protected function fallbackEngine($viewModel){

        $path = $viewModel->getViewPath();
        $view = $viewModel->getView();

        $childs = $viewModel->getChilds();

        if($childs > 1){
            foreach($childs as $childModel){
                $this->resolve($childModel);
            }
        }

        $filename = sprintf('%s/%s.%s', $path, $view, ViewInterface::DEFAULT_VIEW_FILE_EXTENSION);

        if(!is_file($filename) || !file_exists($filename)){
            throw new ViewResolverException(sprintf('Can not resolve view "%s"', $filename));
        }

        $viewModel->setResolvedViewPath($filename);
    }

    /**
     * resolves template in path
     * @param $viewModel ViewModel
     * @return mixed
     */
    public function resolve($viewModel)
    {
        $engine = $this->getEngine();
        if($engine !== null){
            return $engine->resolve($viewModel);
        }

        $this->fallbackEngine($viewModel);
    }
}