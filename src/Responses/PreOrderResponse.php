<?php

namespace Omnipay\BCMPay\Responses;

class PreOrderResponse extends BaseAbstractResponse
{

    /**
     * @var \Omnipay\BCMPay\Requests\PreOrderRequest
     */
    protected $request;



    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        $data = $this->getData();

        if ($data && !empty($data['rsp_body']['redirect_url'])) {
            return $data['rsp_body']['redirect_url'];
        }


        return '';
    }
}
