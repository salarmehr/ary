<?php
/**
 * Created by Reza Salarmehr
 */

use Salarmehr\Ary;

if (!function_exists('ary')) {
    /**
     * @param array $items
     * @return Ary
     */
    function ary(...$items)
    {
      return new Ary(...$items);
    }
}