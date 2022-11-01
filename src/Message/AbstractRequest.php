<?php

namespace Omnipay\Mpgs\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Mpgs\Traits\GatewayParameters;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use GatewayParameters;

    /**
     * Get the basic HTTP authorisation password.
     *
     * @return string
     */
    protected function getAuthorisationBasicPassword()
    {
        $merchantId = $this->getMerchantId();
        $password = $this->getPassword();
        return base64_encode("merchant.{$merchantId}:{$password}");
    }

    /**
     * Get the endpoint to call.
     *
     * @return string
     */
    abstract protected function getEndpoint(): string;

    /**
     * Get the method for the endpoint.
     *
     * @return string
     */
    abstract protected function getMethod(): string;

    /**
     * Get the FQCN to use for a response.
     *
     * @return string
     */
    abstract protected function getResponseClass(): string;

    /**
     * Create a response from the response data.
     *
     * @return \Omnipay\Common\Message\AbstractResponse
     */
    protected function makeResponse($responseData): AbstractResponse
    {
        $responseClass = $this->getResponseClass();
        return new $responseClass($this, $responseData);
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $headers = [
            'Authorization' => "Basic {$this->getAuthorisationBasicPassword()}",
            'Content-Type' => 'application/json',
        ];

        $requestBody = ($this->getMethod() == 'GET') ? null : json_encode($data);
        $requestParams = ($this->getMethod() == 'GET') ? '?' . http_build_query($data) : '';

        $httpResponse = $this->httpClient->request($this->getMethod(), $this->getEndpoint() . $requestParams, $headers, $requestBody);
        $responseData = json_decode($httpResponse->getBody(), true);

        // NOTE: Any 2xx response is to be considered to be successful, although this is not explicitly indicated in the documentation
        //       at `https://test-gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Transaction%3a%20%20Pay.html`
        // NOTE: Including 400s as MPGS uses those for some errors even though it's _technically_ a valid response.
        if (($httpResponse->getStatusCode() < 200 || $httpResponse->getStatusCode() > 299) && $httpResponse->getStatusCode() != 400) {
            throw new InvalidRequestException("Invalid request to the MPGS Hosted Session API. Received status code '{$httpResponse->getStatusCode()}'.");
        }

        // return new PurchaseResponse($this, $responseData);
        return $this->makeResponse($responseData);
    }
}
