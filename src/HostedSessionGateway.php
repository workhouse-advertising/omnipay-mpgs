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
     * Fetch a session.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function getSession(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\GetSessionRequest::class, $options);
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

    /**
     * Initiate the authentication flow.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function initiateAuthentication(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\InitiateAuthenticationRequest::class, $options);
    }

    /**
     * Authenticate a card holder.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function authenticate(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\AuthenticateRequest::class, $options);
    }

    /**
     * Replay an authentication callback POST request to work around the lack of cookies due to SameSite policy issues.
     *
     * @param array $options
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function authenticateRepost(array $options = [])
    {
        return $this->createRequest(\Omnipay\Mpgs\Message\HostedSession\AuthenticateRepostRequest::class, $options);
    }
}
