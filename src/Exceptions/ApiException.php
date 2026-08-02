<?php

namespace AtivaBot\Exceptions;

class ApiException extends AtivaBotException
{
    protected int $statusCode;
    protected ?array $responseBody;

    public function __construct(string $message, int $statusCode = 0, ?array $responseBody = null)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
