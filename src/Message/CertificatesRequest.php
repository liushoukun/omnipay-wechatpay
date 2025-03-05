<?php

namespace Omnipay\WechatPay\Message;

class CertificatesRequest extends BaseAbstractRequest
{
    protected string $method = 'GET';
    protected string $uri = '/v3/certificates';

    public function getData() : array
    {
        return [];
    }

    public function sendData($data)
    {
        $payload = parent::sendData($data);

        return $this->response = new CertificatesResponse($this, $payload);
    }


}