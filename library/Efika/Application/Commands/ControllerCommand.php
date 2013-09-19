<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;
use Efika\Application\Router\Router;
use Efika\Http\HttpException;
use Efika\Http\HttpRequestInterface;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\Response;
use Efika\Http\Response\HttpContent;
use Efika\View\ViewInterface;
use Efika\View\ViewModel;
use Efika\View\ViewModelInterface;

class ControllerCommand implements DispatchableInterface, ParameterInterface {

    const DEFAULT_ACTION_PATTERN = '%actionId%Action'; //:actionId = placeholder
    const DEFAULT_ACTION_PLACEHOLDER = '%actionId%';

    protected $actionId = null;
    protected $controllerId = null;
    protected $viewId = null;
    protected $request = null;
    protected $response = null;
    protected $router = null;
    protected $params = null;
    protected $plugins = null;
    protected $view = null;
    protected $defaultViewPath = null;

    protected function resolveActionMethod(){
        $actionId = $this->getActionId();
        $actionMethod = str_replace(self::DEFAULT_ACTION_PLACEHOLDER,$actionId,self::DEFAULT_ACTION_PATTERN);

        if(!method_exists($this,$actionMethod)){
            throw new ControllerLogicalException(sprintf('Can not resolve method %s by action-id %s',$actionMethod,$actionId));
        }

        return $actionMethod;
    }

    protected function generateViewId(){
        return sprintf('%s/%s', $this->getControllerId() , $this->getActionId());
    }

    public function forward($route, Request $request = null, Response $response = null){

    }

    public function addPreFilter($callback){

    }

    public function addPostFilter($callback){

    }

    /**
     * @param \Efika\Http\PhpEnvironment\Request $request
     * @param \Efika\Http\PhpEnvironment\Response $response
     * @return ViewModelInterface|HttpResponseInterface|false|null
     */
    public function dispatch(Request $request, Response $response)
    {

        $response = $this->getResponse();
        $message = $response->getHttpMessage();

        try {
            // Execute Action
            $result = call_user_func(array($this,$this->resolveActionMethod()));

            //route result
            if(is_array($result) || $result === null){
                $result = new ViewModel();
            }

            if(is_string($result)){
                $result = new HttpContent([$result]);
            }

            $view = $this->getView();
            if($result instanceof ViewModelInterface){
//                $collection = new ViewVarCollection($result === null ? array() : $result);
//                $result->setVarCollection($collection);
                if($result->getViewPath() === null){
                    $result->setViewPath($this->getDefaultViewPath());
                }

                $viewId = $this->getViewId();

                if($viewId === null){
                    $this->setViewId($this->generateViewId());
                    $viewId = $this->getViewId();
                }

                $result->setView($viewId);
                $view->setViewModel($result);
            }

            if($view->getViewModel() instanceof ViewModelInterface){
                $viewEventResponse = $view->execute();
                $result = new HttpContent([$viewEventResponse->getEvent()->getRenderedContent()]);
            }

            if($result instanceof HttpContent){
                $message->setContent($result);
            }

            if($result instanceof HttpResponseInterface){
                $this->setResponse($result);
                $message->setResponse($this->getResponse());
            }

        } catch (HttpException $e){

            $message->setContent(
                new HttpContent([$e->getMessage()])
            );
            $code = $e->getCode() != 0 ? $e->getCode() : 404;
            $response->setResponseCode($code);

        } catch (\Exception $e){
            $message->setContent(
                new HttpContent([$e->getMessage()])
            );
            $response->setResponseCode('500');
        }

        return $response;
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
     * @param null $viewId
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;
    }

    /**
     * @return null
     */
    public function getViewId()
    {
        return $this->viewId;
    }

    /**
     * @return \Efika\Http\HttpResponseInterface|null
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

    /**
     * @param \Efika\View\ViewInterface|null $view
     */
    public function setView(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     * @return \Efika\View\ViewInterface|\Efika\View\View|null
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Will set while resolve controller
     * @param null|string $defaultViewPath
     * @return bool
     */
    public function setDefaultViewPath($defaultViewPath)
    {
        if($this->defaultViewPath === null){
            $this->defaultViewPath = $defaultViewPath;
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return null
     */
    public function getDefaultViewPath()
    {
        if($this->defaultViewPath === null){

            $path = (new \ReflectionClass($this))->getFileName();
            $previousPath = null;
            $viewPath = null;

            while(($path = dirname($path)) !== $previousPath){
                $previousPath = $path;
                $possibleViewPath = sprintf('%s/%s', $path, ViewInterface::DEFAULT_VIEW_FOLDER);
                if(file_exists($possibleViewPath)){
                    $viewPath = $possibleViewPath;
                    break;
                }
            }
            $this->setDefaultViewPath($viewPath);
        }
        return $this->defaultViewPath;
    }

}