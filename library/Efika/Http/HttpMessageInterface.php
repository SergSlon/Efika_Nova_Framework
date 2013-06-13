<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpMessageInterface
{

    /**
     * @param HttpMessageInterface $httpMessage Parent Message
     */
    public function __construct(HttpMessageInterface $httpMessage = null);

    /**
     * Get message Content
     * @abstract
     * @return mixed
     */
    public function getContent();

    /**
     * Returns header field-value by header field-name
     * @abstract
     * @return mixed
     */
    public function getHeader();

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
     * @return \Efika\Http\HttpRequestInterface
     */
    public function getRequest();

    /**
     * @return \Efika\Http\HttpResponseInterface
     */
    public function getResponse();

    /**
     * @abstract
     * @param string $content
     * @param $content
     * @return HttpMessageInterface
     */
    public function setContent(HttpContent $content);

    /**
     * Set http header
     * @abstract
     * @param HttpHeaderInterface $header
     * @return HttpMessageInterface
     */
    public function setHeader(HttpHeaderInterface $header);

    /**
     * Set http version
     * @abstract
     * @param $version
     * @return HttpMessageInterface
     */
    public function setHttpVersion($version);

    /**
     * @param HttpRequestInterface $request
     * @return mixed
     */
    public function setRequest(HttpRequestInterface $request);

    /**
     * @param HttpResponseInterface $response
     * @return mixed
     */
    public function setResponse(HttpResponseInterface $response);

}
