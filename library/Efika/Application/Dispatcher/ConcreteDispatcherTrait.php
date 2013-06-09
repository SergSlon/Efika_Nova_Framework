<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;

use Efika\Application\Router\Router;
use Efika\Application\Router\RouterInterface;
use Efika\Application\Router\RouterResult;
use Efika\Di\DiContainer;
use Efika\Di\DiException;
use Efika\Di\DiService;

trait ConcreteDispatcherTrait {

    /**
     * @var string
     */
    protected $appNs = '';

    /**
     * @var string
     */
    protected $classKeyword = '';

    /**
     * @var string
     */
    protected $classParamKeyword = '';

    /**
     * @var null|Object
     */
    protected $dispatchableInstance = null;

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var RouterInterface
     */
    protected $router = null;

    /**
     * @var array
     */
    protected $requiredInterfaces = [
        'Efika\Application\Dispatcher\DispatchableInterface'
    ];

    /**
     *
     */
    public function dispatch()
    {
        $result = $this->getRouter()->getResult();
        $dispatchableService = $this->getDispatchableService($result);

        $this->executeDispatchable($dispatchableService);
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
        return
            $this->getNamespace()
            . ucfirst(rtrim(strtolower($class),strtolower($this->getClassKeyword())))
            . $this->getClassKeyword();
    }

    /**
     * @param RouterResult $result
     * @throws DispatcherException
     * @return string
     */
    public function getDispatchableService($result){
        $class = $this->makeClassname($result->offsetGet($this->getClassParamKeyword()));

        try {

            $diService = $this->getClassAsService($class);

        } catch (DiException $e) {
            throw new DispatcherException('Requested class not found', 0, $e);
        }

        return $diService;
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
     * @param array $params
     * @internal param string $method
     * @return $this
     */
    abstract public function executeDispatchable(DiService $diService);

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

    /**
     * @return string
     */
    public function getClassKeyword()
    {
        return $this->classKeyword;
    }

    /**
     * @param $classKeyword
     */
    protected function setClassKeyword($classKeyword)
    {
        $this->classKeyword = ucfirst(strtolower($classKeyword));
    }

    /**
     * @return string
     */
    public function getClassParamKeyword()
    {
        return $this->classParamKeyword;
    }

    /**
     * @param string $classParamKeyword
     */
    public function setClassParamKeyword($classParamKeyword)
    {
        $this->classParamKeyword = $classParamKeyword;
    }

    /**
     * @return null|Object
     */
    public function getDispatchableInstance()
    {
        return $this->dispatchableInstance;
    }

    /**
     * @param null|Object $dispatchableInstance
     */
    public function setDispatchableInstance($dispatchableInstance)
    {
        $this->dispatchableInstance = $dispatchableInstance;
    }

    /**
     * @param DiService $diService
     * @throws DispatcherException
     */
    public function validateRequiredInterfaces(DiService $diService){
        $reflection = $diService->getReflection();

        foreach ($this->getRequiredInterfaces() as $interface) {
            if (!$reflection->implementsInterface($interface)) {
                throw new DispatcherException(
                    sprintf('Class does not implement required interface %s!', $interface)
                );
            }
        }
    }
}