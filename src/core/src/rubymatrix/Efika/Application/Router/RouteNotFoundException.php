<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;

use Exception as PhpException;

class RouteNotFoundException extends PhpException
{

    private $request;

    public function __construct($message = "", $request=null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }


}
