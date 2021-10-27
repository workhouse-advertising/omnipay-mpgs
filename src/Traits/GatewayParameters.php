<?php

namespace Omnipay\Mpgs\Traits;

trait GatewayParameters
{
    // protected $liveEndpoint = 'https://ap-gateway.mastercard.com';
    // protected $testEndpoint = 'https://test-gateway.mastercard.com';

    /**
     * @return string
     */
    public function getBaseAssetUrl()
    {
        return $this->getLiveBaseEndpoint();
        // return $this->getBaseEndpoint();
    }

    /**
     * @return string
     */
    public function getBaseEndpoint()
    {
        return $this->getTestMode() ? $this->getTestBaseEndpoint() : $this->getLiveBaseEndpoint();
    }

    /**
     * @return string
     */
    public function getLiveBaseEndpoint()
    {
        return sprintf('https://%s-gateway.mastercard.com', $this->getApiRegion());
    }

    /**
     * @return string
     */
    public function getTestBaseEndpoint()
    {
        // return $this->testEndpoint;
        // return 'https://secure.uat.tnspayments.com';
        return 'https://test-gateway.mastercard.com';
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @return mixed
     */
    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }

    /**
     * @return mixed
     */
    public function getApiRegion()
    {
        return $this->getParameter('apiRegion');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setApiRegion($value)
    {
        return $this->setParameter('apiRegion', $value);
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }
}
