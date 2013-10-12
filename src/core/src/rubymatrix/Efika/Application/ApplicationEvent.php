<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;


use ArrayAccess;
use Efika\Application\Router\Router;
use Efika\Application\Router\RouterAwareTrait;
use Efika\Application\Router\RouterInterface;
use Efika\Config\Config;
use Efika\Di\DiContainer;
use Efika\Di\DiContainerInterface;
use Efika\Di\DiService;
use Efika\EventManager\Event;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\RequestAwareTrait;
use Efika\Http\PhpEnvironment\ResponseAwareTrait;

/**
 * Class ApplicationEvent
 * @package Efika\Application
 */
class ApplicationEvent extends Event{

    use RouterAwareTrait;
    use ResponseAwareTrait;
    use RequestAwareTrait;

    /**
     * @var Router
     */
    const  APPLICATION_ROUTER = 'Efika\Application\Router\Router';

    /**
     * @var \ArrayObject
     */
    public $error = '\ArrayObject';
    /**
     * @var DiContainer
     */
    public $diContainer = null;

    public $dispatcher = null;

    public $appNs = null;

    /**
     * @var array
     */
    public $config = [];

    /**
     *
     */
    public function __construct()
    {
        $this->setDiContainer(DiContainer::getInstance());
    }


    /**
     * @param DiContainerInterface $diContainer
     */
    public function setDiContainer(DiContainerInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @return DiContainer|string
     */
    public function getDiContainer()
    {
        return $this->diContainer;
    }

    /**
     * @return Router|RouterInterface|string
     */
    public function getRouter()
    {
        if(!($this->router instanceof RouterInterface)){
            $this->setRouter(self::APPLICATION_ROUTER);
        }
        return $this->router;
    }

    /**
     * @param ArrayAccess $error
     */
    public function setError($error)
    {
        $this->error = $this->getDiContainer()->createService($error)->makeInstance();
    }

    /**
     * @return \ArrayObject|ArrayAccess|string
     */
    public function getError()
    {
        if(!($this->error instanceof ArrayAccess)){
            $this->setError($this->error);
        }
        if($this->error instanceof DiService){

        }
        return $this->error;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param null $dispatcher
     */
    public function setDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return null
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param null $appNs
     */
    public function setAppNs($appNs)
    {
        $this->appNs = $appNs;
    }

    /**
     * @return null
     */
    public function getAppNs()
    {
        return $this->appNs;
    }

}