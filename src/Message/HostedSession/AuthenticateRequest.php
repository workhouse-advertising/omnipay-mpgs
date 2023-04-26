<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mpgs\Message\AbstractRequest;
use Omnipay\Mpgs\Message\HostedSession\AuthenticateResponse;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthenticateRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'orderId',
            'sessionId',
        );

        return [
            'apiOperation' => 'AUTHENTICATE_PAYER',
            'session' => [
                'id' => $this->getSessionId(),
            ],
            'authentication' => [
                'redirectResponseUrl' => $this->getReturnUrl(),
            ],
            'device' => [
                'browser' => substr($this->getBrowser() ?? '', 0, 255),
                'browserDetails' => (array) $this->getBrowserDetails(),
            ],
            'order' => [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->getParameter('browser');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setBrowser($value)
    {
        return $this->setParameter('browser', $value);
    }

    /**
     * @return mixed
     */
    public function getBrowserDetails()
    {
        return $this->getParameter('browserDetails');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setBrowserDetails($value)
    {
        return $this->setParameter('browserDetails', $value);
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
        return 'PUT';
    }

    /**
     * @inheritDoc
     */
    public function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\AuthenticateResponse::class;
    }
}
