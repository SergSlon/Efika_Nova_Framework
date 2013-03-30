<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

require_once dirname(__FILE__) . 'env.php';
$defaultConfig = require_once dirname(__FILE__) . 'config.default.php';
$enviromentConfigFile = dirname(__FILE__) . 'config.' . EFIKA_ENVIROMENT . '.php';
$enviroments = ['production','developent','test'];

if(
    in_array(EFIKA_ENVIROMENT,$enviroments) &&
    file_exists($enviromentConfigFile)
){
    $enviromentConfig = require_once $enviromentConfigFile;
}

return array_merge_recursive($defaultConfig, $enviromentConfigFile);