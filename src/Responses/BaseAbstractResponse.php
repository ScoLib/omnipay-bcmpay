<?php


namespace Omnipay\BCMPay\Responses;

use Omnipay\Common\Message\AbstractResponse;

abstract class BaseAbstractResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return $data && $data['biz_state'] === 'S';
    }

    public function getRepHead($key = null)
    {
        if (is_null($key)) {
            return array_get($this->data, "rsp_head");
        } else {
            return array_get($this->data, "rsp_head.{$key}");
        }
    }

    public function getRepBody($key = null)
    {
        if (is_null($key)) {
            return array_get($this->data, "rsp_body");
        } else {
            return array_get($this->data, "rsp_body.{$key}");
        }
    }
}
