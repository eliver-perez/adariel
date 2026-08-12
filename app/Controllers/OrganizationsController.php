<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\OrganizationsRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\UsersTypesRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\BillingRepository;
use App\Repositories\SettingsRepository;
use App\Services\OrganizationsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class OrganizationsController extends Controller
{
    private ?OrganizationsRepository $repository = null;

    private function getService(): OrganizationsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $organizationsRepository = new OrganizationsRepository($conn);
        $scheduleTemplatesRepository = new ScheduleTemplatesRepository($conn);
        $usersRepository = new UsersRepository($conn);
        $usersTypesRepository = new UsersTypesRepository($conn);
        $genderRepository = new GenderRepository($conn);
        $locationRepository = new LocationRepository($conn);
        $billingRepository = new BillingRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new OrganizationsService($organizationsRepository,
                                        $scheduleTemplatesRepository,
                                        $usersRepository,
                                        $usersTypesRepository,
                                        $genderRepository,
                                        $locationRepository,
                                        $billingRepository,
                                        $settingsRepository);
    }

    private function getRepository(): OrganizationsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new OrganizationsRepository($conn);
        }

        return $this->repository;
    }

    public function index(Request $request, Response $response) {
        try {
            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));
            
            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 5000000));
            $offset = max(0, $offset);

            $data = $service->getAll($search !== '' ? $search : null,
                $limit,
                $offset
            );

            return $response->json([
                    'success' => true,
                    'data' => [
                        'organizations' => $data
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
                'message' => 'No fue posible obtener los paciente.'
                // 'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }

            $service = $this->getService();

            $organization = $service->create([
                'organization'              => $request->input('organization'),
                'street'                    => $request->input('street'),
                'ext_no'                    => $request->input('ext_no'),
                'int_no'                    => $request->input('int_no'),
                'locality'                  => $request->input('locality'),
                'zip_code'                  => $request->input('zip_code'),

                'phone'                     => $request->input('phone'),
                'mobile'                    => $request->input('mobile'),
                'email'                     => $request->input('email'),
                'password'                     => $request->input('password'),

                'manager'                   => $request->input('manager'),

                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Empresa registrada correctamente.',
                'data' => [
                    'id' => $organization['uuid'],
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

            $service = $this->getService();

            $organization = $service->getOrganizationData([
                'uuid'                      => $id,
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Empresa.',
                'data' => $organization
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

    public function indexBranches(Request $request, Response $response, string $id) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();
            $service = $this->getService();

            $branches = $service->getOrganizationBranches([
                'uuid'                                  => $id,
                'organizationId'                        => $organizationId,
                'uid'                                   => $currentUserId
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'branches' => $branches
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

    public function myUsers(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }

            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("Sin datos de empresa registrada.");
            }
            $active = (int)$this->request->query('active', 1);

            $service = $this->getService();

            $data = $service->getMyUsers([
                'organizationId'                => $organizationId,
                'active'                        => $active,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'users' => $data
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
                'message' => 'No fue posible obtener los paciente.'
                // 'message' => $e->getMessage()
            ], 500);
        }
    }
}