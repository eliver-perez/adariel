<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsTypesRepository;
use App\Repositories\BookingChannelsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\SettingsRepository;
use App\Services\SalesService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SalesController extends Controller
{
    private ?SalesRepository $repository = null;

    private function getService(): SalesService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $salesRepository = new SalesRepository($conn);
        $salesStatusRepository = new SalesStatusRepository($conn);
        $appointmentsRepository = new AppointmentsRepository($conn);
        $appointmentsTypeRepository = new AppointmentsTypesRepository($conn);
        $bookingChannelsRepository = new BookingChannelsRepository($conn);
        $appointmentsStatusRepository = new AppointmentsStatusRepository($conn);
        $patientsRepository = new PatientsRepository($conn);
        $staffRepository = new StaffRepository($conn);
        $proceduresRepository = new ProceduresRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new SalesService($salesRepository, $salesStatusRepository, $appointmentsRepository, $appointmentsTypeRepository, $bookingChannelsRepository, $appointmentsStatusRepository, $patientsRepository, $staffRepository, $proceduresRepository, $settingsRepository);
    }

    private function getRepository(): SalesRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new SalesRepository($conn);
        }

        return $this->repository;
    }

    public function indexBranch(Request $request, Response $response) {
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

            $status = trim((string)$this->request->query('status', ''));

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'searchBy'                          => 'branch',
                'organizationId'                    => $organizationId,
                'branchId'                          => $organizationBranchId,
                'search'                            => $search !== '' ? $search : null,
                'limit'                             => $limit,
                'offset'                            => $offset,
                'status'                            => $status,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'sales' => $data
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

    public function show(Request $request, Response $response, string $id) {
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

            $sale = $service->getSale([
                'organizationId'                    => $organizationId,
                'branchId'                          => $organizationBranchId,
                'uuid'                              => $id,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Venta.',
                'data' => $sale
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