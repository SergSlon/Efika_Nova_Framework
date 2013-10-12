<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Controller;


use Efika\Application\Commands\ControllerCommand;
use Efika\Http\HttpException;
use Efika\Http\Response\HttpContent;

class AnyController extends ControllerCommand{

    public function wayAction(){
        var_dump(__FILE__ . __LINE__);
//        var_dump($this->getParams());
//        throw new HttpException('Hey, error!',400);
//        return array('vars' => 'null');
        return new HttpContent(['My content string!',"\n",'next line']);
    }

}