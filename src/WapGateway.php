<?php

namespace Omnipay\WechatPay;

/**
 * Class MwebGateway
 * @package Omnipay\WechatPay
 */
class WapGateway extends BaseAbstractGateway
{
    public function getName()
    {
        return 'WechatPay Mweb';
    }


    public function getTradeType()
    {
        return 'MWEB';
    }
}
