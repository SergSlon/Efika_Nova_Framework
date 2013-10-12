<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

class Event implements EventInterface
{
    /**
     * Flag which stop event propagation
     * @var bool
     */
    private $propagation = false;

    /**
     * @var null name of event
     */
    protected $name = null;

    /**
     * event target. Most likely the parent class where EventManagerTrait will used
     * @var null
     */
    protected $target = null;

    /**
     * Event arguments
     * @var array
     */
    protected $arguments = [];

    /**
     * @param $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * returns event target
     * @return object
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param $target
     * @throws Exception
     * @return Event
     */
    public function setTarget($target)
    {
        if (!is_object($target)) {
            throw new Exception('Event target must be an object! ' . gettype($target) . ' given');
        }

        $this->target = $target;
        return $this;
    }

    /**
     * Return all arguments
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * set event arguments
     * @param array|string|null $args
     * @return Event
     */
    public function setArguments($args = [])
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        $this->arguments = $args;
        return $this;
    }

    /**
     * Stop event propagation
     */
    public function stopPropagation()
    {
        $this->propagation = true;
        return $this;
    }

    /**
     * returns true if propagation has been stopped otherwise false
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->propagation;
    }
}
