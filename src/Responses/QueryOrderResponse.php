<?php


namespace Omnipay\BCMPay\Responses;


class QueryOrderResponse extends BaseAbstractResponse
{
    public function isPaid()
    {
        return $this->getRepBody('trans_state') == 'N';
    }
}
