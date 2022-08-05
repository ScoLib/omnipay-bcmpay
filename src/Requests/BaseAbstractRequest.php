<?php


namespace Omnipay\BCMPay\Requests;

use Omnipay\BCMPay\Common\Helper;
use Omnipay\BCMPay\Common\Signer;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class BaseAbstractRequest extends AbstractRequest
{

    protected $endpoint = 'https://open.bankcomm.com';

    protected $path;

    protected $reqHead;

    protected $reqHeadKeys = ['term_trans_time', 'trace_no'];

    protected $reqBody;

    protected $reqBodyKeys = [];

    private   $bcmPublicKey;
    private   $privateKey;

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->setDefaults();

        // $this->validateParams();
        $this->validate(
            'app_id',
            'msg_id',
            'fmt_type',
            'timestamp'
        );

        $this->validateReqHead();
        $this->validateReqBody();

        // $this->convertToString();

        $data = $this->parameters->all();

        $data['biz_content'] = json_encode([
            'req_head' => $this->getReqHead(),
            'req_body' => $this->getReqBody(),
        ], JSON_UNESCAPED_UNICODE);


        $data['sign'] = $this->sign($data);

        return $data;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'app_id',
            'msg_id',
            'fmt_type',
            'timestamp'
        );
    }


    protected function setDefaults()
    {
        if (! $this->getTimestamp()) {
            $this->setTimestamp(date('Y-m-d H:i:s'));
        }

        if (!$this->getReqHead()) {
            $this->setReqHead([
                'term_trans_time' => date('YmdHis'),
                'trace_no' => $this->generateTraceNo(),
            ]);
        }

    }

    protected function generateTraceNo()
    {
        return mt_rand(1000, 9999)
            . sprintf('%010d', time())
            . sprintf('%02d', (float)microtime() * 100);
    }


    protected function convertToString()
    {
        foreach ($this->parameters->all() as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $this->parameters->set($key, json_encode($value, true));
            }
        }
    }


    /**
     * @param mixed $data
     *
     * @return mixed|ResponseInterface|StreamInterface
     * @throws NetworkException
     */
    public function sendData($data)
    {
        $method = $this->getRequestMethod();
        $url    = $this->getRequestUrl();
        $body   = $this->getRequestBody($data);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $response = $this->httpClient->request($method, $url, $headers, $body);

        $payload = $this->decode($response->getBody());

        return $payload;
    }


    /**
     * @return string
     */
    protected function getRequestMethod()
    {
        return 'POST';
    }


    /**
     * @return string
     */
    protected function getRequestUrl()
    {
        $url = sprintf('%s%s', $this->getEndpoint(), $this->path);

        return $url;
    }

    /**
     * @return string
     */
    protected function getRequestBody($data)
    {
        $body = http_build_query($data);

        return $body;
    }

    protected function decode($data)
    {
        return json_decode($data, true);
    }


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


    /**
     * @param $params
     * @return string
     * @throws \Exception
     */
    protected function sign($params)
    {
        $signer = new Signer($params);
        $signer->setIgnores(['sign']);

        $content = $this->path .'?' . $signer->getContentToSign();

        $sign = $signer->signContentWithRSA($content, $this->getPrivateKey(), OPENSSL_ALGO_SHA256);
        return $sign;
    }


    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setPrivateKey($value)
    {
        $this->privateKey = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBcmPublicKey()
    {
        return $this->bcmPublicKey;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setBcmPublicKey($value)
    {
        $this->bcmPublicKey = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        $this->endpoint = $value;

        return $this;
    }





    /**
     * @param null $key
     * @param null $default
     *
     * @return mixed
     */
    public function getBizData($key = null, $default = null)
    {
        if (is_string($this->getBizContent())) {
            $data = json_decode($this->getBizContent(), true);
        } else {
            $data = $this->getBizContent();
        }

        if (is_null($key)) {
            return $data;
        } else {
            return array_get($data, $key, $default);
        }
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
    public function getReqHead()
    {
        return $this->reqHead;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setReqHead($value)
    {
        return $this->reqHead = $value;
    }

    /**
     * @return mixed
     */
    public function getReqBody()
    {
        return $this->reqBody;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setReqBody($value)
    {
        return $this->reqBody = $value;
    }

    public function validateReqHead()
    {
        $data = $this->getReqHead();

        foreach ($this->reqHeadKeys as $key) {
            if (! array_has($data, $key)) {
                throw new InvalidRequestException("The biz_content.req_head $key parameter is required");
            }
        }
    }

    public function validateReqBody()
    {
        $data = $this->getReqBody();

        foreach ($this->reqBodyKeys as $key) {
            if (! array_has($data, $key)) {
                throw new InvalidRequestException("The biz_content.req_body $key parameter is required");
            }
        }
    }

}
