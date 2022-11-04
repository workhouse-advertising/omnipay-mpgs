<?php

namespace Omnipay\Mpgs\Message\HostedSession;

/**
 * AuthenticateRepostResponse.
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthenticateRepostResponse extends AbstractResponse implements RedirectResponseInterface
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
    public function isRedirect()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isTransparentRedirect()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        return $this->getRequest()->getEndpoint();
    }

    /**
     * @inheritDoc
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectData()
    {
        return $this->getData();
    }
}
