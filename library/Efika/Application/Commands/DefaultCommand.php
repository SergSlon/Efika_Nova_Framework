<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;
use Efika\Common\ParameterInterface;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\Response;

class DefaultCommand implements DispatchableInterface, ParameterInterface{

    protected $params = [];

    public function dispatch(Request $request, Response $response)
    {

    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

}