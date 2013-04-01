<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;


use Efika\Application\Router\Router;
use Efika\Common\Logger;
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
        $this->getLogger()->addMessage('application service registred');
    }

    /**
     *
     */
    public function connect()
    {
        $this->getParentApplication()->attachEventHandlerAggregate($this);
        $this->getLogger()->addMessage('application service connected');
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
        $parent->attachEventHandler(ApplicationInterface::ON_PREPROCESS, array($this,'onApplicationPreProcess'));
        $parent->attachEventHandler(ApplicationInterface::ON_PROCESS, array($this,'onApplicationProcess'));
        $parent->attachEventHandler(ApplicationInterface::ON_POSTPROCESS, array($this,'onApplicationPostProcess'));
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
        $this->getLogger()->addMessage('Init Application by arguments');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPreProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('Preprocess application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('process application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPostProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('post process application');
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationComplete(ApplicationEvent $event){
        $this->getLogger()->addMessage('complete application');
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