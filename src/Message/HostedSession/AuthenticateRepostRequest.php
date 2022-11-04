<?php

namespace Omnipay\Mpgs\Message\HostedSession;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mpgs\Message\AbstractRequest;

/**
 * AuthenticateRepostRequest Request
 *
 * @method Response send()
 */
class AuthenticateRepostRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        // TODO: Validate a signature to ensure that POST data cannot be modified.

        // $this->validate(
        //     'amount',
        // );

        return $this->getPostData();
    }

    /**
     * @return mixed
     */
    public function getPostData()
    {
        return $this->getParameter('postData');
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setPostData($value)
    {
        return $this->setParameter('postData', $value);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return $this->getReturnUrl();
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function getResponseClass(): string
    {
        return \Omnipay\Mpgs\Message\HostedSession\AuthenticateRepostResponse::class;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        return $this->makeResponse($data);
    }
}
