<?php

namespace App\Services\WhatsApp\Providers;

use App\Core\Http\HttpClient;
use App\Core\Http\HttpException;
use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\Services\WhatsApp\DTO\ConnectionTestResult;
use App\Services\WhatsApp\DTO\SendMessageResult;

final readonly class MetaWhatsAppProvider implements WhatsAppProviderInterface
{
    public function __construct(
        private string $accessToken,
        private string $phoneNumberId,
        private string $apiVersion = 'v25.0'
    ) {
    }

    public function testConnection(): ConnectionTestResult
    {
        try {
            $url = sprintf(
                'https://graph.facebook.com/%s/%s?fields=id,display_phone_number,verified_name',
                'v' . ltrim($this->apiVersion, 'vV'),
                rawurlencode($this->phoneNumberId)
            );

            $response = HttpClient::get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);

            $data = $response->json();

            if ($response->failed()) {
                return new ConnectionTestResult(
                    success: false,
                    statusCode: $response->statusCode,
                    response: $data,
                    error: $this->extractError($data)
                );
            }

            return new ConnectionTestResult(
                success: true,
                statusCode: $response->statusCode,
                response: $data
            );
        } catch (HttpException $exception) {
            return new ConnectionTestResult(
                success: false,
                error: $exception->getMessage()
            );
        }
    }

    public function sendText(
        string $recipient,
        string $message,
        bool $previewUrl = false
    ): SendMessageResult {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $this->normalizePhone($recipient),
            'type'              => 'text',
            'text'              => [
                'preview_url' => $previewUrl,
                'body'        => $message,
            ],
        ];

        return $this->send($payload);
    }

    public function sendTemplate(
        string $recipient,
        string $template,
        string $languageCode = 'en_US',
        array $components = []
    ): SendMessageResult {
        $templateData = [
            'name' => $template,
            'language' => [
                'code' => $languageCode,
            ],
        ];

        /*
         * Las plantillas sin parámetros, como hello_world,
         * no necesitan components.
         */
        if ($components !== []) {
            $templateData['components'] = $components;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $this->normalizePhone($recipient),
            'type'              => 'template',
            'template'          => $templateData,
        ];

        return $this->send($payload);
    }

    private function send(array $payload): SendMessageResult
    {
        try {
            $response = HttpClient::post(
                $this->messagesEndpoint(),
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->accessToken,
                    ],
                    'json' => $payload,
                ]
            );

            $data = $response->json();

            if ($response->failed()) {
                return new SendMessageResult(
                    success: false,
                    statusCode: $response->statusCode,
                    response: $data,
                    error: $this->extractError($data)
                );
            }

            return new SendMessageResult(
                success: true,
                messageId: $data['messages'][0]['id'] ?? null,
                statusCode: $response->statusCode,
                response: $data
            );
        } catch (HttpException $exception) {
            return new SendMessageResult(
                success: false,
                error: $exception->getMessage()
            );
        }
    }

    private function messagesEndpoint(): string
    {
        $version = 'v' . ltrim($this->apiVersion, 'vV');

        return sprintf(
            'https://graph.facebook.com/%s/%s/messages',
            $version,
            rawurlencode($this->phoneNumberId)
        );
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        if ($phone === '') {
            throw new \InvalidArgumentException(
                'El número de teléfono del destinatario no es válido.'
            );
        }

        return $phone;
    }

    private function extractError(array $response): string
    {
        return $response['error']['message']
            ?? 'Meta rechazó el envío del mensaje.';
    }
}