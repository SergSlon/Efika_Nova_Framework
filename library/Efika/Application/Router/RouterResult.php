<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Application\Router;

use ArrayObject;

/**
 * Represents all results of routing
 * @package Efika\Application\Router
 */
class RouterResult extends ArrayObject{

    /**
     *
     */
    const MODE_FIRST = 2;
    /**
     *
     */
    const MODE_LAST = 4;


    /**
     * @param array $array
     */
    public function setBulk(array $array){
        foreach($array as $key => $value){
            $this->offsetSet($key,$value);
        }
    }

    /**
     * @param int $mode
     * @throws RouterException
     * @return mixed
     */
    public function getMatchedRoute($mode = self::MODE_FIRST){

        switch($mode){
            case self::MODE_FIRST : return $this->offsetGet(0);
            case self::MODE_LAST : return $this->offsetGet($this->count()-1);
            default : throw new RouterException('Unknown response mode');
        }

    }

    /**
     * Flushes matched results
     */
    public function flush(){
        $this->exchangeArray([]);
    }

}