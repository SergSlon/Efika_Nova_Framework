<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Controller;


use Efika\Application\Commands\ControllerCommand;
use Efika\Http\Response\HttpContent;

class IndexController extends ControllerCommand{

    public function indexAction(){
//        return new HttpContent(['Hello World',"\n",'Welcome!']);
//        return 'hello world!';
    }

}