<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Common\AesGcm;
use Omnipay\WechatPay\Common\Signer;
use Omnipay\WechatPay\Helper;
use function Symfony\Component\Translation\t;

/**
 * Class CompletePurchaseRequest
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_7&index=3
 * @method  CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends BaseAbstractRequest
{
    public function setRequestParams($requestParams)
    {
        $this->setParameter('request_params', $requestParams);
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed  $data  The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {

        $responseData               = [];
        $responseData['data']       = $data;
        $responseData['paid']       = false;
        $responseData['sign_match'] = false;
        if (isset($data['trade_state']) && $data['trade_state'] == 'SUCCESS') {
            $responseData['paid']       = true;
            $responseData['sign_match'] = true;
        } else {
            $responseData['paid'] = false;
        }

        return $this->response = new CompletePurchaseResponse($this, $responseData);
    }


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $requestBody = $this->getRequestParams();
        // 验签

        $serial = $requestBody['headers']['wechatpay-serial'];

        if (str_starts_with($serial, 'PUB_KEY')) {
            // 微信公钥
            $publicKey = $this->getChannelPublicKey();
        } else {
            // 平台证书
            $publicKey = Signer::getCertPublicKey($this->getChannelCert());
        }


        $verifyState = Signer::verify(
            $requestBody['headers']['wechatpay-timestamp'],
            $requestBody['headers']['wechatpay-nonce'],
            $requestBody['body'],
            $requestBody['headers']['wechatpay-signature'],
            $publicKey
        );
        if($verifyState === false){
            return  null;
        }



        [
            'resource' => [
                'ciphertext'      => $ciphertext,
                'nonce'           => $nonce,
                'associated_data' => $aad
            ]
        ] = $requestBody['data'] ?? [];

        // 解密

        $inBodyResource = AesGcm::decrypt($ciphertext, $this->getEncryptKey(), $nonce, $aad);



        return $responseData = json_decode($inBodyResource, true);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }
}
