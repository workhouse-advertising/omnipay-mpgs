<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mpgs\Message\AbstractRequest;
use Omnipay\Mpgs\Message\HostedSession\InitiateAuthenticationResponse;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class InitiateAuthenticationRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'orderId',
            'sessionId',
        );

        $data = [
            'apiOperation' => 'INITIATE_AUTHENTICATION',
            'session' => [
                'id' => $this->getSessionId(),
            ],
            // TODO: Add support for these authentication parameters.
            'authentication' => [
                // TODO: It appears that all 3DS1 tests have received responses that don't provide any redirect
                //       information. Maybe this is intentional for some reason?
                // 'acceptVersions' => '3DS1',
                // 'acceptVersions' => '3DS2',
                // 'acceptVersions' => '3DS1,3DS2',
                'acceptVersions' => $this->getAuthenticationAcceptVersions(),
                // NOTE: The `authentication.channel` field appears to be compulsory if you actually want
                //       anything to be able to be authenticated. The documentation forgets to include this field
                //       in the list of required fields, possibly because it's a sub-field.
                'channel' => $this->getAuthenticationChannel(),
                // 'channel' => 'PAYER_BROWSER',
                // 'purpose' => 'PAYMENT_TRANSACTION',
            ],
            // // TODO: Add support for other fund sources.
            // 'sourceOfFunds' => [
            //     'type' => 'CARD',
            // ],
            // TODO: Add support for additional order fields.
            'order' => [
                // 'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];

        // Add the `sourceOfFunds` parameter if one is set.
        if ($this->getSourceOfFunds()) {
            $data['sourceOfFunds'] = $this->getSourceOfFunds();
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getSourceOfFunds()
    {
        return $this->getParameter('sourceOfFunds');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setSourceOfFunds($value)
    {
        return $this->setParameter('sourceOfFunds', $value);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        // TODO: Add an option to append `?debug=true` to the URL.
        return sprintf('%s/api/rest/version/%s/merchant/%s/order/%s/transaction/%s', $this->getBaseEndpoint(), $this->getApiVersion(), $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @inheritDoc
     */
    public function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\InitiateAuthenticationResponse::class;
    }
}
