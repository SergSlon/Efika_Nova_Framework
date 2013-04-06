<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Application\Dispatcher\CommandDispatcher;
use Efika\Application\Dispatcher\DispatchFactory;
use Efika\Application\Router\Router;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$routes = array(
    '/(?P<command>\w+)/(?P<params>[a-zA-Z0-9_/]+)' => [
        'route' => ':command/:params',
        'dispatchMode' => 'cmd',
    ],
    '/(?P<controller>\w+)/(?P<action>\w+)/(?P<params>[a-zA-Z0-9_]+)' => [
        'route' => ':controller/:action/:params',
        'dispatchMode' => 'mvc',
    ]
);

$router = new Router();
$router->setRoutes($routes);
//var_dump($router->match('/foo/1w3435/view'));
//var_dump($router->match('/foo/show/value'));
//var_dump($router->match('/'));
$router->match('/myCommand/user/marco/group/admin');

var_dump('Params');
$params = $router->makeParameters('user/marco/group/admin');
var_dump($params);

//var_dump($router);

$dispatcher = DispatchFactory::factory($router->getDispatchMode());

$dispatcher->setAppNs(__NAMESPACE__);
$dispatcher->setRouter($router);
$dispatcher->dispatch();