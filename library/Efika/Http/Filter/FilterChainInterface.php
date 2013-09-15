<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;

use Efika\Http\Filter\FilterInterface;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;

interface FilterChainInterface {
    public function addFilter(FilterInterface $filter);
    public function execute(HttpRequestInterface $request, HttpResponseInterface $response);
}