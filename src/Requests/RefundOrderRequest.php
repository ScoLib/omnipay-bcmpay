<?php

namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Responses\RefundOrderResponse;

/**
 * 申请退款
 *
 */
class RefundOrderRequest extends BaseAbstractRequest
{

    protected $path = '/api/walletpay/misRefund/v1';

    protected $reqBodyKeys = [
        'refund_amount',
        'mcht_id',
        // 'notify_url',
        'mcht_order_no',
    ];

    public function validateParams()
    {
        parent::validateParams();

        $this->validateReqBodyOne('orig_trace_no', 'sys_order_no', 'orig_mcht_order_no');
    }

    /**
     * @param mixed $data
     * @return mixed|\Omnipay\BCMPay\Responses\RefundOrderResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new RefundOrderResponse($this, $payload);
    }
}
