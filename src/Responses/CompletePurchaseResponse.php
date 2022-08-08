<?php


namespace Omnipay\BCMPay\Responses;


class CompletePurchaseResponse extends BaseAbstractResponse
{

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        // $data = $this->getBizContent();

        'trade_state' == 'SUCCESS';
    }

    public function getBizContent()
    {
        $data = $this->getData();

        return $data['biz_content'];
    }
}
