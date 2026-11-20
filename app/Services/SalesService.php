<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsTypesRepository;
use App\Repositories\BookingChannelsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\OrganizationsRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\MediaRepository;
use App\Services\SettingsService;
use App\Pdf\Sale\SalePdfRenderer;
use InvalidArgumentException;
use RuntimeException;

class SalesService extends Service
{
    public function __construct(
        private SalesRepository $salesRepository,
        private SalesStatusRepository $salesStatusRepository,
        private AppointmentsRepository $appointmentsRepository,
        private AppointmentsTypesRepository $appointmentsTypesRepository,
        private BookingChannelsRepository $bookingChannelRepository,
        private AppointmentsStatusRepository $appointmentsStatusRepository,
        private PatientsRepository $patientsRepository,
        private StaffRepository $staffRepository,
        private ProceduresRepository $proceduresRepository,
        private OrganizationsRepository $organizationsRepository,
        private SettingsRepository $settingsRepository,
        private MediaRepository $mediaRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            if($data['status'] != '')
                $status_id = $this->salesStatusRepository->getIdByCode($data['status']);
            else
                $status_id = 0;

            $data = $this->salesRepository->getAll([
                'search_by'                         => $data['searchBy'],
                'organization'                      => $organizationId,
                'branch'                            => $branchId,
                'search'                            => $data['search'] !== '' ? $data['search'] : null,
                'limit'                             => $data['limit'],
                'offset'                            => $data['offset'],
                'status'                            => $status_id,
                'uid'                               => $uid,
            ]);
            $sales = array();

            foreach($data as $d) {
                array_push($sales, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'folio'                     => $d['folio'],
                    'name'                      => $d['nombre'],
                    'subtotal'                  => $d['subtotal'],
                    'taxes'                     => $d['impuestos'],
                    'total'                     => $d['total'],
                    'discount'                  => $d['descuento'],
                    'paid'                      => $d['pagado'],
                    'balance_due'               => $d['adeudo'],
                    'registered_by'             => $d['registro'],
                    'status_code'               => $d['estatus_codigo'],
                    'status'                    => $d['estatus'],
                    'sale_date'                 => $datetimeService->fromUtcFormatted($d['f_venta']),
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                    'last_visit_date'           => $datetimeService->fromUtcFormatted($d['f_ultima_visita']),
                ));
            }

            return $sales;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function getSale($data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador de la venta.'
            );

            $sale_data = $this->salesRepository->getSaleData([
                'uuid'                              => $this->uuidStringToBinary($uuid),
                'branch'                            => $branchId
            ]);

            if(!$sale_data)
                throw new RuntimeException("Ocurrio un error al intentar obtener la información.");
            
            $sale_details_data = $this->salesRepository->getSaleDetails([
                'uuid'                              => $this->uuidStringtoBinary($uuid),
                'branch'                            => $branchId
            ]);
            $sale_details = array();
            foreach($sale_details_data as $psd) {
                array_push($sale_details, array(
                    'id'                                => $this->uuidBinarytoString($psd['uuid']),
                    'sale_folio'                        => $sale_data['folio'],
                    'description'                       => $psd['descripcion'],
                    'quantity'                          => $psd['cantidad'],
                    'base_cost'                         => $psd['precio_base'],
                    'subtotal'                          => $psd['subtotal'],
                    'taxes'                             => $psd['impuestos'],
                    'total'                             => $psd['total'],
                    'discount'                          => $psd['descuento'],
                    'amount_paid'                       => $psd['pagado'],
                    'balance_due'                       => $psd['adeudo'],
                ));
            }
            
            return [
                'id'                                => $this->uuidBinarytoString($sale_data['uuid']),
                'folio'                             => $sale_data['folio'],
                'client'                            => $sale_data['cliente'],
                'patient'                           => $sale_data['paciente'],
                'subtotal'                          => $sale_data['subtotal'],
                'taxes'                             => $sale_data['impuestos'],
                'total'                             => $sale_data['total'],
                'discount'                          => $sale_data['descuento'],
                'amount_paid'                       => $sale_data['pagado'],
                'balance_due'                       => $sale_data['adeudo'],
                'status_code'                       => $sale_data['estatus_codigo'],
                'status'                            => $sale_data['estatus'],
                'observations'                      => $sale_data['observaciones'],
                'sale_date'                         => $datetimeService->fromUtcFormatted($sale_data['f_venta']),
                'registered_date'                   => $datetimeService->fromUtcFormatted($sale_data['f_registro']),
                'update_date'                       => $datetimeService->fromUtcFormatted($sale_data['f_actualizacion']),
                'details'                           => $sale_details,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function cancelSale($data) {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador de la venta.'
            );

            $sale_data = $this->salesRepository->getSaleData([
                'uuid'                              => $this->uuidStringToBinary($uuid),
                'branch'                            => $branchId
            ]);

            if(!$sale_data)
                throw new RuntimeException("No se encontro la venta solicitada.");

            if($sale_data['estatus_codigo'] != 'pendiente')
                throw new RuntimeException("El estatus de la venta no permite continuar con la cancelación.");

            if($sale_data['adeudo'] <= 0)
                throw new RuntimeException("La venta seleccionada no cuenta con adeudos pendientes.");

            $cancelled_status_id = $this->salesStatusRepository->getIdByCode('cancelado');
            
            if(!$this->salesRepository->cancelSale([
                'sale'                          => $this->uuidStringToBinary($uuid),
                'balance_due'                   => $sale_data['adeudo'],
                'status'                        => $cancelled_status_id,
                'uid'                           => $uid
            ]))
                throw new RuntimeException("No se registro la cancelación");

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function previewPdf(array $data): string {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $uuid = $this->normalizeRequiredText($data['uuid'] ?? null, 'No se recibio una venta.');

        $organization_uuid = $this->organizationsRepository->getOrganizationUuid($organizationId);
        $organization_data = $this->organizationsRepository->getOrganizationData($organization_uuid);
        $data['organization'] = $organization_data['empresa'];

        if($branchId != null) {
            $organization_branch_uuid = $this->organizationsRepository->getOrganizationBranchUuid($branchId);
            $organization_branch_data = $this->organizationsRepository->getOrganizationBranchData([
                'organization_uuid'                         => $organization_uuid,
                'branch_uuid'                               => $organization_branch_uuid
            ]);
            $data['branch'] = $organization_branch_data['sucursal'];
        } else {
            $data['branch'] = "";
        }
        $data['logo'] = $this->mediaRepository->findByOrganizationType([
            'organization'                                  => $organizationId,
            'type'                                          => 'ORGANIZATION_LOGO'
        ]);
        $data['font_size'] = 8;
        $data['line_height'] = 1.2;

        $data['sale'] = $this->salesRepository->getSaleData([
            'uuid'                                          => $this->uuidStringToBinary($uuid),
            'branch'                                        => $branchId
        ]);

        if($data['sale'] === null)
            throw new RuntimeException("No se encontro la venta solicitada.");

        $data['sale']['details'] = $this->salesRepository->getSaleDetails([
            'uuid'                                          => $this->uuidStringToBinary($uuid),
            'branch'                                        => $branchId
        ]);

        $renderer = new SalePdfRenderer();

        return $renderer->renderPreview($data);
    }
}