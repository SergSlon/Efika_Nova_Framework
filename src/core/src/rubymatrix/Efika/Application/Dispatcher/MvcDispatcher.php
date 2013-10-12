<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;

use Efika\Di\DiContainer;
use Efika\Loader\StandardAutoloader;
use Efika\View\View;

class MvcDispatcher implements DispatcherInterface
{

    use ConcreteDispatcherTrait;

    /**
     * TODO: Information about sourcefiles will collected by modules!
     */

    /**
     *
     */
    const DEFAULT_APP_NS = 'EfikaApplication';
    /**
     *
     */
    const DEFAULT_CMD_NS = ':appnamespace\Controller\\';

    /**
     *
     */
    const DEFAULT_CLASS_KEYWORD = 'Controller';

    /**
     * @return $this
     */
    protected function createDispatchable()
    {

        $response = $this->getResponse();
        $request = $this->getRequest();

        $result = $this->getRouter()->getResult();
        $dispatchableService = $this->getDispatchableService($result);
        //set additional data like request, result, response

        //Validate required interfaces for dispatchable
        $this->validateRequiredInterfaces($dispatchableService);

        $router = $this->getRouter();
        $result = $router->getResult();
        $params =
            $result->offsetExists('params') ?
                $this->getRouter()->makeParameters($result->offsetGet('params')) :
                [];


        $renderer = $this->getClassAsService(View::DEFAULT_RENDERER)->applyInstance();
        $resolver = $this->getClassAsService(View::DEFAULT_RESOLVER)->applyInstance();

        $viewEvent = $this->getClassAsService(View::DEFAULT_EVENT)->applyInstance();
        $viewEvent->setRenderer($renderer);
        $viewEvent->setResolver($resolver);

        $view = $this->getClassAsService('Efika\View\View')
            ->applyInstance();

//        var_dump(__FILE__ . __LINE__);
//        var_dump($view);
//        exit();

//        $view->setEventObject($viewEvent);

        //configure dispatchable
        if($dispatchableService->getReflection()->implementsInterface('Efika\Application\Commands\Plugins\PluginAwareInterface')){
            $dispatchableService->inject('setPluginManager', ['pluginManager' => DiContainer::getInstance()->getClassAsService('Efika\Application\Commands\Plugins\PluginManager')->makeInstance()]);
        }

        $dispatchableService->inject('setRouter', ['router' => $this->getRouter()])
            ->inject('setParams', ['params' => $params])
            ->inject('setActionId', ['actionId' => $result->offsetGet('actionId')])
            ->inject('setControllerId', ['controllerId' => $result->offsetGet($this->getClassParamKeyword())])
            ->inject('setView', ['view' => $view])
            ->inject('setRequest', ['response' => $request])
            ->inject('setResponse', ['request' => $response])
            ->inject('init');


        return $dispatchableService;

    }
}