<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AtivaBot\Client;
use AtivaBot\Exceptions\ApiException;
use AtivaBot\Exceptions\AtivaBotException;

// Configuração das credenciais geradas no painel do CRM
$apiId    = '999ab3a2-9f1f-4ffb-969a-bfb72234ece1';
$jwtToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';

try {
    // Inicialização do cliente (URL padrão https://api.ativabot.com.br usada automaticamente)
    $client = Client::create($apiId, $jwtToken);

    // 1. Enviar Mensagem de Texto
    echo "Enviando mensagem de texto...\n";
    $response = $client->messages()->sendText(
        '5511999999999',
        'Olá! Esta é uma mensagem de teste enviada via SDK PHP Puro.',
        'pedido_ref_1001'
    );
    print_r($response);

    // 2. Enviar Mensagem com Mídia (PDF/Imagem)
    echo "Enviando mensagem com mídia...\n";
    $mediaResponse = $client->messages()->sendMedia(
        '5511999999999',
        __DIR__ . '/exemplo_comprovante.pdf',
        'Segue o seu comprovante em anexo.',
        'pedido_ref_1002'
    );
    print_r($mediaResponse);

    // 3. Criar Contato
    echo "Criando contato...\n";
    $contact = $client->contacts()->create([
        'name'   => 'Cliente Exemplo',
        'number' => '5511988887777',
        'email'  => 'cliente@exemplo.com',
    ]);
    print_r($contact);

} catch (ApiException $e) {
    echo "Erro de API ({$e->getStatusCode()}): {$e->getMessage()}\n";
    print_r($e->getResponseBody());
} catch (AtivaBotException $e) {
    echo "Erro no SDK: {$e->getMessage()}\n";
}
