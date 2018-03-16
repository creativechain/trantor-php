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

class Donation extends ContentData
{
    /** @var  string */
    private $author;

    /**
     * Donation constructor.
     * @param string $followerAddress
     */
    public function __construct($followerAddress)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_DONATION);
        $this->author = $followerAddress;
    }

    /**
     * @return string
     */
    protected function serialize()
    {
        $bufferHex = ContentData::serializeNumber($this->getVersion());
        $bufferHex .= ContentData::serializeNumber($this->getType());
        $bufferHex .= ContentData::decodeAddress($this->author);

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
        $author = array_slice($data, $offset, $offset + 20);
        $this->author = ContentData::encodeAddress($author);
        $offset += 20;

        return $offset;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Donation
     */
    public static function newEmpty() {
        $donation = new Donation(null);
        return $donation;
    }
}