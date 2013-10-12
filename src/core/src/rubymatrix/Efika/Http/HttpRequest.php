<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpRequest implements HttpRequestInterface
{

    private $httpMessage = null;

    private $requestMethod = null;

    private $requestUrl = null;

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
     * @param \Efika\Http\HttpMessage|null $parentHttpMessage
     * @return \Efika\Http\HttpRequest|mixed
     */
    public static function establish($requestUrl = null, $requestMethod = null, HttpMessage $parentHttpMessage = null)
    {

        if ($parentHttpMessage === null) {
            $parentHttpMessage = new HttpMessage();
        }

        $request = new self($parentHttpMessage);
        $request->setRequestUrl($requestUrl);
        $request->setRequestMethod($requestMethod);

        return $request;
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
        return $this;
    }

    /**
     * Set request url
     * @param string | HttpUrlInterface $url
     * @return HttpMessageInterface
     */
    public function setRequestUrl($url)
    {
        $this->requestUrl = $url;
        return $this;
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

}