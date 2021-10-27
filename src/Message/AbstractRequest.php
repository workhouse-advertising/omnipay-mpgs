<?php

namespace Omnipay\Mpgs\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Mpgs\Traits\GatewayParameters;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use GatewayParameters;

    /**
     * Get the basic HTTP authorisation password.
     *
     * @return string
     */
    protected function getAuthorisationBasicPassword()
    {
        $merchantId = $this->getMerchantId();
        $password = $this->getPassword();
        return base64_encode("merchant.{$merchantId}:{$password}");
    }
}
