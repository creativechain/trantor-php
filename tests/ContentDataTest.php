<?php

namespace Trantor\Tests;

require __DIR__ . '/../vendor/autoload.php';

use BitWasp\Bitcoin\Bitcoin;
use PHPUnit\Framework\TestCase;
use Trantor\Data\ContentData;
use Trantor\Data\MediaData;
use Trantor\Network\CreativecoinTestnet;

/**
 * Created by PhpStorm.
 * User: ander
 * Date: 16/03/18
 * Time: 18:17
 */
class ContentDataTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $net = new CreativecoinTestnet();
        Bitcoin::setNetwork($net);
    }

    public function testDecodeMediaData() {
        $hex = '015d00000002b30000000000000000007ffe5092b26d79128092be3aafa35a67e6d9af2f93bd8a20ac5f53440e881bafcc917af6bade61c2dcf0c9dd10f3497196a57d8ea3fad58db2d7a481ba5586e825718c084d0dbcfa3136c43b38f4cbfe54a03e1522bc3909cf0b3d7cc126ce248a7b2dda6d9eb31b39aecbcb8facc1079089275c91bc2daf4fb07c918b3742377efe90f2f1f060c678005e236a249d80ef19ea789dfe14a1850f26363fa5dccb2ce799c12de6ad14b73046ed307bfffff2ec3000';
        $data = ContentData::deserializeData($hex);

        self::assertEquals(MediaData::class, get_class($data));;
    }

}