<?php

namespace Trantor\Util;
use RangeException;

/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 1:41
 */
class VarInt
{
    const MSB = 0x80;
    const REST = 0x7F;
    const MSBALL = ~VarInt::REST;
    const INT = 2^31;

    /**
     * @param array|string $buff
     * @param int $offset
     * @return array
     */
    public static function decode($buff, $offset = 0) {
        $res = 0;
        $shift = 0;
        $counter = $offset;
        $b = null;

        if (is_string($buff)) {
            $buff = Bytes::hex2ByteArray($buff);
        }

        $l = count($buff);

        do {
            if ($counter >= $l) {
                throw new RangeException('Could not decode varint');
            }

            $b = $buff[$counter++];
            $res += $shift < 28
                ? ($b & VarInt::REST) << $shift
                : ($b & VarInt::REST) * pow(2, $shift);
            $shift += 7;
        } while ($b >= VarInt::MSB);

        return array(
            'value' => $res,
            'bytes' => $counter - $offset
        );

    }

    /**
     * @param int $num
     * @param array $out
     * @param int $offset
     * @return array
     */
    public static function encode($num, $out = array(), $offset = 0) {
        $oldOffset = $offset;

        while ($num >= VarInt::INT) {
            $out[$offset++] = ($num & 0xFF) | VarInt::MSB;
            $num /= 128;
        }

        while ($num & VarInt::MSBALL) {
            $out[$offset++] = ($num & 0xFF) | VarInt::MSB;
            $num >>= 7;
        }

        $out[$offset] = $num | 0;

        return $out;
    }
}