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

//create Autoloader
(new StandardAutoloader)
    ->setNamespaces(
        [
            'Efika\\' => dirname(__FILE__) . '/../../library/Efika/',
            'WebApplication\\' => dirname(__FILE__) . '/../src/Classes/',
        ])
    ->register();

//Initialize Webapplication

$config = require_once dirname(__FILE__) . '/../config/config.php';

var_dump($config);

$app = WebApp::getInstance();
$app->setConfig($config);
$app->configure();

$app->registerService('ApplicationService', new ApplicationService());
$app->registerService('CustomApplicationService', new CustomApplicationService());

$app->connectService('ApplicationService');
$app->connectService('CustomApplicationService');

$app->execute();