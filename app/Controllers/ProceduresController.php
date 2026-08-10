<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ProceduresRepository;
use App\Repositories\StaffRepository;
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
        $staffRepository = new StaffRepository($conn);

        return new ProceduresService($proceduresRepository,
                                        $staffRepository);
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
                'success' => true,
                'data' => [
                    'procedures' => $procedures
                ]
            ], 200);
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

    public function show(Request $request, Response $response, string $id) {
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

            $procedure = $service->getProcedureData([
                'uuid'                      => $id,
                'organizationId'            => $organizationId,
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Servicio/Procedimiento.',
                'data' => $procedure
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

    public function update(Request $request, Response $response, string $id) {
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

            $service->update([
                'uuid'                      => $id,
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
                'message' => 'Servicio/Procedimiento actualizado correctamente.',
                'data' => []
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
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron datos para la empresa.");
            }
            $service = $this->getService();

            if(!$procedure)
                throw new InvalidArgumentException('No se recibio procedimiento');

            $staff = $service->getProcedureStaff([
                'organizationId'                        => $organizationId,
                'procedure'                             => $procedure,
                'uid'                                   => $currentUserId,
            ]);

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
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron datos para la empresa.");
            }
            $service = $this->getService();

            if(!$procedure)
                throw new InvalidArgumentException('No se recibio procedimiento');

            if(!$staff)
                throw new InvalidArgumentException('No se recibio id de personal');

            $data = $service->getProcedureStaffData([
                'organizationId'                        => $organizationId,
                'procedure'                             => $procedure,
                'staff'                                 => $staff,
                'uid'                                   => $currentUserId
            ]);

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

    public function insertProcedureStaff(Request $request, Response $response, string $procedure, string $staff) {
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

            $procedure = $service->insertProcedureStaff([
                'procedure'                 => $procedure,
                'staff'                     => $staff,
                'cost'                      => $request->input('cost'),

                'organizationId'            => $organizationId,
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos registrados con exito',
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
}