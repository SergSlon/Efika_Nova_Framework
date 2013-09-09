<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\Application\Router\RouterInterface;
use Efika\EventManager\Event;
use Efika\View\Engines\RendererEngineInterface;
use Efika\View\Engines\ResolverEngineInterface;

class ViewEvent extends Event {

    private $viewModel = null;
    private $resolver = null;
    private $renderer = null;
    private $resolvedView = null;
    private $renderedContent = null;

    /**
     * @param \Efika\View\Engines\RendererEngineInterface|null $renderStrategy
     */
    public function setRenderer(RendererEngineInterface $renderStrategy)
    {
        $this->renderer = $renderStrategy;
    }

    /**
     * @return RendererEngineInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param \Efika\View\Engines\ResolverEngineInterface|null $resolveStrategy
     */
    public function setResolver(ResolverEngineInterface $resolveStrategy)
    {
        $this->resolver = $resolveStrategy;
    }

    /**
     * @return ResolverEngineInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param \Efika\View\ViewModelInterface|null $viewModel
     */
    public function setViewModel(ViewModelInterface $viewModel)
    {
        $this->viewModel = $viewModel;
    }

    /**
     * @return ViewModelInterface|ViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * @param null $renderedContent
     */
    public function setRenderedContent($renderedContent)
    {
        $this->renderedContent = $renderedContent;
    }

    /**
     * @return null
     */
    public function getRenderedContent()
    {
        return $this->renderedContent;
    }

    /**
     * @param null $resolvedView
     */
    public function setResolvedView($resolvedView)
    {
        $this->resolvedView = $resolvedView;
    }

    /**
     * @return null
     */
    public function getResolvedView()
    {
        return $this->resolvedView;
    }

}