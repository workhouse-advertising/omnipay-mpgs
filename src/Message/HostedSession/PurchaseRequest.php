<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mpgs\Message\AbstractRequest;
use Omnipay\Mpgs\Message\HostedSession\PurchaseResponse;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class PurchaseRequest extends AbstractRequest
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
            'apiOperation' => 'PAY',
            'session' => [
                'id' => $this->getSessionId(),
            ],
            // TODO: Add support for other fund sources.
            'sourceOfFunds' => [
                'type' => 'CARD',
            ],
            'order' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];

        if ($this->getAuthentication()) {
            $data['authentication'] = $this->getAuthentication();
        }

        return $data;
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
        return \Omnipay\Mpgs\Message\HostedSession\PurchaseResponse::class;
    }
}
