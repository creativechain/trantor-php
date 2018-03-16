<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 14:57
 */

namespace Trantor\Data;


use Trantor\Constants;

class Like extends ContentData
{
    /** @var  string */
    private $author;

    /** @var  string */
    private $contentAddress;

    /**
     * Like constructor.
     * @param string $followerAddress
     * @param string $contentAddress
     */
    public function __construct($followerAddress, $contentAddress)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_LIKE);
        $this->author = $followerAddress;
        $this->contentAddress = $contentAddress;
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
        $author = array_slice($data, $offset, 20);
        $this->author = ContentData::encodeAddress($author);
        $offset += 20;

        $contentAddress = array_slice($data, $offset, 20);
        $this->contentAddress =  ContentData::encodeAddress($contentAddress);
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
     * @return string
     */
    public function getContentAddress()
    {
        return $this->contentAddress;
    }

    /**
     * @return Like
     */
    public static function newEmpty() {
        $like = new Like(null, null);
        return $like;
    }
}