<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\WechatPay\Common\Signer;

/**
 * Class BaseAbstractRequest
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractRequest extends AbstractRequest
{

    protected string $endpoint = 'https://api.mch.weixin.qq.com';

    protected string $method = 'POST';

    protected string $uri;


    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->getParameter('app_id');
    }


    /**
     * @param  mixed  $appId
     */
    public function setAppId($appId)
    {
        $this->setParameter('app_id', $appId);
    }

    public function getAppCert()
    {
        return $this->getParameter('app_cert');
    }

    public function setAppCert($appCert)
    {
        $this->setParameter('app_cert', $appCert);
    }



    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }


    /**
     * @param $privateKey
     *
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        return $this->setParameter('private_key', $privateKey);
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->getParameter('api_key');
    }


    /**
     * @param  mixed  $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->setParameter('api_key', $apiKey);
    }


    /**
     * @return mixed
     */
    public function getMchId()
    {
        return $this->getParameter('mch_id');
    }


    /**
     * @param  mixed  $mchId
     */
    public function setMchId($mchId)
    {
        $this->setParameter('mch_id', $mchId);
    }

    /**
     * @return mixed
     */
    public function getSubMchId()
    {
        return $this->getParameter('sub_mch_id');
    }


    /**
     * @param  mixed  $subMchId
     */
    public function setSubMchId($mchId)
    {
        $this->setParameter('sub_mch_id', $mchId);
    }

    /**
     * 子商户 app_id
     *
     * @return mixed
     */
    public function getSubAppId()
    {
        return $this->getParameter('sub_appid');
    }


    /**
     * @param  mixed  $subAppId
     */
    public function setSubAppId($subAppId)
    {
        $this->setParameter('sub_appid', $subAppId);
    }

    /**
     * @return mixed
     */
    public function getEncryptKey()
    {
        return $this->getParameter('encrypt_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEncryptKey($value)
    {
        return $this->setParameter('encrypt_key', $value);
    }
    /**
     * @return mixed
     */
    public function getChannelPublicKey()
    {
        return $this->getParameter('channel_public_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setChannelPublicKey($value)
    {
        return $this->setParameter('channel_public_key', $value);
    }



    /**
     * @return mixed
     */
    public function getChannelCert()
    {
        return $this->getParameter('channel_cert');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setChannelCert($value)
    {
        return $this->setParameter('channel_cert', $value);
    }


    public function sendData($data)
    {

        // TODO 根据查询不同 构建不同的 数据
        if (!empty($data)) {
            $body = json_encode($data, JSON_THROW_ON_ERROR);
        } else {
            $body = '';
        }
        $authorization            = Signer::signer(
            $this->getMchId(),
            $this->getAppCert(),
            $this->getPrivateKey(),
            $this->method,
            $this->uri,
            $body,
        );

        $headers                  = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $headers['Authorization'] = $authorization;
        $url                      = $this->endpoint.$this->uri;

        $response = $this->httpClient->request($this->method, $url, $headers, $body);


        $contents               = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $payload['data']        = $contents;
        $payload['status_code'] = $response->getStatusCode();
        return $payload;
    }

}
