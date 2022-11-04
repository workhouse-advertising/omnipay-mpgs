<?php

namespace Omnipay\Mpgs\Message\HostedSession;

/**
 * Initiate or perform card holder authentication (3DS, etc...).
 */

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class AbstractAuthenticationResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * TODO: Default this to false and implement the ability to override it in a request.
     *
     * @var boolean
     */
    protected $forceHtmlRedirect = false;

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        // NOTE: This just confirms that the _response_ was successful, not a transaction.
        //       There is a bit of overlap and ambiguity with the Omnipay package, but this
        //       point has been clarified and documentation has been updated to confirm.
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
     * Get or set the option to force HTML redirects even for 3DS2.
     *
     * @return bool
     */
    public function forceHtmlRedirect($answer = null)
    {
        if (!is_null($answer)) {
            $this->forceHtmlRedirect = $answer;
        }

        return $this->forceHtmlRedirect;
    }

    /**
     * @inheritDoc
     */
    protected function validateRedirect()
    {
        if ($this->is3ds2()) {
            parent::validateRedirect();
        }

        if (!$this->is3ds1() && !$this->is3ds2()) {
            throw new RuntimeException("Unknown 3DS authentication method {$this->getAuthenticationVersion()} supplied.");
        }

        if (($this->is3ds1() || $this->forceHtmlRedirect()) && !$this->getRedirectHtml()) {
            throw new RuntimeException('Redirection HTML missing for 3DS1.');
        }

        if ($this->is3ds2() && !$this->getRedirectData()) {
            throw new RuntimeException('Redirection POST data missing for 3DS2.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        // Only use URL redirection for 3DS2 as 3DS1 only supports the iframe method.
        $url = '/';
        if ($this->is3ds2()) {
            $authenticationData = $this->getData()['authentication']['redirect']['customizedHtml']['3ds2'] ?? [];
            $url = $authenticationData['acsUrl'] ?? $authenticationData['methodUrl'] ?? null;
        }
        return $url;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectHtml()
    {
        return $this->getData()['authentication']['redirect']['html'] ?? null;
    }

    /**
     * @return HttpRedirectResponse|HttpResponse
     */
    public function getRedirectResponse()
    {
        $this->validateRedirect();
        return ($this->is3ds2() && !$this->forceHtmlRedirect()) ? parent::getRedirectResponse() : (new HttpResponse($this->getRedirectHtml()));
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
        $methodPostData = null;
        $parameterName = 'threeDSMethodData';

        if ($this->is3ds2()) {
            $authenticationData = $this->getData()['authentication']['redirect']['customizedHtml']['3ds2'] ?? [];
            $methodPostData = $authenticationData['cReq'] ?? $authenticationData['methodPostData'] ?? null;
            // Update the parameter name.
            // TODO: Confirm this parameter name as the documentation says `CReq`, the response data says `cReq` and the HTML redirect uses `creq`.
            //       Even if it isn't case sensitive, the documentation _really_ should be updated.
            $parameterName = ($authenticationData['cReq'] ?? null) ? 'creq' : 'threeDSMethodData';
        }

        return $methodPostData ? [$parameterName => $methodPostData] : [];
    }

    /**
     * @inheritDoc
     */
    public function getGatewayCode()
    {
        return $this->getData()['response']['gatewayCode'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getGatewayRecommendation()
    {
        return $this->getData()['response']['gatewayRecommendation'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getAuthenticationStatus()
    {
        return $this->getData()['order']['authenticationStatus'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getAuthenticationVersion()
    {
        return $this->getData()['authentication']['version'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function is3ds1()
    {
        return (strtolower($this->getAuthenticationVersion()) == '3ds1');
    }

    /**
     * @inheritDoc
     */
    public function is3ds2()
    {
        return (strtolower($this->getAuthenticationVersion()) == '3ds2');
    }
}
