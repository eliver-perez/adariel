<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\UserRoleRepository;
use App\Repositories\UsersRepository;
use App\Services\UserRoleService;
use Throwable;

class UserRoleController extends Controller
{
    private ?UserRoleRepository $repository = null;

    private function getService(): UserRoleService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $userRoleRepository = new UserRoleRepository($conn);
        $usersRepository = new UsersRepository($conn);

        return new UserRoleService($userRoleRepository,
                                    $usersRepository);
    }

    private function getRepository(): UserRoleRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new UserRoleRepository($conn);
        }

        return $this->repository;
    }

    public function roles(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();
            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $roles = $service->getRoles([
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentTimezone
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'roles' => $roles
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => 'No fue posible obtener los roles.'
            ], 500);
        }
    }

    public function index(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();
            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }
            $database = new Database();
            $conn = $database->getConnection();

            $repository = new UserRoleRepository($conn);
            $roles = $repository->getAll();

            return $response->json([
                'success' => true,
                'data' => [
                    'roles' => $roles
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => 'No fue posible obtener los roles.'
            ], 500);
        }
    }

    public function userTypeRoles(Request $request, Response $response, $id) {
        try {
            $currentUserId = Auth::id();
            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $data = $service->getUserTypeRoles([
                'id'                                => $id,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentTimezone
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'permissions' => $data
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

    public function userRoles(Request $request, Response $response, $id) {
        try {
            $currentUserId = Auth::id();
            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $data = $service->getUserRoles([
                'user'                              => $id,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentTimezone
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'permissions' => $data
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

    public function addUserPermission(Request $request, Response $response, $id) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }

            $service = $this->getService();

            $data = $service->addUserPermission([
                'user'                              => $id,
                'permission'                        => $request->input('permission', ''),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
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

    public function addUserTypePermission(Request $request, Response $response, $id) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new \RuntimeException("No autenticado.");
            }

            $service = $this->getService();

            $data = $service->addUserTypePermission([
                'user_type'                         => $id,
                'permission'                        => $request->input('permission', ''),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
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