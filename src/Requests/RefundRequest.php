<?php

namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Responses\RefundResponse;

/**
 * 申请退款
 *
 */
class RefundRequest extends BaseAbstractRequest
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
     * @return mixed|\Omnipay\BCMPay\Responses\RefundResponse
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new RefundResponse($this, $payload);
    }
}
