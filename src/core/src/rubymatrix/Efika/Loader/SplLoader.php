<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Loader;

/**
 * PSR-0 compliant autoloader implementation
 */
interface SplLoader
{

    /**
     * Register autoloader to spl_autoloader_stack
     * @abstract
     * @return mixed
     */
    public function register();

    /**
     * autoload a class
     * @abstract
     * @param $class
     * @return mixed
     */
    public function autoload($class);

}
