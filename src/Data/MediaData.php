<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 14:26
 */

namespace Trantor\Data;


use Trantor\Constants;
use Trantor\Util\Bytes;

class MediaData extends ContentData
{

    /** @var  string */
    private $userAddress;

    /** @var  string */
    private $contentAddress;

    /** @var  int */
    private $license;

    /** @var  string */
    private $title;

    /** @var  string */
    private $description;

    /** @var  string */
    private $contentType;

    /** @var  array */
    private $tags;

    /** @var  int */
    private $price;

    /** @var  string */
    private $publicContent;

    /** @var  string */
    private $privateContent;

    /** @var  string */
    private $hash;

    /** @var  int */
    private $publicFileSize;

    /** @var  int */
    private $privateFileSize;

    /**
     * MediaData constructor.
     * @param string $userAddress
     * @param string $contentAddress
     * @param int $license
     * @param string $title
     * @param string $description
     * @param string $contentType
     * @param array $tags
     * @param int $price
     * @param string $publicContent
     * @param string $privateContent
     * @param string $hash
     * @param int $publicFileSize
     * @param int $privateFileSize
     */
    public function __construct($userAddress, $contentAddress, $license, $title, $description, $contentType, $tags, $price, $publicContent, $privateContent, $hash, $publicFileSize, $privateFileSize)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_CONTENT);
        $this->userAddress = $userAddress;
        $this->contentAddress = $contentAddress;
        $this->license = $license;
        $this->title = $title;
        $this->description = $description;
        $this->contentType = $contentType;
        $this->tags = $tags;
        $this->price = $price;
        $this->publicContent = $publicContent;
        $this->privateContent = $privateContent;
        $this->hash = $hash;
        $this->publicFileSize = $publicFileSize;
        $this->privateFileSize = $privateFileSize;
    }


    /**
     * @return string
     */
    protected function serialize()
    {
        $dataHex = ContentData::serializeNumber($this->getVersion());
        $dataHex .= ContentData::serializeNumber($this->getType());
        $dataHex .= ContentData::decodeAddress($this->userAddress);
        $dataHex .= ContentData::decodeAddress($this->contentAddress);
        $dataHex .= ContentData::serializeNumber($this->license);
        $dataHex .= ContentData::serializeText($this->title);
        $dataHex .= ContentData::serializeText($this->description);
        $dataHex .= ContentData::serializeText($this->contentType);
        $tags = json_encode($this->tags);
        $dataHex .= ContentData::serializeText($tags);
        $dataHex .= ContentData::serializeNumber($this->price, 8);
        $dataHex .= ContentData::serializeText($this->publicContent);
        $dataHex .= ContentData::serializeText($this->privateContent);

        $dataHex .= $this->hash;
        $dataHex .= ContentData::serializeNumber($this->publicFileSize, 4);
        $dataHex .= ContentData::serializeNumber($this->privateFileSize, 4);

        return $dataHex;
    }

    protected function deserialize($data, $offset)
    {
        $offset = parent::deserialize($data, $offset);

        $userAddress = array_slice($data, $offset, 20);
        $this->userAddress = ContentData::encodeAddress($userAddress);
        $offset += 20;

        $contentAddress = array_slice($data, $offset, 20);
        $this->contentAddress = ContentData::encodeAddress($contentAddress);
        $offset += 20;

        $this->license = $data[$offset];
        $offset += 1;

        $desTitle = ContentData::deserializeText($data, $offset);
        $this->title = $desTitle['text'];
        $offset += $desTitle['offset'];

        $desComment = ContentData::deserializeText($data, $offset);
        $this->description = $desComment['text'];
        $offset += $desComment['offset'];

        $destContentType = ContentData::deserializeText($data, $offset);
        $this->contentType = $destContentType['text'];
        $offset += $destContentType['offset'];

        $desTags = ContentData::deserializeText($data, $offset);
        $this->tags = json_decode($desTags['text']);
        $offset += $desTags['offset'];

        $this->price = Bytes::readInt64($data, $offset);
        $offset += 8;

        $publicContent = ContentData::deserializeText($data, $offset);
        $this->publicContent = $publicContent['text'];
        $offset += $publicContent['offset'];

        $privateContent = ContentData::deserializeText($data, $offset);
        $this->privateContent = $privateContent['text'];
        $offset += $privateContent['offset'];

        $hash = array_slice($data, $offset, 32);
        $this->hash = Bytes::byteArray2Hex($hash);
        $offset += 32;

        $this->publicFileSize = Bytes::readInt32($data, $offset);
        $offset += 4;

        $this->privateFileSize = Bytes::readInt32($data, $offset);
        $offset += 4;

        return $offset;
    }

    /**
     * @return string
     */
    public function getUserAddress()
    {
        return $this->userAddress;
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
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getPublicContent()
    {
        return $this->publicContent;
    }

    /**
     * @return string
     */
    public function getPrivateContent()
    {
        return $this->privateContent;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getPublicFileSize()
    {
        return $this->publicFileSize;
    }

    /**
     * @return int
     */
    public function getPrivateFileSize()
    {
        return $this->privateFileSize;
    }

    /**
     * @return MediaData
     */
    public static function newEmpty() {
        $media = new MediaData(null, null, null, null, null, null, null, null, null, null, null, null, null);
        return $media;
    }
}