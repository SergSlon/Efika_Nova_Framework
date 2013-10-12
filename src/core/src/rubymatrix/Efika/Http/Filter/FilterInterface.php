<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;

use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Response;

interface FilterInterface {
    public function filter(HttpRequestInterface $request, HttpResponseInterface $response);
}