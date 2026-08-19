<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\RoleRepository;
use Throwable;

class RoleController extends Controller
{
    public function index(Request $request, Response $response)
    {
        try {
            $currentUserId = Auth::id();
            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $database = new Database();
            $conn = $database->getConnection();

            $repository = new RoleRepository($conn);
            $roles = $repository->getAll();

            return $response->json([
                'status' => 'OK',
                'data' => [
                    'roles' => $roles
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => 'No fue posible obtener los roles.'
            ], 500);
        }
    }
}