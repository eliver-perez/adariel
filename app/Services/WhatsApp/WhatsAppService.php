<?php

namespace App\Services\WhatsApp;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\Services\WhatsApp\DTO\SendMessageResult;
use InvalidArgumentException;

final readonly class WhatsAppService
{
    public function __construct(
        private WhatsAppProviderInterface $provider
    ) {
    }

    public function sendText(
        string $recipient,
        string $message,
        bool $previewUrl = false
    ): SendMessageResult {
        $recipient = trim($recipient);
        $message = trim($message);

        if ($recipient === '') {
            throw new InvalidArgumentException(
                'Debes proporcionar el número del destinatario.'
            );
        }

        if ($message === '') {
            throw new InvalidArgumentException(
                'El mensaje no puede estar vacío.'
            );
        }

        return $this->provider->sendText(
            recipient: $recipient,
            message: $message,
            previewUrl: $previewUrl
        );
    }

    public function sendTemplate(
        string $recipient,
        string $template,
        string $languageCode = 'en_US',
        array $components = []
    ): SendMessageResult {
        $recipient = trim($recipient);
        $template = trim($template);
        $languageCode = trim($languageCode);

        if ($recipient === '') {
            throw new InvalidArgumentException(
                'Debes proporcionar el número del destinatario.'
            );
        }

        if ($template === '') {
            throw new InvalidArgumentException(
                'Debes proporcionar el nombre de la plantilla.'
            );
        }

        if ($languageCode === '') {
            throw new InvalidArgumentException(
                'Debes proporcionar el idioma de la plantilla.'
            );
        }

        return $this->provider->sendTemplate(
            recipient: $recipient,
            template: $template,
            languageCode: $languageCode,
            components: $components
        );
    }
}