<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;


use ArrayAccess;
use Efika\Application\Router\Router;
use Efika\Application\Router\RouterInterface;
use Efika\Config\Config;
use Efika\Di\DiContainer;
use Efika\Di\DiContainerInterface;
use Efika\Di\DiService;
use Efika\EventManager\Event;

/**
 * Class ApplicationEvent
 * @package Efika\Application
 */
class ApplicationEvent extends Event{

    /**
     * @var Router
     */
    public $router = 'Efika\Application\Router\Router';
    /**
     * @var \ArrayObject
     */
    public $error = '\ArrayObject';
    /**
     * @var DiContainer
     */
    public $diContainer = 'Efika\Di\DiContainer';

    /**
     * @var array
     */
    public $config = [];

    /**
     *
     */
    function __construct()
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
     * @param Router|RouterInterface|string $router
     */
    public function setRouter($router)
    {
        $this->router = $this->getDiContainer()->createService($router)->makeInstance();
    }

    /**
     * @return Router|RouterInterface|string
     */
    public function getRouter()
    {
        if(!($this->router instanceof RouterInterface)){
            $this->setRouter($this->router);
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

}