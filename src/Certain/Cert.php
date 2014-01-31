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

    protected $host;

    protected $parameters;


    public function setFromChain($chain)
    {
        $self = array_shift($chain);
        $this->parameters = $self[0];
        $this->cert = $self[1];

        if(count($chain) > 0) {
            $this->parent = new self();
            $this->parent->setFromChain($chain);
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



    public function verify()
    {
    }

    public function verifySignature()
    {

    }



}