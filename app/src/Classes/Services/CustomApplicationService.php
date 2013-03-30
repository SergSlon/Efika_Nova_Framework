<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Services;

use Efika\Application\Application as WebApp;
use Efika\Application\ApplicationInterface;
use Efika\Application\ApplicationServiceInterface;
use Efika\Common\Logger;
use Efika\EventManager\EventHandlerAggregateInterface;
use Efika\EventManager\EventInterface;

class CustomApplicationService implements EventHandlerAggregateInterface, ApplicationServiceInterface
{

    const LOGGER_SCOPE = 'application.customService';

    /**
     * @var null | ApplicationInterface
     */
    private $app = null;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @param \Efika\Application\ApplicationInterface|null $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * @return \Efika\Application\ApplicationInterface|null
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param ApplicationInterface $app
     * @param array $arguments
     */
    public function register(ApplicationInterface $app, array $arguments = [])
    {
        $this->setApp($app);
        $this->setArguments($arguments);
        Logger::getInstance()->scope(self::LOGGER_SCOPE)->addMessage('service registred');
    }

    /**
     *
     */
    public function connect()
    {
        $this->getApp()->attachEventHandlerAggregate($this);
        Logger::getInstance()->scope(self::LOGGER_SCOPE)->addMessage('service connected');
    }

    /**
     *
     */
    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @param EventInterface $e
     */
    public function onInit(EventInterface $e)
    {
        var_dump(__FILE__ . __LINE__);
//        var_dump($e);
        var_dump('Init service');
    }

    /**
     * Attach events to parent event observer
     *
     * @param \Efika\EventManager\EventManagerTrait $parent
     *
     * @return mixed
     */
    public function attach($parent)
    {
        $aggregate = $this;
        $parent->attachEventHandler(WebApp::ON_INIT, function ($e) use ($aggregate) {
            $aggregate->onInit($e);
        });
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

}

