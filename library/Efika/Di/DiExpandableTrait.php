<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

/**
 * DiContainer checks whether implements DiExpandableInterface. When DiExpandableTrait
 * will be used DiExpandableInterface need to be implement.
 */
trait DiExpandableTrait
{

    /**
     * Add a method with callback at runtime to an object
     * @param $name
     * @param $callback
     * @return mixed
     */
    public function addMethod($name, $callback)
    {
        // TODO: Implement addMethod() method.
    }

    /**
     * Remove an added method from object
     * @param $name
     * @return mixed
     */
    public function removeMethod($name)
    {
        // TODO: Implement removeMethod() method.
    }

    /**
     * add a property to object
     * @param $name
     * @param $value
     * @return mixed
     */
    public function addProperty($name, $value)
    {
        // TODO: Implement addProperty() method.
    }

    /**
     * Remove a property from object
     * @param $name
     * @return mixed
     */
    public function removeProperty($name)
    {
        // TODO: Implement removeProperty() method.
    }

    /**
     * call an added method
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}
