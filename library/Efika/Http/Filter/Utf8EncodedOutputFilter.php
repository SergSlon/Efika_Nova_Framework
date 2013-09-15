<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Http\Filter;


use Efika\Http\Filter\FilterInterface;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Response;

class Utf8EncodedOutputFilter implements FilterInterface{

    public function filter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        $message = $response->getHttpMessage();

        //fix encoding issues
        mb_internal_encoding("UTF-8");
        mb_http_output( "UTF-8" );
        ob_start("mb_output_handler");
        echo $message->getContent();
        $content = ob_get_clean();
        $message->setContent($content);
    }
}