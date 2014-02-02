<?php
/*
 * This file is part of the Certain package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Certain\Test;

use Certain\Util;

class UtilTest extends \PHPUnit_Framework_TestCase {

    public function testGetDateFromSSLFormat()
    {
        $fromDate = Util::getDateFromSSLFormat('140115145324Z');
        $this->assertEquals('1389797604', $fromDate->getTimestamp(), 'Returns the right date.');

        $validTo = Util::getDateFromSSLFormat('140515000000Z');
        $this->assertEquals('1400112000', $validTo->getTimestamp(), 'Returns the right date.');
    }
}
 