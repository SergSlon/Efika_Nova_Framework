<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;


use Efika\Http\Filter\FilterChainInterface;
use Efika\Http\Filter\FilterInterface;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;

class FilterChain implements FilterChainInterface{

    private $filters = [];

    public function addFilter(FilterInterface $filter)
    {
        $this->filters = $filter;
    }

    public function execute(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        foreach($this->filters as $filter){
            $filter->filter($request,$response);
        }
    }
}