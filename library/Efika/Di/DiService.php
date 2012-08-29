<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

class DiService implements DiServiceInterface
{

    /**
     * A new service for object
     * @param $object
     */
    public function __construct($object)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Inject arguments into given method
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function inject($method, $arguments = [])
    {
        // TODO: Implement inject() method.
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
    public function extend($name, $callback)
    {
        // TODO: Implement extend() method.
    }

    /**
     * Create an instance of given object
     * @return mixed
     */
    public function makeInstance()
    {
        // TODO: Implement makeInstance() method.
    }
}
