<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 13:30
 */

namespace Trantor\Util;


class Arrays
{

    /**
     * @param array $array
     * @param $value
     * @param int $length
     * @return array
     */
    public static function fill($array, $value, $length) {
        if (!is_array($array)) {
            $array = array();
        }

        for ($i = 0; $i < $length; $i++) {
            $array[] = $value;
        }

        return $array;
    }
}