<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;


use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;

/**
 * Add logic for pre and post filtering into one filter. Filter need to implement FilterInterface as well.
 * Class HybridFilterTrait
 * @package Efika\Application\Filter
 */
trait HybridFilterTrait {

    private $preHandled = false;

    private function preHandled(){
        $this->preHandled = true;
    }

    private function isPreHandled(){
        return $this->preHandled;
    }

    public function filter(HttpRequestInterface $request, HttpResponseInterface $response){
        if($this->isPreHandled()){
            $this->postFilter($request, $response);
        }else{
            $this->preFilter($request, $response);
            $this->preHandled();
        }
    }

    abstract protected function preFilter(HttpRequestInterface $request, HttpResponseInterface $response);
    abstract protected function postFilter(HttpRequestInterface $request, HttpResponseInterface $response);
}