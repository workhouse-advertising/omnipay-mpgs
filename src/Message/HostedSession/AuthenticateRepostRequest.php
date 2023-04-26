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

        $postData = (array) $this->getPostData();

        // TODO: Check if we need to also allow any other fields.
        $allowedFields = [
            'order_id',
            'transaction_id',
            'response_gatewayRecommendation',
            'encryptedData_ciphertext',
            'encryptedData_nonce',
            'encryptedData_tag',
            'result',
            // 'sessionId',
        ];

        $data = [];
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $postData)) {
                $data[$field] = $postData[$field];
            }
        }

        return $data;
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
