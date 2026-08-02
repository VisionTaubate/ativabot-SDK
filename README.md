# AtivaBot SDK para PHP e Laravel

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-blue.svg)](https://php.net)

SDK Oficial em PHP para integração com a API Externa do **AtivaBot CRM**. Permite enviar mensagens de texto e mídia via WhatsApp, gerenciar contatos, grupos, templates WABA e receber webhooks com suporte nativo a PHP puro e Laravel.

- 🌐 **Site Oficial:** [https://ativabot.com.br](https://ativabot.com.br)
- 👤 **Área do Usuário:** [https://app.ativabot.com.br](https://app.ativabot.com.br)

---

## 🚀 Instalação

Instale o pacote via Composer:

```bash
composer require ativabot/php-sdk
```

---

## 🔑 Como Obter suas Credenciais (API ID e Token)

Para utilizar o SDK, você precisará do **API ID** e do **Token (JWT)** gerados no painel do **AtivaBot CRM**:

1. Acesse o painel do **AtivaBot CRM** ([https://app.ativabot.com.br](https://app.ativabot.com.br)).
2. No menu lateral esquerdo, acesse **Sistema** ➔ **API** (ou vá diretamente para `/api-service`).
3. Clique no botão **"Adicionar"** no canto superior direito.
4. Preencha as informações da integração:
   - **Nome da API**: Um nome para identificar seu sistema (ex.: `Meu Sistema PHP`, `E-commerce`).
   - **Enviar por**: Selecione a conexão de WhatsApp padrão que realizará os disparos.
   - **WebHook** *(Opcional)*: Caso queira receber notificações de eventos (mensagens recebidas, status de entrega, contatos, etc.), preencha a URL do seu Webhook e selecione os eventos desejados.
5. Clique em **Salvar**.
6. Na lista de integrações cadastradas (ou ao abrir a edição da API), copie facilmente as duas credenciais:
   - **API ID**: Clique no botão de copiar (ícone 📋) no campo **API ID** (ou no cabeçalho do card).
   - **Token (JWT)**: Clique no botão de copiar (ícone 📋) no campo **Token (JWT)**.

---

## ⚙️ Configuração no Laravel

Se você estiver utilizando Laravel, o pacote possui **Package Auto-Discovery** e registrará o Service Provider e a Facade automaticamente.

Para publicar o arquivo de configuração `config/ativabot.php`:

```bash
php artisan vendor:publish --tag="ativabot-config"
```

Em seguida, adicione suas credenciais no arquivo `.env`:

```env
ATIVABOT_API_ID=seu-api-id-uuid
ATIVABOT_JWT_TOKEN=seu-jwt-token-aqui
```

---

## 💻 Uso em PHP Puro

```php
use AtivaBot\Client;

$client = Client::create(
    'seu-api-id-uuid',
    'seu-jwt-token'
);

// Enviar mensagem de texto
$response = $client->messages()->sendText(
    '5511999999999',
    'Olá! Mensagem enviada via SDK AtivaBot em PHP puro.'
);

// Enviar arquivo de mídia (imagem/PDF/áudio)
$mediaResponse = $client->messages()->sendMedia(
    '5511999999999',
    '/caminho/para/documento.pdf',
    'Legenda da mídia',
    'minha_chave_externa_123'
);
```

---

## ⚡ Uso no Laravel (via Facade)

```php
use AtivaBot\Laravel\Facades\AtivaBot;

// Enviar mensagem de texto
AtivaBot::messages()->sendText('5511999999999', 'Olá via Laravel Facade!');

// Criar um contato
AtivaBot::contacts()->create([
    'name'   => 'João Silva',
    'number' => '5511999999999',
    'email'  => 'joao@email.com'
]);

// Enviar template aprovado
AtivaBot::templates()->send([
    'to'           => '5511999999999',
    'templateName' => 'boas_vindas',
    'language'     => 'pt_BR'
]);
```

---

## 🔔 Tratamento de Webhooks

O SDK fornece o utilitário `WebhookHandler` para facilitar a interpretação dos eventos enviados pelo CRM:

```php
use AtivaBot\Webhooks\WebhookHandler;

$payload = WebhookHandler::parse(); // Lê automaticamente php://input

if (WebhookHandler::isMessageStatusEvent($payload)) {
    $externalKey = $payload['externalKey'];
    $status      = $payload['status']; // delivered, read, failed
}
```

---

## 📚 Endpoints Disponíveis no SDK

- **`messages()`**: `sendText()`, `sendMedia()`, `sendSpecial()`
- **`contacts()`**: `list()`, `create()`, `update()`, `delete()`
- **`groups()`**: `list()`, `create()`, `update()`, `delete()`
- **`templates()`**: `list()`, `create()`, `update()`, `delete()`, `submitForApproval()`, `send()`
- **`sessions()`**: `start()`

---

## 📄 Licença

Este SDK é um software open-source sob a licença MIT.
