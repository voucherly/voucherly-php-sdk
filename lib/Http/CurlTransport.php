<?php

namespace VoucherlyApi\Http;

use VoucherlyApi\Exception\ConnectionException;

final class CurlTransport implements TransportInterface
{
    private float $connectTimeout;
    private float $timeout;

    /**
     * @param float $connectTimeout seconds
     * @param float $timeout        seconds
     */
    public function __construct(float $connectTimeout = 10, float $timeout = 30)
    {
        $this->connectTimeout = $connectTimeout;
        $this->timeout = $timeout;
    }

    public function send(HttpRequest $request): HttpResponse
    {
        // cURL adds "Expect: 100-continue" to bodies over 1 KB and then waits up to a second before sending them.
        $headerLines = ['Expect:'];
        foreach ($request->getHeaders() as $name => $value) {
            $headerLines[] = $name . ': ' . $value;
        }

        $responseHeaders = [];
        $options = [
            CURLOPT_URL => $request->getUrl(),
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_ENCODING => '',
            CURLOPT_CONNECTTIMEOUT_MS => (int) round($this->connectTimeout * 1000),
            CURLOPT_TIMEOUT_MS => (int) round($this->timeout * 1000),
            CURLOPT_HEADERFUNCTION => static function ($curl, string $line) use (&$responseHeaders): int {
                if (0 === strncmp($line, 'HTTP/', 5)) {
                    $responseHeaders = [];
                } elseif (false !== strpos($line, ':')) {
                    [$name, $value] = explode(':', $line, 2);
                    $responseHeaders[strtolower(trim($name))] = trim($value);
                }

                return \strlen($line);
            },
        ];

        if (null !== $request->getBody()) {
            $options[CURLOPT_POSTFIELDS] = $request->getBody();
        } elseif (\in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
            // Without a body cURL sends no Content-Length, and some proxies reject such a request with 411.
            $options[CURLOPT_POSTFIELDS] = '';
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $body = curl_exec($curl);

        if (false === $body) {
            throw new ConnectionException(curl_error($curl), curl_errno($curl));
        }

        return new HttpResponse((int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE), $responseHeaders, (string) $body);
    }
}
