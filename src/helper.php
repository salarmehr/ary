<?php
/**
 * Created by Reza Salarmehr
 * Date: 30/09/2015
 * Time: 16:24
 */

use Salarmehr\Ary;

if (!function_exists('ary')) {

    /**
     * @param mixed $items,...
     * @return Ary
     */
    function ary()
    {
        return new Ary(...func_get_args());
    }
}

