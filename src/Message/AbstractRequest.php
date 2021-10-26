<?php

namespace Omnipay\Mpgs\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Mpgs\Traits\GatewayParameters;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use GatewayParameters;

    protected $liveEndpoint = 'https://ap-gateway.mastercard.com';
    protected $testEndpoint = 'https://secure.uat.tnspayments.com';
}
