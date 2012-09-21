<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;

interface HttpResponseInterface
{

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
     * Set http message
     * @abstract
     * @param HttpMessageInterface $httpMessage
     * @return mixed
     */
    public function setHttpMessage(HttpMessageInterface $httpMessage);
    /**
     * Set response-status by code
     * @abstract
     * @param int $code
     * @return HttpMessageInterface
     */
    public function setResponseStatus($code);

    /**
     * send http message
     * @abstract
     * @return mixed
     */
    public function send();

}
