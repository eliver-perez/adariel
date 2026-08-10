<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsTypesRepository;
use App\Repositories\BookingChannelsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
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
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data, ?string $search = null, int $limit = 10, int $offset = 0): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

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
                    'debt'                      => $d['adeudo'],
                    'registered_by'             => $d['registro'],
                    'status_code'               => $d['estatus_codigo'],
                    'status'                    => $d['estatus'],
                    'sale_date'                 => $d['f_venta'],
                    'registered_date'           => $d['f_registro'],
                    'last_visit_date'           => $d['f_ultima_visita'],
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
                'sale_date'                         => $sale_data['f_venta'],
                'registered_date'                   => $sale_data['f_registro'],
                'update_date'                       => $sale_data['f_actualizacion'],
                'details'                           => $sale_details,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}