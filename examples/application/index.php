<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Application\Application as WebApp;
use Efika\Application\ApplicationInterface;
use Efika\Common\Logger;

require_once '../entryPoint/bootstrap.php';

class CustomApplicationService implements \Efika\EventManager\EventHandlerAggregateInterface, \Efika\Application\ApplicationServiceInterface
{

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
    }

    /**
     *
     */
    public function connect()
    {
        $this->getApp()->attachEventHandlerAggregate($this);
    }

    /**
     *
     */
    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @param \Efika\EventManager\EventInterface $e
     */
    public function onInit(Efika\EventManager\EventInterface $e)
    {
        var_dump(__FILE__ . __LINE__);
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
    'events' => [
        WebApp::ON_INIT => [
            /**
             * @param $e
             */
            /**
             * @param $e
             */
            function($e){
                echo 'say hello';
            }
        ]
    ],
];


$app = WebApp::getInstance();
$app->configure($config);

//$app->attachEventHandlerAggregate(new CustomHandlerAggregate());

$app->registerService('customApplicationService', new CustomApplicationService());

$app->connectService('customApplicationService');

var_dump(__FILE__ . __LINE__);
var_dump($app->execute());

echo "<pre>";
echo "<h2>logger</h2>";
echo Logger::getInstance()->toText();
echo "</pre>";

