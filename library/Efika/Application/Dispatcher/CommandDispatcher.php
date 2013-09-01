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

    /**
     * @return $this
     */
    protected function createDispatchable()
    {
        $result = $this->getRouter()->getResult();
        $dispatchableService = $this->getDispatchableService($result);

        //set additional data like request, result, response
        //Validate required interfaces for dispatchable
        $this->validateRequiredInterfaces($dispatchableService);

        $params =
            $result->offsetExists('params') ?
                $this->getRouter()->makeParameters($result->offsetGet('params')) :
                [];

        $dispatchableService->inject('setParams',['params' => $params]);

        return $dispatchableService;

    }

}