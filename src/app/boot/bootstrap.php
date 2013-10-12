<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Application\Modules\ApplicationModule;
use Efika\Loader\StandardAutoloader;
use Efika\Application\Application as WebApp;
use WebApplication\Modules\CustomApplicationModule;

require_once dirname(__FILE__) . '/../../core/src/rubymatrix/Efika/Loader/StandardAutoloader.php';

$config = require_once dirname(__FILE__) . '/../config/config.php';

//create Autoloader
(new StandardAutoloader)
    ->setNamespaces($config['autoloader'])
    ->register();



//Initialize Webapplication
//var_dump($config);

$app = WebApp::getInstance();
$app->setConfig($config);
$app->configure();

//$app->registerModule('ApplicationModule', new ApplicationModule());
$app->registerModule('HttpApplicationModule', new \Efika\Application\Modules\HttpApplicationModule());
$app->registerModule('CustomApplicationModule', new CustomApplicationModule());

//$app->connectModule('ApplicationModule');
$app->connectModule('HttpApplicationModule');
$app->connectModule('CustomApplicationModule');

$app->execute();