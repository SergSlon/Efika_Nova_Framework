<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

/**
 * HttpRequest::establish('index/index', HttpRequestInterface::POST')
 *  ->setHttpMessage(new HttpMessage())
 *  ->send()
 *  ->response()
 *  ->send();
 */
interface HttpRequestInterface
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const TRACE = 'TRACE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';
    const HEAD = 'HEAD';

    /**
     * Build a new request
     * @static
     * @abstract
     * @param null $requestUrl
     * @param null $requestMethod
     * @return mixed
     */
    public static function establish($requestUrl=null,$requestMethod=null);

    /**
     * Get http message object
     * @abstract
     * @param HttpMessageInterface $httpMessage
     * @return mixed
     */
    public function getHttpMessage(HttpMessageInterface $httpMessage);

    /**
     * Get requested method
     * @abstract
     * @return mixed
     */
    public function getRequestMethod();

    /**
     * Get requested Method
     * @abstract
     * @return mixed
     */
    public function getRequestUrl();

    /**
     * Set http message
     * @abstract
     * @param HttpMessageInterface $httpMessage
     * @return mixed
     */
    public function setHttpMessage(HttpMessageInterface $httpMessage);

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
     * @abstract
     * @param string $method
     * @return HttpMessageInterface
     */
    public function setRequestMethod($method);

    /**
     * Set request url
     * @abstract
     * @param string | HttpRequestUrl $url
     * @return HttpMessageInterface
     */
    public function setRequestUrl($url);

    /**
     * send request and returns http response
     * @abstract
     * @return HttpResponseInterface
     */
    public function send();

}
