<?php

namespace VoucherlyApi\Exception;

use VoucherlyApi\Http\HttpResponse;

/**
 * The API answered with a status outside 2xx. getCode() returns the HTTP status.
 */
class ApiException extends VoucherlyException
{
    private const PROBLEM_MEMBERS = ['type', 'title', 'status', 'detail', 'code', 'parameter'];

    private int $statusCode;

    /** @var array<string, mixed> */
    private array $problem;
    private string $rawBody;

    /** @var array<string, string> */
    private array $headers;

    /**
     * @param array<string, mixed>  $problem the problem details read from the body, empty when the body is not JSON
     * @param array<string, string> $headers keyed by lowercase header name
     */
    public function __construct(int $statusCode, array $problem, string $rawBody, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->problem = $problem;
        $this->rawBody = $rawBody;
        $this->headers = $headers;

        $message = trim($this->getTitle() . ' ' . $this->getDetail());
        parent::__construct('' !== $message ? $message : 'The Voucherly API answered with HTTP status ' . $statusCode . '.', $statusCode);
    }

    public static function fromResponse(HttpResponse $response): self
    {
        $problem = json_decode($response->getBody(), true);
        if (!\is_array($problem)) {
            $problem = [];
        }

        switch ($response->getStatusCode()) {
            case 400:
                return new BadRequestException(400, $problem, $response->getBody(), $response->getHeaders());

            case 404:
                return new NotFoundException(404, $problem, $response->getBody(), $response->getHeaders());

            case 409:
                return new ConflictException(409, $problem, $response->getBody(), $response->getHeaders());

            case 422:
                return new UnprocessableEntityException(422, $problem, $response->getBody(), $response->getHeaders());

            case 424:
                return new FailedDependencyException(424, $problem, $response->getBody(), $response->getHeaders());

            default:
                return new self($response->getStatusCode(), $problem, $response->getBody(), $response->getHeaders());
        }
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * A URI reference that identifies the problem type.
     */
    public function getType(): ?string
    {
        return $this->problemString('type');
    }

    /**
     * A short, human-readable summary of the problem type.
     */
    public function getTitle(): ?string
    {
        return $this->problemString('title');
    }

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     */
    public function getDetail(): ?string
    {
        return $this->problemString('detail');
    }

    /**
     * A short string indicating the error, for the errors that can be handled programmatically.
     */
    public function getErrorCode(): ?string
    {
        return $this->problemString('code');
    }

    /**
     * The parameter the error relates to, when the error is parameter-specific.
     */
    public function getParameter(): ?string
    {
        return $this->problemString('parameter');
    }

    /**
     * The members of the problem details other than type, title, status, detail, code and parameter, such as `line` or `operations`.
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return array_diff_key($this->problem, array_flip(self::PROBLEM_MEMBERS));
    }

    public function getRawBody(): string
    {
        return $this->rawBody;
    }

    /**
     * @return array<string, string> keyed by lowercase header name
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    private function problemString(string $member): ?string
    {
        $value = $this->problem[$member] ?? null;

        return \is_scalar($value) ? (string) $value : null;
    }
}
