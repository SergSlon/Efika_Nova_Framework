<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpRequest implements HttpRequestInterface{

    private $httpMessage = null;

    private $requestMethod = null;

    private $requestUrl = null;

    private $rawRequest = [];

    /**
     * @param \Efika\Http\HttpMessageInterface $httpMessage
     */
    public function __construct(HttpMessageInterface $httpMessage)
    {
        $httpMessage->setRequest($this);
        $this->httpMessage = $httpMessage;
    }

    /**
     * Build a new request
     * @static
     * @param null $requestUrl
     * @param null $requestMethod
     * @return mixed
     */
    public static function establish($requestUrl = null, $requestMethod = null)
    {

        var_dump($_SERVER);

        $message = new HttpMessage();
        $message->setHttpVersion($_SERVER['SERVER_PROTOCOL']);

        $request = new self($message);
        $request->setRawRequest($_SERVER);
        if($requestUrl === null){
            $requestUrl = new HttpUrl();
            $requestUrl->setHost($_SERVER['HTTP_HOST']);
            $requestUrl->setPort($_SERVER['SERVER_PORT']);
            $requestUrl->setScheme($_SERVER['REQUEST_SCHEME']);
            $requestUrl->setUrlPath($_SERVER['REQUEST_URI']);

        }else{
            $requestUrl = new HttpUrl($requestUrl);
        }
        $request->setRequestUrl($requestUrl);

        if($requestMethod === null){
            $requestMethod = $_SERVER['REQUEST_METHOD'];
        }
        $request->setRequestMethod($requestMethod);
    }

    /**
     * Get http message object
     * @internal param \Efika\Http\HttpMessageInterface $httpMessage
     * @return mixed
     */
    public function getHttpMessage()
    {
        return $this->httpMessage;
    }

    /**
     * Get requested method
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Get requested Method
     * @return mixed
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * Set a requested method
     * - GET
     * - POST
     * - PUT
     * - DELETE
     * - TRACE
     * - CONNECT
     * - OPTIONS
     * - HEAD
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
     * @param string $method
     * @return HttpMessageInterface
     */
    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
    }

    /**
     * Set request url
     * @param string | HttpRequestUrl $url
     * @return HttpMessageInterface
     */
    public function setRequestUrl($url)
    {
        $this->requestUrl = $url;
    }

    /**
     * send request and returns http response
     * @return HttpResponseInterface
     */
    public function send()
    {
        // TODO: Implement send() method.
        //establish curlrequest
    }

    /**
     * @return array
     */
    public function getRawRequest()
    {
        return $this->rawRequest;
    }

    /**
     * @param array $rawRequest
     */
    public function setRawRequest($rawRequest)
    {
        $this->rawRequest = $rawRequest;
    }
}