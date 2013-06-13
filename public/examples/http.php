<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Http\HttpRequest;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$request = HttpRequest::establish();
var_dump($request);