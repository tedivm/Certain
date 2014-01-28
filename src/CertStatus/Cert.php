<?php
/*
 * This file is part of the CertStatus package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace CertStatus;

/**
 * Cert
 *
 *
 */
class Cert
{

    static public function getCertFromServer($host, $port = 443)
    {
        $options = array();
        $options['ssl']['capture_peer_cert_chain'] = true;
        $options['ssl']['capture_peer_cert'] = true;
        $context = stream_context_create($options);

        $uri = 'ssl://' . $host . ':' . $port;
        $stream = stream_socket_client($uri, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

        $params = stream_context_get_params($stream);

        $sslParms = $params['options']['ssl'];

        if(!isset($sslParms['peer_certificate_chain']) || count($sslParms['peer_certificate_chain']) < 1) {
            $chain = array($params['options']['ssl']['peer_certificate']);
        }else{
            $chain = $params['options']['ssl']['peer_certificate_chain'];
        }


        return new self($chain, $host);
    }

    protected $parent = false;

    protected $parameters;

    public function __construct($chain, $host = null)
    {
        $self = array_shift($chain);
        $this->parameters = openssl_x509_parse($self);
        if(count($chain) > 0)
            $this->parent = new self($chain);
    }
}