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
            'sessionId',
        );

        return [
            'session' => [
                'id' => $this->getSessionId(),
            ],
        ];
    }
}
