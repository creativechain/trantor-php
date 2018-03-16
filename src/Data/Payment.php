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

class Payment extends ContentData
{
    /** @var  string */
    private $author;

    /** @var  string */
    private $contentAddress;

    /** @var  int */
    private $amount;

    /**
     * Payment constructor.
     * @param string $followerAddress
     * @param string $contentAddress
     * @param int $comment
     */
    public function __construct($followerAddress, $contentAddress, $comment)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_PAYMENT);
        $this->author = $followerAddress;
        $this->contentAddress = $contentAddress;
        $this->amount = $comment;
    }

    /**
     * @return string
     */
    protected function serialize()
    {
        $bufferHex = ContentData::serializeNumber($this->getVersion());
        $bufferHex .= ContentData::serializeNumber($this->getType());
        $bufferHex .= ContentData::decodeAddress($this->author);
        $bufferHex .= ContentData::decodeAddress($this->contentAddress);
        $bufferHex .= ContentData::serializeNumber($this->amount, 8);
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

        $contentAddress = array_slice($data, $offset, $offset + 20);
        $this->contentAddress =  ContentData::encodeAddress($contentAddress);
        $offset += 20;

        $this->amount = Bytes::readInt64($data, $offset);

        $offset += 8;
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
     * @return string
     */
    public function getContentAddress()
    {
        return $this->contentAddress;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Payment
     */
    public static function newEmpty() {
        $payment = new Payment(null, null, null);
        return $payment;
    }
}