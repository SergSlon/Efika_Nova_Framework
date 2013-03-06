<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

require_once '../entryPoint/bootstrap.php';

class CustomService{
    public function __construct(\Efika\Application\ApplicationInterface $app){
        $app->attachEventHandler('onInit',new \Efika\EventManager\EventHandlerCallback(function(){
            var_dump('Demacia!');
        }));
    }
}

$config = [
    'ConfigVar' => 'foo.bar',
];

$app = Efika\Application\Application::getInstance();
$app->configure($config);

new CustomService($app);

var_dump($app->execute());

