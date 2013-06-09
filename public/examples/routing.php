<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Application\Dispatcher\CommandDispatcher;
use Efika\Application\Dispatcher\DispatcherFactory;
use Efika\Application\Router\Router;

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
    '/(?P<controller>\w+)/(?P<action>\w+)/(?P<params>[a-zA-Z0-9_/]+)?' => [
        'route' => [
            'route' => ':controller/:action/:params',
            'controller' => 'Ext{controller}Somthing' //ExtBLABLASomething
        ],
        'dispatchMode' => 'mvc',
    ]
);

$router = new Router();
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
$dispatcher->setRouter($router);
$dispatcher->dispatch();