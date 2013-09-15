<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\Di\DiContainerAwareTrait;

class ViewClassProvider{

    use DiContainerAwareTrait;

    const DEFAULT_EVENT = 'Efika\View\ViewEvent';
    const DEFAULT_EVENT_AGGREGATE = 'Efika\View\ViewEventAggregate';
    const DEFAULT_RESOLVER = 'Efika\View\Engines\DefaultResolverEngine';
    const DEFAULT_RENDERER = 'Efika\View\Engines\PhpRenderEngine';
    const DEFAULT_VIEW = 'Efika\View\View';
    const VIEW_HELPER_INTERFACE = 'Efika\View\Helper\ViewHelperInterface';

    /**
     * @return null
     */
    public function getEvent()
    {
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_EVENT)->applyInstance();
    }

    /**
     * @return null
     */
    public function getEventAggregate()
    {
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_EVENT_AGGREGATE)->applyInstance();
    }

    /**
     * @return null
     */
    public function getRenderer()
    {
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_RENDERER)->applyInstance();
    }

    /**
     * @return null
     */
    public function getResolver()
    {
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_RESOLVER)->applyInstance();
    }

    /**
     * @return null
     */
    public function getView()
    {
        return $this->getDiContainer()->getClassAsService(self::DEFAULT_VIEW)->applyInstance();
    }

}