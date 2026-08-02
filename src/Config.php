<?php

namespace AtivaBot;

use AtivaBot\Exceptions\ValidationException;

class Config
{
    private string $baseUrl;
    private string $apiId;
    private string $jwtToken;
    private int $timeout;

    public function __construct(string $baseUrl, string $apiId, string $jwtToken, int $timeout = 30)
    {
        $baseUrl = rtrim(trim($baseUrl), '/');
        if (empty($baseUrl)) {
            throw new ValidationException('O parâmetro baseUrl não pode ser vazio.');
        }

        if (empty($apiId)) {
            throw new ValidationException('O parâmetro apiId não pode ser vazio.');
        }

        if (empty($jwtToken)) {
            throw new ValidationException('O parâmetro jwtToken não pode ser vazio.');
        }

        $this->baseUrl = $baseUrl;
        $this->apiId = $apiId;
        $this->jwtToken = $jwtToken;
        $this->timeout = $timeout;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function getJwtToken(): string
    {
        return $this->jwtToken;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
