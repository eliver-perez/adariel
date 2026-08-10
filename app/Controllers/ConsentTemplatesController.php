<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\ConsentTemplatesRepository;
use App\Repositories\TemplatesStatusRepository;
use App\Repositories\OrganizationsRepository;
use App\Repositories\MediaRepository;
use App\Services\ConsentTemplatesService;
use Throwable;

class ConsentTemplatesController extends Controller
{
    private ?ConsentTemplatesRepository $repository = null;

    private function getService(): ConsentTemplatesService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $consentTemplatesRepository = new ConsentTemplatesRepository($conn);
        $templatesStatusRepository = new TemplatesStatusRepository($conn);
        $organizationsRepository = new OrganizationsRepository($conn);
        $mediaRepository = new MediaRepository($conn);

        return new ConsentTemplatesService($consentTemplatesRepository,
                                            $templatesStatusRepository,
                                            $organizationsRepository,
                                            $mediaRepository);
    }

    private function getRepository(): ConsentTemplatesRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new ConsentTemplatesRepository($conn);
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

            $templates = $service->getAllTemplates([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'templates' => $templates
                ]
            ]);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage(),
                // 'message' => 'No fue posible obtener las plantillas.'
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
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $templateId = $service->create([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'code'                          => $request->input('code'),
                'template_name'                 => $request->input('template_name'),

                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Plantilla registrada correctamente.',
                'data' => [
                    'template_id' => $templateId
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

            $template = $service->getTemplate([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Datos de Plantilla.',
                'data' => $template
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

    public function update(Request $request, Response $response, string $id) {
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

            $templateId = $service->update([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'template_html'                 => $request->input('template_html'),
                'template_delta'                => $request->input('template_delta'),
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Plantilla registrada correctamente.',
                'data' => [
                    'template_id' => $templateId
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
                // 'message' => 'No fue posible actualizar la plantilla.'
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function preview(Request $request, Response $response) {
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

            $pdfContent = $service->previewPdf([
                'appName'                       => env('APP_NAME') ?? 'ERP',
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'delta'                         => $request->input('delta', ''),
                'font_size'                     => $request->input('font_size', 9),
                'line_height'                   => $request->input('line_height', 1.2),

                'logo'                          => $_FILES['logo'] ?? null,
                'logo_width'                    => $request->input('logo_width', 35),
                'uid'                           => $currentUserId
            ]);

            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="preview-consentimiento.pdf"');
            header('Content-Length: ' . strlen($pdfContent));

            echo $pdfContent;
            exit;

        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
                // 'message' => 'Error al generar la vista previa del consentimiento.'
            ], 500);
        }
    }

    public function status(Request $request, Response $response, string $id) {
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

            $data = $service->getTemplateStatus([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Estatus de Plantilla.',
                'data' => [
                    'uuid'                      => $id,
                    'code'                      => $data['codigo'] ?? '',
                    'status'                    => $data['estatus'] ?? '',
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
                'message' => 'Error al generar la vista previa del consentimiento.'
            ], 500);
        }
    }

    public function activate(Request $request, Response $response, string $id) {
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

            $service->activate([
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uuid'                          => $id,
                'uid'                           => $currentUserId,
            ]);

            return $response->json([
                'success' => true,
                'message' => 'Plantilla activada correctamente.',
                'data' => [
                    'uuid'                  => $id
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
                // 'message' => 'No fue posible activar la plantilla.'
                'message' => $e->getMessage()
            ], 500);
        }
    }
}