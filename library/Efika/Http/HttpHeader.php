<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

/**
 * Class HttpHeader
 * @package Efika\Http
 */
class HttpHeader implements HttpHeaderInterface{

    /**
     * @var array|\ArrayObject
     */
    private $headers = [];

    /**
     * Initialize headers with optional headers key-value-pair. Key represents
     * field-name and value represents field-value
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = new \ArrayObject($headers);
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $delimiter
     * @return HttpHeaderInterface
     */
    public function add($name, $value, $delimiter = ':')
    {
        if(!$this->exists($name)){
            $this->headers->offsetSet($name,new HttpHeaderParam($name,$value,$delimiter));
        }
        return $this;
    }

    /**
     * @param $name
     * @return HttpHeaderInterface
     */
    public function remove($name)
    {
        if($this->exists($name)){
            $this->headers->offsetUnset($name);
        }
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name)
    {
        return $this->headers->offsetExists($name);
    }

    /**
     * @return array|\ArrayObject
     */
    public function getHeaders()
    {
        return $this->headers;
    }


}