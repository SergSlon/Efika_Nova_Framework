<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Http\HttpRequest;
use Efika\Http\HttpResponse;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$request = HttpRequest::establish();
//var_dump($request);

$response = new HttpResponse($request->getHttpMessage());
$response->setResponseCode(404);
var_dump($response->sendHeaders());
var_dump(http_response_code());
$response->sendBody();
