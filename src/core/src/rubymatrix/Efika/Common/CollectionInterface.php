<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;


interface CollectionInterface
{
    /**
     * @abstract
     * Set a new or overwrite an item.
     * @param $name
     * @param $item
     * @param bool $overwrite
     */
    public function set($name, $item, $overwrite = false);

    /**
     * register a bunch of validators
     * @param $items
     */
    public function setBunch(array $items);

    /**
     * @abstract
     * get item
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name,$default=null);

    /**
     * @abstract
     * remove an item by name
     * @param $name
     */
    public function remove($name);

    /**
     * @abstract
     * Check if validator exists
     * @param $name
     * @return bool
     */
    public function has($name);

}