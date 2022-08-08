<?php


namespace Omnipay\BCMPay\Requests;



use Omnipay\BCMPay\Common\Signer;
use Omnipay\BCMPay\Responses\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends BaseAbstractRequest
{

    public function getData()
    {
        $this->validateParams();

        return $this->getParams();
    }


    public function validateParams()
    {
        $this->validate('params');
    }


    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->getParameter('params');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setParams($value)
    {
        return $this->setParameter('params', $value);
    }

    public function sendData($data)
    {
        $sign = $data['sign'];

        $signer = new Signer($data);
        $signer->setIgnores(['sign']);
        $content = $signer->getContentToSign();
        $match = $signer->verifyWithRSA($content, $sign, $this->getBcmPublicKey(), OPENSSL_ALGO_SHA256);

        if (! $match) {
            throw new InvalidRequestException('The signature is not match');
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
