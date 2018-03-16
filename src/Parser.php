<?php

namespace Trantor;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Block\BlockFactory;
use BitWasp\Bitcoin\Block\BlockInterface;
use BitWasp\Bitcoin\Exceptions\InvalidNetworkParameter;
use BitWasp\Bitcoin\Script\Opcodes;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Transaction\TransactionInterface;
use BitWasp\Bitcoin\Transaction\TransactionOutputInterface;
use Trantor\Data\Author;
use Trantor\Data\Comment;
use Trantor\Data\ContentData;
use Trantor\Data\Like;
use Trantor\Data\MediaData;
use Trantor\Data\Payment;
use Trantor\Data\Unlike;
use Trantor\Network\BaseNetwork;
use Trantor\Util\Bytes;

/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 0:56
 */
class Parser
{

    /** @var  BaseNetwork */
    private static $net;

    /**
     * @param BaseNetwork $net
     */
    public static function setNetwork($net)
    {
        self::$net = $net;
        Bitcoin::setNetwork($net);
    }

    private static function checkNetwork() {
        if (self::$net == null || !(self::$net instanceof BaseNetwork)) {
            throw new InvalidNetworkParameter('Network is not valid.');
        }
    }

    /**
     * @param TransactionInterface $tx
     * @return null|Author|Comment|Like|MediaData|Payment|Unlike
     */
    public static function getDataFromTx($tx) {
        self::checkNetwork();
        $outs = $tx->getOutputs();

        /** @var TransactionOutputInterface $out */
        foreach ($outs as $out) {

            if ($out->getValue() == 0) {
                $asm = $out->getScript()->getScriptParser()->getHumanReadable();
                $asm = str_replace('OP_RETURN', '', $asm);
                $asm = trim($asm);
                $hexData = $asm;
                $buff = Bytes::hex2ByteArray($hexData);
                //print_r($buff);
                if ($buff[0] == self::$net->getMagicByte()) {
                    $hexData = substr($hexData, 2, strlen($hexData));

                    return ContentData::deserializeData($hexData);
                }
            }
        }

        return null;
    }

    /**
     * @param string $hexTx
     * @return null|Author|Comment|Like|MediaData|Payment|Unlike
     */
    public static function getDataFromRawTx($hexTx) {
        self::checkNetwork();
        return self::getDataFromTx(TransactionFactory::fromHex($hexTx));
    }

    /**
     * @param BlockInterface $block
     * @return array
     */
    public static function getDataFromBlock($block) {
        self::checkNetwork();
        $txs = $block->getTransactions();

        $datas = array();

        /** @var TransactionInterface $tx */
        foreach ($txs as $tx) {
            $d = self::getDataFromTx($tx);
            if ($d != null) {
                $datas[] = $d;
            }
        }

        return $datas;
    }

    /**
     * @param string $hexBlock
     * @return array
     */
    public static function getDataFromRawBlock($hexBlock) {
        self::checkNetwork();
        $block = BlockFactory::fromHex($hexBlock);
        return self::getDataFromBlock($block);
    }
}