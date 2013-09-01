<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Services;


use Efika\Application\ApplicationEvent;
use Efika\Application\ApplicationService;

class HttpApplicationService extends ApplicationService{

    const DEFAULT_APPLICATION_NAMESPACE = 'WebApplication';

    protected $appNs = self::DEFAULT_APPLICATION_NAMESPACE;

    public function onApplicationInit(ApplicationEvent $event){
        $this->getLogger()->addMessage('Init Application by arguments');
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPreProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('Preprocess application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('process application');

    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationPostProcess(ApplicationEvent $event){
        $this->getLogger()->addMessage('post process application');
    }

    /**
     * @param ApplicationEvent $event
     */
    public function onApplicationComplete(ApplicationEvent $event){
        $this->getLogger()->addMessage('complete application');
    }

}