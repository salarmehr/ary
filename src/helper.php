<?php
/**
 * Created by Reza Salarmehr
 * Date: 30/09/2015
 * Time: 16:24
 */

use Salarmehr\Ary;

if (!function_exists('ary') && phpversion()) {


    function parse_version($version)
    {
        $version = explode('.', $version);
        return $version[0] * 10000 + $version[1] * 100 + $version[2];
    }

    if (!defined('ary') && parse_version(PHP_VERSION) > parse_version('5.6.0')) {
        /**
         * @param mixed $items,...
         * @return Ary
         */
        function ary()
        {
            return new Ary(...func_get_args());
        }
    }
}

