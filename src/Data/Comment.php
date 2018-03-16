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

class Comment extends ContentData
{
    /** @var  string */
    private $author;

    /** @var  string */
    private $contentAddress;

    /** @var  string */
    private $comment;

    /**
     * Comment constructor.
     * @param string $followerAddress
     * @param string $contentAddress
     * @param string $comment
     */
    public function __construct($followerAddress, $contentAddress, $comment)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_COMMENT);
        $this->author = $followerAddress;
        $this->contentAddress = $contentAddress;
        $this->comment = $comment;
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
        $bufferHex .= ContentData::serializeText($this->comment);
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

        $comment = ContentData::deserializeText($data, $offset);
        $this->comment = $comment['text'];
        $offset += $comment['offset'];

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
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return Comment
     */
    public static function newEmpty() {
        $comment = new Comment(null, null, null);
        return $comment;
    }
}