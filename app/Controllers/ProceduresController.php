<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ProceduresRepository;
use App\Services\ProceduresService;
use Throwable;
use InvalidArgumentException;
use RuntimeException;

class ProceduresController extends Controller
{
    private ?ProceduresRepository $repository = null;

    private function getService(): ProceduresService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $proceduresRepository = new ProceduresRepository($conn);

        return new ProceduresService($proceduresRepository);
    }

    private function getRepository(): ProceduresRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new ProceduresRepository($conn);
        }

        return $this->repository;
    }

    public function index(Request $request, Response $response)
    {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("Sin información de empresa.");
            }

            $service = $this->getService();

            $procedures = $service->getAll([
                'organization'  => $organizationId,
                'uid'           => $currentUserId,
            ]);

            return $response->json([
                'status' => 'OK',
                'data' => [
                    'procedures' => $procedures
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
                // 'message' => 'No fue posible obtener los servicios & procedimientos.'
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
                throw new RuntimeException("No se encontraron datos para la empresa.");
            }

            $service = $this->getService();

            $procedure = $service->create([
                'procedure'                 => $request->input('procedure'),
                'description'               => $request->input('description'),
                'duration'                  => $request->input('duration'),
                'base_cost'                 => $request->input('base_cost'),
                'requires_material'         => $request->input('requires_material'),
                'is_procedure'              => $request->input('is_procedure'),
                'is_active'                 => $request->input('is_active'),

                'organizationId'            => $organizationId,
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Servicio/Procedimiento registrado correctamente.',
                'data' => [
                    'id' => $procedure['uuid'],
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

    public function staff(Request $request, Response $response, string $procedure) {
        try {
            $service = $this->getService();

            if(!$procedure)
                throw new InvalidArgumentException('No se recibio procedimiento');

            $staff = $service->getProcedureStaff($procedure);

            return $response->json([
                'status' => 'OK',
                'data' => [
                    'staff' => $staff
                ]
            ]);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function procedureStaffData(Request $request, Response $response, string $procedure, string $staff) {
        try {
            $service = $this->getService();

            if(!$procedure)
                throw new InvalidArgumentException('No se recibio procedimiento');

            if(!$staff)
                throw new InvalidArgumentException('No se recibio id de personal');

            $data = $service->getProcedureStaffData($procedure, $staff);

            return $response->json([
                'status' => 'OK',
                'data' => $data
            ]);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                // 'message' => 'No fue posible obtener datos del servicio/procedimiento.'
            ], 500);
        }
    }
}