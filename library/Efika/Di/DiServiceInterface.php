<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

interface DiServiceInterface
{

    /**
     * A new service for object
     * @param $object
     */
    public function __construct($object);

    /**
     * Inject arguments into given method
     * @abstract
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function inject($method,$arguments=[]);

    /**
     * Removes each injection for given method
     * @abstract
     * @param string $method
     * @return mixed
     */
    public function eject($method);

    /**
     * If object is instance of DiExtendableInterface, given object
     * could extend with given method.
     * @abstract
     * @param string $name
     * @param callable $callback
     * @return mixed
     */
    public function expand($name,$callback);

    /**
     * Create an instance of given object
     * @abstract
     * @return mixed
     */
    public function makeInstance();

}
