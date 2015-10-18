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

class Ary implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{

    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new collection.
     *
     * @param  mixed $items
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

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param  mixed $items
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if ($items instanceof self) {
            return $items->all();
        }
        if (method_exists($items, 'toArray')) {
            return $items->toArray();
        }
        if ($items instanceof \JsonSerializable) {
            return json_decode(json_encode($items), true);
        }
        return (array)$items;
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    public function &__get($item)
    {
        return $this->get($item);
    }

    public function __set($name, $value)
    {
        $this->items['name'] = $value;
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
        return $default;
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
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
        }
        else {
            $this->items[$key] = $value;
        }
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

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->jsonSerialize();
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  mixed $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get a CachingIterator instance.
     *
     * @param  int $flags
     * @return \CachingIterator
     */
    public function getCachingIterator($flags = CachingIterator::CALL_TOSTRING)
    {
        return new CachingIterator($this->getIterator(), $flags);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */

    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->all(), $options);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array|string $keys
     * @return array
     */
    public function only($keys)
    {
        return array_intersect_key($this->all(), array_flip((array)$keys));
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed $key
     * @return bool
     */
    public function has($key)
    {
        return $this->offsetExists($key);
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
     * Search the ary for a given value and return the corresponding key if successful.
     *
     * @param  mixed $value
     * @param  bool $strict
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        return array_search($value, $this->items, $strict);
    }
}