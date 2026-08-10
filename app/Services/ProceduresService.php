<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\ProceduresRepository;
use App\Repositories\StaffRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class ProceduresService extends Service
{
    public function __construct(
        private ProceduresRepository $proceduresRepository,
        private StaffRepository $staffRepository
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

    public function getProcedureStaff(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

            $procedureUuid = $this->uuidStringToBinary($data['procedure']);

            $procedure_data = $this->proceduresRepository->getProcedureStaff([
                'organizationId'                => $organizationId,
                'uuid'                          => $procedureUuid
            ]);
            $procedures = array();

            foreach($procedure_data as $d) {
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

    public function getProcedureStaffData(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

            $procedureUuid = $this->uuidStringToBinary($data['procedure']);
            $staffUuid = $this->uuidStringToBinary($data['staff']);

            $procedure_data = $this->proceduresRepository->getProcedureStaffData([
                'procedure'                     => $procedureUuid,
                'staff'                         => $staffUuid,
                'organization'                  => $organizationId
            ]);

            $staff = array('id'                     => $procedure_data['id'],
                            'procedimiento_id'      => $this->uuidBinaryToString($procedure_data['procedimiento_uuid']),
                            'procedimiento'         => $procedure_data['procedimiento'],
                            'personal_id'           => $this->uuidBinaryToString($procedure_data['personal_uuid']),
                            'nombre'                => $procedure_data['nombre'],
                            'duracion'              => $procedure_data['duracion_min'],
                            'costo'                 => $procedure_data['costo']);

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

    function getProcedureData($data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador del servicio/procedimiento.'
            );

            $procedure_data = $this->proceduresRepository->getProcedureData([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organizationId'                => $organizationId
            ]);

            if(!$procedure_data)
                throw new RuntimeException("No se encontro información.");

            $staff_data = $this->proceduresRepository->getProcedureStaff([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organizationId'                => $organizationId
            ]);
            
            $staff = array();
            foreach($staff_data as $sd) {
                array_push($staff, array(
                    'id'                                => $this->uuidBinarytoString($sd['uuid']),
                    'name'                              => $sd['nombre'],
                    'cost'                              => $sd['costo'],
                    'duration'                          => $sd['duracion_min'],
                ));
            }
            
            return [
                'id'                                => $this->uuidBinarytoString($procedure_data['uuid']),
                'procedure'                         => $procedure_data['servicio'],
                'description'                       => $procedure_data['descripcion'],
                'duration'                          => $procedure_data['duracion_min'],
                'base_cost'                         => $procedure_data['costo_base'],
                'requires_material'                 => $procedure_data['requiere_material'],
                'is_procedure'                      => $procedure_data['es_procedimiento'],
                'is_active'                         => $procedure_data['activo'],
                'staff'                             => $staff
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function update($data): void {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador de servicio/procedimiento.'
            );
            
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

            $procedure_data = $this->proceduresRepository->getProcedureData([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organizationId'                => $organizationId
            ]);

            if(!$procedure_data)
                throw new RuntimeException("No se encontro información de empresa.");
            
            $this->proceduresRepository->updateProcedure([
                'uuid'                                  => $this->uuidStringToBinary($uuid),
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
        } catch(\Throwable $e) {
            throw $e;
        }
    }

    public function insertProcedureStaff(array $data): ?array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

        $procedure = $this->normalizeRequiredText(
            $data['procedure'] ?? null,
            'No se recibio el servicio/procedimiento.'
        );

        $staff = $this->normalizeRequiredText(
            $data['staff'] ?? null,
            'No se recibio la seleccion del personal.'
        );

        $cost = $this->normalizeRequiredFloat(
            $data['cost'] ?? null,
            'El costo es obligatorio.'
        );

        $procedure_data = $this->proceduresRepository->getProcedureData([
            'uuid'                          => $this->uuidStringtoBinary($procedure),
            'organizationId'                => $organizationId
        ]);

        if(!$procedure_data)
            throw new RuntimeException("No se encontro información del procedimiento.");

        $staffId = $this->staffRepository->getStaffId($this->uuidStringToBinary($staff));
        $procedureId = $this->proceduresRepository->getProcedureId($this->uuidStringToBinary($procedure));

        try {
            $procedureStaffUuid = $this->generateUuidBinary();
            $procedureId = $this->proceduresRepository->insertProcedureStaff([
                'uuid'                                  => $procedureStaffUuid,
                'procedure'                             => $procedureId,
                'staff'                                 => $staffId,
                'cost'                                  => $cost,
            ]);

            return [
                'uuid' => $this->uuidBinaryToString($procedureStaffUuid),
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}