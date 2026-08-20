<?php

namespace App\Services\WhatsApp;

use App\Core\Service;
use App\Core\Security\EncryptionService;
use App\Repositories\WhatsAppIntegrationRepository;
use App\Services\WhatsApp\Factories\WhatsAppProviderFactory;
use App\Services\WhatsApp\DTO\ConnectionTestResult;
use JsonException;
use RuntimeException;

class WhatsAppIntegrationService extends Service
{
    private const ENCRYPTION_CONTEXT =
        'whatsapp.integration.credentials';

    public function __construct(
        private WhatsAppIntegrationRepository $repository,
        private EncryptionService $encryption,
        private WhatsAppProviderFactory $providerFactory
    ) {
    }

    public function testConnection(array $data): ConnectionTestResult {
        /*
        * No usamos findActiveByCompany porque también debe poder
        * probarse una integración guardada pero todavía inactiva.
        */
        $integration = $this->repository->findByCompany($data['organizationId']);

        if ($integration === null) {
            throw new RuntimeException(
                'Primero debes guardar la configuración de WhatsApp.'
            );
        }

        $credentials = $this->decryptCredentials(
            $integration['credenciales']
        );

        $configuration = array_merge(
            $integration['configuracion'],
            $credentials
        );

        $provider = $this->providerFactory->create(
            $integration['proveedor'],
            $configuration
        );

        $result = $provider->testConnection();

        $this->repository->updateTestResult(
            integrationId: $integration['id'],
            successful: $result->success,
            error: $result->error,
            userId: $data['uid']
        );

        return $result;
    }

    public function saveConfiguration(array $data): int {
        return match ($data['provider']) {
            'meta' => $this->saveMetaConfiguration(
                organizationId: $data['organizationId'],
                name: 'Meta WhatsApp Cloud API',
                phoneNumberId: (string) (
                    $data['data']['configuration']['phone_number_id'] ?? ''
                ),
                businessAccountId: (string) (
                    $data['data']['configuration']['business_account_id'] ?? ''
                ),
                apiVersion: 'v25.0',
                accessToken: (string) (
                    $data['data']['credentials']['access_token'] ?? ''
                ),
                active: filter_var(
                    $data['data']['active'] ?? false,
                    FILTER_VALIDATE_BOOL
                ),
                userId: $data['uid']
            ),

            default => throw new InvalidArgumentException(
                "El proveedor de WhatsApp '{$data['provider']}' no es compatible."
            ),
        };
    }

    public function saveMetaConfiguration(
        int $organizationId,
        string $name,
        string $phoneNumberId,
        string $businessAccountId,
        string $apiVersion,
        string $accessToken,
        bool $active,
        int $userId
    ): int {
        $phoneNumberId = trim($phoneNumberId);
        $businessAccountId = trim($businessAccountId);
        $apiVersion = trim($apiVersion);
        $accessToken = trim($accessToken);

        if ($phoneNumberId === '') {
            throw new RuntimeException(
                'El Phone Number ID es obligatorio.'
            );
        }

        $credentials = '';
        if ($accessToken !== '') {
            $credentials = $this->encryptCredentials([
                'access_token' => $accessToken,
            ]);
        }


        $uuid = $this->generateUuidBinary();

        return $this->repository->save(
            uuid: $uuid,
            organizationId: $organizationId,
            provider: 'meta',
            name: trim($name) ?: 'WhatsApp principal',
            configuration: [
                'phone_number_id' => $phoneNumberId,
                'whatsapp_business_account_id' => $businessAccountId,
                'api_version' => $apiVersion ?: 'v25.0',
            ],
            credentials: $credentials,
            active: $active,
            userId: $userId
        );
    }

    public function findForSettings(array $data): ?array
    {
        $integration = $this->repository->findByCompany($data['organizationId']);

        if ($integration === null) {
            return null;
        }

        /*
         * No devolvemos credenciales al frontend.
         */
        unset($integration['credenciales']);

        $integration['token_configurado'] = true;

        return [
            'id'                                        => $this->uuidBinarytoString($integration['id']),
            'business'                                  => $integration['empresa'],
            'provider'                                  => $integration['proveedor'],
            'name'                                      => $integration['nombre'],
            'settings'                                  => $integration['configuracion'],
            'active'                                    => $integration['activo'],
            'last_test_date'                            => $integration['ultima_prueba_at'],
            'last_test_successful'                      => $integration['ultima_prueba_exitosa'],
            'last_error'                                => $integration['ultimo_error'],
            'registered_by'                             => $integration['registrado_por'],
            'updated_by'                                => $integration['actualizado_por'],
            'registered_date'                           => $integration['f_registro'],
            'update_date'                               => $integration['f_actualizacion'],
        ];
    }

    // public function createWhatsAppService(
    //     int $organizationId
    // ): WhatsAppService {
    //     $integration = $this->repository
    //         ->findActiveByCompany($organizationId);

    //     if ($integration === null) {
    //         throw new RuntimeException(
    //             'La empresa no tiene una integración de WhatsApp activa.'
    //         );
    //     }

    //     $credentials = $this->decryptCredentials(
    //         $integration['credenciales']
    //     );

    //     $configuration = array_merge(
    //         $integration['configuracion'],
    //         $credentials
    //     );

    //     $provider = $this->providerFactory->create(
    //         $integration['proveedor'],
    //         $configuration
    //     );

    //     return new WhatsAppService($provider);
    // }
    public function createWhatsAppService(
        int $organizationId
    ): WhatsAppService {
        $resolved = $this->resolveActiveIntegration($organizationId);

        return $resolved['service'];
    }

    public function resolveActiveIntegration(int $organizationId): array
    {
        $integration = $this->repository->findActiveByCompany($organizationId);

        if ($integration === null) {
            throw new RuntimeException(
                'La empresa no tiene una integración de WhatsApp activa.'
            );
        }

        $credentials = $this->decryptCredentials(
            $integration['credenciales']
        );

        $configuration = array_merge(
            $integration['configuracion'],
            $credentials
        );

        $provider = $this->providerFactory->create(
            $integration['proveedor'],
            $configuration
        );

        return [
            'integration' => $integration,
            'service' => new WhatsAppService($provider),
        ];
    }

    private function encryptCredentials(
        array $credentials
    ): string {
        try {
            $json = json_encode(
                $credentials,
                JSON_THROW_ON_ERROR |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            );
        } catch (JsonException $exception) {
            throw new RuntimeException(
                'No fue posible preparar las credenciales.',
                previous: $exception
            );
        }

        return $this->encryption->encryptString(
            $json,
            self::ENCRYPTION_CONTEXT
        );
    }

    private function decryptCredentials(
        string $encryptedCredentials
    ): array {
        $json = $this->encryption->decryptString(
            $encryptedCredentials,
            self::ENCRYPTION_CONTEXT
        );

        try {
            $credentials = json_decode(
                $json,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new RuntimeException(
                'Las credenciales descifradas no son válidas.',
                previous: $exception
            );
        }

        if (!is_array($credentials)) {
            throw new RuntimeException(
                'Las credenciales de WhatsApp no son válidas.'
            );
        }

        return $credentials;
    }
}