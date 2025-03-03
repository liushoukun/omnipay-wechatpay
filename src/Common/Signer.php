<?php

namespace Omnipay\WechatPay\Common;

use UnexpectedValueException;

class Signer
{
    const int KEY_TYPE_PUBLIC  = 1;
    const int KEY_TYPE_PRIVATE = 2;


    public static function getCertSn($cert, $parsed = false) : string
    {
        if ($parsed) {
            $ssl = $cert;
        } else {
            if (is_file($cert)) {
                $cert = file_get_contents($cert);
            }
            $ssl = openssl_x509_parse($cert);
        }
        if (str_starts_with($ssl['serialNumber'], '0x')) {
            return substr($ssl['serialNumber'], 2);
        }
        throw new UnexpectedValueException('please checking your $cert whether or nor correct.');

    }

    /**
     * @param  string  $appId
     * @param  string  $privateKey
     * @param  string  $prepayId
     *
     * @return array{nonce:string,timestamp:string,signature:string}
     */
    public static function orderSigner(string $appId, string $privateKey, string $prepayId) : array
    {

        $nonce     = static::nonce();
        $timestamp = (string) static::timestamp();


        $signature = Rsa::sign(
            static::joinedByLineFeed($appId, $timestamp, $nonce, $prepayId)
            , static::format($privateKey, static::KEY_TYPE_PRIVATE));

        return [
            'nonce'     => $nonce,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];
    }

    public static function signer(
        string $merchantId,
        string $cert,
        string $privateKey,
        string $method,
        string $uri,
        string $body = ''
    ) : string {

        $serial = static::getCertSn($cert);

        $nonce     = static::nonce();
        $timestamp = (string) static::timestamp();
        $signature = Rsa::sign(
            static::request($method, $uri, $timestamp, $nonce, $body)
            , static::format($privateKey, static::KEY_TYPE_PRIVATE));


        return static::authorization(
            $merchantId,
            $nonce,
            $signature,
            $timestamp,
            $serial
        );
    }

    public static function authorization(
        string $mchid,
        string $nonce,
        string $signature,
        string $timestamp,
        string $serial
    ) : string {
        return sprintf(
            'WECHATPAY2-SHA256-RSA2048 mchid="%s",serial_no="%s",timestamp="%s",nonce_str="%s",signature="%s"',
            $mchid, $serial, $timestamp, $nonce, $signature
        );
    }

    public static function request(
        string $method,
        string $uri,
        string $timestamp,
        string $nonce,
        string $body = ''
    ) : string {
        return static::joinedByLineFeed($method, $uri, $timestamp, $nonce, $body);
    }

    public static function joinedByLineFeed(...$pieces) : string
    {
        return implode("\n", array_merge($pieces, ['']));
    }

    public static function nonce(int $size = 32) : string
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Size must be a positive integer.');
        }

        return implode('', array_map(static function (string $c) : string {
            return '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'[ord($c) % 62];
        }, str_split(random_bytes($size))));
    }

    public static function timestamp() : int
    {
        return time();
    }


    /**
     * Convert key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public static function format($key, $type)
    {
        if (is_file($key)) {
            $key = file_get_contents($key);
        }

        if (is_string($key) && strpos($key, '-----') === false) {
            $key = static::convertKey($key, $type);
        }

        return $key;
    }

    /**
     * Convert one line key to standard format
     *
     * @param  string  $key
     * @param  int  $type
     *
     * @return string
     */
    public static function convertKey($key, $type) : string
    {
        $lines = [];

        if ($type === self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----BEGIN PUBLIC KEY-----';
        } else {
            $lines[] = '-----BEGIN RSA PRIVATE KEY-----';
        }

        for ($i = 0; $i < strlen($key); $i += 64) {
            $lines[] = trim(substr($key, $i, 64));
        }

        if ($type === self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----END PUBLIC KEY-----';
        } else {
            $lines[] = '-----END RSA PRIVATE KEY-----';
        }

        return implode("\n", $lines);
    }


}