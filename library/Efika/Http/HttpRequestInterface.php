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
     * @param \Efika\Http\HttpMessageInterface $httpMessage
     */
    public function __construct(HttpMessageInterface $httpMessage);

    /**
     * Get http message object
     * @abstract
     * @internal param \Efika\Http\HttpMessageInterface $httpMessage
     * @return mixed
     */
    public function getHttpMessage();

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
