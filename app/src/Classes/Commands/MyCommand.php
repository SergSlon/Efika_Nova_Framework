<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Commands;




use Efika\Application\Commands\DefaultCommand;

class MyCommand extends DefaultCommand{

    public function execute()
    {
        var_dump('hello world');
        var_dump($this->getParams());
    }
}