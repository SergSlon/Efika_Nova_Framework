<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * Class EventInterface
 * @package Efika\EventManager
 */
interface EventInterface
{

    /**
     * @param $name
     * @return EventInterface
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param $target
     * @return EventInterface
     */
    public function setTarget($target);

    /**
     * @return mixed
     */
    public function getTarget();

    /**
     * @param array $args
     * @return EventInterface
     */
    public function setArguments($args=[]);

    /**
     * @return mixed
     */
    public function getArguments();

    /**
     * @return mixed
     */
    public function stopPropagation();

    /**
     * @return mixed
     */
    public function isPropagationStopped();
}
