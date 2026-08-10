<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ConsultationsRepository;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\DiagnosticsRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\FoliosRepository;
use App\Repositories\OrganizationsRepository;
use App\Repositories\SettingsRepository;
use App\Services\ConsultationsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ConsultationsController extends Controller
{
    private ?ConsultationsRepository $repository = null;

    private function getService(): ConsultationsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $consultationsRepository = new ConsultationsRepository($conn);
        $salesRepository = new SalesRepository($conn);
        $salesStatusRepository = new SalesStatusRepository($conn);
        $appointmentsRepository = new AppointmentsRepository($conn);
        $appointmentsStatusRepository = new AppointmentsStatusRepository($conn);
        $diagnosticsRepository = new DiagnosticsRepository($conn);
        $proceduresRepository = new ProceduresRepository($conn);
        $patientsRepository = new PatientsRepository($conn);
        $genderRepository = new GenderRepository($conn);
        $locationRepository = new LocationRepository($conn);
        $foliosRepository = new FoliosRepository($conn);
        $organizationsRepository = new OrganizationsRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new ConsultationsService($consultationsRepository,
                                        $salesRepository,
                                        $salesStatusRepository,
                                        $appointmentsRepository,
                                        $appointmentsStatusRepository,
                                        $diagnosticsRepository,
                                        $proceduresRepository,
                                        $patientsRepository,
                                        $genderRepository,
                                        $locationRepository,
                                        $foliosRepository,
                                        $organizationsRepository,
                                        $settingsRepository);
    }

    private function getRepository(): ConsultationsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new ConsultationsRepository($conn);
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
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }

            $service = $this->getService();

            $search = trim((string)$this->request->query('search', ''));
            
            $appointment_status = trim((string)$this->request->query('appointment_status', ''));

            $limit = (int)$this->request->query('limit', 10);
            $offset = (int)$this->request->query('offset', 0);

            $limit = max(1, min($limit, 50));
            $offset = max(0, $offset);

            $data = $service->getAll([
                'organizationId'        => $organizationId,
                'branchId'              => $organizationBranchId,
                'status'                => $appointment_status,
                'search'                => $search !== '' ? $search : null,
                'limit'                 => $limit,
                'offset'                => $offset,
                'uid'                   => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'consultations' => $data
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
                // 'message' => 'No fue posible obtener los datos de las consultas.'
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
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }

            $service = $this->getService();

            $consultation = $service->getConsultation([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
                'start'                         => 0
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Consulta.',
                'data' => $consultation
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

    public function view(string $id, int $start = 0) {
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

            $consultation = $service->getConsultation([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
                'start'                         => $start
            ]);

            return json_encode([
                'success' => true,
                'data' => $consultation
            ]);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update_consultation_observations(Request $request, Response $response, string $id) {
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

            $observations = $service->updateConsultationInitialObservations([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'observations'                  => $request->input('observations'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Observaciones Actualizados.',
                'data' => $observations
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

    public function update_consultation_podiatric_exploration(Request $request, Response $response, string $id) {
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

            $observations = $service->updateConsultationPodiatricExploration([
                'uuid'                          => $id,
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'foot_type'                     => $request->input('foot_type'),
                'metatarsal_formula'            => $request->input('metatarsal_formula'),
                'gait_disorder'                 => $request->input('gait_disorder'),
                'left_pulse_type'               => $request->input('left_pulse_type'),
                'right_pulse_type'              => $request->input('right_pulse_type'),
                'left_sensitivity_type'         => $request->input('left_sensitivity_type'),
                'right_sensitivity_type'        => $request->input('right_sensitivity_type'),
                'temperature_type'              => $request->input('temperature_type'),
                'foot_color_type'               => $request->input('foot_color_type'),
                'observations'                  => $request->input('observations'),
                'advice'                        => $request->input('advice'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Exploración Podológica Actualizados.',
                'data' => $observations
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

    public function get_consultation_procedures(Request $request, Response $response, string $uuid) {
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

            $procedures = $service->getConsultationProcedures([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $uuid,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'procedures' => $procedures
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
                // 'message' => 'No fue posible obtener los datos de las consultas.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_consultation_podiatric_exploration(Request $request, Response $response, string $uuid) {
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

            $podiatricExploration = $service->getConsultationPodiatricExploration([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $uuid,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => $podiatricExploration
                ], 200);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                // 'message' => 'No fue posible obtener los datos de las consultas.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_procedure_data(Request $request, Response $response, string $consultation_id, string $procedure_id) {
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

            $procedure = $service->getProcedureData([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $consultation_id,
                'procedure'                     => $procedure_id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Procedimiento.',
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

    public function update_consultation_procedures(Request $request, Response $response, string $id) {
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

            $procedure = $service->updateConsultationsProcedures([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'procedures'                    => $request->input('procedures'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Procedimiento Actualizados.',
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

    public function update_consultation_evidence_upload(Request $request, Response $response, string $id) {
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

            $evidence = $service->updateConsultationEvidenceUpload([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'evidence'                      => $request->file('evidence'),
                'type'                          => $request->input('type'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Evidencia Actualizados.',
                'data' => $evidence
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

    public function get_consultation_diagnostics(Request $request, Response $response, string $uuid) {
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

            $diagnostics = $service->getConsultationDiagnostics([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $uuid,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => $diagnostics
                ], 200);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                // 'message' => 'No fue posible obtener los datos de las consultas.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_diagnostic_data(Request $request, Response $response, string $consultation_id, string $diagnostic_id) {
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

            $diagnostic = $service->getDiagnosticData([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $consultation_id,
                'diagnostic'                    => $diagnostic_id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Diagnostico.',
                'data' => $diagnostic
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

    public function update_consultation_diagnostics(Request $request, Response $response, string $id) {
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

            $diagnostics = $service->updateConsultationsDiagnostics([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'diagnostics'                   => $request->input('diagnostics'),
                'observations'                  => $request->input('observations'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Diagnosticos Actualizados.',
                'data' => $diagnostics
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

    public function get_consultation_sores(Request $request, Response $response, string $uuid) {
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

            $sores = $service->getConsultationSores([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $uuid,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'sores' => $sores
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
                // 'message' => 'No fue posible obtener los datos de las consultas.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update_consultation_sores(Request $request, Response $response, string $id) {
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

            $sores = $service->updateConsultationsSores([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'sores'                         => $request->input('sores'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Lesiones Actualizados.',
                'data' => $sores
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

    public function update_consultation_indications(Request $request, Response $response, string $id) {
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

            $indications = $service->updateConsultationIndications([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'indications'                   => $request->input('indications'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Indicaciones Actualizados.',
                'data' => $indications
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

    public function finish_consultation(Request $request, Response $response, string $id) {
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

            $finish = $service->finishConsultation([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Consulta finalizada con éxito.',
                'data' => $finish
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