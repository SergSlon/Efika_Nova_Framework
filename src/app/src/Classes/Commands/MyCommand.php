<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Commands;




use Efika\Application\Commands\DefaultCommand;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\Response;

class MyCommand extends DefaultCommand{

    public function dispatch(Request $request, Response $response)
    {
        var_dump(__FILE__ . __LINE__);
        var_dump('hello world');
        var_dump($this->getParams());


    }


}