<?php
/**
 * Created by Aiden Adrian
 */

namespace Salarmehr;

use CachingIterator;
use JsonSerializable;

class Ary extends \ArrayObject implements JsonSerializable
{
    /**
     * Create a new ary.
     */
    public function __construct(object|iterable $array)
    {
        parent::__construct($array, self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * Alias for getArrayCopy
     * @return array
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * Get a CachingIterator instance.
     *
     * @param int $flags
     * @return \CachingIterator
     */
    public function getCachingIterator(int $flags = CachingIterator::CALL_TOSTRING): CachingIterator
    {
        return new \CachingIterator($this->getIterator(), $flags);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */

    public function __toString()
    {
        return json_encode($this);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array|string $keys
     * @return array
     */
    public function only(array|string $keys): array
    {
        return array_intersect_key($this->toArray(), array_flip((array)$keys));
    }

    /**
     * Get the ary of items as a plain object.
     *
     * @return object
     */
    public function toObject(): object
    {
        return (object) $this->toArray();
    }

    #region extra helpers

    /**
     * Replaces elements from passed arrays into the collection
     *
     * @param mixed $items
     * @return static
     * @see http://php.net/manual/en/function.array-replace-recursive.php
     */
    public function replace($items): static
    {
        return new static(array_replace($this->items, $items));
    }

    /**
     * Replaces elements from passed arrays into the collection recursively
     *
     * @param mixed $items
     * @return static
     * @see http://php.net/manual/en/function.array-replace-recursive.php
     */
    public function replaceRecursively(mixed $items): static
    {
        return new static(array_replace_recursive($this->items, $items));
    }
}