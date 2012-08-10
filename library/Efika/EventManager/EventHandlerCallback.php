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

    private $callback = null;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Returns false if ->callback is null otherwise true
     * @return bool
     */
    public function hasCallback(){
        return $this->callback != null;
    }

    /**
     * Check if Callback is callable
     * @return bool
     */
    public function isCallable(){
        return is_callable($this->callback);
    }

    /**
     * Execute target handler callback with arguments
     * @param null $arguments
     * @return mixed
     * @throws Exception
     */
    public function execute($arguments=null)

    {
        if(!is_array($arguments))
            $arguments = [$arguments];

        if (
            $this->isCallable()
        ) {
           return call_user_func_array($this->callback,$arguments);
        }else{
            throw new Exception('Invalid callback!');
        }
    }
}
