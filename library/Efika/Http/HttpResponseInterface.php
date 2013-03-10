<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpResponseInterface
{

    /**
     * @param \Efika\Http\HttpMessageInterface $httpMessage
     */
    public function __construct($httpMessage);

    /**
     * Get response code
     * @abstract
     * @return mixed
     */
    public function getResponseCode();

    /**
     * get message response-code
     * @abstract
     * @return mixed
     */
    public function getResponseStatus();

    /**
     * Get http message
     * @abstract
     * @return HttpMessageInterface $httpMessage
     */
    public function getHttpMessage();

    /**
     * Set response-status by code
     * @abstract
     * @param int $code
     * @return HttpMessageInterface
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     */
    public function setResponseStatus($code);

    /**
     * send http message
     * @abstract
     * @return mixed
     */
    public function send();

}
