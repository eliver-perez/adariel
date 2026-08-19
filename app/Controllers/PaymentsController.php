<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\PaymentsRepository;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\CashReconciliationStatusRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\SettingsRepository;
use App\Services\PaymentsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class PaymentsController extends Controller
{
    private ?CashReconciliationRepository $repository = null;

    private function getService(): PaymentsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $paymentsRepository = new PaymentsRepository($conn);
        $cashReconciliationRepository = new CashReconciliationRepository($conn);
        $cashReconciliationStatusRepository = new CashReconciliationStatusRepository($conn);
        $cashRegisterRepository = new CashRegisterRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new PaymentsService($paymentsRepository,
                                            $cashReconciliationRepository,
                                            $cashReconciliationStatusRepository,
                                            $cashRegisterRepository,
                                            $settingsRepository);
    }

    private function getRepository(): PaymentsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new PaymentsRepository($conn);
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
            $status = (int)$this->request->query('status', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'searchBy'                  => 'branch',
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
                        'payments' => $data
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

            $search = trim((string)$this->request->query('search', ''));

            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $payment = $service->getPayment([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'uuid'                      => $id,
                'search'                    => $search !== '' ? $search : null,
                'limit'                     => $limit,
                'offset'                    => $offset,
                'timezone'                  => $currentTimezone ?? env('TIMEZONE'),
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Pago.',
                'data' => $payment
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

    public function view($id) {
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

            $payment = $service->getPaymentReceipt([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'uuid'                      => $id,
                'timezone'                  => $currentTimezone ?? env('TIMEZONE'),
                'uid'                       => $currentUserId,
            ]);

            return json_encode([
                'success' => true,
                'message' => 'Datos de Recibo de Pago',
                'data' => $payment
            ]);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}