<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Commands\Plugins\PluginAwareInterface;
use Efika\Application\Commands\Plugins\PluginManager;
use Efika\Application\Dispatcher\DispatchableInterface;
use Efika\Application\Router\RouterAwareTrait;
use Efika\Common\ParameterInterface;
use Efika\Common\ParameterTrait;
use Efika\Di\DiContainer;
use Efika\Http\HttpException;
use Efika\Http\HttpResponseInterface;
use Efika\Http\PhpEnvironment\Request;
use Efika\Http\PhpEnvironment\RequestAwareTrait;
use Efika\Http\PhpEnvironment\Response;
use Efika\Http\PhpEnvironment\ResponseAwareTrait;
use Efika\Http\Response\HttpContent;
use Efika\View\ViewAwareTrait;
use Efika\View\ViewInterface;
use Efika\View\ViewModel;
use Efika\View\ViewModelInterface;

class ControllerCommand implements DispatchableInterface, ParameterInterface, PluginAwareInterface {

    use RouterAwareTrait;
    use RequestAwareTrait;
    use ResponseAwareTrait;
    use ParameterTrait;
    use ViewAwareTrait;

    const DEFAULT_ACTION_PATTERN = '%actionId%Action'; //:actionId = placeholder
    const DEFAULT_ACTION_PLACEHOLDER = '%actionId%';

    protected $actionId = null;
    protected $controllerId = null;
    protected $viewId = null;
    protected $pluginManager = null;
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

    /**
     * Hook will executed before Controller is dispatching
     */
    public function beforeDispatch(){

    }

    /**
     * Hook will executed after controller is dispatching
     * @param $response
     */
    public function afterDispatch($response){

    }

    /**
     * @param \Efika\Http\PhpEnvironment\Request $request
     * @param \Efika\Http\PhpEnvironment\Response $response
     * @return ViewModelInterface|HttpResponseInterface|false|null
     */
    public function dispatch(Request $request, Response $response)
    {

        $this->beforeDispatch();

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

        $this->afterDispatch($response);

        return $response;
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

    /**
     * @return PluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    /**
     * @param PluginManager $pluginManager
     */
    public function setPluginManager(PluginManager $pluginManager)
    {
        $pluginManager->setParentCommand($this);
        $this->pluginManager = $pluginManager;
    }
}