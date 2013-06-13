<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;
use Efika\Application\Router\Router;
use Efika\Application\Router\RouterInterface;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\View\ViewModelInterface;

class ControllerCommand implements DispatchableInterface, ParameterInterface {

    const DEFAULT_ACTION_PATTERN = '%actionId%Action'; //:actionId = placeholder
    const DEFAULT_ACTION_PLACEHOLDER = '%actionId%';

    protected $actionId = null;
    protected $controllerId = null;
    protected $request = null;
    protected $response = null;
    protected $router = null;
    protected $params = null;
    protected $plugins = null;

    protected function resolveActionMethod(){
        $actionId = $this->getActionId();
        $actionMethod = str_replace(self::DEFAULT_ACTION_PLACEHOLDER,$actionId,self::DEFAULT_ACTION_PATTERN);

        if(!method_exists($this,$actionMethod)){
            throw new ControllerLogicalException(sprintf('Can not resolve method %s by action-id %s',$actionMethod,$actionId));
        }

        return $actionMethod;
    }

    /**
     * @return ViewModelInterface|HttpResponseInterface|false|null
     */
    public function dispatch()
    {


        // Execute Action
        $result = call_user_func(array($this,$this->resolveActionMethod()));

        var_dump(__FILE__ . __LINE__);
        var_dump($result);
        /**
         * Result could be null, false, ViewModel or HttpResponse
         *
         *  - null = empty result, use ViewStrategy
         *  - false = no ViewModel or HttpResponse, no output, use ErrorStrategy (400, Bad Request)
         *  - ViewModel = process returned ViewModel, use ViewAwareStrategy
         *  - Response = process returned Response, use ResponseAwareStrategy
         *
         * Strategy will resolved by output processor
         */

        /**
         * ------------------------------------------------------------
         */

        /**
         *
         */

        return $result;
    }

    /**
     * @param null $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param null $plugins
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @return null
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param \Efika\Http\HttpRequestInterface|null $request
     */
    public function setRequest(HttpRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Efika\Application\Router\Router|null $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return null
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return null
     */
    public function getActionId()
    {
        return $this->actionId;
    }

    /**
     * @param null $action
     */
    public function setActionId($action)
    {
        $this->actionId = $action;
    }

    /**
     * @return null
     */
    public function getControllerId()
    {
        return $this->controllerId;
    }

    /**
     * @param null $controllerId
     */
    public function setControllerId($controllerId)
    {
        $this->controllerId = $controllerId;
    }

    /**
     * @return null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Efika\Http\HttpResponseInterface|null $response
     */
    public function setResponse(HttpResponseInterface $response = null)
    {
        $this->response = $response;
    }

}