<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

class DiContainer implements DiContainerInterface
{

    /**
     * Provide singleton pattern
     * @static
     * @return DiContainerInterface
     */
    public static function getInstance()
    {
        // TODO: Implement getInstance() method.
    }

    /**
     * Create a new service. $object could be an instance or name of an object.
     * An instance will be prototyped. Name will be object name by default if $name is null.
     * @param string|object $object
     * @param null|string $name
     * @return DiServiceInterface
     */
    public function createService($object, $name = null)
    {
        // TODO: Implement createService() method.
    }

    /**
     * Delivers service an allows to manipulate service.
     * @param string $name
     * @return DiServiceInterface
     */
    public function getService($name)
    {
        // TODO: Implement getService() method.
    }

    /**
     * returns an instance of given object with injections which happens in DiService
     * @param string $name
     * @return object
     */
    public function get($name)
    {
        // TODO: Implement get() method.
    }
}
