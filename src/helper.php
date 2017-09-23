<?php
/**
 * Created by Reza Salarmehr
 */

use Salarmehr\Ary;

if (!function_exists('ary') && defined('PHP_VERSION_ID') && PHP_VERSION_ID > 50600) {
    /**
     * @param array $items
     * @return Ary
     */
    function ary(...$items)
    {
      return new Ary(...$items);
    }
}