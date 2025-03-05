<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Helper;
use Psr\Http\Client\Exception\NetworkException;
use Psr\Http\Client\Exception\RequestException;

/**
 * Class QueryOrderRequest
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_2&index=4
 * @method  QueryOrderResponse send()
 */
class QueryOrderRequest extends BaseAbstractRequest
{

    protected string $uri = '/v3/pay/transactions/out-trade-no/';

    protected string $method = 'GET';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {

        $this->validate('app_id','mch_id', 'out_trade_no');

        if (!$this->getTransactionId() && !$this->getOutTradeNo()) {
            throw new InvalidRequestException("The 'transaction_id' or 'out_trade_no' parameter is required");
        }

        $data = [
            'mchid' => $this->getMchId(),

        ];

        $data = array_filter($data);


        $this->uri .= $this->getOutTradeNo();


        return $data;
    }


    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }


    /**
     * @param  mixed  $outTradeNo
     */
    public function setOutTradeNo($outTradeNo)
    {
        $this->setParameter('out_trade_no', $outTradeNo);
    }


    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->getParameter('transaction_id');
    }


    public function setTransactionId($transactionId)
    {
        $this->setParameter('transaction_id', $transactionId);
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed  $data  The data to send
     *
     * @return ResponseInterface
     * @throws NetworkException
     * @throws RequestException
     */
    public function sendData($data)
    {
        $payload = parent::sendData($data);
        return $this->response = new QueryOrderResponse($this, $payload);
    }
}
