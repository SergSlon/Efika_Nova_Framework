<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpHeaderParam {
    private $name = null;
    private $value = null;
    private $delimiter = null;

    public function __construct($name,$value,$delimiter = ':'){
        $this->name = $name;
        $this->value = $value;
        $this->delimiter = $delimiter;
    }

    /**
     * @return null|string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }
}