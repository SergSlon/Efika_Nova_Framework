<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Commands;




use Efika\Application\Commands\DefaultCommand;

class MyCommand extends DefaultCommand{

    public function dispatch()
    {
        var_dump(__FILE__ . __LINE__);
        var_dump('hello world');
        var_dump($this->getParams());


    }
}