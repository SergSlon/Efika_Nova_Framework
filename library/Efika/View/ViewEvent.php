<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\Application\Router\RouterInterface;
use Efika\EventManager\Event;

class ViewEvent extends Event {

    private $viewModel = null;
    private $resolver = null;
    private $renderer = null;
    private $router = null;

    /**
     * @param \Efika\View\ViewRendererInterface|null $renderStrategy
     */
    public function setRenderer(ViewRendererInterface $renderStrategy)
    {
        $this->renderer = $renderStrategy;
    }

    /**
     * @return null
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param \Efika\View\ViewResolverInterface|null $resolveStrategy
     */
    public function setResolver(ViewResolverInterface $resolveStrategy)
    {
        $this->resolver = $resolveStrategy;
    }

    /**
     * @return null
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param \Efika\Application\Router\RouterInterface|null $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return null
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return null
     */
    public function getViewModel()
    {
        return $this->getTarget()->getViewModel();
    }


}