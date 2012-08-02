<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

require_once __DIR__ . '/../Loader/PackageFoundation.php';

use Efika\Loader\PackageFoundation;

class Package extends PackageFoundation
{
    public function __construct()
    {
        $this->setPath(__DIR__);
        $this->setNamespace(__NAMESPACE__);

        $this->setClasses(
            $this->getClassesFromDir($this->getPath(), $this->namespace)
        );

        $this->importClass(
            'SingletonTrait',
            'Efika\Common\\',
            __DIR__ . '/../Common/'
        );
    }

}
