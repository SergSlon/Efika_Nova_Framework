<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;


use Efika\Di\DiService;

class MvcDispatcher implements DispatcherInterface{

    use ConcreteDispatcherTrait;

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

    public function __construct(){
        $this->setAppNs(self::DEFAULT_APP_NS);
        $this->setClassKeyword(self::DEFAULT_CLASS_KEYWORD);
        $this->setClassParamKeyword(strtolower(self::DEFAULT_CLASS_KEYWORD));
        $this->setNamespace(self::DEFAULT_CMD_NS);
    }

    /**
     * @param DiService $diService
     * @return $this
     */
    public function executeDispatchable(DiService $diService)
    {
        //set additional data like request, result, response

        //Validate required interfaces for dispatchable
        $this->validateRequiredInterfaces($diService);

        $router = $this->getRouter();
        $result = $router->getResult();
        $params =
            $result->offsetExists('params') ?
                $this->getRouter()->makeParameters($result->offsetGet('params')) :
                [];

        $diService->inject('setRouter',['router' => $this->getRouter()]);
        $diService->inject('setParams',['params' => $params]);
        $diService->inject('setAction',['action' => $result->offsetGet('action')]);

        $diService->inject('execute');
        $this->setDispatchableInstance($diService->makeInstance());

        return $this;

    }
}