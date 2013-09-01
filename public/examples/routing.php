<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Application\Dispatcher\CommandDispatcher;
use Efika\Application\Dispatcher\DispatcherFactory;
use Efika\Application\Router\Router;
use Efika\Common\Logger;
use Efika\Di\DiContainer;
use Efika\Di\DiException;
use Efika\Http\HttpMessage;
use Efika\Http\HttpMessageInterface;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Request;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$routes = array(
    '/cmd/(?P<command>\w+)' => [
        'route' => [
            'params' => 'user/foo/group/bar'
        ],
        'dispatchMode' => 'cmd',
    ],
    '/cmd/(?P<command>\w+)/(?P<params>[a-zA-Z0-9_/\-]+)' => [
        'route' => ':command/:params',
        'dispatchMode' => 'cmd',
    ],
    '/(?P<controller>\w+)/(?P<actionId>\w+)(/(?P<params>[a-zA-Z0-9_/]+)?)?' => [
        'route' => [
            'route' => ':controller/:actionId/:params',
            'controller' => 'Ext{controller}Somthing' //ExtBLABLASomething
        ],
        'dispatchMode' => 'mvc',
    ]
);

function getClassAsService($class)
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

//app initHttp
$httpMessageService = getClassAsService('Efika\Http\HttpMessage');
$httpMessage = $httpMessageService->makeInstance();

$request = $httpMessage->getRequest();
if(!($request instanceof HttpRequestInterface)){
    $request = getClassAsService('Efika\Http\PhpEnvironment\Request')->makeInstance([$httpMessage]);
}

$response = $httpMessage->getResponse();

if(!($response instanceof HttpResponseInterface)){
    $response = getClassAsService('Efika\Http\PhpEnvironment\Response')->makeInstance([$httpMessage]);
}

//init router
//Efika\Application\Router\Router
//$router = new Router();
$router = getClassAsService('Efika\Application\Router\Router')->makeInstance();
$router->setRoutes($routes);
//var_dump($router->match('/foo/1w3435/view'));
//var_dump($router->match('/foo/show/value'));
//var_dump($router->match('/'));
$router->match('/cmd/my/user/marco/group/admin');

//Controller
//$router->match('/any/way/to');
if (array_key_exists('r', $_GET) && strlen($_GET['r']) > 0) {
    $router->match($_GET['r']);
}

$dispatcher = DispatcherFactory::factory($router->getDispatchMode());

$dispatcher->setAppNs(__NAMESPACE__);
$dispatcher->setRequest($request);
$dispatcher->setResponse($response);
$dispatcher->setRouter($router);
$dispatcher->execute();

//var_dump(__FILE__ . __LINE__);
$dispatcherResult = $dispatcher->getDispatchableResult();
//var_dump($dispatcherResult);

if($dispatcherResult instanceof HttpResponseInterface){
    $http = $dispatcherResult;
    $http->sendBody();
}

echo "<pre>";
echo "<h2>logger</h2>";
echo Logger::getInstance()->toText();
echo "</pre>";