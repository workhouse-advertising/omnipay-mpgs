<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Mpgs\Message\AbstractRequest;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class CompletePurchaseRequest extends AbstractRequest
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
    }

    /**
     * @inheritDoc
     */
    protected function getEndpoint(): string
    {
        // TODO: Add an option to append `?debug=true` to the URL.
        return sprintf('%s/api/rest/version/%s/merchant/%s/order/%s/transaction/%s', $this->getBaseEndpoint(), $this->getApiVersion(), $this->getMerchantId(), $this->getOrderId(), $this->getTransactionId());
    }

    /**
     * @inheritDoc
     */
    protected function getMethod(): string
    {
        return 'PUT';
    }

    /**
     * @inheritDoc
     */
    protected function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\PurchaseResponse::class;
    }
}
