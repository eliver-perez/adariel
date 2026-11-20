<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\SuppliersRepository;
use App\Repositories\MediaRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class SuppliersService extends Service
{
    public function __construct(
        private SuppliersRepository $suppliersRepository,
        private MediaRepository $mediaRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            $suppliers_data = $this->suppliersRepository->getAll([
                'organization'                      => $organizationId,
                'branch'                            => $branchId,
                'search'                            => $data['search'] !== '' ? $data['search'] : null,
                'limit'                             => $data['limit'],
                'offset'                            => $data['offset'],
                'uid'                               => $data['uid'],
            ]);

            $suppliers = array();
            foreach($suppliers_data as $p) {
                array_push($suppliers, array(
                    'id'                            => $this->uuidBinarytoString($p['uuid']),
                    'code'                          => $p['clave'],
                    'rfc'                           => $p['rfc'],
                    'organization'                  => $p['razon_social'],
                    'address'                       => $p['domicilio'],
                    'phone'                         => $p['telefono'],
                    'mobile_phone'                  => $p['movil'],
                    'email'                         => $p['email'],
                    'trade_payables'                => $p['adeudos'],
                    'registered_by'                 => $p['registro'],
                    'registered_date'               => $datetimeService->fromUtcFormatted($p['f_registro']),
                ));
            }

            return $suppliers;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}