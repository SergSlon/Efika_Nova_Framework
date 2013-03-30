<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Common\Logger;

require_once '../app/boot/bootstrap.php';

echo "<pre>";
echo "<h2>logger</h2>";
echo Logger::getInstance()->toText();
echo "</pre>";