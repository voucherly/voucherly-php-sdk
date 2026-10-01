<?php

namespace VoucherlyApi\Tests\Support;

use VoucherlyApi\Exception\ConnectionException;
use VoucherlyApi\Http\HttpRequest;
use VoucherlyApi\Http\HttpResponse;
use VoucherlyApi\Http\TransportInterface;

final class FakeTransport implements TransportInterface
{
    /** @var list<HttpRequest> */
    public array $requests = [];

    /** @var list<ConnectionException|HttpResponse> */
    private array $responses = [];

    /**
     * @param array<string, mixed>|string $body
     */
    public function respond(int $status, $body = '', array $headers = []): self
    {
        $this->responses[] = new HttpResponse($status, $headers, \is_string($body) ? $body : json_encode($body, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION));

        return $this;
    }

    public function fail(ConnectionException $exception): self
    {
        $this->responses[] = $exception;

        return $this;
    }

    public function send(HttpRequest $request): HttpResponse
    {
        $this->requests[] = $request;
        $response = array_shift($this->responses);
        if (null === $response) {
            throw new \LogicException('FakeTransport has no response queued for ' . $request->getMethod() . ' ' . $request->getUrl() . '.');
        }
        if ($response instanceof ConnectionException) {
            throw $response;
        }

        return $response;
    }

    public function lastRequest(): HttpRequest
    {
        if ([] === $this->requests) {
            throw new \LogicException('FakeTransport received no request.');
        }

        return $this->requests[\count($this->requests) - 1];
    }
}
