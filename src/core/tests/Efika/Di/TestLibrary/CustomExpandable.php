<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace EfikaTest\Di\TestLibrary;

use Efika\Di\DiExpandableTrait;
use Efika\Di\DiExpendableInterface;

class CustomExpandable implements DiExpendableInterface
{
    use DiExpandableTrait;

    public function regularMethod(){
        return 'something like a string';
    }

}
