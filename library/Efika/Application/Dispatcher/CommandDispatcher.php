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
//        $this->setAppNs(self::DEFAULT_APP_NS);
        $this->setClassKeyword(self::DEFAULT_CLASS_KEYWORD);
        $this->setClassParamKeyword(strtolower(self::DEFAULT_CLASS_KEYWORD));
        $this->setNamespace(self::DEFAULT_APP_NS);
    }

    /**
     * @param DiService $diService
     * @param array $params
     * @throws DispatcherException
     * @internal param string $method
     * @return $this
     */
    public function executeDispatchable(DiService $diService, $params = [])
    {
        //set additional data like request, result, response
        $reflection = $diService->getReflection();

        foreach ($this->getRequiredInterfaces() as $interface) {
            if (!$reflection->implementsInterface($interface)) {
                throw new DispatcherException(
                    sprintf('Class does not implement required interface %s!', $interface)
                );
            }
        }

        $diService->inject('execute', $params);
        $this->setDispatchableInstance($diService->makeInstance());

        return $this;

    }

}