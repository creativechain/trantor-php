<?php

namespace Trantor\Data;
use BitWasp\Bitcoin\Address\AddressCreator;
use BitWasp\Bitcoin\Base58;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\Hash;
use BitWasp\Buffertools\Buffer;
use Trantor\Constants;
use Trantor\Util\Arrays;
use Trantor\Util\Bytes;
use Trantor\Util\SevenZip;
use Trantor\Util\TextUtils;
use Trantor\Util\VarInt;

/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 1:05
 */
abstract class ContentData {

    /**
     * @var int
     */
    private $version;

    /**
     * @var int
     */
    private $type;

    /**
     * @var boolean
     */
    private $mustBeCompressed = 0;

    /**
     * ContentData constructor.
     * @param int $version
     * @param int $type
     */
    public function __construct($version, $type)
    {
        $this->version = $version;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isMustBeCompressed()
    {
        return $this->mustBeCompressed;
    }

    /**ç
     * @return int
     */
    public function size() {
        return strlen($this->serialize());
    }

    public function setMustBeCompressed()
    {
        $length = $this->size();
        $this->mustBeCompressed = $length >= 160 ? 1 : 0;
    }


    /**
     * @return string
     */
    protected abstract function serialize();

    /**
     * @param array $data
     * @param int $offset
     * @return int
     */
    protected function deserialize($data, $offset) {
        $this->version = Bytes::readInt16($data, $offset);
        $offset += 2;
        $this->type = Bytes::readInt($data, $offset);
        $offset += 1;
        return $offset;
    }

    /**
     *
     * @param int $number
     * @param int $length
     * @return string
     */
    public static function serializeNumber($number, $length = 0) {
        $numberHex = dechex($number);

        $numberHexLength = strlen($numberHex);

        $pairChars = $numberHexLength % 2 === 0;

        $neededChars = null;
        if ($length) {
            $neededChars = $length * 2;
        } else {
            $neededChars = $pairChars ? $numberHexLength : $numberHexLength + 1;
        }

        $leadingZeros = $neededChars - $numberHexLength;

        for ($x = 0; $x < $leadingZeros; $x++) {
            $numberHex = '0' . $numberHex;
        }

        return $numberHex;
    }

    /**
     * @param string $text
     * @return string
     */
    public static function serializeText($text) {
        if ($text && strlen($text)) {
            $textHex = unpack('C*', $text);
            return Bytes::byteArray2Hex(VarInt::encode(count($textHex))) . $textHex;
        } else {
            return Bytes::byteArray2Hex(VarInt::encode(0));
        }
    }

    /**
     * @param array $hex
     * @param int $offset
     * @return array
     */
    public static function deserializeText($hex, $offset) {
        $varInt = VarInt::decode($hex, $offset);
        $offset += $varInt['bytes'];

        $textHex = array_slice($hex, $offset, $varInt['value']);

        return array(
            'text' => TextUtils::hex2str($textHex),
            'offset' => $varInt['value'] + $varInt['bytes']
        );
    }

    /**
     * @param string $input
     * @return string
     */
    public static function decodeAddress($input) {

        $buff = Base58::decode($input);
        print_r($buff->getHex() . PHP_EOL);
        $buff = $buff->slice(1, $buff->getSize() - 5);

        return $buff->getHex();
    }

    /**
     * @param array|string $hash160
     * @return string
     */
    public static function encodeAddress($hash160) {
        if (is_array($hash160)) {
            $hash160 = Bytes::byteArray2Hex($hash160);
        }

        $hash160 = Buffer::hex(Bitcoin::getNetwork()->getAddressByte() . $hash160);
        //$hash160 = hex2bin($hash160);

        $buffer = Hash::sha256d($hash160);
        $checksum = $buffer->slice(0, 4);
        $payload = $hash160->getHex() . $checksum->getHex();

        return Base58::encode(Buffer::hex($payload));
    }

    /**
     * @param array|string $data
     * @return null|Author|Comment|Like|MediaData|Payment|Unlike
     */
    public static function deserializeData($data) {
        if (is_string($data)) {
            $data = Bytes::hex2ByteArray($data);
        }

        $compressed = Bytes::readInt($data, 0);
        if ($compressed) {
            if (!file_exists('lzma')) {
                mkdir('lzma');
            }

            $data = array_slice($data, 1, count($data));
            $bin = Bytes::byteArray2Hex($data);
            $bin = hex2bin($bin);

            $outFile = 'lzma/' . uniqid('temp');
            $file = $outFile . '.xz';
            file_put_contents($file, $bin, LOCK_EX);
            $archive = new SevenZip($file, array('binary' => '/usr/bin/7za'));
            $archive->extractTo('lzma');
            $decompressed = file_get_contents($outFile);
            $out = bin2hex($decompressed);
            unlink($file);
            unlink($outFile);

            $data = Bytes::hex2ByteArray($out);
        }

        $type = Bytes::readInt($data, 2);
        $contentData = null;

        switch ($type) {
            case Constants::TYPE_CONTENT:
                $contentData = MediaData::newEmpty();
                break;
            case Constants::TYPE_USER:
                $contentData = Author::newEmpty();
                break;
            case Constants::TYPE_LIKE:
                $contentData = Like::newEmpty();
                break;
            case Constants::TYPE_UNLIKE:
                $contentData = Unlike::newEmpty();
                break;
            case Constants::TYPE_PAYMENT:
                $contentData = Payment::newEmpty();
                break;
            case Constants::TYPE_COMMENT:
                $contentData = Comment::newEmpty();
                break;
            case Constants::TYPE_DONATION:
                $contentData = Donation::newEmpty();
                break;
            case Constants::TYPE_FOLLOW:
                $contentData = Follow::newEmpty();
                break;
            case Constants::TYPE_UNFOLLOW:
                $contentData = Unfollow::newEmpty();
                break;
            case Constants::TYPE_BLOCK:
                $contentData = BlockContent::newEmpty();
                break;
            case Constants::TYPE_INDEX:
                $contentData = Index::newEmpty();
                break;
        }

        if ($contentData) {
            $contentData->mustBeCompressed = $compressed;
            $contentData->deserialize($data, 0);
            return $contentData;
        } else {
            print_r('Not data deserialized! Type:' . $type . PHP_EOL);
        }

        return null;
    }
}