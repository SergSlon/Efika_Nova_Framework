<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Modules;


use Efika\Application\ApplicationInterface;

interface ModuleInterface {

    public function register(ApplicationInterface $app, array $arguments = []);
    public function connect();
    public function disconnect();

}