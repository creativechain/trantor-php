<?php

namespace Trantor\Network;

use BitWasp\Bitcoin\Network\Network;
use BitWasp\Bitcoin\Script\ScriptType;

/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 19:49
 */
class CreativecoinTestnet extends Network
{
    /**
     * {@inheritdoc}
     * @see Network::$base58PrefixMap
     */
    protected $base58PrefixMap = [
        self::BASE58_ADDRESS_P2PKH => "57",
        self::BASE58_ADDRESS_P2SH => "5F",
        self::BASE58_WIF => "b0",
    ];

    /**
     * {@inheritdoc}
     * @see Network::$bech32PrefixMap
     */
    protected $bech32PrefixMap = [
        self::BECH32_PREFIX_SEGWIT => "tcrea",
    ];

    /**
     * {@inheritdoc}
     * @see Network::$bip32PrefixMap
     */
    protected $bip32PrefixMap = [
        self::BIP32_PREFIX_XPUB => "043587cf",
        self::BIP32_PREFIX_XPRV => "04358394",
    ];

    /**
     * {@inheritdoc}
     * @see Network::$bip32ScriptTypeMap
     */
    protected $bip32ScriptTypeMap = [
        self::BIP32_PREFIX_XPUB => ScriptType::P2PKH,
        self::BIP32_PREFIX_XPRV => ScriptType::P2PKH,
    ];

    /**
     * {@inheritdoc}
     * @see Network::$signedMessagePrefix
     */
    protected $signedMessagePrefix = "Creativecoin Signed Message";

    /**
     * {@inheritdoc}
     * @see Network::$p2pMagic
     */
    protected $p2pMagic = "cacacaca";
}