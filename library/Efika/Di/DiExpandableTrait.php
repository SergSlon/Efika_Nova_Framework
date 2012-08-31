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
