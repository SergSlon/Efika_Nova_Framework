<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Exception;

class ViewRenderer implements ViewRendererInterface, ViewEngineAwareInterface{

    /**
     * @var null|ViewRendererInterface
     */
    private $engine = null;
    private $resolvedView = null;

    /**
     * TODO: Add recursive rendering of childviews (partitials)
     * @param ViewModelInterface|ViewModel $viewModel
     * @return mixed
     */
    protected function fallbackEngine(ViewModelInterface $viewModel){
        $childs = $viewModel->getChilds();

        if($childs > 1){
            foreach($childs as $childModel){
                $this->render($childModel);
            }
        }

        $______filename = $viewModel->getResolvedViewPath();
        $vars = array_merge($viewModel->getVarCollection()->getAll(), $childs);

        $contentCapsule = function () use ($viewModel, $vars, $______filename){
            ob_start();
            ob_implicit_flush(false);
            extract($vars, EXTR_SKIP);

            require($______filename);

            return ob_get_clean();
        };

        $content = $contentCapsule();

        $viewModel->setRenderedContent($content);
    }

    public function setResolvedView($view)
    {
        $this->resolvedView = $view;
    }

    /**
     * @return null
     */
    public function getResolvedView()
    {
        return $this->resolvedView;
    }

    /**
     * Renders given view
     * @param ViewModelInterface $viewModel
     * @return mixed
     */
    public function render(ViewModelInterface $viewModel)
    {
        $engine = $this->getEngine();
        if($engine !== null){
            return $engine->render($viewModel);
        }

        return $this->fallbackEngine($viewModel);
    }

    /**
     * @param ViewRendererInterface|ViewResolverInterface $engine
     * @return null|void
     * @throws \Exception
     */
    public function setEngine($engine){
        if(!($engine instanceof ViewRendererInterface)){
            throw new Exception(sprintf('given engine needs to be an instance of %s\\ViewRendererInterface', __NAMESPACE__));
        }
        $this->engine = $engine;
    }

    /**
     * @return null|ViewRendererInterface
     */
    public function getEngine()
    {
        return $this->engine;
    }
}