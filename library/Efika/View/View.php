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

    public function __construct(){

    }

    public function setEventObject(ViewEvent $eventObject = null){
        if($eventObject === null){
            $eventClassname = self::DEFAULT_EVENT;
            $eventObject = new $eventClassname;
        }

        $eventObject->setTarget($this);

    }

    public function setViewModel(ViewModelInterface $model)
    {
        // TODO: Implement setViewModel() method.
    }

    public function resolve()
    {
        $this->triggerEvent(self::BEFORE_RENDER_VIEW,array());
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}