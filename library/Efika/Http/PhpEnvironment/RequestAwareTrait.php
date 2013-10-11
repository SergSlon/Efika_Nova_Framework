<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\PhpEnvironment;


use Efika\Http\HttpRequestInterface;

trait RequestAwareTrait {

    protected $request = null;

    /**
     * @param \Efika\Http\HttpRequestInterface|null $request
     */
    public function setRequest(HttpRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Efika\Http\HttpRequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }
}