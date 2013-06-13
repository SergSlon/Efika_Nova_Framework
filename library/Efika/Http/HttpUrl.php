<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpUrl implements HttpUrlInterface{
    private $fragment;
    private $host;
    private $password;
    private $port;
    private $scheme;
    private $schemeDelimiter;
    private $searchPart;
    private $urlPath;

    /**
     * @param null $url
     */
    public function __construct($url = null)
    {
        // TODO: url parser
    }

    /**
     * @param mixed $fragment
     * @return $this|\Efika\Http\HttpUrlInterface
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param mixed $host
     * @return $this|mixed
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $password
     * @return $this|mixed
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $port
     * @return $this|mixed
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $scheme
     * @param string $delimiter
     * @return $this|mixed
     */
    public function setScheme($scheme,$delimiter='://')
    {
        $this->scheme = $scheme;
        $this->schemeDelimiter = $delimiter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param mixed $schemeDelimiter
     * @return $this
     */
    public function setSchemeDelimiter($schemeDelimiter)
    {
        $this->schemeDelimiter = $schemeDelimiter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSchemeDelimiter()
    {
        return $this->schemeDelimiter;
    }

    /**
     * @param mixed $searchPart
     * @return $this|mixed
     */
    public function setSearchPart($searchPart)
    {
        $this->searchPart = $searchPart;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSearchPart()
    {
        return $this->searchPart;
    }

    /**
     * @param mixed $urlPath
     * @return mixed|void
     */
    public function setUrlPath($urlPath)
    {
        $this->urlPath = $urlPath;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getUrlPath()
    {
        return $this->urlPath;
    }

    public function composeUrl(){
        // TODO: add url composer
    }
}