<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

interface EventInterface
{
    public function setName($name);
    public function getName();
    public function setTarget($target);
    public function setArguments($args=[]);
    public function stopPropagation();
    public function isPropagationStopped();
}
