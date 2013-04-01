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
class Router implements RouterInterface
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
     *
     */
    public function __construct()
    {
        $this->result = new RouterResult();
    }

    /**
     * Match request with given routes
     * @param $request
     * @return mixed
     */
    public function match($request)
    {
        $this->result->flush();
        $result = $this->result;
        if($this->matcherCommand === null){
            $result = $this->matcherFallback($request,$result);
        }else{
            //Route matcher strategy
            //$routeMatcher = new AnyRouteMatcher($this);
            //$routeMatcher->match($request,$this->result)
        }

        $this->setResult($result);

        return $result;
    }

    /**
     * Match route by given regular expression
     *
     * For example:
     * array(
     *  '/' => 'index/index',
     *  '/(?P<controller>\w+)/(?P<action>\w+)/(?P<params>[a-zA-Z0-9_]+)' => ':controller/:action/:params',
     *  '/foo/(?P<params>[a-zA-Z0-9_]+)/view' => 'foo/view/:params',
     * )
     * @param $request
     * @param RouterResult $result
     * @param string $idDelimiter
     * @return mixed
     */
    protected function matcherFallback($request,RouterResult $result, $idDelimiter = ':'){
        foreach ($this->getRoutes() as $pattern => $route) {

            $matches = [];

            $matched = preg_match(
                '#^' . $pattern . '$#i',
                $request,
                $matches
            );

            foreach($matches as $key => $value){
                $route = preg_replace('#' . $idDelimiter . $key .'#i',$value,$route,1);
            }

            if($matched !== 0){
                $result->offsetSet($pattern,$route);
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
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param \Efika\Application\Router\RouterResult|null $result
     */
    protected function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Returns last result from matcher
     * @return \Efika\Application\Router\RouterResult|null
     */
    public function getResult()
    {
        return $this->result;
    }

}