<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;

class MyCommand implements DispatchableInterface{

    public function execute($params = [])
    {
        var_dump('hello world');

        var_dump($params);
    }
}