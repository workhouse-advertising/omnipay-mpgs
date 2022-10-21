<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mpgs\Message\AbstractRequest;
use Omnipay\Mpgs\Message\HostedSession\AuthenticateResponse;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthenticateRequest extends AbstractRequest
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

        return [
            'apiOperation' => 'AUTHENTICATE_PAYER',
            'session' => [
                'id' => $this->getSessionId(),
            ],
            // TODO: Add support for these authentication parameters.
            'authentication' => [
                'redirectResponseUrl' => $this->getReturnUrl(),
            ],
            // // TODO: Add support for other fund sources.
            // 'sourceOfFunds' => [
            //     'type' => 'CARD',
            // ],
            'order' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getEndpoint()
    {
        // TODO: Add an option to append `?debug=true` to the URL.
        return sprintf('%s/api/rest/version/%s/merchant/%s/order/%s/transaction/%s', $this->getBaseEndpoint(), $this->getApiVersion(), $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $headers = [
            'Authorization' => "Basic {$this->getAuthorisationBasicPassword()}",
            'Content-Type' => 'application/json',
        ];

        $httpResponse = $this->httpClient->request('PUT', $this->getEndpoint(), $headers, json_encode($data));
        $responseData = json_decode($httpResponse->getBody(), true);

        // NOTE: Any 2xx response is to be considered to be successful, although this is not explicitly indicated in the documentation
        //       at `https://test-gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Transaction%3a%20%20Pay.html`
        // NOTE: Including 400s as MPGS uses those for some errors even though it's _technically_ a valid response.
        if (($httpResponse->getStatusCode() < 200 || $httpResponse->getStatusCode() > 299) && $httpResponse->getStatusCode() != 400) {
            throw new InvalidRequestException("Invalid request to the MPGS Hosted Session API. Received status code '{$httpResponse->getStatusCode()}'.");
        }

        return new AuthenticateResponse($this, $responseData);
    }
}
