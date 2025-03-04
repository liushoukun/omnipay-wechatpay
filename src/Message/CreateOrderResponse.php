<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\WechatPay\Common\Signer;
use Omnipay\WechatPay\Helper;

/**
 * Class CreateOrderResponse
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_1
 */
class CreateOrderResponse extends BaseAbstractResponse
{

    /**
     * @var CreateOrderRequest
     */
    protected $request;


    public function getAppOrderData()
    {

        if ($this->isSuccessful()) {
            $data              = [
                'appId'        => $this->request->getAppId(),
                'partnerId'    => $this->request->getMchId(),
                'prepayId'     => $this->getPrepayId(),
                'packageValue' => 'Sign=WXPay',
            ];
            $orderSigner       = Signer::orderSigner(
                $this->request->getAppId(),
                $this->request->getPrivateKey(),
                $this->getPrepayId(),

            );
            $data['timeStamp'] = $orderSigner['timestamp'];
            $data['nonceStr']  = $orderSigner['nonce'];
            $data['sign']      = $orderSigner['signature'];

        } else {
            $data = null;
        }

        return $data;
    }


    public function getPrepayId()
    {
        if ($this->isSuccessful()) {
            $data = $this->getData();

            return $data['data']['prepay_id'];
        } else {
            return null;
        }
    }


    public function getJsOrderData() : ?array
    {

        if ($this->isSuccessful()) {
            $data              = [
                'appId'   => $this->request->getAppId(),
                'package' => 'prepay_id='.$this->getPrepayId(),

            ];
            $orderSigner       = Signer::orderSigner(
                $this->request->getAppId(),
                $this->request->getPrivateKey(),
                $this->getPrepayId(),

            );
            $data['signType']  = 'RSA';
            $data['paySign']   = $orderSigner['signature'];
            $data['timeStamp'] = $orderSigner['timestamp'];
            $data['nonceStr']  = $orderSigner['nonce'];
        } else {
            $data = null;
        }

        return $data;
    }


    public function getCodeUrl()
    {
        if ($this->isSuccessful() && $this->request->getTradeType() == 'NATIVE') {
            $data = $this->getData();

            return $data['data']['code_url'];
        } else {
            return null;
        }
    }


    public function getWapUrl()
    {
        // 外部 H5 发起支付
        if ($this->isSuccessful() && $this->request->getTradeType() == 'WAP') {
            $data = $this->getData();

            //$query['redirect_url'] = $this->request->getReturnUrl();
            return $data['data']['h5_url'].'&redirect_url='.$this->request->getReturnUrl();
        } else {
            return null;
        }
    }
}
