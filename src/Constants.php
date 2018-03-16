<?php

namespace Trantor;
/**
 * Created by PhpStorm.
 * User = ander
 * Date = 16/03/18
 * Time = 0 =57
 */
class Constants {

    const VERSION = 0x0100;
    const MAGIC_BYTE = 0xB8;
    const MAGIC_BYTE_TESTNET = 0xB8;
    
    const TYPE_EMPTY = 0x00;
    const TYPE_CONTENT = 0x01;
    const TYPE_USER = 0x02;
    const TYPE_LIKE = 0x03;
    const TYPE_COMMENT = 0x04;
    const TYPE_DONATION = 0x05;
    const TYPE_FOLLOW = 0x06;
    const TYPE_UNFOLLOW = 0x07;
    const TYPE_INDEX = 0x08;
    const TYPE_UNLIKE = 0x09;
    const TYPE_PAYMENT = 0x10;
    const TYPE_BLOCK = 0x11;
    const TYPE_OTHER = 0xFF;

    const LIC_CC010 = 0x00; //Creativecoin Commons Public Domain
    const LIC_PPBYNCSA = 0x01; //CC Peer Production. Attribution-NonCommercial-ShareAlike
    const LIC_CCBYNCND40 = 0x02; //CC Attribution-NonComercial-NoDerivs 4.0 International
    const LIC_CCBYNCSA40 = 0x03; //CC Attribution-NonCommercial-ShareAlike 4.0 International
    const LIC_CCBYNC40 = 0x04; //CC Attribution-NonComercial 4.0 International
    const LIC_CCBYSA40 = 0x05; //CC CC-BY-SA-4.0 = Attribution-ShareAlike 4.0 International
    const LIC_CCBYND40 = 0x06; //CC CC-BY-ND-4.0 = Attribution-NoDerivs 4.0 International
    const LIC_CCBY40 = 0x07; //CC Attribution 4.0 international

}