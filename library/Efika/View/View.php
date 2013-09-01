<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\View;


use Efika\EventManager\Event;
use Efika\EventManager\EventInterface;
use Efika\EventManager\EventManagerTrait;

class View implements ViewInterface, ViewModelAwareInterface{

    use EventManagerTrait;

    protected $viewModel = null;

    public function __construct(){

    }

    public function setViewModel(ViewModelInterface $model)
    {
        $this->viewModel = $model;
    }

    /**
     * @return null
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    public function resolve()
    {
        $this->triggerEvent(self::BEFORE_RESOLVE_VIEW);
        $this->triggerEvent(self::WHILE_RESOLVE_VIEW);
        $this->triggerEvent(self::AFTER_RESOLVE_VIEW);
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}