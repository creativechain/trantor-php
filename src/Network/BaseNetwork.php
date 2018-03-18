<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 22:57
 */

namespace Trantor\Network;


use BitWasp\Bitcoin\Network\Network;

class BaseNetwork extends Network
{

    /**
     * {@inheritdoc}
     * @see Network::$signedMessagePrefix
     */
    protected $signedMessagePrefix = "Creativecoin Signed Message:\n";

    /**
     * @var int
     */
    protected $magicByte;

    /**
     * @return int
     */
    public function getMagicByte()
    {
        return $this->magicByte;
    }


}