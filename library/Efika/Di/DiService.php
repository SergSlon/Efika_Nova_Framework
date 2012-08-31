<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

use ReflectionClass;

class DiService implements DiServiceInterface
{

    protected $reflection = null;
    protected $injections = [];
    protected $expansions = [];
    protected $instance = null;

    /**
     * A new service for object
     * @param $object
     */
    public function __construct($object)
    {
        $reflection = new ReflectionClass($object);

        $this->setReflection($reflection);

    }

    /**
     * Inject arguments into given method
     * @param string $method
     * @param array $arguments
     * @throws Exception
     * @return mixed
     */
    public function inject($method, $arguments = [])
    {
        if ($this->getReflection()->hasMethod($method)) {
            $this->addInjection(
                $method,
                $this->getReflection()->getMethod($method),
                $arguments
            );
        } else {
            throw new Exception('Requested method "' . $method . '" does not exists!');
        }

    }

    /**
     * Removes each injection for given method
     * @param string $methodName
     * @return mixed
     */
    public function eject($methodName)
    {
        $injections = $this->getInjections();
        $editInjections = [];
        $removed = false;

        foreach ($injections as $name => $options) {
            if ($methodName != $name) {
                $editInjections[$name] = $options;
            } else {
                $removed = true;
            }
        }

        if ($removed) {
            $this->setInjections($editInjections);
        }

        return $removed;

    }

    /**
     * If object is instance of DiExpandableInterface, given object
     * could extend with given method.
     * @param string $name
     * @param callable $callback
     * @return mixed
     */
    public function expand($name, $callback)
    {
        if ($this->isExpandable()) {
            $this->expansions[$name] = $callback;
        }
    }

    public function isExpandable()
    {
        return in_array('DiExpandableInterface', $this->getReflection()->getInterfaceNames());
    }

    /**
     * Create an instance of given object
     * @param array $arguments
     * @return mixed
     */
    public function makeInstance($arguments = [])
    {
        $instance = $this->getReflection()->newInstanceArgs($arguments);

        //add injections to instance
        $injections = $this->getInjections();
        foreach ($injections as $name => $options) {
            $this->invokeMethod($name, $instance);
        }

        //add expansions to instance
        $expansions = $this->getExpansions();
        foreach ($expansions as $name => $callback) {
            $instance->$name = $callback;
        }

        $this->instance = $instance;

        return $instance;
    }

    /**
     * Get instance of service object
     * @return null
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Invoke a method from object
     * @param $method
     * @param $object
     */
    public function invokeMethod($method, $object)
    {
        $invokable = $this->getInjections($method);
        if ($invokable !== null) {
            $invokable['reflection']->invoke($object, $invokable['arguments']);
        }
    }

    public function setReflection($object)
    {
        $this->reflection = $object;
    }

    public function getReflection()
    {
        return $this->reflection;
    }

    public function addInjection($method, $methodReflection, array $arguments = [])
    {
        $this->injections[$method][] = [
            'reflection' => $methodReflection,
            'arguments' => $arguments
        ];
    }

    public function getInjection($method)
    {
        if (array_key_exists($method, $this->injections)) {
            return $this->injections[$method];
        } else {
            return false;
        }
    }

    protected function setInjections($injections)
    {
        $this->injections = $injections;
    }

    protected function getInjections()
    {
        return $this->injections;
    }

    protected function setExpansions($expansions)
    {
        $this->expansions = $expansions;
    }

    protected function getExpansions()
    {
        return $this->expansions;
    }


}
