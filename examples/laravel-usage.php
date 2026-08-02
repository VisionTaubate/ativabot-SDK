<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AtivaBot\Laravel\Facades\AtivaBot;
use AtivaBot\Webhooks\WebhookHandler;
use AtivaBot\Exceptions\ApiException;

class AtivaBotNotificationController extends Controller
{
    /**
     * Exemplo de envio de mensagem de texto via Facade do Laravel.
     */
    public function sendWelcomeMessage(Request $request)
    {
        try {
            $response = AtivaBot::messages()->sendText(
                $request->input('phone'),
                "Seja bem-vindo ao nosso sistema, {$request->input('name')}!",
                "user_registration_" . $request->input('user_id')
            );

            return response()->json([
                'success' => true,
                'result'  => $response,
            ]);
        } catch (ApiException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'details' => $e->getResponseBody(),
            ], $e->getStatusCode() ?: 400);
        }
    }

    /**
     * Exemplo de envio de Template WABA aprovado.
     */
    public function sendNotificationTemplate(Request $request)
    {
        $response = AtivaBot::templates()->send([
            'to'           => $request->input('phone'),
            'templateName' => 'codigo_verificacao',
            'language'     => 'pt_BR',
            'components'   => [
                [
                    'type'       => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => '123456'],
                    ],
                ],
            ],
        ]);

        return response()->json($response);
    }

    /**
     * Exemplo de Webhook Controller no Laravel.
     */
    public function handleWebhook(Request $request)
    {
        $payload = WebhookHandler::parse($request->getContent());

        if (!$payload) {
            return response()->json(['error' => 'Payload inválido'], 400);
        }

        if (WebhookHandler::isMessageStatusEvent($payload)) {
            $externalKey = $payload['externalKey'] ?? null;
            $status      = $payload['status'] ?? null; // delivered, read, failed

            \Log::info("Webhook status de mensagem: {$externalKey} -> {$status}");
        }

        return response()->json(['status' => 'ok']);
    }
}
