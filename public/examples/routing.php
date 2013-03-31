<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Application\Router\Router;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$routes = array(
    '/' => 'index/index',
    '/(?P<controller>\w+)/(?P<action>\w+)/(?P<params>[a-zA-Z0-9_]+)' => ':controller/:action/:params',
    '/foo/(?P<params>[a-zA-Z0-9_]+)/view' => 'foo/view/:params',
);

$router = new Router($routes);
var_dump($router->match('/foo/1w3435/view'));
var_dump($router->match('/foo/show/value'));
var_dump($router->match('/'));

var_dump($router);