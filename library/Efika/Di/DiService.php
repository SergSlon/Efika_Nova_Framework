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
    protected $injection = [];

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
     * @param string $method
     * @return mixed
     */
    public function eject($method)
    {
        // TODO: Implement eject() method.
    }

    /**
     * If object is instance of DiExtendableInterface, given object
     * could extend with given method.
     * @param string $name
     * @param callable $callback
     * @return mixed
     */
    public function expand($name, $callback)
    {
        if($this->isExpandable()){

        }
    }

    public function isExpandable(){
        return in_array('DiExpandableInterface',$this->getReflection()->getInterfaceNames());
    }

    /**
     * Create an instance of given object
     * @return mixed
     */
    public function makeInstance()
    {
        // TODO: Implement makeInstance() method.
    }

    /**
     * Invoke a method from object
     * @param $method
     * @param $object
     */
    public function invokeMethod($method, $object)
    {
        $invokable = $this->getInjection($method);
        if($invokable !== null){
            $invokable['reflection']->invoke($object,$invokable['arguments']);
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
        $this->injection[$method][] = [
            'reflection' => $methodReflection,
            'arguments' => $arguments
        ];
    }

    public function getInjection($method)
    {
        if (array_key_exists($method, $this->injection)) {
            return $this->injection[$method];
        } else {
            return false;
        }
    }

}
