<?php


namespace Omnipay\BCMPay\Responses;


class QueryOrderResponse extends BaseAbstractResponse
{
    public function isPaid()
    {
        $data = $this->getData();

        return isset($data['rsp_body']) && $data['rsp_body']['trans_state'] == 'N';
    }
}
