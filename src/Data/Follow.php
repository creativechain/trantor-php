<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 15:19
 */

namespace Trantor\Data;


use Trantor\Constants;

class Follow extends AddressRelation
{


    /**
     * Follow constructor.
     * @param string $followerAddress
     * @param string $followedAddress
     */
    public function __construct($followerAddress, $followedAddress)
    {
        parent::__construct(Constants::TYPE_FOLLOW, $followerAddress, $followedAddress);
    }

    /**
     * @return Follow
     */
    public static function newEmpty() {
        $follow = new Follow(null, null, null);
        return $follow;
    }
}