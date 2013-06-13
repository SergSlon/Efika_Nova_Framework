<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Dispatcher;


use Efika\Di\DiContainer;
use Efika\Di\DiException;
use Efika\Di\DiService;

/**
 * Class DispatcherFactory
 * @package Efika\Application\Dispatcher
 */
class DispatcherFactory
{

    /**
     *
     */
    const MODE_COMMAND = 'cmd';
    /**
     *
     */
    const MODE_MVC = 'mvc';
    /**
     *
     */
    const MODE_MODULE = 'module';
    /**
     *
     */
    const MODE_VIEW = 'view';

    /**
     * @var array
     */
    private static $dispatcherList = [
        self::MODE_COMMAND => 'Efika\Application\Dispatcher\CommandDispatcher',
        self::MODE_MVC => 'Efika\Application\Dispatcher\MvcDispatcher',
        self::MODE_MODULE => 'Efika\Application\Dispatcher\ModuleDispatcher',
        self::MODE_VIEW => 'Efika\Application\Dispatcher\ViewDispatcher',
    ];

    /**
     * @param $mode
     * @return mixed
     */
    public static function factory($mode)
    {
        $di = DiContainer::getInstance();

        $dispatcher = self::getDispatcher($mode);

        try{
            $service = $di->getService($dispatcher);
        } catch (DiException $e){
            $service =$di->createService($dispatcher);
        }

        if($service instanceof DiService){
            $service = $service->makeInstance();
        }

        return $service;
    }

    /**
     * @return array
     */
    public static function getDispatcherList()
    {
        return self::$dispatcherList;
    }

    /**
     * @param $mode
     * @param $dispatcher
     */
    public static function addDispatcher($mode,$dispatcher)
    {
        if(is_object($dispatcher)){
            $dispatcher = get_class($dispatcher);
        }

        self::$dispatcherList[$mode] = $dispatcher;
    }

    /**
     * @param $mode
     * @return mixed
     * @throws DispatcherNotFoundException
     */
    public static function getDispatcher($mode){
        if(array_key_exists($mode,self::$dispatcherList)){
            return self::$dispatcherList[$mode];
        }else{
            throw new DispatcherNotFoundException('Dispatcher not found in list');
        }
    }


}