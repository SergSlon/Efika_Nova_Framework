<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Config;



use ArrayObject;

class Config {

    const DEFAULT_CONFIGURATOR_DIRECTIVE = 'default';

    /**
     * Whether modifications to configuration data are allowed.
     *
     * @var boolean
     */
    protected $allowModifications;

    /**
     * Number of elements in configuration data.
     *
     * @var integer
     */
    protected $count;

    /**
     * Data withing the configuration.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Collection of different configuration methods
     * @var array
     */
    protected $directives = array();

    /**
     * Used when unsetting values during iteration to ensure we do not skip
     * the next element.
     *
     * @var boolean
     */
    protected $skipNextIteration;

    public function __construct(array $config = array(), $allowModifications = false)
    {
        $this->allowModifications = (boolean)$allowModifications;

        //setup directives
        if (array_key_exists('directives', $config)) {
            $directives = $config['directives'];
        } else {
            $directives = array();
        }

        $this->directives = new ArrayObject($directives);

        foreach ($config as $key => $value) {

            if (is_array($value)) {
//                if ($this->directives->offsetExists($key)) {
//                    $adapterName = '\\' . ltrim($this->directives->offsetGet($key), '\\');
//
//                    if (!is_subclass_of($adapterName, 'Efika\Config\Configurable')) {
//                        throw new ConfigException('Configuration Error: Adapter "' . $adapterName . ' must be an instance of Efika\Config\Configurable');
//                    }
//
//                    $adapter = new $adapterName($value, $this->allowModifications);
//                } else {
                    $adapter = new self($value, $this->allowModifications);
//                }
                $this->data[$key] = $adapter;
            } else {
                $this->data[$key] = $value;
            }

            $this->count++;
        }
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Set a value in the config.
     *
     * Only allow setting of a property if $allowModifications was set to true
     * on construction. Otherwise, throw an exception.
     *
     * @param string $name
     * @param mixed $value
     * @throws \RuntimeException
     * @return void
     */
    public function __set($name, $value)
    {
        if ($this->allowModifications) {
            if (is_array($value)) {
                $this->data[$name] = new self($value, true);
            } else {
                $this->data[$name] = $value;
            }

            $this->count++;
        } else {
            throw new \RuntimeException('Config is read only');
        }
    }

    /**
     * Deep clone of this instance to ensure that nested Zend\Configs are also
     * cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = array();

        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->data = $array;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $data = $this->data;

        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * isset() overloading
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * unset() overloading
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return void
     */
    public function __unset($name)
    {
        if (!$this->allowModifications) {
            throw new \InvalidArgumentException('Config is read only');
        } elseif (isset($this->data[$name])) {
            unset($this->data[$name]);
            $this->count--;
            $this->skipNextIteration = true;
        }
    }

    /**
     * count(): defined by Countable interface.
     *
     * @see Countable::count()
     * @return integer
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * current(): defined by Iterator interface.
     *
     * @see Iterator::current()
     * @return mixed
     */
    public function current()
    {
        $this->skipNextIteration = false;
        return current($this->data);
    }

    /**
     * key(): defined by Iterator interface.
     *
     * @see Iterator::key()
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * next(): defined by Iterator interface.
     *
     * @see Iterator::next()
     * @return void
     */
    public function next()
    {
        if ($this->skipNextIteration) {
            $this->skipNextIteration = false;
            return;
        }

        next($this->data);
    }

    /**
     * rewind(): defined by Iterator interface.
     *
     * @see Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        $this->skipNextIteration = false;
        reset($this->data);
    }

    /**
     * valid(): defined by Iterator interface.
     *
     * @see Iterator::valid()
     * @return boolean
     */
    public function valid()
    {
        return ($this->key() !== null);
    }

    /**
     * offsetExists(): defined by ArrayAccess interface.
     *
     * @see ArrayAccess::offsetExists()
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * offsetGet(): defined by ArrayAccess interface.
     *
     * @see ArrayAccess::offsetGet()
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * offsetSet(): defined by ArrayAccess interface.
     *
     * @see ArrayAccess::offsetSet()
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * offsetUnset(): defined by ArrayAccess interface.
     *
     * @see ArrayAccess::offsetUnset()
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->__unset($offset);
    }

    /**
     * Merge another Config with this one.
     *
     * For duplicate keys, the following will be performed:
     * - Nested Configs will be recursively merged.
     * - Items in $merge with INTEGER keys will be appended.
     * - Items in $merge with STRING keys will overwrite current values.
     *
     * @param Config $merge
     * @internal param \Efika\Config\Config $replace
     * @return Config
     */
    public function merge(self $merge)
    {
        foreach ($merge as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                if (is_int($key)) {
                    $this->data[] = $value;
                } elseif ($value instanceof self && $this->data[$key] instanceof self) {
                    $this->data[$key]->merge($value);
                } else {
                    if ($value instanceof self) {
                        $this->data[$key] = new self($value->toArray(), $this->allowModifications);
                    } else {
                        $this->data[$key] = $value;
                    }
                }
            } else {
                if ($value instanceof self) {
                    $this->data[$key] = new self($value->toArray(), $this->allowModifications);
                } else {
                    $this->data[$key] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Prevent any more modifications being made to this instance.
     *
     * Useful after merge() has been used to merge multiple Config objects
     * into one object which should then not be modified again.
     *
     * @return void
     */
    public function setReadOnly()
    {
        $this->allowModifications = false;

        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->setReadOnly();
            }
        }
    }

    /**
     * Returns whether this Config object is read only or not.
     *
     * @return boolean
     */
    public function isReadOnly()
    {
        return !$this->allowModifications;
    }

    /**
     * Set a new directive
     * @param $name
     * @param $adapter
     * @internal param array $directives
     */
    public function setDirective($name, $adapter)
    {
        $this->directives[$name] = $adapter;
    }

    /**
     *
     * @return array
     */
    public function getDirectives()
    {
        return $this->directives;
    }


}