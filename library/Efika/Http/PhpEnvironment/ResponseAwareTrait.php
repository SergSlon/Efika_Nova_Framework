<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\PhpEnvironment;


use Efika\Http\HttpResponseInterface;

trait ResponseAwareTrait {

    protected $response = null;

    /**
     * @return \Efika\Http\HttpResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Efika\Http\HttpResponseInterface|null $response
     */
    public function setResponse(HttpResponseInterface $response = null)
    {
        $this->response = $response;
    }
}