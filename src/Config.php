<?php

namespace AtivaBot;

use AtivaBot\Exceptions\ValidationException;

class Config
{
    public const DEFAULT_BASE_URL = 'https://api.ativabot.com.br';

    private string $baseUrl;
    private string $apiId;
    private string $jwtToken;
    private int $timeout;

    public function __construct(string $apiId, string $jwtToken, int $timeout = 30, ?string $baseUrl = null)
    {
        if (empty($apiId)) {
            throw new ValidationException('O parâmetro apiId não pode ser vazio.');
        }

        if (empty($jwtToken)) {
            throw new ValidationException('O parâmetro jwtToken não pode ser vazio.');
        }

        $this->apiId = $apiId;
        $this->jwtToken = $jwtToken;
        $this->timeout = $timeout;
        $this->baseUrl = !empty($baseUrl) ? rtrim(trim($baseUrl), '/') : self::DEFAULT_BASE_URL;
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
