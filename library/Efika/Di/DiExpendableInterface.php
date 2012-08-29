<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

/**
 * This interface will include by classes which should expendable on runtime
 */

namespace Efika\Di;

interface DiExpendableInterface
{

    /**
     * Add a method with callback at runtime to an object
     * @abstract
     * @param $name
     * @param $callback
     * @return mixed
     */
    public function addMethod($name,$callback);

    /**
     * Remove an added method from object
     * @abstract
     * @param $name
     * @return mixed
     */
    public function removeMethod($name);

    /**
     * add a property to object
     * @abstract
     * @param $name
     * @param $value
     * @return mixed
     */
    public function addProperty($name,$value);

    /**
     * Remove a property from object
     * @abstract
     * @param $name
     * @return mixed
     */
    public function removeProperty($name);

    /**
     * call an added method
     * @abstract
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments);


}
