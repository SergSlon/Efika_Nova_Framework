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
     * call an added method
     * @abstract
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments);


}
