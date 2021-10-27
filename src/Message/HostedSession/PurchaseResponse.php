<?php

namespace Omnipay\Mpgs\Message\HostedSession;

/**
 * Send the user to the Hosted Payment Page to authorize their payment.
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return ($this->getData()['result'] ?? null) === 'SUCCESS';
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->getData()['response']['acquirerMessage'] ?? null;
    }
}
