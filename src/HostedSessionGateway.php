<?php

namespace Omnipay\Mpgs;

use Omnipay\Mpgs\AbstractGateway;

class HostedSessionGateway extends AbstractGateway
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'MPGS Hosted Session';
    }

    /**
     * @return string
     */
    public function getSessionJsUrl()
    {
        return sprintf('%s/form/version/%s/merchant/%s/session.js', $this->getBaseAssetUrl(), $this->getApiVersion(), $this->getMerchantId());
    }

    /**
     * Authorize and immediately capture an amount on the customers card
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\PurchaseRequest::class, $options);
    }

    /**
     * Handle return from off-site gateways after purchase
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\CompletePurchaseRequest::class, $options);
    }
}
