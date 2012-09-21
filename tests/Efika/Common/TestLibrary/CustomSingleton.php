<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace EfikaTest\Common\TestLibrary;

use Efika\Common\SingletonTrait;

class CustomSingleton
{
    use SingletonTrait;

    public function regularMethod(){
        return 'something like a string';
    }

}
