<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;


use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Request;

class MvcDispatcher implements DispatcherInterface{

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


        $renderer = $this->getClassAsService('Efika\View\ViewRenderer')->makeInstance();
        $resolver = $this->getClassAsService('Efika\View\ViewResolver')->makeInstance();

        $viewEvent = $this->getClassAsService('Efika\View\ViewEvent')->makeInstance();
        $viewEvent->setRenderer($renderer);
        $viewEvent->setResolver($resolver);

        $view = $this->getClassAsService('Efika\View\View')->makeInstance();
        $view->setEventObject($viewEvent);

        $dispatchableService->inject('setRouter',['router' => $this->getRouter()]);
        $dispatchableService->inject('setParams',['params' => $params]);
        $dispatchableService->inject('setActionId',['actionId' => $result->offsetGet('actionId')]);
        $dispatchableService->inject('setControllerId',['controllerId' => $result->offsetGet($this->getClassParamKeyword())]);
        $dispatchableService->inject('setView',['view' => $view]);
        $dispatchableService->inject('setRequest',['response' => $request]);
        $dispatchableService->inject('setResponse',['request' => $response]);

        return $dispatchableService;

    }
}