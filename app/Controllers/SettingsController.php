<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\SettingsRepository;
use App\Repositories\MediaRepository;
use App\Repositories\OrganizationsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SettingsController extends Controller
{
    private ?SettingsRepository $repository = null;

    private function getService(): SettingsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $settingsRepository = new SettingsRepository($conn);
        $mediaRepository = new MediaRepository($conn);
        $organizationsRepository = new OrganizationsRepository($conn);

        return new SettingsService($settingsRepository,
                                        $mediaRepository,
                                        $organizationsRepository);
    }

    private function getRepository(): OrganizationsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new OrganizationsRepository($conn);
        }

        return $this->repository;
    }

    public function regimenes(Request $request, Response $response)
    {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            $repository = new SettingsRepository($conn);
            $regimenes = $repository->getRegimenes();

            return $response->json([
                'status' => 'OK',
                'data' => [
                    'regimenes' => $regimenes
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => 'No fue posible obtener los regimenes.'
            ], 500);
        }
    }

    public function updateLogo(Request $request, Response $response, string $id) {
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

            $logo_response = $service->updateLogo([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'logo'                          => $request->file('logo'),
                'type'                          => $request->input('type'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Logo actualizado',
                'data' => $logo_response
            ], 200);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}