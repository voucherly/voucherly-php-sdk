<?php

namespace VoucherlyApi\Tests\Support;

use VoucherlyApi\Request\RequestParams;
use VoucherlyApi\Service\AbstractService;
use VoucherlyApi\VoucherlyObject;

final class TestService extends AbstractService
{
    /**
     * @return array<string, mixed>
     */
    public function json(string $method, string $path, ?VoucherlyObject $body = null, ?RequestParams $params = null): array
    {
        return $this->requestJson($method, $path, $body, $params);
    }

    public function bytes(string $path): string
    {
        return $this->requestBytes($path, 'application/pdf');
    }

    public function noContent(string $method, string $path): void
    {
        $this->requestNoContent($method, $path);
    }

    public static function buildPath(string $format, string ...$ids): string
    {
        return self::path($format, ...$ids);
    }
}
