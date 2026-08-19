<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\PatientsRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\BillingRepository;
use App\Repositories\SettingsRepository;
use App\Services\PatientsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class PatientsController extends Controller
{
    private ?PatientsRepository $repository = null;

    private function getService(): PatientsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $patientsRepository = new PatientsRepository($conn);
        $scheduleRepository = new ScheduleRepository($conn);
        $scheduleTemplatesRepository = new ScheduleTemplatesRepository($conn);
        $genderRepository = new GenderRepository($conn);
        $locationRepository = new LocationRepository($conn);
        $billingRepository = new BillingRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new PatientsService($patientsRepository,
                                    $scheduleRepository,
                                    $scheduleTemplatesRepository,
                                    $genderRepository,
                                    $locationRepository,
                                    $billingRepository,
                                    $settingsRepository);
    }

    private function getRepository(): PatientsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new PatientsRepository($conn);
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
                throw new RuntimeException("No se encontraron datos para la empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();
            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron datos para la sucursal.");
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
                'organizationBranchId'              => $organizationBranchId,
                'search'                            => $search !== '' ? $search : null,
                'limit'                             => $limit,
                'offset'                            => $offset,
                'timezone'                          => $currentTimezone ?? env('TIMEZONE'),
                'uid'                               => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'patients' => $data
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
                throw new RuntimeException("No se encontraron datos para la empresa.");
            }

            $service = $this->getService();

            $patient = $service->create([
                'organizationId'            => $organizationId,
                'first_name'                => $request->input('nombre'),
                'last_name'                 => $request->input('paterno'),
                'last_name_2'               => $request->input('materno'),
                'dob'                       => $request->input('fecha_nacimiento'),
                'gender'                    => $request->input('genero', 'N'),
                'curp'                      => $request->input('curp', null),

                'street'                    => $request->input('calle', null),
                'ext_no'                    => $request->input('no_exterior', null),
                'int_no'                    => $request->input('no_interior', null),
                'locality'                  => $request->input('colonia', null),

                'email'                     => $request->input('email', null),
                'phone'                     => $request->input('telefono', null),
                'mobile'                    => $request->input('telefono_movil', null),

                'general_observations'      => $request->input('observaciones', null),
                'current_medications'       => $request->input('medicamentos', null),
                'supplements'               => $request->input('suplementos', null),
                'family_medical_history'    => $request->input('antecedentes_familiares', null),
                
                'add_billing'               => $request->input('agregar_facturacion', 'off'),
                'billing_rfc'               => $request->input('facturacion_rfc', null),
                'billing_name'              => $request->input('facturacion_razon_social', null),
                'billing_regimen'           => $request->input('facturacion_regimen', null),
                'billing_zip_code'          => $request->input('facturacion_codigo_postal', null),
                'billing_street'            => $request->input('facturacion_calle', null),
                'billing_ext_no'            => $request->input('facturacion_no_exterior', null),
                'billing_int_no'            => $request->input('facturacion_no_interior', null),
                'billing_locality'          => $request->input('facturacion_colonia', null),
                'billing_email'             => $request->input('facturacion_email', null),
                'billing_phone'             => $request->input('facturacion_telefono', null),

                'uid'                       => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Paciente registrado correctamente.',
                'data' => $patient
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