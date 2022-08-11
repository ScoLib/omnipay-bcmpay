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
        return $this->getRepBody('redirect_url') ?? '';
    }
}
