<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;

use Efika\Application\Dispatcher\DispatchFactory;

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
     * @var null
     */
    protected $dispatchMode = null;


    /**
     *
     */
    public function __construct()
    {
        $this->result = new RouterResult();
    }

    /**
     * Match request with given routes
     * @param string $request
     * @throws RouteNotFoundException
     * @return mixed
     */
    public function match($request)
    {
        $this->result->flush();
        $result = $this->result;
        if ($this->matcherCommand === null) {
            $result = $this->matcherFallback($request, $result);
        } else {
            //Route matcher strategy
            //$routeMatcher = new AnyRouteMatcher($this);
            //$routeMatcher->match($request,$this->result)
        }

        if($result->count() < 1){
            throw new RouteNotFoundException('Request route not found',$request);
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
    protected function matcherFallback($request, RouterResult $result, $idDelimiter = ':')
    {

        foreach ($this->getRoutes() as $pattern => $routeArray) {

            $matches = [];

            $route = $routeArray['route'];

            $matched = preg_match(
                '#^' . $pattern . '$#i',
                $request,
                $matches
            );

            $matchedRoute = null;

            foreach ($matches as $key => $value) {
                $matchedRoute = preg_replace('#' . $idDelimiter . $key . '#i', $value, $route, 1);
            }

            if ($matched !== 0) {
                $dispatchMode = array_key_exists('dispatchMode',$routeArray) ?
                    $routeArray['dispatchMode'] :
                    null;

                $this->setDispatchMode($dispatchMode);
                $result->offsetSet('pattern', $pattern);
                $result->offsetSet('matechedRoute', $matchedRoute);
                $result->setBulk($matches);
                $result->setBulk($routeArray);
                break;
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

    /**
     * @return null
     */
    public function getDispatchMode()
    {
        return $this->dispatchMode;
    }

    /**
     * @param null $dispatchMode
     * @param string $default
     */
    protected function setDispatchMode($dispatchMode=null,$default=DispatchFactory::MODE_MVC)
    {
        if($dispatchMode == null){
            $dispatchMode = $default;
        }

        $this->dispatchMode = $dispatchMode;
    }

    /**
     * @param $paramRoute
     * @param string $delimiter
     * @return array
     */
    public function makeParameters($paramRoute,$delimiter='/'){
        $raw = explode($delimiter,trim($paramRoute,$delimiter));
        $paramList = [];
        $key = null;

        foreach($raw as $n => $value){
            $mode = $n%2;
            if($mode === 0){
                $key = $value;
            }else{
                $paramList[$key] = $value;
            }
        }

        return $paramList;

    }

}