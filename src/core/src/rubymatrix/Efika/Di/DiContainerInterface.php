<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

interface DiContainerInterface
{
    /**
     * Provide singleton pattern
     * @static
     * @abstract
     * @return DiContainerInterface
     */
    public static function getInstance();


    /**
     * Create a new service. $object could be an instance or name of an object.
     * An instance will be prototyped. Name will be object name by default if $name is null.
     * @abstract
     * @param string|object $object
     * @param null|string $name
     * @return DiServiceInterface
     */
    public function createService($object,$name=null);

    /**
     * Delivers service an allows to manipulate service.
     * @abstract
     * @param string $name
     * @return DiServiceInterface
     */
    public function getService($name);

    /**
     * returns an instance of given object with injections which happens in DiService
     * @abstract
     * @param string $name
     * @return object
     */
    public function get($name);

}
