<?php

namespace VoucherlyApi\Http;

final class HttpResponse
{
    private int $statusCode;

    /** @var array<string, string> */
    private array $headers;
    private string $body;

    /**
     * @param array<string, string> $headers keyed by lowercase header name
     */
    public function __construct(int $statusCode, array $headers, string $body)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, string> keyed by lowercase header name
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
