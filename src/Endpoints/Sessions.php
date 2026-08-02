<?php

namespace AtivaBot\Endpoints;

class Sessions extends AbstractEndpoint
{
    /**
     * Verifica o status ou inicia a sessão do canal do WhatsApp associado à API.
     *
     * @return array Resposta com status da sessão
     */
    public function start(): array
    {
        return $this->client->request('POST', '/v1/api/external/:apiId/start-session');
    }
}
