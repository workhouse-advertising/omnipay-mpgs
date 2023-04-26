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
        return [
            // TODO: Consider adding verification for available API regions.
            'apiRegion' => 'ap',
            'apiVersion' => 68,
            'merchantId' => '',
            'password' => '',
            'testMode' => false,
            // 3DS authentication
            // NOTE: `3DS1` is disabled by default as it has been deprecated worldwide.
            // 'authenticationAcceptVersions' => '3DS1,3DS2',
            'authenticationAcceptVersions' => '3DS2',
            'authenticationChannel' => 'PAYER_BROWSER',
            // TODO: Add support for the 'debug' parameter.
            'debug' => false,
        ];
    }
}
