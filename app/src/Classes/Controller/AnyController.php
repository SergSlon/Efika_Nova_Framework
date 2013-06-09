<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Controller;


use Efika\Application\Commands\ControllerCommand;

class AnyController extends ControllerCommand{

    public function wayAction($to='empty yo!'){
        var_dump($to);
    }

}