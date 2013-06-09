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
     * @param array $params
     * @throws DispatcherException
     * @internal param string $method
     * @return $this
     */
    public function executeDispatchable(DiService $diService)
    {
        //set additional data like request, result, response

        //Validate required interfaces for dispatchable
        $this->validateRequiredInterfaces($diService);

        $result = $this->getRouter()->getResult();
        $params =
            $result->offsetExists('params') ?
                $this->getRouter()->makeParameters($result->offsetGet('params')) :
                [];

        var_dump($params);

        $diService->inject('setRouter',['router' => $this->getRouter()]);
        $diService->inject('setParams',$params);

        $diService->inject('execute');
        $this->setDispatchableInstance($diService->makeInstance());

        var_dump('YOLO');

        return $this;

    }
}