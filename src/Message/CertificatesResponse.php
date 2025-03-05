<?php

namespace Omnipay\WechatPay\Message;

use Omnipay\WechatPay\Common\AesGcm;

class CertificatesResponse extends BaseAbstractResponse
{


    public function getCertificates()
    {

        $data = $this->getData();
        if (!$this->isSuccessful()) {
            return null;
        }

        $certificates = [];

        foreach ($data['data']['data'] ?? [] as $item) {

            $ciphertext          = $item['encrypt_certificate']['ciphertext'];
            $associated_data     = $item['encrypt_certificate']['associated_data'];
            $nonce               = $item['encrypt_certificate']['nonce'];
            $item['certificate'] = AesGcm::decrypt($ciphertext, $this->request->getEncryptKey(), $nonce,
                $associated_data);
            unset($item['encrypt_certificate']);
            $certificates[] = $item;
        }
        return $certificates;
    }

}