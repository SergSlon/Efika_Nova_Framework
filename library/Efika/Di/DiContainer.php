<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Di;

use Efika\Common\SingletonTrait;

/**
 * Class DiContainer
 * @package Efika\Di
 */
class DiContainer implements DiContainerInterface
{

    //DiContainer is Singleton
    use SingletonTrait {
        SingletonTrait::getInstance as TRAITgetInstance;
    }

    /**
     * Collection of services
     * @var array
     */
    protected $services = [];


    /**
     * @return DiContainer
     */
    public static function getInstance()
    {
        return self::TRAITgetInstance();
    }


    /**
     * Create a new service. $object could be an instance or name of an object.
     * An instance will be prototyped. Name will be object name by default if $name is null.
     * @param string|object $object
     * @param null|string $name
     * @return DiService
     */
    public function createService($object, $name = null)
    {
        if (is_object($object) && $name == null) {
            $serviceName = get_class($object);
        } else if (is_string($object) && $name == null) {
            $serviceName = $object;
        } else {
            $serviceName = $name;
        }

        $service = new DiService($object);
        $this->services[$serviceName] = $service;

        return $service;
    }

    /**
     * Delivers service an allows to manipulate service.
     * @param string $name
     * @throws DiException
     * @return DiService
     */
    public function getService($name)
    {
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        } else {
            throw new DiException('Requested Service "' . $name . '" does not exist!');
        }
    }

    /**
     * @deprecated
     * returns an instance of given object with injections which happens in DiService
     * @param string $name
     * @return object
     */
    public function get($name)
    {
        return $this->getService($name)->getInstance();
    }
}
