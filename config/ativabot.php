<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AtivaBot CRM API Credentials
    |--------------------------------------------------------------------------
    |
    | Aqui você define as credenciais de acesso à API Externa do AtivaBot.
    | O `api_id` é o UUID da configuração de API no painel.
    | O `jwt_token` é o token de autenticação gerado no painel do CRM.
    |
    */

    'api_id'    => env('ATIVABOT_API_ID', ''),
    'jwt_token' => env('ATIVABOT_JWT_TOKEN', ''),
    'timeout'   => env('ATIVABOT_TIMEOUT', 30),

    /*
    | URL da API (Opcional - padrão: https://api.ativabot.com.br)
    */
    'base_url'  => env('ATIVABOT_BASE_URL', 'https://api.ativabot.com.br'),
];
