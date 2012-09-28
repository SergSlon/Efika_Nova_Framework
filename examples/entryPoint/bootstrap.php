<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Loader\StandardAutoloader;

require_once __DIR__ . '/../../library/Efika/Loader/StandardAutoloader.php';

//create Autoloader

(new StandardAutoloader)
    ->setNamespaces(
    [
        'Efika\\' => __DIR__ . '/../library/Efika/',
    ])
    ->register();