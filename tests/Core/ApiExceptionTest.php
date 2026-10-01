<?php

namespace VoucherlyApi\Tests\Core;

use PHPUnit\Framework\TestCase;
use VoucherlyApi\Exception\ApiException;
use VoucherlyApi\Exception\BadRequestException;
use VoucherlyApi\Exception\ConflictException;
use VoucherlyApi\Exception\FailedDependencyException;
use VoucherlyApi\Exception\NotFoundException;
use VoucherlyApi\Exception\UnprocessableEntityException;
use VoucherlyApi\Http\ApiRequestor;
use VoucherlyApi\Tests\Support\FakeTransport;
use VoucherlyApi\Tests\Support\SpecExamples;
use VoucherlyApi\Tests\Support\TestService;

final class ApiExceptionTest extends TestCase
{
    private const CLASSES = [
        400 => BadRequestException::class,
        404 => NotFoundException::class,
        409 => ConflictException::class,
        422 => UnprocessableEntityException::class,
        424 => FailedDependencyException::class,
    ];

    public function testThereIsAnExceptionForEveryErrorStatusOfTheSpec(): void
    {
        $statuses = array_unique(array_column(SpecExamples::errorExamples(), 1));
        sort($statuses);

        self::assertSame(array_keys(self::CLASSES), $statuses);
    }

    /**
     * @dataProvider provideReadsTheProblemDetailsOfEveryErrorExampleOfTheSpecCases
     *
     * @param array<string, mixed> $body
     */
    public function testReadsTheProblemDetailsOfEveryErrorExampleOfTheSpec(int $status, array $body): void
    {
        $exception = $this->exceptionFor($status, json_encode($body), ['content-type' => 'application/problem+json']);

        self::assertInstanceOf(self::CLASSES[$status], $exception);
        self::assertSame($status, $exception->getStatusCode());
        self::assertSame($status, $exception->getCode());
        self::assertSame($body['title'] ?? null, $exception->getTitle());
        self::assertSame($body['detail'] ?? null, $exception->getDetail());
        self::assertSame($body['code'] ?? null, $exception->getErrorCode());
        self::assertSame($body['parameter'] ?? null, $exception->getParameter());
        self::assertSame($body['type'] ?? null, $exception->getType());
        self::assertSame(array_diff_key($body, array_flip(['type', 'title', 'status', 'detail', 'code', 'parameter'])), $exception->getExtensions());
        self::assertSame(json_encode($body), $exception->getRawBody());
        self::assertSame('application/problem+json', $exception->getHeaders()['content-type']);
    }

    /**
     * @return iterable<string, array{int, array<string, mixed>}>
     */
    public static function provideReadsTheProblemDetailsOfEveryErrorExampleOfTheSpecCases(): iterable
    {
        foreach (SpecExamples::errorExamples() as [$operationId, $status, $name, $body]) {
            yield "{$operationId} {$status} {$name}" => [$status, $body];
        }
    }

    public function testBuildsTheMessageFromTitleAndDetail(): void
    {
        $exception = $this->exceptionFor(404, '{"title":"Customer was not found.","detail":"Entity \"Customer\" (cs_1) was not found."}');

        self::assertSame('Customer was not found. Entity "Customer" (cs_1) was not found.', $exception->getMessage());
    }

    public function testExposesThePaymentStatusOfAConflict(): void
    {
        $exception = $this->exceptionFor(409, '{"title":"This operation is already processed.","status":409,"code":"ALREADY_CONFIRMED","paymentStatus":"Confirmed"}');

        self::assertInstanceOf(ConflictException::class, $exception);
        self::assertSame('Confirmed', $exception->getPaymentStatus());
    }

    public function testUsesTheBaseExceptionForTheStatusesTheSpecDoesNotDeclare(): void
    {
        $exception = $this->exceptionFor(401, '{"title":"Unauthorized","status":401}');

        self::assertSame(ApiException::class, \get_class($exception));
        self::assertSame(401, $exception->getStatusCode());
    }

    public function testKeepsTheRawBodyWhenItIsNotJson(): void
    {
        $exception = $this->exceptionFor(502, '<html>Bad Gateway</html>');

        self::assertSame('The Voucherly API answered with HTTP status 502.', $exception->getMessage());
        self::assertSame('<html>Bad Gateway</html>', $exception->getRawBody());
        self::assertNull($exception->getTitle());
        self::assertSame([], $exception->getExtensions());
    }

    /**
     * @param array<string, string> $headers
     */
    private function exceptionFor(int $status, string $body, array $headers = []): ApiException
    {
        $transport = (new FakeTransport())->respond($status, $body, $headers);
        $service = new TestService(new ApiRequestor('https://api.example.test', [], $transport));

        try {
            $service->json('GET', '/v1/things');
        } catch (ApiException $exception) {
            return $exception;
        }

        self::fail('No ApiException was thrown.');
    }
}
