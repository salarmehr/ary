<?php
/**
 * Created by Reza Salarmehr.
 */

namespace Salarmehr;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class Ary implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
  /**
   * The items contained in the ary.
   *
   * @var array
   */
  protected $items = [];

  /**
   * Create a new ary.
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
   * Get an item from the ary by key.
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
   * Determine if the given key(s) exist in the ary.
   *
   * @param  mixed $key
   * @return bool
   */
  public function has($key)
  {
    $keys = is_array($key) ? $key : func_get_args();

    foreach ($keys as $value) {
      if (!$this->offsetExists($value)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Get all of the items in the ary.
   *
   * @return array
   */
  public function all()
  {
    return $this->items;
  }

  /**
   * Determine if an item exists at a given offset.
   *
   * @param  mixed $key
   * @return bool
   */
  #[\ReturnTypeWillChange]
  public function offsetExists($key)
  {
    return isset($this->items[$key]);
  }

  /**
   * Set the item at a given offset.
   *
   * @param  mixed $key
   * @param  mixed $value
   * @return void
   */
  #[\ReturnTypeWillChange]
  public function offsetSet($key, $value)
  {
    if (is_null($key)) {
      $this->items[] = $value;
      return;
    }

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
  #[\ReturnTypeWillChange]
  public function &offsetGet($key)
  {
    return $this->get($key);
  }

  /**
   * Unset the item at a given offset.
   *
   * @param  mixed $key
   * @return void
   */
  #[\ReturnTypeWillChange]
  public function offsetUnset($key)
  {
    unset($this->items[$key]);
  }

  public function __isset($name)
  {
    return $this->has($name);
  }

  /**
   * Count the number of items in the ary.
   *
   * @return int
   */
  #[\ReturnTypeWillChange]
  public function count()
  {
    return count($this->items);
  }

  /**
   * Get an iterator for the items.
   *
   * @return \ArrayIterator
   */
  #[\ReturnTypeWillChange]
  public function getIterator()
  {
    return new ArrayIterator($this->items);
  }

  /**
   * Convert the ary into something JSON serializable.
   *
   * @return array
   */
  #[\ReturnTypeWillChange]
  public function jsonSerialize()
  {
    return array_map(function ($value) {
      if ($value instanceof JsonSerializable) {
        return $value->jsonSerialize();
      }
      if ($value instanceof self) {
        return $value->toArray();
      }
      return $value;
    }, $this->items);
  }

  /**
   * Get the ary of items as a plain array.
   *
   * @return array
   */
  public function toArray()
  {
    return array_map(function ($value) {
      return $value instanceof self ? $value->toArray() : $value;
    }, $this->items);
  }

  /**
   * Get the ary of items as a plain object.
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
    return new self($this->get($item));
  }

  public function __unset($key)
  {
    $this->offsetUnset($key);
  }

  /**
   * Results array of items from another ary or arrayable value.
   *
   * @param  mixed $items
   * @return array
   */
  protected function getArrayableItems($items)
  {
    if (is_array($items)) {
      return $items;
    }
    if ($items instanceof self) {
      return $items->all();
    }
    if ($items instanceof JsonSerializable) {
      return (array)$items->jsonSerialize();
    }
    if ($items instanceof \Traversable) {
      return iterator_to_array($items);
    }
    return (array)$items;
  }

  #region extra helpers

  /**
   * Replaces elements from passed arrays into the ary
   *
   * @param  mixed $items
   * @return static
   * @see http://php.net/manual/en/function.array-replace.php
   */
  public function replace($items)
  {
    return new static(array_replace($this->items, $this->getArrayableItems($items)));
  }

  /**
   * Replaces elements from passed arrays into the ary recursively
   *
   * @param  mixed $items
   * @return static
   * @see http://php.net/manual/en/function.array-replace-recursive.php
   */
  public function replaceRecursively($items)
  {
    return new static(array_replace_recursive($this->items, $this->getArrayableItems($items)));
  }

  /**
   * Get the items with the specified keys.
   *
   * @param  mixed $keys
   * @param bool   $returnArray
   * @return static|array
   */
  public function only($keys, $returnArray = false)
  {
    $result = new static(array_intersect_key($this->items, array_flip((array)$keys)));
    return $returnArray ? $result->toArray() : $result;
  }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param  mixed $keys
     * @param bool $returnArray
     * @return static|array
     */
  public function except($keys, $returnArray = false)
  {
    $result = new static(array_diff_key($this->items, array_flip((array)$keys)));
    return $returnArray ? $result->toArray() : $result;
  }

  #endregion
}
