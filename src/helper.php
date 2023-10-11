<?php
/**
 * Created by Aiden Adrian
 */

use Salarmehr\Ary;

if (!function_exists('ary') && defined('PHP_VERSION_ID') && PHP_VERSION_ID > 50600) {
    /**
     * @param array $items
     * @return Ary
     */
    function ary(array $items): Ary
    {
        return new Ary($items);
    }
}