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

        $arr = explode(',"sign":"', $data);

        $content = substr($arr[0], 1);

        $sign = substr($arr[1],0, -2);

        $match = (new Signer())->verifyWithRSA($content, $sign, $this->getBcmPublicKey(), OPENSSL_ALGO_SHA256);

        if (! $match) {
            throw new InvalidRequestException('The signature is not match');
        }

        $data = json_decode($data, true);

        $data = $data['encrypt_key'] ? $this->decryptBizContent($data) : $data['biz_content'];

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function decryptBizContent(array $data)
    {
        $privateKey = (new Signer())->format($this->getPrivateKey(), Signer::KEY_TYPE_PRIVATE);

        $res = openssl_pkey_get_private($privateKey);

        // 解密 encrypt_key
        openssl_private_decrypt(base64_decode($data['encrypt_key']), $key, $res);

        // 解密 biz_content
        $decrypted = openssl_decrypt(base64_decode($data['biz_content']), 'AES-256-CBC', base64_decode($key), OPENSSL_RAW_DATA);

        return json_decode($decrypted, true);
    }
}
