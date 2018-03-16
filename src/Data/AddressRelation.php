<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 14:57
 */

namespace Trantor\Data;


use Trantor\Constants;
use Trantor\Util\Bytes;

class AddressRelation extends ContentData
{
    /** @var  string */
    private $followerAddress;

    /** @var  string */
    private $followedAddress;

    /**
     * AddressRelation constructor.
     * @param int $type
     * @param string $followerAddress
     * @param string $followedAddress
     */
    public function __construct($type, $followerAddress, $followedAddress)
    {
        parent::__construct(Constants::VERSION, $type);
        $this->followerAddress = $followerAddress;
        $this->followedAddress = $followedAddress;
    }

    /**
     * @return string
     */
    protected function serialize()
    {
        $bufferHex = ContentData::serializeNumber($this->getVersion());
        $bufferHex .= ContentData::serializeNumber($this->getType());
        $bufferHex .= ContentData::decodeAddress($this->followerAddress);
        $bufferHex .= ContentData::decodeAddress($this->followedAddress);

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
        $followerAddress = array_slice($data, $offset, $offset + 20);
        $this->followerAddress = ContentData::encodeAddress($followerAddress);
        $offset += 20;

        $followedAddress = array_slice($data, $offset, $offset + 20);
        $this->followedAddress = ContentData::encodeAddress($followedAddress);
        $offset += 20;

        return $offset;
    }

    /**
     * @return string
     */
    public function getFollowerAddress()
    {
        return $this->followerAddress;
    }

    /**
     * @return string
     */
    public function getFollowedAddress()
    {
        return $this->followedAddress;
    }
}