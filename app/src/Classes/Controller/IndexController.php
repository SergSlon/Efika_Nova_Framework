<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication\Controller;


use Efika\Application\Commands\ControllerCommand;
use Efika\Http\Response\HttpContent;
use Efika\View\ViewModel;

class IndexController extends ControllerCommand{

    public function indexAction(){
        $viewModel = new ViewModel();
        $viewModel->assignVar('Masura', 'plasterium');
//        return new HttpContent(['Hello World',"\n",'Welcome!']);
//        return 'hello world!';
        return $viewModel;
    }

}