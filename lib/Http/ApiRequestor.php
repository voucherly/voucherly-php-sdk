<?php

namespace VoucherlyApi\Http;

use VoucherlyApi\Exception\ApiException;
use VoucherlyApi\Exception\VoucherlyException;
use VoucherlyApi\Request\RequestParams;
use VoucherlyApi\VoucherlyObject;

/**
 * @internal
 */
final class ApiRequestor
{
    private string $baseUrl;

    /** @var array<string, string> */
    private array $headers;
    private TransportInterface $transport;

    /**
     * @param array<string, string> $headers sent with every request
     */
    public function __construct(string $baseUrl, array $headers, TransportInterface $transport)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->headers = $headers;
        $this->transport = $transport;
    }

    /**
     * @throws ApiException        when the status is not 2xx
     * @throws VoucherlyException  when the request cannot be sent
     */
    public function request(string $method, string $path, ?VoucherlyObject $body = null, ?RequestParams $params = null, string $accept = 'application/json'): HttpResponse
    {
        $url = $this->baseUrl . $path;
        $headers = $this->headers;

        if (null !== $params) {
            $query = $params->toQueryString();
            if ('' !== $query) {
                $url .= '?' . $query;
            }
            $headers = array_merge($headers, $params->toHeaders());
        }

        $headers['Accept'] = $accept;

        $payload = null;
        if (null !== $body) {
            try {
                $payload = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
            } catch (\JsonException $exception) {
                throw new VoucherlyException('The request body cannot be encoded as JSON: ' . $exception->getMessage(), 0, $exception);
            }
        } elseif (\in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            // The API answers 415 to a POST without a JSON body, even where the spec declares none.
            $payload = '{}';
        }
        if (null !== $payload) {
            $headers['Content-Type'] = 'application/json';
        }

        $response = $this->transport->send(new HttpRequest($method, $url, $headers, $payload));

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw ApiException::fromResponse($response);
        }

        return $response;
    }
}
