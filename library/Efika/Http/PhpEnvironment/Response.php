<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\PhpEnvironment;

use Efika\Http\HttpMessage;
use Efika\Http\HttpResponse;

class Response extends HttpResponse{

    const DEFAULT_CONTENT_TYPE  = 'text/html';

    public function __construct(HttpMessage $httpMessage)
    {
        $httpMessage->setHttpVersion($_SERVER['SERVER_PROTOCOL']);
        parent::__construct($httpMessage);
    }


    public function sendHeaders()
    {
        $header = $this->getHttpMessage()->getHeader();
        $httpProtocol = sprintf('HTTP/%s',$this->getHttpMessage()->getHttpVersion());

        //add current status if unknown
        if (!($header->exists('Status') || $header->exists($httpProtocol))) {

            if (strpos(php_sapi_name(), 'cgi') !== false) {
                $header->add('Status', sprintf('%s %s',$this->getResponseCode(),$this->getResponseStatusMessage()));
            } else {
                $header->add(
                    $httpProtocol,
                    sprintf('%s %s',$this->getResponseCode(),$this->getResponseStatusMessage()),
                    ' '
                );
            }
        }

        if(!$header->exists('Content-type')){
            $header->add('Content-type', sprintf('%s; charset=%s',self::DEFAULT_CONTENT_TYPE, self::DEFAULT_ENCODING));
        }

        $result = [];

        foreach($header->getHeaders() as $headers){
            $string = sprintf('%s%s %s', $headers->getName(), $headers->getDelimiter(), $headers->getValue());
            $result[$headers->getName()] = header($string);
        }

        return $result;

    }

    /**
     * Send content to browser
     * @param bool $return
     * @return bool|mixed
     */
    public function sendBody($return = false)
    {
        //fix encoding issues
        mb_internal_encoding("UTF-8");
        mb_http_output( "UTF-8" );
        ob_start("mb_output_handler");
        echo $this->getHttpMessage()->getContent();
        $content = ob_get_clean();

        if($return){
            return $content;
        }else{
            echo $content;
            return true;
        }
    }

    /**
     * send http message and echo output
     * @return mixed
     */
    public function send()
    {
        $this->sendHeaders();
        return $this->sendBody();
    }
}