<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\AbstractRequest;

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


    public function getAppCertSn()
    {
        return $this->getParameter('app_cert_sn');
    }

    public function setAppCertSn($appCertSn)
    {
        $this->setParameter('app_cert_sn', $appCertSn);
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

}
