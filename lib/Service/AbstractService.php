<?php

namespace VoucherlyApi\Service;

use VoucherlyApi\Exception\VoucherlyException;
use VoucherlyApi\Http\ApiRequestor;
use VoucherlyApi\Request\RequestParams;
use VoucherlyApi\VoucherlyObject;

abstract class AbstractService
{
    private ApiRequestor $requestor;

    /**
     * @internal the services are created by VoucherlyClient
     */
    public function __construct(ApiRequestor $requestor)
    {
        $this->requestor = $requestor;
    }

    /**
     * @return array<string, mixed>
     */
    protected function requestJson(string $method, string $path, ?VoucherlyObject $body = null, ?RequestParams $params = null): array
    {
        $response = $this->requestor->request($method, $path, $body, $params);

        try {
            $data = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new VoucherlyException('The response body is not valid JSON: ' . $exception->getMessage(), 0, $exception);
        }

        if (!\is_array($data)) {
            throw new VoucherlyException('The response body is not a JSON object.');
        }

        return $data;
    }

    protected function requestBytes(string $path, string $accept): string
    {
        return $this->requestor->request('GET', $path, null, null, $accept)->getBody();
    }

    protected function requestNoContent(string $method, string $path, ?RequestParams $params = null): void
    {
        $this->requestor->request($method, $path, null, $params);
    }

    /**
     * Fills the %s placeholders of $format with the ids, each encoded as a path segment.
     */
    protected static function path(string $format, string ...$ids): string
    {
        foreach ($ids as $id) {
            if ('' === $id) {
                throw new \InvalidArgumentException('An id cannot be an empty string.');
            }
        }

        return vsprintf($format, array_map('rawurlencode', $ids));
    }
}
