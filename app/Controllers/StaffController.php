<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\StaffRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRoleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SpecialtyRepository;
use App\Services\StaffService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class StaffController extends Controller
{
    private ?StaffRepository $repository = null;

    private function getService(): StaffService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $staffRepository = new StaffRepository($conn);
        $scheduleRepository = new ScheduleRepository($conn);
        $scheduleTemplatesRepository = new ScheduleTemplatesRepository($conn);
        $usersRepository = new UsersRepository($conn);
        $genderRepository = new GenderRepository($conn);
        $locationRepository = new LocationRepository($conn);
        $userRoleRepository = new UserRoleRepository($conn);
        $roleRepository = new RoleRepository($conn);
        $specialtyRepository = new SpecialtyRepository($conn);

        return new StaffService($staffRepository,
                                $scheduleRepository,
                                $scheduleTemplatesRepository,
                                $usersRepository,
                                $genderRepository,
                                $locationRepository,
                                $userRoleRepository,
                                $roleRepository,
                                $specialtyRepository);
    }

    private function getRepository(): StaffRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new StaffRepository($conn);
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
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));
            
            $limit = (int)$this->request->query('limit', 50);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'organizationId'                => $organizationId,
                'search'                        => $search !== '' ? $search : null,
                'limit'                         => $limit,
                'offset'                        => $offset,
                'timezone'                      => $currentTimezone ?? env('TIMEZONE'),
                'uid'                           => $currentUserId
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'staff' => $data
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
                // 'message' => 'No fue posible registrar el personal.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, Response $response, string $id): void
    {
        // $result = $auth->handle($request, $response);
        // if ($result !== true) {
        //     return;
        // }

        // if (!ctype_digit($id)) {
        //     $this->error('El identificador es inválido', 422);
        //     return;
        // }

        // $row = $this->staffModel->findById((int)$id);

        // if ($row === null) {
        //     $this->error('Empleado no encontrado', 404);
        //     return;
        // }

        // // $this->success($row, 'Empleado obtenido correctamente');
        // $response->json([
        //     'status' => 'OK',
        //     'id' => (int)$id
        // ], 200);
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

            $staffId = $service->create([
                'organizationId'            => $organizationId,
                'branchId'                  => $organizationBranchId,
                'first_name'                => $request->input('nombre'),
                'last_name'                 => $request->input('paterno'),
                'last_name_2'               => $request->input('materno'),
                'dob'                       => $request->input('fecha_nacimiento'),
                'gender'                    => $request->input('genero'),
                'curp'                      => $request->input('curp'),

                'street'                    => $request->input('calle'),
                'ext_no'                    => $request->input('no_exterior'),
                'int_no'                    => $request->input('no_interior'),
                'locality'                  => $request->input('colonia'),

                'email'                     => $request->input('email'),
                'phone'                     => $request->input('telefono'),
                'mobile'                    => $request->input('telefono_movil'),

                'user_uuid'                 => $request->input('usuario'),

                'role'                      => $request->input('puesto'),

                'cedula'                    => $request->input('cedula'),
                'specialty'                 => $request->input('especialidad'),
                'university'                => $request->input('universidad'),
                'university_grad_year'      => $request->input('universidad_anyo_egreso'),
                'university_municipality'   => $request->input('universidad_municipio'),

                'rfc'                       => $request->input('rfc'),
                'salary'                    => $request->input('salario_mensual'),

                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Personal registrado correctamente.',
                'data' => [
                    'staff_id' => $staffId
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
                // 'message' => 'No fue posible registrar el personal.'
                'message' => $e->getMessage()
            ], 500);
        }
    }
}