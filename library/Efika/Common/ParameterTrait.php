<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;


trait ParameterTrait {

    protected $params = null;

    /**
     * @param null $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return null
     */
    public function getParams()
    {
        return $this->params;
    }
}