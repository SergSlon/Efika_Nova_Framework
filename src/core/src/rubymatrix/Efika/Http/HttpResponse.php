<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http;


class HttpResponse implements HttpResponseInterface
{

    const DEFAULT_ENCODING = 'utf-8';
    const X_FRAME_OPTIONS = 'X-Frame-Options';
    const X_XSS_PROTECTION = 'X-XSS-PROTECTION';
    const X_CONTENT_TYPE_OPTIONS = 'X-CONTENT-TYPE-OPTIONS';

    private $responseCode = 200;
    private $responseStatusMessages = [
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * @var HttpMessageInterface|null
     */
    private $httpMessage = null;

    private $outputEncoding = self::DEFAULT_ENCODING;


    /**
     * @param \Efika\Http\HttpMessage|\Efika\Http\HttpMessageInterface $httpMessage
     * @return \Efika\Http\HttpResponse
     */
    public function __construct(HttpMessage $httpMessage)
    {
        $httpMessage->setResponse($this);
        $this->httpMessage = $httpMessage;
    }

    /**
     * @return null
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param null $code
     * @throws \InvalidArgumentException
     * @return $this|\Efika\Http\HttpMessageInterface
     */
    public function setResponseCode($code)
    {
        if (!array_key_exists($code, $this->responseStatusMessages) || !is_numeric($code)) {
            $code = is_scalar($code) ? $code : gettype($code);
            throw new \InvalidArgumentException(sprintf(
                'Invalid status code provided: "%s"',
                $code
            ));
        }

        $this->responseCode = intval($code);
        return $this;
    }


    /**
     * @return null
     * @return mixed|null
     */
    public function getResponseStatusMessage()
    {
        return array_key_exists($this->getResponseCode(), $this->responseStatusMessages) ? $this->responseStatusMessages[$this->getResponseCode()] : null;
    }

    /**
     * Get http message
     * @return HttpMessageInterface $httpMessage
     */
    public function getHttpMessage()
    {
        return $this->httpMessage;
    }

    /**
     * @param string $outputEncoding
     */
    public function setOutputEncoding($outputEncoding)
    {
        $this->outputEncoding = $outputEncoding;
    }

    /**
     * @return string
     */
    public function getOutputEncoding()
    {
        return $this->outputEncoding;
    }

    public function acivateXFrameOptions($boolean = true, $option = 'deny')
    {
        $name = self::X_FRAME_OPTIONS;
        $this->applyHeader($boolean, $name, $option);
    }

    public function acivateXXSSProtection($boolean = true, $option = '1; mode=block')
    {
        $name = self::X_XSS_PROTECTION;
        $this->applyHeader($boolean, $name, $option);
    }

    public function acivateXContentTypeOptions($boolean = true, $option = 'nosniff')
    {
        $name = self::X_CONTENT_TYPE_OPTIONS;
        $this->applyHeader($boolean, $name, $option);
    }

    protected function applyHeader($boolean = true, $name = null, $option = null)
    {
        $header = $this->getHttpMessage()->getHeader();
        if ($boolean) {
            $header->add($name, $option);
        } else {
            $header->remove($name);
        }
    }
}