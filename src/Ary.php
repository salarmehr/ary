<?php
/**
 * Created by Reza Salarmehr.
 */

namespace Salarmehr;

use Illuminate\Support\Collection;

class Ary extends Collection
{
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

  #region extra helpers

  /**
   * Replaces elements from passed arrays into the collection
   *
   * @param  mixed $items
   * @return static
   * @see http://php.net/manual/en/function.array-replace-recursive.php
   */
  public function replace($items)
  {
    return new static(array_replace($this->items, $this->getArrayableItems($items)));
  }

  /**
   * Replaces elements from passed arrays into the collection recursively
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
   * @return static
   */
  public function only($keys, $returnArray = false)
  {
    $result = parent::only($keys);
    return $returnArray ? $result->toArray() : $result;
  }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param  mixed $keys
     * @param bool $returnArray
     * @return static
     */
  public function except($keys, $returnArray = false)
  {
    $result = parent::except($keys);
    return $returnArray ? $result->toArray() : $result;
  }

  #endregion
}