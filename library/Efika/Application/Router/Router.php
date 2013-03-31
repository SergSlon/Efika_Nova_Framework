<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;

/**
 * Class Router
 * @package Efika\Application
 */
class Router
{

    /**
     * @var array
     * TODO replace by routerStack
     */
    protected $routes = array();
    /**
     * @var RouterResult|null
     */
    protected $result = null;
    /**
     * @var null
     */
    protected $matcherCommand = null;


    /**
     * @param $routes
     */
    public function __construct($routes)
    {
        $this->setRoutes($routes);
        $this->result = new RouterResult();

    }

    /**
     * @param $request
     * @return mixed
     */
    public function match($request)
    {
        $this->result->flush();
        $result = $this->result;
        if($this->matcherCommand === null){
            $result = $this->simpleMatcher($request,$result);
        }else{
            //Route matcher strategy
            //$routeMatcher = new AnyRouteMatcher($this);
            //$routeMatcher->match($request,$this->result)
        }

        return $result;
    }

    /**
     * Match route by given pattern
     *
     * For example:
     * array(
     *  '/' => 'index/index',
     *  '/(?P<controller>\w+)/(?P<action>\w+)/(?P<params>[a-zA-Z0-9_]+)' => ':controller/:action/:params',
     *  '/foo/(?P<params>[a-zA-Z0-9_]+)/view' => 'foo/view/:params',
     * )
     * @param $request
     * @param RouterResult $result
     * @return mixed
     */
    protected function simpleMatcher($request,RouterResult $result){
        foreach ($this->getRoutes() as $pattern => $route) {

            $matched = preg_match(
                '#^' . $pattern . '$#i',
                $request,
                $matches
            );

            if($matched !== 0){
                $result->offsetSet($pattern,$matches);
            }

        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param $routes
     */
    protected function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return \Efika\Application\Router\RouterResult|null
     */
    public function getPreviousResult()
    {
        return $this->result;
    }

    /**
     * @param \Efika\Application\Router\RouterResult|null $result
     */
    protected function setResult($result)
    {
        $this->result = $result;
    }
}