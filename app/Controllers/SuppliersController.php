<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\SuppliersRepository;
use App\Repositories\MediaRepository;
use App\Repositories\SettingsRepository;
use App\Services\SuppliersService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SuppliersController extends Controller
{
    private function getService(): SuppliersService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $suppliersRepository = new SuppliersRepository($conn);
        $mediaRepository = new MediaRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new SuppliersService($suppliersRepository,
                                            $mediaRepository,
                                            $settingsRepository);
    }

    public function index(Request $request, Response $response) {
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
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));

            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);
            $status = (int)$this->request->query('status', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'search'                    => $search !== '' ? $search : null,
                'limit'                     => $limit,
                'offset'                    => $offset,
                'status'                    => $status,
                'timezone'                  => $currentTimezone ?? env('TIMEZONE'),
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'suppliers' => $data
                    ]
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