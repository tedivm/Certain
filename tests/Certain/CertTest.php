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

//use Certain\Cert;
use Certain\CertFactory;

class CertTest extends \PHPUnit_Framework_TestCase {

    static public function getTestChain($name)
    {
        $path = TESTING_DIRECTORY . '/Certs/' . $name . '/';
        if(!file_exists($path) || !is_dir($path))
            throw new \Exception('Requested certificate files not found at: ' . $path);

        $certPaths = array();

        $files = scandir($path);
        foreach($files as $file)
        {
            if($file == '.' || $file == '..')
                continue;

            $certPaths[] = $path . $file;
        }

        return CertFactory::getCertFromFiles($certPaths);
    }


    public function testSetFromChain()
    {

    }

    public function testSetHost()
    {

    }

    public function testGetParent()
    {
        $cert = $this->getTestChain('Google');
        $this->assertInstanceOf('\Certain\Cert', $cert, 'getCertFromFiles returns Cert.');

        $parent = $cert->getParent();
        $this->assertInstanceOf('\Certain\Cert', $parent, 'getCertFromFiles properly populates parent');

        $grandParent = $parent->getParent();
        $this->assertInstanceOf('\Certain\Cert', $grandParent, 'getCertFromFiles properly populates grand parent');

    }

}
 