<?php

namespace App\Services\WhatsApp;

use App\Core\Service;
use App\Repositories\WhatsAppMessageRepository;
use App\Services\WhatsApp\DTO\SendMessageResult;
use Throwable;

final class WhatsAppMessageService extends Service
{
    public function __construct(
        private WhatsAppMessageRepository $messageRepository,
        private WhatsAppIntegrationService $integrationService
    ) {
    }

    public function sendTestTemplate(
        int $companyId,
        int $userId,
        string $recipient,
        string $template = 'hello_world',
        string $languageCode = 'en_US',
        array $components = []
    ): SendMessageResult {
        return $this->sendTemplate(
            companyId: $companyId,
            userId: $userId,
            recipient: $recipient,
            template: $template,
            languageCode: $languageCode,
            components: $components,
            event: 'test_message',
            isTest: true
        );
    }

    public function sendTemplate(
        int $companyId,
        int $userId,
        string $recipient,
        string $template,
        string $languageCode = 'en_US',
        array $components = [],
        ?string $event = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        bool $isTest = false
    ): SendMessageResult {
        $recipient = trim($recipient);
        $template = trim($template);
        $languageCode = trim($languageCode);

        if ($recipient === '') {
            throw new \InvalidArgumentException(
                'El número destinatario es obligatorio.'
            );
        }

        if ($template === '') {
            throw new \InvalidArgumentException(
                'La plantilla es obligatoria.'
            );
        }

        $resolved = $this->integrationService
            ->resolveActiveIntegration($companyId);

        $integration = $resolved['integration'];

        /** @var WhatsAppService $whatsApp */
        $whatsApp = $resolved['service'];

        $requestPayload = [
            'recipient' => $recipient,
            'type' => 'template',
            'template' => $template,
            'language' => $languageCode,
            'components' => $components,
        ];

        $messageId = $this->messageRepository->createPending(
            uuid: $this->generateUuidBinary(),
            companyId: $companyId,
            integrationId: (int) $integration['id'],
            provider: $integration['proveedor'],
            type: 'template',
            recipient: $recipient,
            event: $event,
            referenceType: $referenceType,
            referenceId: $referenceId,
            template: $template,
            language: $languageCode,
            parameters: $components,
            requestPayload: $requestPayload,
            isTest: $isTest,
            userId: $userId
        );

        try {
            $result = $whatsApp->sendTemplate(
                recipient: $recipient,
                template: $template,
                languageCode: $languageCode,
                components: $components
            );

            if ($result->success) {
                $this->messageRepository->markAsSent(
                    messageId: $messageId,
                    providerMessageId: $result->messageId,
                    statusCode: $result->statusCode,
                    response: $result->response
                );

                return $result;
            }

            $this->messageRepository->markAsFailed(
                messageId: $messageId,
                statusCode: $result->statusCode,
                errorCode: $this->extractErrorCode(
                    $result->response
                ),
                error: $result->error,
                response: $result->response
            );

            return $result;
        } catch (Throwable $exception) {
            $this->messageRepository->markAsFailed(
                messageId: $messageId,
                statusCode: null,
                errorCode: null,
                error: $exception->getMessage()
            );

            return new SendMessageResult(
                success: false,
                error: $exception->getMessage()
            );
        }
    }

    public function sendText(
        int $companyId,
        int $userId,
        string $recipient,
        string $content,
        bool $previewUrl = false,
        ?string $event = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        bool $isTest = false
    ): SendMessageResult {
        $recipient = trim($recipient);
        $content = trim($content);

        if ($recipient === '') {
            throw new \InvalidArgumentException(
                'El número destinatario es obligatorio.'
            );
        }

        if ($content === '') {
            throw new \InvalidArgumentException(
                'El mensaje no puede estar vacío.'
            );
        }

        $resolved = $this->integrationService
            ->resolveActiveIntegration($companyId);

        $integration = $resolved['integration'];

        /** @var WhatsAppService $whatsApp */
        $whatsApp = $resolved['service'];

        $requestPayload = [
            'recipient' => $recipient,
            'type' => 'text',
            'content' => $content,
            'preview_url' => $previewUrl,
        ];

        $messageId = $this->messageRepository->createPending(
            uuid: $this->generateUuidBinary(),
            companyId: $companyId,
            integrationId: (int) $integration['id'],
            provider: $integration['proveedor'],
            type: 'text',
            recipient: $recipient,
            event: $event,
            referenceType: $referenceType,
            referenceId: $referenceId,
            content: $content,
            requestPayload: $requestPayload,
            isTest: $isTest,
            userId: $userId
        );

        try {
            $result = $whatsApp->sendText(
                recipient: $recipient,
                message: $content,
                previewUrl: $previewUrl
            );

            if ($result->success) {
                $this->messageRepository->markAsSent(
                    messageId: $messageId,
                    providerMessageId: $result->messageId,
                    statusCode: $result->statusCode,
                    response: $result->response
                );

                return $result;
            }

            $this->messageRepository->markAsFailed(
                messageId: $messageId,
                statusCode: $result->statusCode,
                errorCode: $this->extractErrorCode(
                    $result->response
                ),
                error: $result->error,
                response: $result->response
            );

            return $result;
        } catch (Throwable $exception) {
            $this->messageRepository->markAsFailed(
                messageId: $messageId,
                statusCode: null,
                errorCode: null,
                error: $exception->getMessage()
            );

            return new SendMessageResult(
                success: false,
                error: $exception->getMessage()
            );
        }
    }

    private function extractErrorCode(array $response): ?string
    {
        $errorCode = $response['error']['code'] ?? null;

        return $errorCode === null
            ? null
            : (string) $errorCode;
    }
}