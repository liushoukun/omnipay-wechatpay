<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class BaseAbstractResponse
 * @package Omnipay\WechatPay\Message
 */
abstract class BaseAbstractResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful() : bool
    {

        $data = $this->getData();

        return isset($data['status_code']) && $data['status_code'] === 200;
    }
}
