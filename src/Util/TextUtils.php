<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 12:05
 */

namespace Trantor\Util;


class TextUtils
{

    /**
     * @param array|string $hex
     * @return string
     */
    public static function hex2str($hex) {
        if (is_string($hex)) {
            $hex = Bytes::hex2ByteArray($hex);
        }

        $string = '';
        for ($x = 0; $x < count($hex); $x++) {
            $string .= chr($hex[$x]);
        }

        return $string;
    }

    /**
     * @param $string
     * @return string
     */
    public static function str2hex($string) {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
        }
        return strtolower($hex);
    }

    /**
     * @param string $string
     * @return array
     */
    public static function toCharArray($string) {
        $stringArray = str_split($string);
        $charArray = array();

        //die(print_r($stringArray));
        foreach ($stringArray as $char) {
            //print_r(unpack('C*', $char));
            $charArray[] = unpack('C*', $char)[1];
        }
        //die(print_r($charArray));


        return $charArray;
    }

    /**
     * @param array $chars
     * @return string
     */
    public static function fromCharArray($chars) {
        $string = '';
        foreach ($chars as $c) {
            $string .= chr($c);
        }

        return $string;
    }
}