<?php

namespace AtivaBot\Webhooks;

class WebhookHandler
{
    /**
     * Faz o parse do payload recebido do webhook via JSON/HTTP input.
     *
     * @param string|null $rawPayload Conteúdo raw em JSON (se nulo, lê php://input)
     * @return array|null Dados do evento de webhook ou null se payload for inválido
     */
    public static function parse(?string $rawPayload = null): ?array
    {
        if ($rawPayload === null) {
            $rawPayload = file_get_contents('php://input');
        }

        if (empty($rawPayload)) {
            return null;
        }

        $data = json_decode($rawPayload, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Retorna o tipo do evento de webhook recebido (ex: hookMessageStatus, hookMessage, hookContact).
     *
     * @param array $webhookData Payload retornado pelo parse()
     * @return string|null Tipo de evento
     */
    public static function getEventType(array $webhookData): ?string
    {
        return $webhookData['event'] ?? $webhookData['type'] ?? null;
    }

    /**
     * Helper para verificar se o evento é uma atualização de status de mensagem.
     */
    public static function isMessageStatusEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === 'hookMessageStatus';
    }

    /**
     * Helper para verificar se o evento é de mensagem recebida.
     */
    public static function isIncomingMessageEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === 'hookMessage';
    }
}
