<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\CashRegisterRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class CashRegisterService extends Service
{
    public function __construct(
        private CashRegisterRepository $cashRegisterRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data) {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $data = $this->cashRegisterRepository->getAll([
                'organization'                  => $organizationId,
                'branch'                        => $branchId,
            ]);
            $cash_registers = array();

            foreach($data as $d) {
                array_push($cash_registers, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['codigo'],
                    'register'                  => $d['caja'],
                ));
            }

            return $cash_registers;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}