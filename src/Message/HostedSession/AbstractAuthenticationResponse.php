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
    public const AUTHENTICATION_ATTEMPTED = 'AUTHENTICATION_ATTEMPTED'; // Payer authentication was attempted and a proof of authentication attempt was obtained.
    public const AUTHENTICATION_AVAILABLE = 'AUTHENTICATION_AVAILABLE'; // Payer authentication is available for the payment method provided.
    public const AUTHENTICATION_EXEMPT = 'AUTHENTICATION_EXEMPT'; // Exemption from the Regulatory Technical Standards (RTS) requirements for Strong Customer Authentication (SCA) under the Payment Services Directive 2 (PSD2) regulations in the European Economic Area has been claimed or granted.
    public const AUTHENTICATION_FAILED = 'AUTHENTICATION_FAILED'; // The payer was not authenticated. You should not proceed with this transaction.
    public const AUTHENTICATION_NOT_IN_EFFECT = 'AUTHENTICATION_NOT_IN_EFFECT'; // There is no authentication information associated with this transaction.
    public const AUTHENTICATION_NOT_SUPPORTED = 'AUTHENTICATION_NOT_SUPPORTED'; // The requested authentication method is not supported for this payment method.
    public const AUTHENTICATION_PENDING = 'AUTHENTICATION_PENDING'; // Payer authentication is pending completion of a challenge process.
    public const AUTHENTICATION_REJECTED = 'AUTHENTICATION_REJECTED'; // The issuer rejected the authentication request and requested that you do not attempt authorization of a payment.
    public const AUTHENTICATION_REQUIRED = 'AUTHENTICATION_REQUIRED'; // Payer authentication is required for this payment, but was not provided.
    public const AUTHENTICATION_SUCCESSFUL = 'AUTHENTICATION_SUCCESSFUL'; // The payer was successfully authenticated.
    public const AUTHENTICATION_UNAVAILABLE = 'AUTHENTICATION_UNAVAILABLE'; // The payer was not able to be authenticated due to a technical or other issue.

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
        return $this->getResultCode() === 'SUCCESS' || $this->getResultCode() === 'PENDING';
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
            // A nicer error message than the default of "The given redirectUrl cannot be empty.".
            if (empty($this->getRedirectUrl())) {
                throw new RuntimeException('There was an error with the 3DS2 response where no redirect URL was supplied. Please, try again or choose a different payment method.');
            }

            parent::validateRedirect();
        }

        if (!$this->isSuccessful()) {
            $resultCode = $this->getResultCode() ?? 'GATEWAY_FAILURE';
            $message = $this->getErrorMessage() ?? 'An unexpected error occurred with this payment gateway. Please, try again or choose a different payment method.';
            throw new RuntimeException("Result code '{$resultCode}': {$message}");
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
    public function getMessage()
    {
        // TODO: Consider success messages.
        return $this->isSuccessful() ? null : $this->getErrorMessage();
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        // Only use URL redirection for 3DS2 as 3DS1 only supports the iframe method.
        $url = null;
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
    public function getResultCode()
    {
        return $this->getData()['result'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage()
    {
        return $this->getData()['error']['explanation'] ?? null;
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
        return $this->getData()['transaction']['authenticationStatus'] ?? null;
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
    public function isAuthenticationAvailable(): bool
    {
        // TODO: Confirm all statuses that we need to check for.
        $authenticationAvailableStatuses = [
            self::AUTHENTICATION_AVAILABLE,
        ];
        return in_array($this->getAuthenticationStatus(), $authenticationAvailableStatuses);
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticationNotAvailable(): bool
    {
        // TODO: Confirm all statuses that we need to check for.
        $authenticationNotAvailableStatuses = [
            self::AUTHENTICATION_EXEMPT,
            self::AUTHENTICATION_NOT_SUPPORTED,
            self::AUTHENTICATION_UNAVAILABLE,
        ];
        return in_array($this->getAuthenticationStatus(), $authenticationNotAvailableStatuses);
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticationSuccessful(): bool
    {
        // TODO: Confirm all statuses that we need to check for.
        $authenticationAvailableStatuses = [
            self::AUTHENTICATION_SUCCESSFUL,
        ];
        return in_array($this->getAuthenticationStatus(), $authenticationAvailableStatuses);
    }

    /**
     * @inheritDoc
     */
    public function shouldProceed()
    {
        return $this->getGatewayRecommendation() == 'PROCEED';
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
