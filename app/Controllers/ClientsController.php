<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ClientsRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\BillingRepository;
use App\Repositories\SettingsRepository;
use App\Services\ClientsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ClientsController extends Controller
{
    private ?ClientsRepository $repository = null;

    private function getService(): ClientsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $clientsRepository = new ClientsRepository($conn);
        $genderRepository = new GenderRepository($conn);
        $locationRepository = new LocationRepository($conn);
        $billingRepository = new BillingRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new ClientsService($clientsRepository, $genderRepository, $locationRepository, $billingRepository, $settingsRepository);
    }

    private function getRepository(): ClientsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new ClientsRepository($conn);
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
                throw new RuntimeException("No autenticado.");
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
                        'clients' => $data
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

    public function store(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No autenticado.");
            }

            $service = $this->getService();

            $client = $service->create([
                'organizationId'            => $organizationId,
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

                'general_observations'      => $request->input('observaciones'),
                'current_medications'       => $request->input('medicamentos'),
                'supplements'               => $request->input('suplementos'),
                'family_medical_history'    => $request->input('antecedentes_familiares'),
                
                'add_billing'               => $request->input('agregar_facturacion'),
                'billing_rfc'               => $request->input('facturacion_rfc'),
                'billing_name'              => $request->input('facturacion_razon_social'),
                'billing_regimen'           => $request->input('facturacion_regimen'),
                'billing_zip_code'          => $request->input('facturacion_codigo_postal'),
                'billing_street'            => $request->input('facturacion_calle'),
                'billing_ext_no'            => $request->input('facturacion_no_exterior'),
                'billing_int_no'            => $request->input('facturacion_no_interior'),
                'billing_locality'          => $request->input('facturacion_colonia'),
                'billing_email'             => $request->input('facturacion_email'),
                'billing_phone'             => $request->input('facturacion_telefono'),

                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'status' => 'OK',
                'message' => 'Cliente registrado correctamente.',
                'data' => [
                    'id' => $client['uuid'],
                ]
            ], 201);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'status' => 'ERROR',
                // 'message' => 'No fue posible registrar el personal.'
                'message' => $e->getMessage()
            ], 500);
        }
    }
}