<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;

use ArrayObject;

class Collection implements CollectionInterface
{

    /**
     * collecion of registered items
     * @var array
     */
    private $_items = array();

    private $_activationLookup = array();

    /**
     * register validators
     * @param array $items
     */
    public function __construct($items=array())
    {
        $this->_activationLookup = new ArrayObject();
        $this->setBunch($items);
    }

    /**
     * remove an item by name
     * @param $name
     */
    public function remove($name)
    {
        $items = array();
        foreach ($this->_items as $itemKey => $value) {
            if ($name !== $itemKey) {
                $items[$itemKey] = $value;
            }
        }
        $this->_items = $items;
    }

    /**
     * register a bunch of validators
     * @param $items
     */
    public function setBunch(array $items)
    {
        foreach($items as $name => $item){
            $this->set($name,$item);
        }
    }

    /**
     * get item
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name,$default=null)
    {
        if($this->has($name) && $this->isActive($name))
            return $this->_items[$name];
        else
            return $default;
    }

    /**
     * Check if item exists
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->_items);
    }

    /**
     * deactivate an item. item won't be accessable
     * @param $name
     */
    public function deactivate($name)
    {
        if($this->_activationLookup->offsetExists($name))
            $this->_activationLookup->offsetSet($name,false);
    }

    /**
     * activate an item. items will activate while setting
     * @param $name
     */
    public function activate($name)
    {
        $this->_activationLookup->offsetSet($name,true);
    }

    /**
     * Returns TRUE if item is active otherwise FALSE
     * @param $name
     * @return mixed
     */
    public function isActive($name)
    {
        return $this->_activationLookup->offsetGet($name);
    }

    /**
     * Set a new or overwrite an item.
     * @param $name
     * @param $item
     * @param bool $overwrite
     */
    public function set($name, $item, $overwrite = false)
    {
        if(!array_key_exists($name,$this->_items)){
            $this->_items[$name] = $item;
            $this->activate($name);
        }else{
            if($overwrite)
                $this->_items[$name] = $item;
        }
    }

    /**
     * Returns all active items as array
     * @return array
     */
    public function getAll(){
        $items = array();

        foreach($this->_items as $key => $value){
            if($this->isActive($key))
                $items[$key] = $value;
        }

        return $items;
    }
}