<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 12:31
 */

namespace Trantor\Data;


use SebastianBergmann\CodeCoverage\Report\PHP;
use Trantor\Constants;

class Author extends ContentData
{
    /** @var  string */
    private $address;

    /** @var  string */
    private $nick;

    /** @var  string */
    private $email;

    /** @var string */
    private $web;

    /** @var  string */
    private $description;

    /** @var  string */
    private $avatar;

    /** @var  array */
    private $tags;

    /**
     * Author constructor.
     * @param string $address
     * @param string $nick
     * @param string $email
     * @param string $web
     * @param string $description
     * @param string $avatar
     * @param array $tags
     */
    public function __construct($address, $nick, $email, $web, $description, $avatar, $tags)
    {
        parent::__construct(Constants::VERSION, Constants::TYPE_USER);
        $this->address = $address;
        $this->nick = $nick;
        $this->email = $email;
        $this->web = $web;
        $this->description = $description;
        $this->avatar = $avatar;
        $this->tags = $tags;
    }


    /**
     * @return string
     */
    protected function serialize() {
        $bufferHex = ContentData::serializeNumber($this->getVersion());
        $bufferHex .= ContentData::serializeNumber($this->getType());
        $bufferHex .= ContentData::decodeAddress($this->address);
        $bufferHex .= ContentData::serializeText($this->nick);
        $bufferHex .= ContentData::serializeText($this->email);
        $bufferHex .= ContentData::serializeText($this->web);
        $bufferHex .= ContentData::serializeText($this->description);
        $bufferHex .= ContentData::serializeText($this->avatar);
        $tags = json_encode($this->tags);
        $bufferHex .= ContentData::serializeText($tags);
        
        return $bufferHex;
    }

    /**
     * @param array $data
     * @param int $offset
     * @return int
     */
    public function deserialize($data, $offset)
    {
        $offset = parent::deserialize($data, $offset);
        $address = array_slice($data, $offset, 20);
        $this->address = self::encodeAddress($address);
        $offset += 20;

        $nick = self::deserializeText($data, $offset);
        $this->nick = $nick['text'];
        $offset += $nick['offset'];

        $email = self::deserializeText($data, $offset);
        $this->email = $email['text'];
        $offset += $email['offset'];

        $web = self::deserializeText($data, $offset);
        $this->web = $web['text'];
        $offset += $web['offset'];

        $desc = self::deserializeText($data, $offset);
        $this->description = $desc['text'];
        $offset += $desc['offset'];

        $avatar = self::deserializeText($data, $offset);
        $this->avatar = $avatar['text'];
        $offset += $avatar['offset'];

        $tags = self::deserializeText($data, $offset);
        $this->tags = json_decode($tags['text']);
        $offset += $tags['offset'];

        return $offset;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
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
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Author
     */
    public static function newEmpty() {
        $empty = new Author(null, null, null, null, null, null, null);
        return $empty;
    }

}