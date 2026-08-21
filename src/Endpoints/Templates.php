<?php

namespace AtivaBot\Endpoints;

use AtivaBot\Exceptions\ValidationException;

class Templates extends AbstractEndpoint
{
    /**
     * Lista os templates de mensagem cadastrados no CRM / Meta WABA.
     *
     * @return array Lista de templates
     */
    public function list(): array
    {
        return $this->client->request('GET', '/v1/api/external/:apiId/templates');
    }

    /**
     * Cria um novo template de mensagem WABA.
     *
     * @param array $templateData Ex: ['templateName' => 'boas_vindas', 'templateContent' => 'Olá {{1}}', 'category' => 'UTILITY']
     * @return array Objeto do template criado
     * @throws ValidationException
     */
    public function create(array $templateData): array
    {
        if (empty($templateData['templateName'])) {
            throw new ValidationException('O campo templateName é obrigatório.');
        }
        if (empty($templateData['templateContent'])) {
            throw new ValidationException('O campo templateContent é obrigatório.');
        }

        return $this->client->request('POST', '/v1/api/external/:apiId/templates', [
            'json' => $templateData,
        ]);
    }

    /**
     * Atualiza um template de mensagem existente.
     *
     * @param string|int $templateId ID do template
     * @param array $templateData Dados atualizados do template
     * @return array Objeto do template atualizado
     */
    public function update($templateId, array $templateData): array
    {
        return $this->client->request('PUT', "/v1/api/external/:apiId/templates/{$templateId}", [
            'json' => $templateData,
        ]);
    }

    /**
     * Exclui um template de mensagem pelo ID.
     *
     * @param string|int $templateId ID do template
     * @return array Confirmação de exclusão
     */
    public function delete($templateId): array
    {
        return $this->client->request('DELETE', "/v1/api/external/:apiId/templates/{$templateId}");
    }

    /**
     * Submete o template para análise e aprovação da Meta.
     *
     * @param string|int $templateId ID do template
     * @return array Resposta da submissão à Meta
     */
    public function submitForApproval($templateId): array
    {
        return $this->client->request('POST', "/v1/api/external/:apiId/templates/{$templateId}/submit");
    }

    /**
     * Envia uma mensagem baseada em um template aprovado.
     *
     * @param array $payload Ex: ['to' => '5511999999999', 'templateId' => 42, 'language' => 'pt_BR', 'components' => [...]]
     * @return array Resposta de enfileiramento do template
     * @throws ValidationException
     */
    public function send(array $payload): array
    {
        if (empty($payload['to'])) {
            throw new ValidationException('O campo to (destinatário) é obrigatório.');
        }
        if (empty($payload['templateId']) && empty($payload['templateName'])) {
            throw new ValidationException('O campo templateId ou templateName é obrigatório.');
        }

        return $this->client->request('POST', '/v1/api/external/:apiId/templates/send', [
            'json' => $payload,
        ]);
    }
}
