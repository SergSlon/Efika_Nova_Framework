<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View\Engines;


use Efika\EventManager\Exception;
use Efika\View\ViewEngineAwareInterface;
use Efika\View\ViewInterface;
use Efika\View\ViewModel;
use Efika\View\Engines\ResolverEngineException;
use Efika\View\Engines\ResolverEngineInterface;

class DefaultResolverEngine implements ResolverEngineInterface, ViewEngineAwareInterface{

    /**
     * @var null|ResolverEngineInterface
     */
    private $engine = null;

    public function setEngine($engine){
        if(!($engine instanceof ResolverEngineInterface)){
            throw new Exception(sprintf('given engine needs to be an instance of %s\\ResolverEngineInterface', __NAMESPACE__));
        }
        $this->engine = $engine;
    }

    /**
     * @return null|ResolverEngineInterface
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param $viewModel ViewModel
     * @return string
     * @throws ResolverEngineException
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
            throw new ResolverEngineException(sprintf('Can not resolve view "%s"', $filename));
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