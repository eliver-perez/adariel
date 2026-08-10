<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\CashRegisterRepository;
use App\Repositories\SettingsRepository;
use App\Services\CashRegisterService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class CashRegisterController extends Controller
{
    private ?CashRegisterRepository $repository = null;

    private function getService(): CashRegisterService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $cashRegisterRepository = new CashRegisterRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new CashRegisterService($cashRegisterRepository, $settingsRepository);
    }

    private function getRepository(): CashRegisterRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new CashRegisterRepository($conn);
        }

        return $this->repository;
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
            $service = $this->getService();

            $cash_registers = $service->getAll([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'cash_registers' => $cash_registers
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