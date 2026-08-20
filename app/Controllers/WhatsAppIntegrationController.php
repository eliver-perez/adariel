<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Core\Security\EncryptionService;
use App\Repositories\WhatsAppIntegrationRepository;
use App\Services\WhatsApp\Factories\WhatsAppProviderFactory;
use App\Services\WhatsApp\WhatsAppIntegrationService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class WhatsAppIntegrationController
{
    private ?WhatsAppIntegrationService $service = null;

    private function getService(): WhatsAppIntegrationService
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

        $repository = new WhatsAppIntegrationRepository(
            $connection
        );

        $encryption = new EncryptionService(
            $encryptionKey
        );

        $providerFactory = new WhatsAppProviderFactory();

        $this->service = new WhatsAppIntegrationService(
            repository: $repository,
            encryption: $encryption,
            providerFactory: $providerFactory
        );

        return $this->service;
    }

    public function testConnection(
        Request $request,
        Response $response
    ): void {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $result = $service->testConnection([
                'organizationId'                        => $organizationId,
                'branchId'                              => $organizationBranchId,
                'uid'                                   => $currentUserId
            ]);

            if (!$result->success) {
                $response->json([
                    'success' => false,
                    'message' => $result->error
                        ?? 'No fue posible establecer conexión con WhatsApp.',
                    'data' => [
                        'status_code' => $result->statusCode,
                    ],
                ], 422);

                return;
            }

            $response->json([
                'success' => true,
                'message' => 'La conexión con WhatsApp se estableció correctamente.',
                'data' => [
                    'phone_number_id' => $result->response['id'] ?? null,
                    'phone_number' => $result->response['display_phone_number'] ?? null,
                    'verified_name' => $result->response['verified_name'] ?? null,
                ],
            ]);
        } catch (Throwable $exception) {
            $response->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, Response $response): void {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            die('');
            $integration = $service->findForSettings([
                'organizationId'                    => $organizationId,
                'branchId'                          => $organizationBranchId,
                'uid'                               => $currentUserId
            ]);

            $response->json([
                'success' => true,
                'data' => $integration,
            ]);
        } catch (Throwable $exception) {
            $response->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function save(Request $request, Response $response): void {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $data = $request->input();

            $provider = strtolower(
                trim((string) ($data['provider'] ?? ''))
            );

            if ($provider === '') {
                throw new InvalidArgumentException(
                    'Debes seleccionar un proveedor de WhatsApp.'
                );
            }

            $integrationId = $service->saveConfiguration([
                'organizationId'                    => $organizationId,
                'provider'                          => $provider,
                'data'                              => $data,
                'uid'                               => $currentUserId
            ]);

            $response->json([
                'success' => true,
                'message' => 'La integración de WhatsApp fue guardada correctamente.',
                'data' => [
                    'id' => $integrationId,
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
}