<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */


/*
* Set error reporting to the level to which Zend Framework code must comply.
*/
error_reporting( E_ALL | E_STRICT );

$phpUnitVersion = PHPUnit_Runner_Version::id();
if ('@package_version@' !== $phpUnitVersion && version_compare($phpUnitVersion, '3.6.0', '<')) {
    echo 'This version of PHPUnit (' . PHPUnit_Runner_Version::id() . ') is not supported in Efika Framework (Nova) unit tests.' . PHP_EOL;
    exit(1);
}
unset($phpUnitVersion);

/**
 * set Autoloader
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . '_autoloader.php';