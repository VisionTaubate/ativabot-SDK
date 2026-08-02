<?php

namespace AtivaBot\Endpoints;

use AtivaBot\Exceptions\ValidationException;

class Contacts extends AbstractEndpoint
{
    /**
     * Lista ou busca contatos.
     *
     * @param array $query Parâmetros de filtro (ex: ['searchParam' => 'João', 'pageNumber' => 1])
     * @return array Resposta da API contendo a lista de contatos
     */
    public function list(array $query = []): array
    {
        return $this->client->request('GET', '/v1/api/external/:apiId/contacts', [
            'query' => $query,
        ]);
    }

    /**
     * Cria um novo contato no CRM.
     *
     * @param array $contactData Ex: ['name' => 'Nome', 'number' => '5511999999999', 'email' => 'email@exemplo.com']
     * @return array Objeto do contato criado
     * @throws ValidationException
     */
    public function create(array $contactData): array
    {
        if (empty($contactData['name'])) {
            throw new ValidationException('O campo name é obrigatório para criar um contato.');
        }
        if (empty($contactData['number'])) {
            throw new ValidationException('O campo number é obrigatório para criar um contato.');
        }

        return $this->client->request('POST', '/v1/api/external/:apiId/contacts', [
            'json' => $contactData,
        ]);
    }

    /**
     * Atualiza um contato existente pelo ID.
     *
     * @param string|int $contactId ID do contato
     * @param array $contactData Dados a serem atualizados
     * @return array Objeto do contato atualizado
     */
    public function update($contactId, array $contactData): array
    {
        return $this->client->request('PUT', "/v1/api/external/:apiId/contacts/{$contactId}", [
            'json' => $contactData,
        ]);
    }

    /**
     * Exclui um contato existente pelo ID.
     *
     * @param string|int $contactId ID do contato
     * @return array Confirmação de exclusão
     */
    public function delete($contactId): array
    {
        return $this->client->request('DELETE', "/v1/api/external/:apiId/contacts/{$contactId}");
    }
}
