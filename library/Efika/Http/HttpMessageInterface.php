<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpMessageInterface
{

    /**
     * @param HttpMessageInterface $httpMessage
     * @param HttpRequestInterface | HttpResponseInterface $type
     */
    public function __construct(HttpMessageInterface $httpMessage = null, $type = null);

    /**
     * Add a header to header collection
     * @abstract
     * @param $name
     * @param $value
     * @return mixed
     */
    public function addHeader($name, $value);

    /**
     * Add a bunch of http headers
     * @abstract
     * @param HttpHeaderInterface $header
     * @return mixed
     */
    public function addHeaders(HttpHeaderInterface $header);

    /**
     * Get message body
     * @abstract
     * @return mixed
     */
    public function getBody();

    /**
     * Returns header field-value by header field-name
     * @abstract
     * @return mixed
     */
    public function getHeader();

    /**
     * Return http headers
     * @abstract
     * @return HttpHeaderInterface
     */
    public function getHeaders();

    /**
     * Get http version
     * @abstract
     * @return mixed
     */
    public function getHttpVersion();

    /**
     * Returns parent HttpMessage of an child message
     * @abstract
     * @return mixed
     */
    public function getParentMessage();

    /**
     * @abstract
     * @param string $body
     * @param $body
     * @return HttpMessageInterface
     */
    public function setBody($body);

    /**
     * Set http header
     * @abstract
     * @param HttpHeaderInterface $header
     * @return HttpMessageInterface
     */
    public function setHeaders(HttpHeaderInterface $header);

    /**
     * Set http version
     * @abstract
     * @param $version
     * @return HttpMessageInterface
     */
    public function setHttpVersion($version);

    /**
     * Type could be Response or Request
     * @abstract
     * @param HttpRequestInterface | HttpResponseInterface $type
     * @return HttpMessageInterface
     */
    public function setType($type);

    /**
     * send http message
     * @abstract
     * @return mixed
     */
    public function send();


}
