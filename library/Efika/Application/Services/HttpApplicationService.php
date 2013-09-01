<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Services;


use Efika\Application\ApplicationEvent;
use Efika\Application\ApplicationService;
use Efika\Application\Dispatcher\DispatcherFactory;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;

class HttpApplicationService extends ApplicationService{

    const LOGGER_SCOPE = 'httpapplication.service';

    public function onApplicationInit(ApplicationEvent $event){
        parent::onApplicationInit($event);

        $di = $event->getDiContainer();

        $httpMessage = $di->getClassAsService('Efika\Http\HttpMessage')->applyInstance();

        $request = $httpMessage->getRequest();
        if(!($request instanceof HttpRequestInterface)){
            $request = $di->getClassAsService('Efika\Http\PhpEnvironment\Request')->applyInstance([$httpMessage]);
        }

        $response = $httpMessage->getResponse();
        if(!($response instanceof HttpResponseInterface)){
            $response = $di->getClassAsService('Efika\Http\PhpEnvironment\Response')->applyInstance([$httpMessage]);
        }

        $event->setRequest($request);
        $event->setResponse($response);
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPreProcess(ApplicationEvent $event){}

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationProcess(ApplicationEvent $event){
        $router = $event->getRouter();
        $request = $event->getRequest();
        $response = $event->getResponse();
        $query = $request->getQuery();

        $route = array_key_exists('r', $query) && strlen($query['r']) > 0 ? $query['r'] : null;
        $router->match($route);

        $dispatcher = DispatcherFactory::factory($router->getDispatchMode());

        $dispatcher->setAppNs($event->getAppNs());
        $dispatcher->setRequest($request);
        $dispatcher->setResponse($response);
        $dispatcher->setRouter($router);
        $dispatcher->execute();

        $event->setDispatcher($dispatcher);
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPostProcess(ApplicationEvent $event){}

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationComplete(ApplicationEvent $event){
        $dispatcher = $event->getDispatcher();
        //var_dump(__FILE__ . __LINE__);
        $dispatcherResult = $dispatcher->getDispatchableResult();
        //var_dump($dispatcherResult);

        if($dispatcherResult instanceof HttpResponseInterface){
            $http = $dispatcherResult;
            $http->send();
        }
    }

}