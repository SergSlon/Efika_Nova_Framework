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

    public function onInit(){
        $this->getPluginManager()->register('filter', 'Efika\Application\Commands\Plugins\FilterPlugin');
    }

    public function indexAction(){
//        var_dump($this->getPluginManager()->filter()->getCommand());
        $viewModel = new ViewModel();

        $detailView = new ViewModel();
        $detailView->setViewPath($this->getDefaultViewPath());
        $detailView->setView('index/detail');
        $detailView->assignVar('container_title', 'Detail container');

        $panelView = new ViewModel();
        $panelView->setViewPath($this->getDefaultViewPath());
        $panelView->setView('someview');
        $panelView->assignVar('title', 'Panel container');
        $panelView->assignVar('subtitle', 'A panel container in someview');
        $panelView->assignVar('copyright', 'me &copy;');
        $panelView->addChildren('detail', $detailView);

        $viewModel->assignVar('title', 'Efika Nova Framework');
        $viewModel->assignVar('content', 'Hello world!');
        $viewModel->addChildren('panel', $panelView);
//        return new HttpContent(['Hello World',"\n",'Welcome!']);
//        return 'hello world!';
        
        return $viewModel;
    }

}