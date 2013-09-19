<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;


use Efika\Application\Router\Router;
use Efika\Common\Logger;
use Efika\Di\DiContainer;
use Efika\EventManager\EventHandlerAggregateInterface;

/**
 * Class ApplicationService
 * @package Efika\Application
 */
class ApplicationService implements ApplicationServiceInterface, EventHandlerAggregateInterface{

    /**
     *
     */
    const LOGGER_SCOPE = 'application.service';

    /**
     * @var null
     */
    public $parentApplication = null;
    /**
     * @var null
     */
    public $arguments = null;
    /**
     * @var null
     */
    public $logger = null;

    /**
     * @param ApplicationInterface $app
     * @param array $arguments
     */
    public function register(ApplicationInterface $app, array $arguments = [])
    {
        $this->setParentApplication($app);
        $this->setArguments($arguments);
        $this->getLogger()->info('application service registred');
    }

    /**
     *
     */
    public function connect()
    {
        $this->getParentApplication()->attachEventHandlerAggregate($this);
        $this->getLogger()->info('application service connected');
    }

    /**
     *
     */
    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * Attach events to parent event observer
     *
     * @param Application $parent
     *
     * @return mixed
     */
    public function attach($parent)
    {
        $parent->attachEventHandler(ApplicationInterface::ON_INIT, array($this,'onApplicationInit'));
        $parent->attachEventHandler(ApplicationInterface::ON_ROUTE, array($this,'onApplicationPreProcess'));
        $parent->attachEventHandler(ApplicationInterface::ON_DISPATCH, array($this,'onApplicationProcess'));
        $parent->attachEventHandler(ApplicationInterface::ON_COMPLETE, array($this,'onApplicationComplete'));
    }

    /**
     * Detach events from parent event observer
     *
     * @param $parent
     *
     * @return mixed
     */
    public function detach($parent)
    {
        // TODO: Implement detach() method.
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationInit(ApplicationEvent $event){
        $di = $event->getDiContainer();
        $config = $event->getTarget()->getConfig();
        $routerConfig = $config->offsetGet('router')->toArray();

        if($config->offsetExists('appNs')){
            $event->setAppNs($config->offsetGet('appNs'));
        }

        $router = $di->getClassAsService('Efika\Application\Router\Router')->applyInstance();
        $router->setRoutes($routerConfig);

        $event->setRouter($router);
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPreProcess(ApplicationEvent $event){
        $this->getLogger()->info('Preprocess application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationProcess(ApplicationEvent $event){
        $this->getLogger()->info('route application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPostProcess(ApplicationEvent $event){
        $this->getLogger()->info('post route application');
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationComplete(ApplicationEvent $event){
        $this->getLogger()->info('complete application');
    }


    /**
     * @return Logger
     */
    public function getLogger()
    {
        if($this->logger === null){
            $this->logger = Logger::getInstance()->scope(self::LOGGER_SCOPE);
        }
        return $this->logger;
    }

    /**
     * @return null
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return Application
     */
    public function getParentApplication()
    {
        return $this->parentApplication;
    }

    /**
     * @param $parentApplication
     */
    protected function setParentApplication($parentApplication)
    {
        $this->parentApplication = $parentApplication;
    }
}