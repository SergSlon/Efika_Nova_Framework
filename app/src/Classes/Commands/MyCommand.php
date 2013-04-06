<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Commands;


use Efika\Application\Commands\CommandInterface;

class MyCommand implements CommandInterface{

    public function execute($params = [])
    {
        var_dump('hello world');
    }
}