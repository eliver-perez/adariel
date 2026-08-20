<?php

namespace App\Services\WhatsApp\DTO;

final readonly class SendMessageResult
{
    public function __construct(
        public bool $success,
        public ?string $messageId = null,
        public ?int $statusCode = null,
        public array $response = [],
        public ?string $error = null
    ) {
    }
}