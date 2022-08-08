<?php


namespace Omnipay\BCMPay\Responses;

use Omnipay\Common\Message\AbstractResponse;

abstract class BaseAbstractResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return $data && $data['biz_state'] === 'S';
    }
}
