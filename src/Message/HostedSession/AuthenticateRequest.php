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
            // TODO: provide actual device details.
            'device' => [
                'browser' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
                'browserDetails' => [
                    'acceptHeaders' => '*/*',
                    'javaEnabled' => false,
                    'language' => 'en-AU',
                    'screenHeight' => '1080',
                    'screenWidth' => '1920',
                    'timeZone' => '-480',
                    'colorDepth' => '32',
                    '3DSecureChallengeWindowSize' => 'FULL_SCREEN',
                ],
            ],
            'order' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];
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
        return \Omnipay\Mpgs\Message\HostedSession\AuthenticateResponse::class;
    }
}
