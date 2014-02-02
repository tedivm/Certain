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

use Certain\CertFactory;


class CertFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCertFromChain()
    {

    }

    public function testGetCertFromFiles()
    {
        $path = TESTING_DIRECTORY . '/Certs/Google/';
        if(!file_exists($path) || !is_dir($path))
            throw new \Exception('Requested certificate files not found at: ' . $path);

        $certPaths = array(
            $path . 'cert0.crt',
            $path . 'cert1.crt',
            $path . 'cert2.crt'
        );

        $cert = CertFactory::getCertFromFiles($certPaths);
        $this->assertInstanceOf('\Certain\Cert', $cert, 'getCertFromFiles returns Cert.');

        $parent = $cert->getParent();
        $this->assertInstanceOf('\Certain\Cert', $parent, 'getCertFromFiles properly populates parent');

        $grandParent = $parent->getParent();
        $this->assertInstanceOf('\Certain\Cert', $grandParent, 'getCertFromFiles properly populates grand parent');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetCertFromFiles_PermissionException()
    {
        $files = array(
            '/root/cert1.crt',
            '/root/cert2.crt',
            '/root/cert3.crt',
        );
        CertFactory::getCertFromFiles($files);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetCertFromFiles_WrongPathException()
    {
        $files = array(
            '/fakepath/cert1.crt',
            '/fakepath/cert2.crt',
            '/fakepath/cert3.crt',
        );
        CertFactory::getCertFromFiles($files);
    }


    public function testGetCertFromServer()
    {
        $cert = CertFactory::getCertFromServer('www.google.com', 443);
        $this->assertInstanceOf('\Certain\Cert', $cert, 'getCertFromFiles returns Cert.');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetCertFromServerException()
    {
        CertFactory::getCertFromServer('localhost', 30000);
    }

}
