<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Commands;


use Efika\Application\Dispatcher\DispatchableInterface;

class ControllerCommand implements DispatchableInterface, ParameterInterface {

    protected $action = null;
    protected $request = null;
    protected $router = null;
    protected $params = null;
    protected $plugins = null;

    public function execute()
    {

        var_dump(__FILE__ . __LINE__);
        var_dump($this->getAction());
        // Execute Action
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
     * @param null $request
     */
    public function setRequest($request)
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
     * @param null $router
     */
    public function setRouter($router)
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param null $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

}