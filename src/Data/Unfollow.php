<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 15:19
 */

namespace Trantor\Data;


use Trantor\Constants;

class Unfollow extends AddressRelation
{


    /**
     * Unfollow constructor.
     * @param string $followerAddress
     * @param string $followedAddress
     */
    public function __construct($followerAddress, $followedAddress)
    {
        parent::__construct(Constants::TYPE_UNFOLLOW, $followerAddress, $followedAddress);
    }

    /**
     * @return Unfollow
     */
    public static function newEmpty() {
        $unfollow = new Unfollow(null, null, null);
        return $unfollow;
    }
}