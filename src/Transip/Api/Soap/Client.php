<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-3.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @author      Jeroen Bleijenberg
 *
 * @copyright   Copyright (c) 2017
 * @license     http://opensource.org/licenses/GPL-3.0 General Public License (GPL 3.0)
 */
namespace Transip\Api\Soap;

use Transip\Api\Config;

class Client
{

    /**
     * @var \SoapClient $soapClient
     */
    protected $soapClient;

    protected $timestamp;

    protected $nonce;

    protected $service;

    protected $classmap;

    public function getClient()
    {
        if (!$this->soapClient instanceof \SoapClient) {
            $extensions = get_loaded_extensions();

            if (!class_exists('SoapClient') || !in_array('soap', $extensions)) {
                throw new \Exception('The required PHP SOAP extension could not be found. You need to install the PHP SOAP extension. (See: http://www.php.net/manual/en/book.soap.php)');
            }

            if (!in_array('openssl', $extensions)) {
                throw new \Exception('The required PHP OpenSSL extension could not be found. You need to install PHP with the OpenSSL extension. (See: http://www.php.net/manual/en/book.openssl.php)');
            }

            $options = [
                'classmap' => $this->getClassMap(),
                'encoding' => 'utf-8',
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                'trance' => false
            ];

            $config = Config::getInstance();

            try {
                $wsdlUrl    = sprintf("https://%s/wsdl?service=%s", $config->getEndpoint(), $this->getService());
                $soapClient = new \SoapClient($wsdlUrl, $options);

                $this->timestamp = time();
                $this->nonce = uniqid('', true);

                $soapClient->__setCookie('login', $config->getUser());
                $soapClient->__setCookie('mode', $config->getMode());
                $soapClient->__setCookie('timestamp', $this->timestamp);
                $soapClient->__setCookie('nonce', $this->nonce);
                $soapClient->__setCookie('clientVersion', (string)$config->getApiVersion());

                $this->soapClient = $soapClient;
            } catch (\SoapFault $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return $this;
    }

    public function getClassMap()
    {
        return $this->classmap;
    }

    public function getService()
    {
        return $this->service;
    }

    public function doRequest($method, $parameters)
    {
        $parametersArray = array_merge([$parameters], ['__method' => $method]);

        $this->soapClient->__setCookie('signature', $this->urlEncode(
            $this->getSignature($parametersArray)
        ));

        return $this->soapClient->$method($parameters);
    }

    protected function getSignature($parameters)
    {
        $signature = $this->sign(array_merge($parameters, [
            '__service'   => $this->service,
            '__hostname'  => Config::getInstance()->getEndpoint(),
            '__timestamp' => $this->timestamp,
            '__nonce'     => $this->nonce
        ]));

        return $signature;
    }

    /**
     * Our own function to encode a string according to RFC 3986 since.
     * PHP < 5.3.0 encodes the ~ character which is not allowed.
     *
     * @param string $string The string to encode.
     * @return string The encoded string according to RFC 3986.
     */
    protected function urlEncode($string)
    {
        $string = rawurlencode($string);

        return str_replace('%7E', '-', $string);
    }

    /**
     * @param $args
     * @return string
     * @throws \Exception
     */
    protected function sign($args)
    {
        if (!preg_match('/-----BEGIN (RSA )?PRIVATE KEY-----(.*)-----END (RSA )?PRIVATE KEY-----/si', Config::getInstance()->getPrivateKey(), $matches)) {
            throw new \Exception('Invalid or no private key found. Check your config.yaml file or request a new private key in your TransIP controlpanel.');
        }

        $key = $matches[2];
        $key = preg_replace('/\s*/s', '', $key);
        $key = chunk_split($key, 64, "\n");
        $key = "-----BEGIN PRIVATE KEY-----\n" . $key . "-----END PRIVATE KEY-----";

        $digest = $this->sha512Asn1($this->encodeParameters($args));

        if (!@openssl_private_encrypt($digest, $signature, $key)) {
            throw new \Exception('Could not sign your request, please supply your private key in the ApiSettings file. You can request a new private key in your TransIP controlpanel.');
        }

        return base64_encode($signature);
    }

    /**Creats a digest of the given data, with an ssh1 header
     *
     * @param $args
     * @return string
     */
    protected function sha512Asn1($args)
    {
        $digest = hash('sha512', $args, true);

        $asn1  = chr(0x30).chr(0x51);
        $asn1 .= chr(0x30).chr(0x0d);
        $asn1 .= chr(0x06).chr(0x09);
        $asn1 .= chr(0x60).chr(0x86).chr(0x48).chr(0x01).chr(0x65);
        $asn1 .= chr(0x03).chr(0x04);
        $asn1 .= chr(0x02).chr(0x03);
        $asn1 .= chr(0x05).chr(0x00);
        $asn1 .= chr(0x04).chr(0x40);
        $asn1 .= $digest;

        return $asn1;
    }

    /**
     * Encodes the given paramaters into a url encoded string based upon RFC 3986.
     *
     * @param $args
     * @param null $keyPrefix
     * @return string
     */
    protected function encodeParameters($args, $keyPrefix = null)
    {
        if (!is_array($args) && !is_object($args)) {
            return $this->urlEncode($args);
        }

        $encodedData = [];

        foreach ($args as $key => $value) {
            if ($keyPrefix == null) {
                $encodedKey = $this->urlEncode($key);
            } else {
                $encodedKey = sprintf('%s[%s]', $keyPrefix , $this->urlEncode($key));
            }

            if (is_array($value) || is_object($value)) {
                $encodedData[] = $this->encodeParameters($value, $encodedKey);
            } else {
                $encodedData[] = sprintf('%s=%s', $encodedKey, $this->urlEncode($value));
            }
        }

        return implode('&', $encodedData);
    }
}