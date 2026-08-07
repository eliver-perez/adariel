<?php

namespace App\Services\WhatsApp\DTO;

final readonly class ConnectionTestResult
{
    public function __construct(
        public bool $success,
        public ?int $statusCode = null,
        public array $response = [],
        public ?string $error = null
    ) {
    }
}