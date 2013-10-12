<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;


interface ParameterInterface {
    public function getParams();
    public function setParams($params);
}