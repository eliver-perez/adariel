<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\ProceduresRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class ProceduresService extends Service
{
    public function __construct(
        private ProceduresRepository $proceduresRepository
    ) {
    }

    public function getAll(array $data): array {

        try {
            $procedures_data = $this->proceduresRepository->getAll([
                'organization'                  => $data['organization'],
                'uid'                           => $data['uid']
            ]);
            $procedures = array();

            foreach($procedures_data as $d) {
                array_push($procedures, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'procedure'                 => $d['servicio'],
                    'duration'                  => $d['duracion_min'],
                    'base_cost'                 => $d['costo_base'],
                    'material_required'         => $d['requiere_material'],
                    'is_procedure'              => $d['es_procedimiento'],
                    'active'                    => $d['activo'],
                ));
            }

            return $procedures;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getProcedureStaff($procedure): array {

        try {
            $procedureUuid = $this->uuidStringToBinary($procedure);

            $data = $this->proceduresRepository->getProcedureStaff($procedureUuid);
            $procedures = array();

            foreach($data as $d) {
                array_push($procedures, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'name'                      => $d['nombre'],
                    'duration'                  => $d['duracion_min'],
                    'cost'                      => $d['costo'],
                ));
            }

            return $procedures;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getProcedureStaffData($procedure, $staff): array {

        try {
            $procedureUuid = $this->uuidStringToBinary($procedure);
            $staffUuid = $this->uuidStringToBinary($staff);

            $data = $this->proceduresRepository->getProcedureStaffData($procedureUuid, $staffUuid);

            $staff = array('id'                     => $data['id'],
                            'procedimiento_id'      => $this->uuidBinaryToString($data['procedimiento_uuid']),
                            'procedimiento'         => $data['procedimiento'],
                            'personal_id'           => $this->uuidBinaryToString($data['personal_uuid']),
                            'nombre'                => $data['nombre'],
                            'duracion'              => $data['duracion_min'],
                            'costo'                 => $data['costo']);

            return $staff;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): ?array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

        $procedure = $this->normalizeRequiredText(
            $data['procedure'] ?? null,
            'El nombre del servicio/procedimiento es obligatorio.'
        );

        $duration = $this->normalizeRequiredInt(
            $data['duration'] ?? null,
            'La duración es obligatoria.'
        );

        $base_cost = $this->normalizeRequiredFloat(
            $data['base_cost'] ?? null,
            'El costo es obligatorio.'
        );

        $description = $this->normalizeOptionalText($data['description'] ?? null);
        $requires_material = $this->normalizeOptionalInt($data['requires_material'] ?? 0, true);
        $is_procedure = $this->normalizeOptionalInt($data['is_procedure'] ?? 0, true);
        $is_active = $this->normalizeOptionalInt($data['is_active'] ?? 0, true);

        try {
            $procedureUuid = $this->generateUuidBinary();
            $procedureId = $this->proceduresRepository->insertProcedure([
                'uuid'                                  => $procedureUuid,
                'procedure'                             => $procedure,
                'description'                           => $description,
                'duration'                              => $duration,
                'base_cost'                             => $base_cost,
                'requires_material'                     => $requires_material,
                'is_procedure'                          => $is_procedure,
                'is_active'                             => $is_active,
                'organization'                          => $organizationId,
                'uid'                                   => $uid,
            ]);

            return [
                'uuid' => $this->uuidBinaryToString($procedureUuid),
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}