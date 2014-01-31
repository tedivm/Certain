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
 * CertFactory
 *
 *
 */
class CertFactory
{

    static public function getCertFromFiles($path)
    {
        if(!is_array(($path)))
            $path = array($path);

        $chain = array();
        foreach($path as $index => $certPath)
        {
            if(!file_exists($certPath)) {
                return false;
            }

            if(!is_readable($certPath)) {
                return false;
            }

            $certFile = file_get_contents($certPath);
            $x509 = openssl_x509_read($certFile);
            $certParameters = openssl_x509_parse($x509);

            $chain[$index] = array($x509, $certParameters);
        }

        $cert = new Cert();
        $cert->setFromChain($chain);
        return $cert;

    }

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
            $rawChain = array($params['options']['ssl']['peer_certificate']);
        }else{
            $rawChain = $params['options']['ssl']['peer_certificate_chain'];
        }

        $chain = array();
        foreach($rawChain as $rawCert) {
            $rawCertInfo = openssl_x509_parse($rawCert);
            $chain[] = array($rawCert, $rawCertInfo);
        }

        $cert =  new Cert();
        $cert->setFromChain($chain);
        $cert->setHost($host);
        return $cert;
    }
}