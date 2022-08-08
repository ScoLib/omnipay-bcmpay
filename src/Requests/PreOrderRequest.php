<?php

namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Responses\PreOrderResponse;

/**
 * 预订单下单
 *
 */
class PreOrderRequest extends BaseAbstractRequest
{

    protected $path = '/api/walletpay/misPreOrder/v1';

    protected $reqBodyKeys = [
        'mch_id',
        'front_notify_url',
        'notify_url',
        'title',
        'device_info',
        'time_start',
        'time_expire',
        'total_amount',
        'trans_type',
        'out_trade_no',
    ];

    /**
     * @param mixed $data
     * @return \Omnipay\BCMPay\Responses\PreOrderResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new PreOrderResponse($this, $payload);
    }
}
