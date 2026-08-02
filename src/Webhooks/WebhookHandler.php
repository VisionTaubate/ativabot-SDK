<?php

namespace AtivaBot\Webhooks;

class WebhookHandler
{
    public const EVENT_MESSAGE_STATUS = 'hookMessageStatus';
    public const EVENT_MESSAGE        = 'hookMessage';
    public const EVENT_CONTACT        = 'hookContact';
    public const EVENT_GROUP          = 'hookGroup';
    public const EVENT_TEMPLATE       = 'hookTemplate';
    public const EVENT_SESSION_STATUS = 'hookSessionStatus';

    // Status ACK de mensagens
    public const ACK_SENT      = 1;
    public const ACK_DELIVERED = 2;
    public const ACK_READ      = 3;
    public const ACK_FAILED    = -1;
    public const ACK_BLOCKED   = -2;

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
        return $webhookData['type'] ?? $webhookData['event'] ?? null;
    }

    /**
     * Helper para verificar se o evento é uma atualização de status de mensagem (ACK).
     */
    public static function isMessageStatusEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_MESSAGE_STATUS;
    }

    /**
     * Helper para verificar se o evento é de mensagem (recebida ou enviada).
     */
    public static function isMessageEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_MESSAGE;
    }

    /**
     * Helper para verificar se o evento é de mensagem recebida.
     */
    public static function isIncomingMessageEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_MESSAGE
            && (($webhookData['action'] ?? '') === 'received' || ($webhookData['message']['fromMe'] ?? false) === false);
    }

    /**
     * Helper para verificar se o evento é de contato (criação, edição, exclusão).
     */
    public static function isContactEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_CONTACT;
    }

    /**
     * Helper para verificar se o evento é de grupo.
     */
    public static function isGroupEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_GROUP;
    }

    /**
     * Helper para verificar se o evento é de ciclo de vida de template.
     */
    public static function isTemplateEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_TEMPLATE;
    }

    /**
     * Helper para verificar se o evento é de status de sessão / conexão do canal.
     */
    public static function isSessionStatusEvent(array $webhookData): bool
    {
        return static::getEventType($webhookData) === self::EVENT_SESSION_STATUS;
    }

    /**
     * Retorna a descrição legível de um código ACK.
     */
    public static function getAckDescription(int $ack): string
    {
        return match ($ack) {
            self::ACK_SENT      => 'Enviada ao servidor WhatsApp',
            self::ACK_DELIVERED => 'Entregue ao destinatário',
            self::ACK_READ      => 'Lida',
            self::ACK_FAILED    => 'Falha no envio',
            self::ACK_BLOCKED   => 'Bloqueada',
            default             => 'Desconhecido',
        };
    }
}
