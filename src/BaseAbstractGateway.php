<?php

namespace Omnipay\BCMPay;

use Omnipay\BCMPay\Requests\PreOrderRequest;
use Omnipay\BCMPay\Requests\CompletePurchaseRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractGateway extends AbstractGateway
{
    protected $endpoints = [
        'production' => 'https://open.bankcomm.com',
        'test'       => 'https://117.184.192.242:9443',
    ];

    public function getDefaultParameters()
    {
        return [
            'msg_id'    => uniqid(),
            'fmt_type'  => 'json',
            'charset'   => 'UTF-8',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }


    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->getParameter('app_id');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setAppId($value)
    {
        return $this->setParameter('app_id', $value);
    }

    /**
     * @return mixed
     */
    public function getMsgId()
    {
        return $this->getParameter('msg_id');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setMsgId($value)
    {
        return $this->setParameter('msg_id', $value);
    }


    /**
     * @return mixed
     */
    public function getFmtType()
    {
        return $this->getParameter('fmt_type');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setFmtType($value)
    {
        return $this->setParameter('fmt_type', $value);
    }


    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->getParameter('charset');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setCharset($value)
    {
        return $this->setParameter('charset', $value);
    }

    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
    }


    /**
     * @return mixed
     */
    public function getIsEncrypt()
    {
        return $this->getParameter('is_encrypt');
    }


    /**
     *
     * @return $this
     */
    public function setIsEncrypt()
    {
        return $this->setParameter('is_encrypt', true);
    }




    // /**
    //  * @return mixed
    //  */
    // public function getNotifyUrl()
    // {
    //     return $this->getParameter('notify_url');
    // }
    //
    //
    // /**
    //  * @param $value
    //  *
    //  * @return $this
    //  */
    // public function setNotifyUrl($value)
    // {
    //     return $this->setParameter('notify_url', $value);
    // }


    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->getParameter('timestamp');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    // /**
    //  * @return mixed
    //  */
    // public function getBizContent()
    // {
    //     return $this->getParameter('biz_content');
    // }
    //
    //
    // /**
    //  * @param $value
    //  *
    //  * @return $this
    //  */
    // public function setBizContent($value)
    // {
    //     return $this->setParameter('biz_content', $value);
    // }

    /**
     * @return mixed
     */
    public function getBcmPublicKey()
    {
        return $this->getParameter('bcm_public_key');
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setBcmPublicKey($value)
    {
        return $this->setParameter('bcm_public_key', $value);
    }

    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function production()
    {
        return $this->setEnvironment('production');
    }

    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function test()
    {
        return $this->setEnvironment('test');
    }

    /**
     * @param $value
     *
     * @return $this
     * @throws InvalidRequestException
     */
    public function setEnvironment($value)
    {
        $env = strtolower($value);

        if (!isset($this->endpoints[$env])) {
            throw new InvalidRequestException('The environment is invalid');
        }

        $this->setEndpoint($this->endpoints[$env]);

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BCMPay\Requests\PreOrderRequest
     */
    public function purchase($parameters = array())
    {
        return $this->createRequest(PreOrderRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BCMPay\Requests\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
