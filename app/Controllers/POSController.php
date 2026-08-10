<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\POSRepository;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\PaymentsRepository;
use App\Repositories\PaymentsMethodsRepository;
use App\Repositories\ClientsRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\FoliosRepository;
use App\Repositories\SettingsRepository;
use App\Services\POSService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class POSController extends Controller
{
    private ?POSRepository $repository = null;

    private function getService(): POSService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $posRepository = new POSRepository($conn);
        $salesRepository = new SalesRepository($conn);
        $salesStatusRepository = new SalesStatusRepository($conn);
        $paymentsRepository = new PaymentsRepository($conn);
        $paymentsMethodsRepository = new PaymentsMethodsRepository($conn);
        $clientsRepository = new ClientsRepository($conn);
        $patientsRepository = new PatientsRepository($conn);
        $cashReconciliationRepository = new CashReconciliationRepository($conn);
        $productsRepository = new ProductsRepository($conn);
        $foliosRepository = new FoliosRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new POSService($posRepository,
                                $salesRepository,
                                $salesStatusRepository,
                                $paymentsRepository,
                                $paymentsMethodsRepository,
                                $clientsRepository,
                                $patientsRepository,
                                $cashReconciliationRepository,
                                $productsRepository,
                                $foliosRepository,
                                $settingsRepository);
    }

    private function getRepository(): POSRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new POSRepository($conn);
        }

        return $this->repository;
    }

    public function update(Request $request, Response $response) {
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

            $action = trim((string)$this->request->input('action', ''));
            $data = json_decode(trim((string)$this->request->input('data', '')));

            $cart = $service->updateCart([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'action'                    => $action,
                'data'                      => $data,
                'uid'                       => $currentUserId
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'cart' => $cart
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

    public function get_cart(Request $request, Response $response) {
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

            $cart = $service->getCart();

            return $response->json([
                    'success' => true,
                    'data' => [
                        'cart' => $cart
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

    public function checkout(Request $request, Response $response) {
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

            $cart = json_decode(trim((string)$this->request->input('cart', '')));

            $cart = $service->checkout([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'cart'                      => $cart,
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'message' => 'Pago registrado con éxito.',
                    'data' => []
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

    public function empty_cart(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }

            $service = $this->getService();

            $service->createEmptyCart();

            return $response->json([
                    'success' => true,
                    'message' => 'Carrito vaciado con éxito.',
                    'data' => []
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