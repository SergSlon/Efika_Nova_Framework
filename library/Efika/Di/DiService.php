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
     * @throws DiException
     * @return \Efika\Di\DiService|mixed
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
            throw new DiException('Requested method "' . $method . '" does not exists!');
        }

        return $this;

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
     * could extend with given method or property.
     * @param string $name
     * @param mixed | callable $callback
     * @return \Efika\Di\DiService|mixed
     */
    public function expand($name, $callback)
    {
        if ($this->isExpandable()) {
            $this->expansions[$name] = $callback;
        }

        return $this;
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
     * @return \Efika\Di\DiService
     */
    public function invokeMethod($method, $object)
    {
        $invokable = $this->getInjections($method);
        if ($invokable !== null) {
            $invokable['reflection']->invoke($object, $invokable['arguments']);
        }

        return $this;
    }

    /**
     * Set reflection of service
     * @param $object
     */
    protected function setReflection($object)
    {
        $this->reflection = $object;
        return $this;
    }

    /**
     * get reflection of service
     * @return null
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * Add injection to service
     * @param $method
     * @param $methodReflection
     * @param array $arguments
     * @return \Efika\Di\DiService
     */
    protected function addInjection($method, $methodReflection, array $arguments = [])
    {
        $this->injections[$method][] = [
            'reflection' => $methodReflection,
            'arguments' => $arguments
        ];

        return $this;
    }

    /**
     * Returns all injections of given method
     * @param $method
     * @return bool
     */
    public function getInjection($method)
    {
        if (array_key_exists($method, $this->injections)) {
            return $this->injections[$method];
        } else {
            return false;
        }
    }

    /**
     * Set a bunch of injections
     * @param $injections
     * @return \Efika\Di\DiService
     */
    protected function setInjections($injections)
    {
        $this->injections = $injections;
        return $this;
    }

    /**
     * Get all injections
     * @return array
     */
    public function getInjections()
    {
        return $this->injections;
    }

    /**
     * Set a bunch of expansions
     * @param $expansions
     * @return \Efika\Di\DiService
     */
    protected function setExpansions($expansions)
    {
        $this->expansions = $expansions;
        return $this;
    }

    /**
     * get all expansions
     * @return array
     */
    protected function getExpansions()
    {
        return $this->expansions;
    }


}
