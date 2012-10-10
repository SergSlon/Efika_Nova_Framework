<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application;

/**
 * Application
 */
interface ApplicationInterface
{
    /**
     * Returns Application Request
     * @abstract
     * @return mixed
     */
    public function getRequest();

    /**
     * Returns Application Response
     * @abstract
     * @return mixed
     */
    public function getResponse();

    /**
     * Executes Application
     * @abstract
     * @return mixed
     */
    public function run();
}
