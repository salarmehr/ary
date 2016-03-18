<?php
/**
 * Created by Reza Salarmehr.
 *
 * Some methods are from Laravel source code.
 *
 * Date: 18/09/2015
 * Time: 22:37
 */

namespace Salarmehr;

use ArrayAccess;
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use JsonSerializable;

class Ary extends Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Create a new collection.
     *
     * @param mixed $items
     */

    public function __construct()
    {
        $items = func_get_args();
        if (count($items) === 0) {
            $items = [];
        }
        elseif (count($items) === 1) {
            $items = is_array($items[0]) ? $items[0] : $this->getArrayableItems($items[0]);
        }
        $this->items = $items;
    }

    public function &__get($item)
    {
        return $this->get($item);
    }

    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function &get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->items[$key];
        }

        $array = $this->items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
            return;
        }

//        $this->items[$key] = $value;

        $keys = explode('.', $key);
        $array =& $this->items;
        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
        return;
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function &offsetGet($key)
    {
        return $this->get($key);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Get the collection of items as a plain object.
     *
     * @return object
     */
    public function toObject()
    {
        return (object)$this->all();
    }

    /**
     * Return a subset of current ary as a new ary
     * @param $item
     * @return Ary
     */
    public function ary($item)
    {
        return new ary($this->get($item));
    }

    public function __unset($key)
    {
        $this->offsetUnset($key);
    }
}