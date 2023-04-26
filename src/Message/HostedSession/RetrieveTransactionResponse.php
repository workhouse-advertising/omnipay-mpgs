<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Message\AbstractResponse;

class RetrieveTransactionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return ($this->getData()['result'] ?? null) === 'SUCCESS';
    }
}
