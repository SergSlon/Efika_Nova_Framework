<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\Di\DiContainer;
use Efika\Http\HttpRequest;
use Efika\Http\HttpMessage;
use Efika\Http\HttpResponse;
use Efika\Http\HttpService;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\Response;

require_once __DIR__ . '/../../app/boot/bootstrap.php';




$request = Request::establish();
//var_dump($request);

$response = new Response($request->getHttpMessage());
$response->setResponseCode(404);
var_dump($response->sendHeaders());
var_dump(http_response_code());
$response->sendBody();
