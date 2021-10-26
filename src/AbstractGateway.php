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
            // 'baseEndpoint' => https://ap-gateway.mastercard.com/
            'apiRegion' => 'ap',
            'merchantId' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    // /**
    //  * Authorize and immediately capture an amount on the customers card
    //  *
    //  * @param array $options
    //  * @return \Omnipay\Common\Message\ResponseInterface
    //  */
    // public function purchase(array $options = [])
    // {
    //     return $this->createRequest(\Omnipay\Mpgs\Message\PurchaseRequest::class, $options);
    // }

    // /**
    //  * Handle return from off-site gateways after purchase
    //  *
    //  * @param array $options
    //  * @return \Omnipay\Common\Message\ResponseInterface
    //  */
    // public function completePurchase(array $options = [])
    // {
    //     return $this->createRequest(\Omnipay\Mpgs\Message\CompletePurchaseRequest::class, $options);
    // }
}
