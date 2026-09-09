<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\PaymentsStatusRepository;
use Throwable;

class PaymentsStatusController extends Controller
{
    public function index(Request $request, Response $response)
    {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            $repository = new PaymentsStatusRepository($conn);
            $payments_status = $repository->getAll();

            return $response->json([
                'status' => 'OK',
                'data' => [
                    'payments_status' => $payments_status
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}