<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;

class DefaultCommand implements DispatchableInterface, ParameterInterface{

    protected $params = [];

    public function execute()
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