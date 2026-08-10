<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\CashReconciliationStatusRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\PaymentsRepository;
use App\Repositories\FoliosRepository;
use App\Repositories\SettingsRepository;
use App\Services\CashReconciliationService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class CashReconciliationController extends Controller
{
    private ?CashReconciliationRepository $repository = null;

    private function getService(): CashReconciliationService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $cashReconciliationRepository = new CashReconciliationRepository($conn);
        $cashReconciliationStatusRepository = new CashReconciliationStatusRepository($conn);
        $cashRegisterRepository = new CashRegisterRepository($conn);
        $paymentsRepository = new PaymentsRepository($conn);
        $foliosRepository = new FoliosRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new CashReconciliationService($cashReconciliationRepository,
                                            $cashReconciliationStatusRepository,
                                            $cashRegisterRepository,
                                            $paymentsRepository,
                                            $foliosRepository,
                                            $settingsRepository);
    }

    private function getRepository(): CashReconciliationRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new CashReconciliationRepository($conn);
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
            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));

            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'searchBy'                      => 'branch',
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'search'                        => $search !== '' ? $search : null,
                'limit'                         => $limit,
                'offset'                        => $offset,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'cash_reconciliations' => $data
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
            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));

            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $cash_reconciliation = $service->getCashReconciliation([
                'uuid'                          => $id,
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'search'                        => $search !== '' ? $search : null,
                'limit'                         => $limit,
                'offset'                        => $offset,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Corte.',
                'data' => $cash_reconciliation
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

    public function store(Request $request, Response $response) {
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

            $cash_reconciliation = $service->create([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'initialize_amount'             => $request->input('initialize_amount'),
                'cash_register'                 => $request->input('cash_register'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Corte registrado correctamente.',
                'data' => [
                    'cash_reconciliation_id' => $cash_reconciliation
                ]
            ], 201);
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

    public function close(Request $request, Response $response) {
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

            $cash_reconciliation = $service->close([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'id'                            => $request->input('id'),
                'closing_amount'                => $request->input('closing_amount'),
                'observations'                  => $request->input('observations'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Corte cerrado con éxito.',
                'data' => [
                    'cash_reconciliation_id' => $cash_reconciliation
                ]
            ], 201);
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

    public function activeCashReconciliation() {
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

            $data = $service->checkForActiveCashReconciliation([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uid'                           => $currentUserId,
            ]);

            return json_encode([
                'success' => true,
                'data' => [
                    'id'                                    => $data['id'] ?? '',
                    'opened_by_id'                          => $data['opened_by_id'] ?? '',
                    'opened_by_name'                        => $data['opened_by_name'] ?? '',
                    'opened_date'                           => $data['opened_date'] ?? ''
                ]
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

    public function cashReconciliationClosingData(Request $request, Response $response) {
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
            $data = $service->cashReconciliationClosingData([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Corte.',
                'data' => $data
            ], 201);
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