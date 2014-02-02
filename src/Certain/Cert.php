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

/**
 * Cert
 *
 *
 */
class Cert
{

    protected $parent = false;

    protected $cert = false;

    protected $host = false;

    protected $parameters = false;

    public function __construct($cert, $parent = null)
    {
        $this->cert = $cert;
        $this->parameters = openssl_x509_parse($cert);

        if (isset($parent)) {
            $this->parent = $parent;
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getParent()
    {
        return isset($this->parent) ? $this->parent : false;
    }

    public function getOpenSSLCert()
    {
        return $this->cert;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

}
