<?php

namespace Omnipay\Mpgs;

use Omnipay\Common\AbstractGateway as AbstractGatewayBase;
use Omnipay\Mpgs\Traits\GatewayParameters;

abstract class AbstractGateway extends AbstractGatewayBase
{
    use GatewayParameters;

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            // TODO: Consider adding verification for available API regions.
            'apiRegion' => 'ap',
            'apiVersion' => 61,
            'merchantId' => '',
            'password' => '',
            'testMode' => false,
            // TODO: Add support for the 'debug' parameter.
            'debug' => false,
        );
    }
}
