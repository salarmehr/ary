<?php
/**
 * Created by Reza Salarmehr
 */

use Salarmehr\Ary;

if (!function_exists('ary') && phpversion()) {
  if (!defined('ary') && PHP_VERSION_ID > 50600) {
    /**
     * @param array $items
     * @return Ary
     */
    function ary(...$items)
    {
      return new Ary($items);
    }
  }
}

