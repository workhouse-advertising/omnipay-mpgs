<?php

namespace Omnipay\Mpgs\Message\HostedSession;

/**
 * Initiate card holder authentication (3DS, etc...).
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class InitiateAuthenticationResponse extends AbstractResponse implements RedirectResponseInterface
{
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
        // TODO: Consider also checking the gateway code and recommendation.
        // return $this->getRedirectUrl() || $this->getRedirectHtml();
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
        // TODO: All of the required information appears to be missing for 3DS1 tests.
        //       Possibly the payment gateway is broken? Or maybe it's pure garbage? Both?

        $url = null;
        // if ($this->is3ds2()) {
        //     $url = $this->getData()['authentication']['redirect']['customizedHtml']['3ds2']['methodUrl'] ?? null;
        // } elseif ($this->is3ds1()) {
        //     // TODO: Test and confirm for 3DS1.
        //     // $url = $this->getData()['authentication']['redirect']['customizedHtml']['3ds1']['methodUrl'] ?? null;
        // }
        return $url;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectHtml()
    {
        // TODO: Need to handle empty responses.
        return $this->getData()['authentication']['redirect']['html'] ?? null;
    }

    /**
     * @return HttpRedirectResponse|HttpResponse
     */
    public function getRedirectResponse()
    {
        // TODO: Either redirection method currently appears to only redirect to an empty page.
        //       Need to confirm if this is the expected behaviour in any way.
        $this->validateRedirect();
        $output = $this->getRedirectHtml();
        return new HttpResponse($this->getRedirectHtml());
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
        if ($this->is3ds2()) {
            $methodPostData = $this->getData()['authentication']['redirect']['customizedHtml']['3ds2']['methodPostData'] ?? null;
        } elseif ($this->is3ds1()) {
            // // TODO: Test and confirm for 3DS1.
            // $methodPostData = $this->getData()['authentication']['redirect']['customizedHtml']['3ds1']['methodPostData'] ?? null;
        }
        return $methodPostData ? ['threeDSMethodData' => $methodPostData] : [];
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
