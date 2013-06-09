<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;

use Efika\Application\Router\Router;
use Efika\Application\Router\RouterInterface;
use Efika\Di\DiContainer;
use Efika\Di\DiException;
use Efika\Di\DiService;

/**
 * Class CommandDispatcher
 * @package Efika\Application\Dispatcher
 */
class CommandDispatcher implements DispatcherInterface
{

    use ConcreteDispatcherTrait;

    /**
     *
     */
    const DEFAULT_APP_NS = 'EfikaApplication';
    /**
     *
     */
    const DEFAULT_CMD_NS = ':appnamespace\Commands\\';

    /**
     *
     */
    const DEFAULT_CLASS_KEYWORD = 'Command';

    public function __construct(){
        $this->setAppNs(self::DEFAULT_APP_NS);
        $this->setClassKeyword(self::DEFAULT_CLASS_KEYWORD);
        $this->setClassParamKeyword(strtolower(self::DEFAULT_CLASS_KEYWORD));
        $this->setNamespace(self::DEFAULT_CMD_NS);
    }

    /**
     * @param DiService $diService
     * @internal param array $params
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

        $diService->inject('setParams',['params' => $params]);
        $diService->inject('execute');
        $this->setDispatchableInstance($diService->makeInstance());

        return $this;

    }

}