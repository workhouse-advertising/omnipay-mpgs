<?php

namespace Omnipay\Mpgs\Traits;

trait GatewayParameters
{
    // protected $liveEndpoint = 'https://ap-gateway.mastercard.com';
    // protected $testEndpoint = 'https://secure.uat.tnspayments.com';

    /**
     * @return string
     */
    public function getBaseAssetUrl()
    {
        return $this->getLiveBaseEndpoint();
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
        return 'https://secure.uat.tnspayments.com';
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
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setMerchantSecret($value)
    {
        return $this->setParameter('merchantSecret', $value);
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
}
