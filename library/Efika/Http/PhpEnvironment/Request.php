<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\PhpEnvironment;


use Efika\Http\HttpMessageInterface;
use Efika\Http\HttpRequest;
use Efika\Http\HttpUrl;

class Request extends HttpRequest
{

    private $env = null;
    private $query = null;
    private $post = null;
    private $cookies = null;
    private $files = null;
    private $server = null;
    private $url = null;

    /**
     * @param null $cookies
     */
    public function setCookies($cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * @return null
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param null $env
     */
    public function setEnv($env)
    {
        $this->env = $env;
    }

    /**
     * @return null
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param null $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return null
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param null $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return null
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param null $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param null $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return null
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param null $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return null
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function __construct(HttpMessageInterface $httpMessage)
    {
        parent::__construct($httpMessage);
        $this->setEnv(new ParameterCollection($_ENV));

        if ($_GET) {
            $this->setQuery(new ParameterCollection($_GET));
        }
        if ($_POST) {
            $this->setPost(new ParameterCollection($_POST));
        }
        if ($_COOKIE) {
            $this->setCookies(new ParameterCollection($_COOKIE));
        }
        if ($_FILES) {
            // convert PHP $_FILES superglobal
            //$files = $this->mapPhpFiles();
            //$this->setFiles(new Parameters($files));
        }

        $this->setServer(new ParameterCollection($_SERVER));
    }

    /**
     * @param \Efika\Http\HttpUrlInterface|string $url
     * @return HttpMessageInterface
     */
    public function setRequestUrl($url)
    {
        if ($url === null) {
            $httpUrl = new HttpUrl();
            $httpUrl->setHost($_SERVER['HTTP_HOST']);
            $httpUrl->setPort($_SERVER['SERVER_PORT']);
            $httpUrl->setScheme($_SERVER['REQUEST_SCHEME']);
            $httpUrl->setUrlPath($_SERVER['REQUEST_URI']);
            $httpUrl->setQuery($_SERVER['QUERY_STRING']);

        } else {
            $httpUrl = new HttpUrl($url);
        }

        return parent::setRequestUrl($httpUrl);
    }

    /**
     * @param string $method
     * @return HttpMessageInterface
     */
    public function setRequestMethod($method)
    {
        if($method === null){
            $method = $_SERVER['REQUEST_METHOD'];
        }
        return parent::setRequestMethod($method);
    }


}