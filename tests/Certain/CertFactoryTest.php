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

use Certain\Cert;
use Certain\CertFactory;

class CertFactoryTest extends PHPUnit_Framework_TestCase {

    static public function getTestChain($name)
    {
        $path = TESTING_DIRECTORY . '/Certs/' . $name . '/';
        if(!file_exists(($path) || !is_dir($path))
        throw new \Exception('Requested certificate files not found at: ' . $path);

        $certPaths = array();

        $files = scandir($path);
        foreach($files as $file)
        {
            if($file == '.' || $file == '..')
                continue;

            $certPaths[] = $file;
        }

        return CertFactory::getCertFromFiles($certPaths);
    }

    public function testGetCertFromFiles()
    {
        $path = TESTING_DIRECTORY . '/Certs/' . $name . '/';
        if(!file_exists(($path) || !is_dir($path))
        throw new \Exception('Requested certificate files not found at: ' . $path);

        $certPaths = array();

        $files = scandir($path);
        foreach($files as $file)
        {
            if($file == '.' || $file == '..')
                continue;

            $certPaths[] = $file;
        }

        $cert = CertFactory::getCertFromFiles($certPaths);



    }

    public function testGetCertFromServer()
    {

    }
}
