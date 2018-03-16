<?php

namespace Trantor\Util;
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 2:36
 */
class Bytes
{
    /**
     * @param array $buff
     * @param int $offset
     * @param int $bytes
     * @return int
     */
    public static function readInt($buff, $offset, $bytes = 1) {

        $byteArray = array_slice($buff, $offset, $bytes);

        return Bytes::byteArray2Dec($byteArray);
    }

    /**
     * @param array $buff
     * @param int $offset
     * @return int
     */
    public static function readInt16($buff, $offset) {
        return Bytes::readInt($buff, $offset, 2);
    }

    /**
     * @param array $buff
     * @param int $offset
     * @return int
     */
    public static function readInt32($buff, $offset) {
        return Bytes::readInt($buff, $offset, 4);
    }

    /**
     * @param array $buff
     * @param int $offset
     * @return int
     */
    public static function readInt64($buff, $offset) {
        return Bytes::readInt($buff, $offset, 8);
    }

    /**
     * @param string $hex
     * @return array
     */
    public static function hex2ByteArray($hex) {
        if (strlen($hex) % 2 != 0) {
            $hex = '0' . $hex;
        }

        $bytes = array();
        for ($x = 0; $x < strlen($hex); $x += 2) {
            $b = substr($hex, $x, 2);
            $bytes[] = intval($b, 16);
        }

        return $bytes;
    }

    /**
     * @param array $bytes
     * @return string
     */
    public static function byteArray2Hex($bytes) {
        $hexString = '';

        for ($x = 0; $x < count($bytes); $x++) {
            $dec = $bytes[$x];
            $hex = dechex($dec);

            if (strlen($hex) == 1) {
                $hex = '0' . $hex;
            }

            $hexString = $hexString . $hex;
        }

        return $hexString;
    }

    /**
     * @param int $dec
     * @return array
     */
    public static function dec2ByteArray($dec) {
        $hex = dechex($dec);

        if (strlen($hex) % 2 != 0) {
            $hex = '0' . $hex;
        }

        return Bytes::hex2ByteArray($hex);
    }

    /**
     * @param array $bytes
     * @return number
     */
    public static function byteArray2Dec($bytes) {
        $hexString = '';

        for ($x = 0; $x < count($bytes); $x++) {
            $dec = $bytes[$x];
            $hex = dechex($dec);

            if (strlen($hex) == 1) {
                $hex = '0' . $hex;
            }

            $hexString = $hexString . $hex;
        }

        return hexdec($hexString);
    }

    /**
     * @param array $bytes
     * @return string
     */
    public static function byteArray2Bin($bytes) {
        $hex = Bytes::byteArray2Hex($bytes);

        return hex2bin($hex);
    }

    /**
     * @param $bin
     * @return array
     */
    public static function bin2ByteArray($bin) {
        $hex = bin2hex($bin);

        if (strlen($hex) % 2 != 0) {
            $hex = '0' . $hex;
        }

        return Bytes::hex2ByteArray($hex);
    }
}