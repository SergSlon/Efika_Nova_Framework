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

    /**
     *
     */
    const DEFAULT_APP_NS = 'EfikaApplication';
    /**
     *
     */
    const DEFAULT_CMD_NS = ':appnamespace\Commands\\';

    /**
     * @var string
     */
    protected $appNs = self::DEFAULT_APP_NS;
    /**
     * @var string
     */
    protected $namespace = self::DEFAULT_CMD_NS;

    /**
     * @var RouterInterface
     */
    protected $router = null;

    /**
     * @var array
     */
    protected $requiredInterfaces = [
        'Efika\Application\Commands\CommandInterface'
    ];

    /**
     *
     */
    public function dispatch()
    {
        $result = $this->getRouter()->getResult();


        var_dump($result);

        $class = $this->makeClassname($result->offsetGet('command'));

        var_dump($class);

        try {
            $params =
                $result->offsetExists('params') ?
                    $this->getRouter()->makeParameters($result->offsetGet('params')) :
                    [];

            $diService = $this->getClassAsService($class);
            $this->executeCommand($diService, $params);

        } catch (DiException $e) {
            throw new DispatcherException('Requested class not found', 0, $e);
        }
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function getAppNs()
    {
        return $this->appNs;
    }

    /**
     * @param string $appNs
     */
    public function setAppNs($appNs)
    {
        $this->appNs = '\\' . trim($appNs, '\\');
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return str_replace(':appnamespace', $this->getAppNs(), $this->namespace);
    }

    /**
     * @param string $ns
     */
    public function setNamespace($ns)
    {
        $this->namespace = trim($ns, '\\') . '\\';
        ;
    }

    /**
     * @param $class
     * @return string
     */
    protected function makeClassname($class)
    {
        return $this->getNamespace() . ucfirst($class);
    }

    /**
     * @param $class
     * @return mixed
     */
    protected function getClassAsService($class)
    {
        $di = DiContainer::getInstance();

        $service = null;

        try {
            $service = $di->getService($class);
        } catch (DiException $e) {
            $service = $di->createService($class);
        }

        return $service;
    }

    /**
     * @param DiService $diService
     * @param string $method
     * @throws DispatcherException
     */
    public function executeCommand(DiService $diService, $params = [], $method = 'execute')
    {
        //set additional data like request, result, response
        $reflection = $diService->getReflection();

        $instance = null;

        if (!$reflection->isInstance($diService)) {

            foreach ($this->getRequiredInterfaces() as $interface) {
                if (!$reflection->implementsInterface($interface)) {
                    throw new DispatcherException(
                        sprintf('Class does not implement required interface %s!', $interface)
                    );
                }
            }

            $diService->inject($method, $params);
            $diService->makeInstance();

        }

    }

    /**
     * @return array
     */
    public function getRequiredInterfaces()
    {
        return $this->requiredInterfaces;
    }

    /**
     * @param $requiredInterface
     */
    public function setRequiredInterface($requiredInterface)
    {
        $this->requiredInterfaces[] = $requiredInterface;
    }
}