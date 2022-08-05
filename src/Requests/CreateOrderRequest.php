<?php

namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Responses\CreateOrderResponse;

class CreateOrderRequest extends BaseAbstractRequest
{

    protected $path = '/api/walletpay/misPreOrder/v1';

    /**
     * @return mixed
     */
    // public function getNotifyUrl()
    // {
    //     return $this->getParameter('notify_url');
    // }


    /**
     * @param $value
     *
     * @return $this
     */
    // public function setNotifyUrl($value)
    // {
    //     return $this->setParameter('notify_url', $value);
    // }
    //
    //
    // /**
    //  * @return mixed
    //  */
    // public function getReturnUrl()
    // {
    //     return $this->getParameter('return_url');
    // }
    //
    //
    // /**
    //  * @param $value
    //  *
    //  * @return $this
    //  */
    // public function setReturnUrl($value)
    // {
    //     return $this->setParameter('return_url', $value);
    // }

    /**
     * @param mixed $data
     * @return mixed|\Omnipay\BCMPay\Responses\CreateOrderResponse|\Omnipay\Common\Message\ResponseInterface|\Psr\Http\Message\StreamInterface
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new CreateOrderResponse($this, $payload);
    }
}
