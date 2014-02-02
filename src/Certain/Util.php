<?php
/*
 * This file is part of the Certain package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Certain;

use \DateTime;

/**
 * Util
 *
 *
 */
class Util
{
    public static function getDateFromSSLFormat($dateString)
    {
        //14 01 15 14 53 24 Z
        $dateString = rtrim($dateString, 'Z');

        //14 01 15 14 53 24
        // y  m  d  H  i  s
        $format = 'ymdHis';

        return DateTime::createFromFormat($format, $dateString, new \DateTimeZone('UTC'));
    }

}
