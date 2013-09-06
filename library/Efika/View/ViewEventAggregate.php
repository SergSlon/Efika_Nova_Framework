<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;

use Efika\EventManager\EventHandlerAggregateInterface;

class ViewEventAggregate implements EventHandlerAggregateInterface{

    /**
     * Attach events to parent event observer
     *
     * @param $parent View
     *
     * @return mixed
     */
    public function attach($parent)
    {
        $parent->attachEventHandler(ViewInterface::ON_INIT, array($this,'onViewInit'));
        $parent->attachEventHandler(ViewInterface::ON_RESOLVE, array($this,'onViewResolve'));
        $parent->attachEventHandler(ViewInterface::ON_RENDER, array($this,'onViewRender'));
    }

    /**
     * Detach events from parent event observer
     *
     * @param $parent View
     *
     * @return mixed
     */
    public function detach($parent)
    {
        $parent->detachEventHandler(ViewInterface::ON_INIT);
        $parent->detachEventHandler(ViewInterface::ON_RESOLVE);
        $parent->detachEventHandler(ViewInterface::ON_RENDER);
    }

    /**
     * @param ViewEvent $event
     */
    public function onViewInit(ViewEvent $event){
        $args = $event->getArguments();
        $event->setViewModel($args['viewModel']);
        $event->setResolver($args['resolver']);
        $event->setRenderer($args['renderer']);

    }

    /**
     * @param ViewEvent $event
     */
    public function onViewResolve(ViewEvent $event){
        $viewModel = $event->getViewModel();
        $resolver = $event->getResolver();

        $resolvedView = $resolver->resolve($viewModel->getView(), $viewModel->getViewPath());

        $event->setResolvedView($resolvedView);
    }

    /**
     * @param ViewEvent $event
     */
    public function onViewRender(ViewEvent $event){
        $renderer = $event->getRenderer();
        $viewModel = $event->getViewModel();


        $resolvedView = $event->getResolvedView();
        $renderer->setResolvedView($resolvedView);
        $content = $renderer->render($viewModel);

        $event->setRenderedContent($content);
    }
}