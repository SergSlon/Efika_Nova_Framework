<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Response;

/**
 * Class HttpContent
 * @package Efika\Http
 */
class HttpContent extends \ArrayObject{

    /**
     * @return string
     */
    public function __toString()
    {
        return implode('',$this->getArrayCopy());
    }
}