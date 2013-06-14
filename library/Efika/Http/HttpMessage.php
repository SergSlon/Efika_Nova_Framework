<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpMessage implements HttpMessageInterface
{

    const DEFAULT_HTTP_VERSION = '1.1';

    /**
     * @var HttpHeaderInterface
     */
    private $headers;

    /**
     * @var HttpContent
     */
    private $content;

    /**
     * @var HttpRequestInterface
     */
    private $request;

    /**
     * @var HttpResponseInterface
     */
    private $response;

    /**
     * @var string
     */
    private $httpVersion = self::DEFAULT_HTTP_VERSION;

    /**
     * @var HttpMessageInterface
     */
    private $parentMessage;

    /**
     * @param HttpMessageInterface $httpMessage Parent Message
     */
    public function __construct(HttpMessageInterface $httpMessage = null)
    {
        if ($httpMessage !== null) {
            $this->parentMessage = $httpMessage;
        }
    }

    /**
     * Get message Content
     * @return mixed
     */
    public function getContent()
    {
        if ($this->content === null)
            $this->content = new HttpContent();
        return $this->content;
    }

    /**
     * Returns header field-value by header field-name
     * @return mixed
     */
    public function getHeader()
    {
        if ($this->headers === null)
            $this->headers = new HttpHeader();
        return $this->headers;
    }

    /**
     * Get http version
     * @return mixed
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     * Returns parent HttpMessage of an child message
     * @return mixed
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * @return \Efika\Http\HttpRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Efika\Http\HttpResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $content
     * @param $content
     * @return HttpMessageInterface
     */
    public function setContent(HttpContent $content)
    {
        $this->content = $content;
    }

    /**
     * Set http header
     * @param HttpHeaderInterface $header
     * @return HttpMessageInterface
     */
    public function setHeader(HttpHeaderInterface $header)
    {
        $this->headers = $header;
    }

    /**
     * Set http version
     * @param $version
     * @return HttpMessageInterface
     */
    public function setHttpVersion($version)
    {
        $this->httpVersion = str_replace('HTTP/', '', $version);
    }

    /**
     * @param HttpRequestInterface $request
     * @return mixed
     */
    public function setRequest(HttpRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param HttpResponseInterface $response
     * @return mixed
     */
    public function setResponse(HttpResponseInterface $response)
    {
        $this->response = $response;
    }
}