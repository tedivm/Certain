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
    public static function getCertFromChain($chain)
    {
        $self = array_shift($chain);
        $parent = null;
        if (count($chain) > 0) {
            $parent = static::getCertFromChain(($chain));
        }

        return new Cert($self, $parent);
    }

    public static function getCertFromFiles($path)
    {
        if(!is_array(($path)))
            $path = array($path);

        $chain = array();
        foreach ($path as $index => $certPath) {

            if (!file_exists($certPath) || !is_readable($certPath)) {
                throw new \RuntimeException('Path to cert is not accessible: ' . $certPath);
            }

            $certFile = file_get_contents($certPath);
            $chain[$index] = openssl_x509_read($certFile);
        }

        $cert = static::getCertFromChain($chain);

        return $cert;

    }

    public static function getCertFromServer($host, $port = 443)
    {
        $options = array();
        $options['ssl']['capture_peer_cert_chain'] = true;
        $options['ssl']['capture_peer_cert'] = true;
        $context = stream_context_create($options);

        $timeout = defined('CERTAIN_TIMEOUT') && is_numeric(CERTAIN_TIMEOUT) ? CERTAIN_TIMEOUT : 30;

        $uri = 'ssl://' . $host . ':' . $port;
        $stream = @stream_socket_client($uri, $errorNumber, $errorString, $timeout, STREAM_CLIENT_CONNECT, $context);

        if ($stream == false) {
            throw new \RuntimeException('Error getting chain from server: ' . $errorNumber . ' ' . $errorString);
        }

        $params = stream_context_get_params($stream);

        $sslParms = $params['options']['ssl'];

        if (!isset($sslParms['peer_certificate_chain']) || count($sslParms['peer_certificate_chain']) < 1) {
            $chain = array($params['options']['ssl']['peer_certificate']);
        } else {
            $chain = $params['options']['ssl']['peer_certificate_chain'];
        }

        $cert = static::getCertFromChain($chain);
        $cert->setHost($host);

        return $cert;
    }
}
