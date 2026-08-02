<?php

namespace AtivaBot\Endpoints;

use AtivaBot\Exceptions\ValidationException;

class Groups extends AbstractEndpoint
{
    /**
     * Lista todos os grupos de contatos.
     *
     * @return array Lista de grupos
     */
    public function list(): array
    {
        return $this->client->request('GET', '/v1/api/external/:apiId/groups');
    }

    /**
     * Cria um novo grupo de contatos.
     *
     * @param array $groupData Ex: ['name' => 'Clientes VIP']
     * @return array Objeto do grupo criado
     * @throws ValidationException
     */
    public function create(array $groupData): array
    {
        if (empty($groupData['name'])) {
            throw new ValidationException('O campo name é obrigatório para criar um grupo.');
        }

        return $this->client->request('POST', '/v1/api/external/:apiId/groups', [
            'json' => $groupData,
        ]);
    }

    /**
     * Atualiza um grupo existente pelo ID.
     *
     * @param string|int $groupId ID do grupo
     * @param array $groupData Dados a atualizar (ex: ['name' => 'Novo Nome'])
     * @return array Objeto do grupo atualizado
     */
    public function update($groupId, array $groupData): array
    {
        return $this->client->request('PUT', "/v1/api/external/:apiId/groups/{$groupId}", [
            'json' => $groupData,
        ]);
    }

    /**
     * Exclui um grupo pelo ID.
     *
     * @param string|int $groupId ID do grupo
     * @return array Confirmação de exclusão
     */
    public function delete($groupId): array
    {
        return $this->client->request('DELETE', "/v1/api/external/:apiId/groups/{$groupId}");
    }
}
