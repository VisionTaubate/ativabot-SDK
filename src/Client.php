<?php

namespace AtivaBot;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use AtivaBot\Exceptions\ApiException;
use AtivaBot\Exceptions\AtivaBotException;
use AtivaBot\Endpoints\Messages;
use AtivaBot\Endpoints\Contacts;
use AtivaBot\Endpoints\Groups;
use AtivaBot\Endpoints\Templates;
use AtivaBot\Endpoints\Sessions;

class Client
{
    private Config $config;
    private GuzzleClient $httpClient;

    private ?Messages $messages = null;
    private ?Contacts $contacts = null;
    private ?Groups $groups = null;
    private ?Templates $templates = null;
    private ?Sessions $sessions = null;

    public function __construct(Config $config, ?GuzzleClient $httpClient = null)
    {
        $this->config = $config;

        $this->httpClient = $httpClient ?? new GuzzleClient([
            'base_uri' => $this->config->getBaseUrl(),
            'timeout'  => $this->config->getTimeout(),
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->config->getJwtToken(),
                'Accept'        => 'application/json',
            ],
        ]);
    }

    public static function create(string $apiId, string $jwtToken, int $timeout = 30, ?string $baseUrl = null): self
    {
        $config = new Config($apiId, $jwtToken, $timeout, $baseUrl);
        return new self($config);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function messages(): Messages
    {
        if ($this->messages === null) {
            $this->messages = new Messages($this);
        }
        return $this->messages;
    }

    public function contacts(): Contacts
    {
        if ($this->contacts === null) {
            $this->contacts = new Contacts($this);
        }
        return $this->contacts;
    }

    public function groups(): Groups
    {
        if ($this->groups === null) {
            $this->groups = new Groups($this);
        }
        return $this->groups;
    }

    public function templates(): Templates
    {
        if ($this->templates === null) {
            $this->templates = new Templates($this);
        }
        return $this->templates;
    }

    public function sessions(): Sessions
    {
        if ($this->sessions === null) {
            $this->sessions = new Sessions($this);
        }
        return $this->sessions;
    }

    /**
     * Executa uma requisição HTTP REST para a API do AtivaBot.
     *
     * @param string $method GET|POST|PUT|DELETE
     * @param string $endpoint Rota relativa (ex: '/v1/api/external/:apiId')
     * @param array $options Opções do Guzzle (json, multipart, query)
     * @return array Resposta decodificada do JSON
     * @throws ApiException|AtivaBotException
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        // Substitui a variável :apiId no endpoint se estiver presente
        $path = str_replace(':apiId', $this->config->getApiId(), $endpoint);

        try {
            $response = $this->httpClient->request($method, $path, $options);
            $body = (string) $response->getBody();

            if (empty($body)) {
                return [];
            }

            $decoded = json_decode($body, true);
            return is_array($decoded) ? $decoded : ['data' => $body];
        } catch (RequestException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            $responseBody = null;

            if ($e->hasResponse()) {
                $rawBody = (string) $e->getResponse()->getBody();
                $responseBody = json_decode($rawBody, true) ?: ['raw' => $rawBody];
            }

            $message = $responseBody['error'] ?? $responseBody['message'] ?? $e->getMessage();
            throw new ApiException($message, $statusCode, $responseBody);
        } catch (GuzzleException $e) {
            throw new AtivaBotException('Erro na requisição HTTP: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
