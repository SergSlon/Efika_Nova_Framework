<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Application\Application as WebApp;

require_once '../entryPoint/bootstrap.php';

class CustomHandlerAggregate implements \Efika\EventManager\EventHandlerAggregateInterface
{

    public function onInit(\Efika\EventManager\EventInterface $e)
    {
        var_dump($e);
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
        $parent->attachEventHandler(WebApp::ON_INIT,function($e) use ($aggregate){
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

$config = [
    'ConfigVar' => 'foo.bar',
];


$app = WebApp::getInstance();
$app->configure($config);

$app->attachEventHandlerAggregate(new CustomHandlerAggregate());

var_dump($app->execute());

