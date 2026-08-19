<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ProductsRepository;
use App\Services\ProductsService;
use Throwable;
use InvalidArgumentException;
use RuntimeException;

class ProductsController extends Controller
{
    private ?ProductsRepository $repository = null;

    private function getService(): ProductsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $productsRepository = new ProductsRepository($conn);

        return new ProductsService($productsRepository);
    }

    private function getRepository(): ProductsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new ProductsRepository($conn);
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
            
            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'organizationId'                    => $organizationId,
                'search'                            => $search !== '' ? $search : null,
                'limit'                             => $limit,
                'offset'                            => $offset,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'products' => $data
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
            $currentTimezone = Auth::organizationBranchTimeZone();
            if($currentTimezone === null) {
                $currentTimezone = Auth::organizationTimeZone();
            }
            $service = $this->getService();

            $product = $service->getProduct([
                'uuid'                          => $id,
                'organizationId'                => $organizationId,
                'timezone'                      => $currentTimezone ?? env('TIMEZONE'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Producto.',
                'data' => $product
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

    public function categories(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $service = $this->getService();

            $categories = $service->getCategories([
                'organizationId'                        => $organizationId,
                'uid'                                   => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'categories' => $categories
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
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
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $service = $this->getService();

            $product = $service->create([
                'organizationId'            => $organizationId,
                'code'                      => $request->input('code'),
                'bar_code'                  => $request->input('bar_code'),
                'category'                  => $request->input('category'),
                'product'                   => $request->input('product'),
                'description'               => $request->input('description'),
                'unit_measure'              => $request->input('unit_measure'),
                'base_cost'                 => $request->input('base_cost'),
                'tax_rate'                  => $request->input('tax_rate'),
                'taxes'                     => $request->input('taxes'),
                'total_cost'                => $request->input('total_cost'),
                'enable_sale'               => $request->input('enable_sale'),
                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Producto registrado correctamente.',
                'data' => [
                    'product_id' => $product
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