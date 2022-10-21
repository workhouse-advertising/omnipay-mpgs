<?php

namespace Omnipay\Mpgs\Message\HostedSession;

/**
 * Initiate card holder authentication (3DS, etc...).
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthenticateResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return false;
    }
}
