<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

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

$config = dirname(__FILE__) . '/../config.php';

$app = WebApp::getInstance();
$app->configure($config);

$app->registerService('customApplicationService', new CustomApplicationService());
$app->connectService('customApplicationService');

$app->execute();