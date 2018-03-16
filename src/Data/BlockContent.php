<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 15:19
 */

namespace Trantor\Data;


use Trantor\Constants;

class BlockContent extends AddressRelation
{


    /**
     * BlockContent constructor.
     * @param string $followerAddress
     * @param string $followedAddress
     */
    public function __construct($followerAddress, $followedAddress)
    {
        parent::__construct(Constants::TYPE_BLOCK, $followerAddress, $followedAddress);
    }

    /**
     * @return BlockContent
     */
    public static function newEmpty() {
        $blockContent = new BlockContent(null, null, null);
        return $blockContent;
    }
}