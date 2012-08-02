<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace EfikaTest\EventManager\TestLibrary;

use Efika\EventManager\Event;

class CustomEvent extends Event
{

    public function forHandler(){
        return true;
    }

}
