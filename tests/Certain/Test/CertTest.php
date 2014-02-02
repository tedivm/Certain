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

class CertTest extends \PHPUnit_Framework_TestCase
{
    public static function getTestChain($name)
    {
        $path = TESTING_DIRECTORY . '/Certs/' . $name . '/';
        if(!file_exists($path) || !is_dir($path))
            throw new \Exception('Requested certificate files not found at: ' . $path);

        $certPaths = array();

        $files = scandir($path);
        foreach ($files as $file) {
            if(substr($file, -4) !== '.crt')
                continue;

            $certPaths[] = $path . $file;
        }

        return CertFactory::getCertFromFiles($certPaths);
    }


    public function testConstruct()
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

    public function testGetOpenSSLCert()
    {
        $cert = $this->getTestChain('Google');
        $sslCert = $cert->getOpenSSLCert();
        $this->assertInternalType('resource', $sslCert, 'Returns resource.');
        $this->assertEquals('OpenSSL X.509', get_resource_type($sslCert), 'Resources is of type OpenSSL X.509');
    }

    public function testGetParameters()
    {
        $cert = static::getTestChain('Google');

        $parameters = $cert->getParameters();
        $this->assertInternalType('array', $parameters, 'getParameters returns array');
        $this->assertGreaterThan(0, count($parameters), 'Parameters array not empty.');
    }

    public function testGetValidFrom()
    {
        $cert = $this->getTestChain('Google');
        $validFrom = $cert->getValidFrom();
        $this->assertInstanceOf('\DateTime', $validFrom, 'ValidFrom is DateTime.');
        $this->assertEquals('1389797604', $validFrom->getTimestamp(), 'Returns right timestamp.');
    }

    public function testGetValidTo()
    {
        $cert = $this->getTestChain('Google');
        $validTo = $cert->getValidTo();
        $this->assertInstanceOf('\DateTime', $validTo, 'ValidFrom is DateTime.');
        $this->assertEquals('1400112000', $validTo->getTimestamp(), 'Returns right timestamp.');
    }

    public function testGetCommonName()
    {
        $cert = $this->getTestChain('Google');
        $this->assertEquals('www.google.com', $cert->getCommonName());
    }
}
