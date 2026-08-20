<?php

namespace App\Services\WhatsApp\Factories;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\Services\WhatsApp\Providers\MetaWhatsAppProvider;
use InvalidArgumentException;

final class WhatsAppProviderFactory
{
    public function create(
        string $provider,
        array $configuration
    ): WhatsAppProviderInterface {
        return match (strtolower(trim($provider))) {
            'meta' => $this->createMetaProvider($configuration),

            default => throw new InvalidArgumentException(
                "El proveedor de WhatsApp '{$provider}' no está soportado."
            ),
        };
    }

    private function createMetaProvider(
        array $configuration
    ): MetaWhatsAppProvider {
        $this->validateConfiguration(
            configuration: $configuration,
            requiredFields: [
                'access_token',
                'phone_number_id',
            ]
        );

        return new MetaWhatsAppProvider(
            accessToken: $configuration['access_token'],
            phoneNumberId: $configuration['phone_number_id'],
            apiVersion: $configuration['api_version'] ?? 'v25.0'
        );
    }

    private function validateConfiguration(
        array $configuration,
        array $requiredFields
    ): void {
        foreach ($requiredFields as $field) {
            if (
                !array_key_exists($field, $configuration) ||
                trim((string) $configuration[$field]) === ''
            ) {
                throw new InvalidArgumentException(
                    "Falta el campo de configuración '{$field}'."
                );
            }
        }
    }
}