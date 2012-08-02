<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\EventManager;

/**
 * contains a single callback. Contains method to check a callback
 * and is able to execute a callback
 */
class EventHandlerCallback
{

    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Execute target handler callback with arguments
     * @param null $arguments
     * @throws Exception
     */
    public function execute($arguments=null)

    {
        if(!is_array($arguments))
            $arguments = [$arguments];

        if (
            is_callable($this->callback)
            || is_array($this->callback)
            || is_string($this->callback)
        ) {
           call_user_func_array($this->callback,$arguments);
        }else{
            throw new Exception('Invalid callback!');
        }
    }
}
