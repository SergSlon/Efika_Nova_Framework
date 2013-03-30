<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

define('EFIKA_ENV_PRODUCTION', 'production');
define('EFIKA_ENV_DEVELOPMENT', 'development');
define('EFIKA_ENV_TEST', 'test');

if (defined('EFIKA_ENVIROMENT')) {
    define('EFIKA_ENVIROMENT', EFIKA_ENV_PRODUCTION);
}