<?php

namespace AtivaBot\Endpoints;

use AtivaBot\Exceptions\ValidationException;
use fopen;
use is_resource;
use file_exists;

class Messages extends AbstractEndpoint
{
    /**
     * Envia uma mensagem de texto simples.
     *
     * @param string $number Número de telefone com DDI (ex: 5511999999999 ou 11999999999)
     * @param string $body Texto da mensagem
     * @param string|null $externalKey Chave única de referência externa para Webhooks
     * @return array Resposta da API (ex: ['message' => 'Message add queue'])
     * @throws ValidationException
     */
    public function sendText(string $number, string $body, ?string $externalKey = null): array
    {
        if (empty(trim($number))) {
            throw new ValidationException('O número de telefone é obrigatório.');
        }

        if (empty(trim($body))) {
            throw new ValidationException('O corpo da mensagem é obrigatório.');
        }

        $payload = [
            'number'      => $number,
            'body'        => $body,
            'externalKey' => $externalKey ?? ('ext_' . uniqid()),
        ];

        return $this->client->request('POST', '/v1/api/external/:apiId', [
            'json' => $payload,
        ]);
    }

    /**
     * Envia uma mensagem com arquivo de mídia (imagem, áudio, vídeo, documento PDF, etc).
     *
     * @param string $number Número de telefone com DDI
     * @param string|resource $filePathOrResource Caminho absoluto do arquivo no disco ou resource de arquivo
     * @param string|null $caption Legenda opcional da mídia (body)
     * @param string|null $externalKey Chave de referência externa
     * @return array Resposta da API
     * @throws ValidationException
     */
    public function sendMedia(string $number, $filePathOrResource, ?string $caption = null, ?string $externalKey = null): array
    {
        if (empty(trim($number))) {
            throw new ValidationException('O número de telefone é obrigatório.');
        }

        $multipart = [
            [
                'name'     => 'number',
                'contents' => $number,
            ],
            [
                'name'     => 'externalKey',
                'contents' => $externalKey ?? ('ext_' . uniqid()),
            ],
        ];

        if ($caption !== null && $caption !== '') {
            $multipart[] = [
                'name'     => 'body',
                'contents' => $caption,
            ];
        }

        if (is_string($filePathOrResource)) {
            if (!file_exists($filePathOrResource)) {
                throw new ValidationException("Arquivo de mídia não encontrado no caminho: {$filePathOrResource}");
            }
            $multipart[] = [
                'name'     => 'media',
                'contents' => fopen($filePathOrResource, 'r'),
                'filename' => basename($filePathOrResource),
            ];
        } elseif (is_resource($filePathOrResource)) {
            $multipart[] = [
                'name'     => 'media',
                'contents' => $filePathOrResource,
                'filename' => 'media_file',
            ];
        } else {
            throw new ValidationException('O parâmetro media deve ser um caminho de arquivo válido ou um resource.');
        }

        return $this->client->request('POST', '/v1/api/external/:apiId', [
            'multipart' => $multipart,
        ]);
    }

    /**
     * Envia mensagem especial (botões, listas, localização, etc).
     *
     * @param array $payload Dados da mensagem especial (ticketId, body, type, etc)
     * @return array Resposta da API
     */
    public function sendSpecial(array $payload): array
    {
        return $this->client->request('POST', '/v1/api/external/:apiId/messages/special', [
            'json' => $payload,
        ]);
    }
}
