<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Mpgs\Message\AbstractRequest;

/**
 * GetSession Request
 *
 * @method Response send()
 */
class GetSessionRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'sessionId',
        );

        return [];
    }

    /**
     * @inheritDoc
     */
    protected function getEndpoint(): string
    {
        // TODO: Add an option to append `?debug=true` to the URL.
        return sprintf('%s/api/rest/version/%s/merchant/%s/session/%s', $this->getBaseEndpoint(), $this->getApiVersion(), $this->getMerchantId(), $this->getSessionId());
    }

    /**
     * @inheritDoc
     */
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    protected function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\GetSessionResponse::class;
    }
}
