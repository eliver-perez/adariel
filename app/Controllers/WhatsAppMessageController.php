<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Security\EncryptionService;
use App\Repositories\WhatsAppIntegrationRepository;
use App\Repositories\WhatsAppMessageRepository;
use App\Services\WhatsApp\Factories\WhatsAppProviderFactory;
use App\Services\WhatsApp\WhatsAppIntegrationService;
use App\Services\WhatsApp\WhatsAppMessageService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class WhatsAppMessageController
{
    private ?WhatsAppMessageService $service = null;

    private function getService(): WhatsAppMessageService
    {
        if ($this->service !== null) {
            return $this->service;
        }

        $encryptionKey = env('APP_ENCRYPTION_KEY');

        if (
            !is_string($encryptionKey) ||
            trim($encryptionKey) === ''
        ) {
            throw new RuntimeException(
                'No se encontró APP_ENCRYPTION_KEY.'
            );
        }

        $database = new Database();
        $connection = $database->getConnection();

        $integrationRepository =
            new WhatsAppIntegrationRepository($connection);

        $messageRepository =
            new WhatsAppMessageRepository($connection);

        $encryptionService =
            new EncryptionService($encryptionKey);

        $providerFactory =
            new WhatsAppProviderFactory();

        $integrationService =
            new WhatsAppIntegrationService(
                repository: $integrationRepository,
                encryption: $encryptionService,
                providerFactory: $providerFactory
            );

        $this->service = new WhatsAppMessageService(
            messageRepository: $messageRepository,
            integrationService: $integrationService
        );

        return $this->service;
    }

    public function sendTestTemplate(
        Request $request,
        Response $response
    ): void {
        try {
            $service = $this->getService();

            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }

            $companyId = Auth::organizationId();

            if($companyId === null) {
                throw new RuntimeException("Sin datos de empresa.");
            }

            $data = $request->input();

            $recipient = trim(
                (string) ($data['recipient'] ?? '')
            );

            if ($recipient === '') {
                throw new InvalidArgumentException(
                    'Debes proporcionar el número destinatario.'
                );
            }

            $result = $this->getService()->sendTestTemplate(
                companyId: $companyId,
                userId: $currentUserId,
                recipient: $recipient,
                template: trim(
                    (string) ($data['template'] ?? 'hello_world')
                ),
                languageCode: trim(
                    (string) ($data['language'] ?? 'en_US')
                )
            );

            if (!$result->success) {
                $response->json([
                    'success' => false,
                    'message' => $result->error
                        ?? 'No fue posible enviar el mensaje.',
                    'data' => [
                        'status_code' => $result->statusCode,
                    ],
                ], 422);

                return;
            }

            $response->json([
                'success' => true,
                'message' => 'El mensaje de prueba fue enviado correctamente.',
                'data' => [
                    'message_id' => $result->messageId,
                    'status_code' => $result->statusCode,
                ],
            ]);
        } catch (InvalidArgumentException $exception) {
            $response->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        } catch (Throwable $exception) {
            $response->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    private function getAuthenticatedUserId(): int
    {
        $userId = Auth::id();

        if ($userId === null || (int) $userId <= 0) {
            throw new RuntimeException(
                'No se encontró un usuario autenticado.'
            );
        }

        return (int) $userId;
    }

    private function getAuthenticatedCompanyId(): int
    {
        $companyId = Auth::organizationId();

        if ($companyId === null || (int) $companyId <= 0) {
            throw new RuntimeException(
                'No se encontró una empresa autenticada.'
            );
        }

        return (int) $companyId;
    }
}