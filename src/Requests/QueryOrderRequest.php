<?php


namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Responses\QueryOrderResponse;

/**
 * 查询订单（消费、退款）
 */
class QueryOrderRequest extends BaseAbstractRequest
{
    protected $path = '/api/walletpay/misQueryOrder/v1';

    protected $reqBodyKeys = [
        'trans_type',
        'mcht_id',
    ];

    public function validateParams()
    {
        parent::validateParams();

        $this->validateReqBodyOne('orig_trace_no', 'mcht_order_no');
    }

    /**
     * @param mixed $data
     * @return mixed|\Omnipay\BCMPay\Responses\QueryOrderResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new QueryOrderResponse($this, $payload);
    }
}
