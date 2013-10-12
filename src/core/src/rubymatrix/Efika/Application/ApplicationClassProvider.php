<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;


use Efika\Di\DiContainerAwareTrait;

class ApplicationClassProvider {
    use DiContainerAwareTrait;

    private $httpResponse;
    private $httpRequest;
    private $router;
    private $httpMessage;
    private $application;
    private $applicationEvent;
    private $applicationService;
    private $httpApplicationService;

    /**
     * @return mixed
     */
    public function getHttpMessage()
    {
        return $this->getDiContainer()->getClassAsService('Efika\Http\HttpMessage')->applyInstance();
    }

    /**
     * @return mixed
     */
    public function getHttpRequest()
    {
        return $this->getDiContainer()->getClassAsService('Efika\Http\PhpEnvironment\Request')->applyInstance([$this->getHttpMessage()]);
    }

    /**
     * @return mixed
     */
    public function getHttpResponse()
    {
        return $this->getDiContainer()->getClassAsService('Efika\Http\PhpEnvironment\Response')->applyInstance([$this->getHttpMessage()]);
    }

    /**
     * @return mixed
     */
    public function getRouter()
    {
        return $this->getDiContainer()->getClassAsService('Efika\Http\HttpMessage')->applyInstance();
    }




}