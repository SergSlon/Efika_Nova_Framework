<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Application\ApplicationService;
use Efika\Loader\StandardAutoloader;
use Efika\Application\Application as WebApp;
use WebApplication\Services\CustomApplicationService;

require_once dirname(__FILE__) . '/../../library/Efika/Loader/StandardAutoloader.php';

$config = require_once dirname(__FILE__) . '/../config/config.php';

//create Autoloader
(new StandardAutoloader)
    ->setNamespaces($config['autoloader'])
    ->register();



//Initialize Webapplication
var_dump($config);

$app = WebApp::getInstance();
$app->setConfig($config);
$app->configure();

$app->registerService('ApplicationService', new ApplicationService());
$app->registerService('CustomApplicationService', new CustomApplicationService());

$app->connectService('ApplicationService');
$app->connectService('CustomApplicationService');

$app->execute();