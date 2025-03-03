<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\WechatPay\Common\Signer;
use Omnipay\WechatPay\Helper;
use Psr\Http\Client\Exception\NetworkException;
use Psr\Http\Client\Exception\RequestException;

/**
 * Class CreateOrderRequest
 *
 * @package Omnipay\WechatPay\Message
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1
 * @method  CreateOrderResponse send()
 */
class CreateOrderRequest extends BaseAbstractRequest
{

    const string JSAPI  = 'JSAPI';
    const string WAP    = 'WAP';
    const string NATIVE = 'WEB';
    const string APP    = 'APP';

    protected array $uriList = [
        self::JSAPI  => '/v3/pay/transactions/jsapi',
        self::WAP    => '/v3/pay/transactions/h5',
        self::NATIVE => '/v3/pay/transactions/native',
        self::APP    => '/v3/pay/transactions/app',
    ];


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {


        $this->validate(
            'app_id',
            'mch_id',
            'out_trade_no',
            'amount',
            'notify_url',
            'trade_type'
        );

        $tradeType = strtoupper($this->getTradeType());

        $this->uri = $this->uriList[$tradeType];
        if ($tradeType == 'JSAPI') {
            $this->validate('payer');
        }

        // 转换下单参数
        $data = array (
            'appid'       => $this->getAppId(),//*
            'mchid'       => $this->getMchId(),
            'sub_appid'   => $this->getSubAppId(),
            'sub_mch_id'  => $this->getSubMchId(),
            'device_info' => $this->getDeviceInfo(),//*
            'time_expire' => $this->getTimeExpire(),//*

            'description'  => $this->getDescription(),
            'attach'       => $this->getAttach(),
            'out_trade_no' => $this->getOutTradeNo(),//*
            'amount'       => [
                'total'    => (int) $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
            'payer'        => $this->getPayer(),
            'detail'       => null,
            'scene_info'   => $this->getSceneInfo(),
            'settle_info'  => [
                'profit_sharing' => false,
            ],
            'goods_tag'    => $this->getGoodsTag(),
            'notify_url'   => $this->getNotifyUrl(), //*
        );


        return array_filter($data);

    }


    /**
     * @return mixed
     */
    public function getTradeType()
    {
        return $this->getParameter('trade_type');
    }


    /**
     * @return mixed
     */
    public function getDeviceInfo()
    {
        return $this->getParameter('device_Info');
    }


    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->getParameter('body');
    }


    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->getParameter('detail');
    }


    public function getSceneInfo()
    {
        return $this->getParameter('scene_info');
    }

    public function setSceneInfo($sceneInfo)
    {
        return $this->setParameter('scene_info', $sceneInfo);
    }


    /**
     * @return mixed
     */
    public function getAttach()
    {
        return $this->getParameter('attach');
    }


    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }


    /**
     * @return mixed
     */
    public function getFeeType()
    {
        return $this->getParameter('fee_type');
    }


    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }


    /**
     * @return mixed
     */
    public function getSpbillCreateIp()
    {
        return $this->getParameter('spbill_create_ip');
    }


    /**
     * @return mixed
     */
    public function getTimeStart()
    {
        return $this->getParameter('time_start');
    }


    /**
     * @return mixed
     */
    public function getTimeExpire()
    {
        return $this->getParameter('time_expire');
    }


    /**
     * @return mixed
     */
    public function getGoodsTag()
    {
        return $this->getParameter('goods_tag');
    }


    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }


    /**
     * @return mixed
     */
    public function getLimitPay()
    {
        return $this->getParameter('limit_pay');
    }


    /**
     * @return mixed
     */
    public function getOpenId()
    {
        return $this->getParameter('open_id');
    }


    /**
     * @param  mixed  $deviceInfo
     */
    public function setDeviceInfo($deviceInfo)
    {
        $this->setParameter('device_Info', $deviceInfo);
    }


    /**
     * @param  mixed  $body
     */
    public function setBody($body)
    {
        $this->setParameter('body', $body);
    }


    /**
     * @param  mixed  $detail
     */
    public function setDetail($detail)
    {
        $this->setParameter('detail', $detail);
    }

    public function setPayer($payer)
    {
        $this->setParameter('payer', $payer);
    }


    public function getPayer()
    {
        return $this->getParameter('payer');
    }


    /**
     * @param  mixed  $attach
     */
    public function setAttach($attach)
    {
        $this->setParameter('attach', $attach);
    }


    /**
     * @param  mixed  $outTradeNo
     */
    public function setOutTradeNo($outTradeNo)
    {
        $this->setParameter('out_trade_no', $outTradeNo);
    }


    /**
     * @param  mixed  $feeType
     */
    public function setFeeType($feeType)
    {
        $this->setParameter('fee_type', $feeType);
    }


    /**
     * @param  mixed  $totalFee
     */
    public function setTotalFee($totalFee)
    {
        $this->setParameter('total_fee', $totalFee);
    }


    /**
     * @param  mixed  $spbillCreateIp
     */
    public function setSpbillCreateIp($spbillCreateIp)
    {
        $this->setParameter('spbill_create_ip', $spbillCreateIp);
    }


    /**
     * @param  mixed  $timeStart
     */
    public function setTimeStart($timeStart)
    {
        $this->setParameter('time_start', $timeStart);
    }


    /**
     * @param  mixed  $timeExpire
     */
    public function setTimeExpire($timeExpire)
    {
        $this->setParameter('time_expire', $timeExpire);
    }


    /**
     * @param  mixed  $goodsTag
     */
    public function setGoodsTag($goodsTag)
    {
        $this->setParameter('goods_tag', $goodsTag);
    }


    public function setNotifyUrl($notifyUrl)
    {
        $this->setParameter('notify_url', $notifyUrl);
    }


    /**
     * @param  mixed  $tradeType
     */
    public function setTradeType($tradeType)
    {
        $this->setParameter('trade_type', $tradeType);
    }


    /**
     * @param  mixed  $limitPay
     */
    public function setLimitPay($limitPay)
    {
        $this->setParameter('limit_pay', $limitPay);
    }


    /**
     * @param  mixed  $openId
     */
    public function setOpenId($openId)
    {
        $this->setParameter('open_id', $openId);
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


        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];


        $body                     = json_encode($data, JSON_THROW_ON_ERROR);
        $authorization            = Signer::signer(
            $this->getMchId(),
            $this->getAppCert(),
            $this->getPrivateKey(),
            $this->method,
            $this->uri,
            $body,
        );
        $headers['Authorization'] = $authorization;


        $response = $this->httpClient->request(
            'POST',
            $this->endpoint.$this->uri,
            $headers, $body);


        $contents = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $payload['data']        = $contents;
        $payload['status_code'] = $response->getStatusCode();


        return $this->response = new CreateOrderResponse($this, $payload);
    }
}
