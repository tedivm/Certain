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
 * Cert
 *
 *
 */
class Cert
{

    protected $parent = false;

    protected $cert = false;

    protected $cn;

    protected $validFrom;

    protected $validTo;

    protected $host = false;

    protected $parameters = false;

    public function __construct($cert, $parent = null)
    {
        $this->cert = $cert;
        $parameters = $this->parameters = openssl_x509_parse($cert);

        if (isset($parent)) {
            $this->parent = $parent;
        }

        $this->cn = $parameters['subject']['CN'];

        if(isset($parameters['validTo_time_t']))
        {
            $validTo = new DateTime();
            $validTo->setTimestamp($parameters['validTo_time_t']);
        }else{
            $validTo = Util::getDateFromSSLFormat($parameters['validTo']);
        }
        $this->validTo = $validTo;

        if(isset($parameters['validFrom_time_t']))
        {
            $validFrom = new DateTime();
            $validFrom->setTimestamp($parameters['validFrom_time_t']);
        }else{
            $validFrom = Util::getDateFromSSLFormat($parameters['validFrom']);
        }
        $this->validFrom = $validFrom;
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

    public function getValidFrom()
    {
        return $this->validFrom;
    }

    public function getValidTo()
    {
        return $this->validTo;
    }

    public function getCommonName()
    {
        return $this->cn;
    }

}