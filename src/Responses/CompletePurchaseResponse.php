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
        $data = $this->getData();

        return $data && $data['trade_state'] == 'SUCCESS';
    }

    public function getOutTradeNo()
    {
        $data = $this->getData();

        return $data['out_trade_no'];
    }
}
