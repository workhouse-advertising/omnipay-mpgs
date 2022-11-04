<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Mpgs\Message\AbstractRequest;

/**
 * RetrieveTransactionRequest Request
 *
 * @method Response send()
 */
class RetrieveTransactionRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'orderId',
            'transactionId',
        );

        return [];
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
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    public function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\RetrieveTransactionResponse::class;
    }
}
