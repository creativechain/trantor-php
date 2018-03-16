<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 12:18
 */

namespace Trantor\Data;


use Trantor\Constants;
use Trantor\Util\Bytes;
use Trantor\Util\VarInt;

class Index extends ContentData
{

    /**
     * @var array
     */
    private $txIds;

    /**
     * Index constructor.
     * @param array $txIds
     */
    public function __construct($txIds)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_INDEX);
        $this->txIds = $txIds;
    }

    /**
     * @return string
     */
    protected function serialize()
    {
        $bufferHex = ContentData::serializeNumber($this->getVersion());
        $bufferHex .= ContentData::serializeNumber($this->getType());

        $bufferHex .= Bytes::byteArray2Hex(VarInt::encode(count($this->txIds)));

        foreach ($this->txIds as $txId) {
            $buff = Bytes::hex2ByteArray($txId);
            if (count($buff) != 32) {
                throw new \InvalidArgumentException('Invalid txId: ' . $txId);
            }

            $bufferHex .= Bytes::byteArray2Hex($buff);
        }

        return $bufferHex;
    }

    /**
     * @param array $data
     * @param int $offset
     * @return int
     */
    protected function deserialize($data, $offset)
    {
        $offset = parent::deserialize($data, $offset);
        $varInt = VarInt::decode($data, $offset);
        $offset += $varInt['bytes'];

        $this->txIds = array();

        for ($x = 0; $x < $varInt['value']; $x++) {
            $tx = array_slice($offset, 32);
            $offset += 32;
            $this->txIds[] = Bytes::byteArray2Hex($tx);
        }

        return $offset;
    }

    /**
     * @return array
     */
    public function getTxIds()
    {
        return $this->txIds;
    }

    /**
     * @return Index
     */
    public static function newEmpty() {
        $index = new Index(null);
        return $index;
    }
}