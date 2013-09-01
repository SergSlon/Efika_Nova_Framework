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
use Efika\Http\Response\HttpContent;
use Efika\View\ViewInterface;
use Efika\View\ViewModel;
use Efika\View\ViewModelInterface;
use Efika\View\ViewVarCollection;

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
    protected $view = null;

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
        $view = $this->getView();
        $response = $this->getResponse();
        $message = $response->getHttpMessage();
        $content = null;

        try {
            // Execute Action
            $result = call_user_func(array($this,$this->resolveActionMethod()));

//            var_dump(__FILE__ . __LINE__);
//            var_dump($result);


            if(is_array($result)){
                $collection = new ViewVarCollection($result);
                $result = new ViewModel();
                $result->setVarCollection($collection);
            }else if($result instanceof ViewModelInterface){
                $view->setViewModel($result);
                $view->resolve();
                $content = new HttpContent($view->render());
                $message->setContent($content);
            }else if($result instanceof HttpContent){
                $message->setContent($result);
            }
        } catch (HttpException $e){
            $content = new HttpContent([$e->getMessage()]);
            $message->setContent($content);
            $response->setResponseCode('404');

        } catch (\Exception $e){
            $content = new HttpContent([$e->getMessage()]);
            $message->setContent($content);
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
     * @return \Efika\View\ViewInterface|null
     */
    public function getView()
    {
        return $this->view;
    }

}