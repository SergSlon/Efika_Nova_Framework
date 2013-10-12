<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Loader\StandardAutoloader;

require_once __DIR__ . '/../src/rubymatrix/Efika/Loader/StandardAutoloader.php';

(new StandardAutoloader)
    ->setNamespaces(
    [
        'Efika\\' => __DIR__ . '/../src/Efika/',
        'EfikaTest\\' => __DIR__ . '/../tests/Efika/'
    ])
    ->register();