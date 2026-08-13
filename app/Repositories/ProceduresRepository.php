<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ProceduresRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(array $data): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.id,
                s.uuid,
                s.servicio,
                s.duracion_min,
                s.costo_base,
                s.requiere_material,
                s.es_procedimiento,
                s.activo
            FROM servicios s
            WHERE s.empresa = :empresa
            ORDER BY s.id ASC
        ");
        $stmt->bindParam(':empresa', $data['organization']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM servicios
            WHERE uuid = :uuid
                AND empresa = :empresa
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getProcedureEnabledModules(array $data): array {
        $stmt = $this->db->prepare("
           SELECT cm.uuid,
                cm.codigo,
                cm.nombre,
                cm.descripcion,
                cm.orden_default
				FROM servicios_consulta_modulos scm
					INNER JOIN servicios s
						ON scm.servicio = s.id
                    INNER JOIN consultas_modulos cm
                        ON scm.modulo = cm.id
				WHERE s.uuid = :uuid
                    AND scm.activo = 1
                    AND s.empresa = :empresa
                ORDER BY scm.orden
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationProcedureModules(array $data): array {
        $stmt = $this->db->prepare("
           SELECT cm.uuid,
                cm.codigo,
                cm.nombre,
                cm.descripcion,
                cm.orden_default,
                scm.obligatorio
				FROM consultas c
                    INNER JOIN consultas_procedimientos cp
                        ON cp.consulta = c.id
					INNER JOIN servicios s
						ON cp.servicio = s.id
                    INNER JOIN servicios_consulta_modulos scm
                        ON scm.servicio = s.id
                    INNER JOIN consultas_modulos cm
                        ON scm.modulo = cm.id
				WHERE c.uuid = :uuid
                    AND c.sucursal = :sucursal
                ORDER BY scm.orden
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureStaff(array $data): array {
        $stmt = $this->db->prepare("
           SELECT p.id,
                p.uuid,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) nombre,
				ps.costo,
				s.duracion_min
				FROM servicios s
					INNER JOIN personal_servicios ps
						ON s.id = ps.servicio
					INNER JOIN personal p
						ON ps.personal = p.id
				WHERE s.uuid = :uuid
                    AND s.empresa = :empresa
        ");

        $stmt->bindValue(':empresa', $data['organizationId'], PDO::PARAM_LOB);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureStaffData(array $data): ?array {
        $stmt = $this->db->prepare("
           SELECT ps.id,
                    p.id personal_id,
                    s.uuid procedimiento_uuid,
                    p.uuid personal_uuid,
                    TRIM(
                        CONCAT(
                            p.nombre, ' ',
                            COALESCE(p.paterno, ''), ' ',
                            COALESCE(p.materno, '')
                        )
                    ) nombre,
                    s.id procedimiento_id,
                    s.servicio procedimiento,
                    ps.costo,
                    s.duracion_min
                    FROM servicios s
                        INNER JOIN personal_servicios ps
                            ON s.id = ps.servicio
                        INNER JOIN personal p
                            ON ps.personal = p.id
                    WHERE s.uuid = :procedure
                        AND p.uuid = :staff
                        AND ps.f_baja IS NULL
                        AND s.empresa = :empresa
                    ORDER BY ps.f_registro DESC
                    LIMIT 1
        ");

        $stmt->bindValue(':procedure', $data['procedure'], PDO::PARAM_LOB);
        $stmt->bindValue(':staff', $data['staff'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsById(string $id): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM servicios
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }
    
    public function insertProcedure(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO servicios (
                uuid,
                empresa,
                servicio,
                descripcion,
                duracion_min,
                costo_base,
                requiere_material,
                es_procedimiento,
                registro,
                activo,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :servicio,
                :descripcion,
                :duracion_min,
                :costo_base,
                :requiere_material,
                :es_procedimiento,
                :registro,
                :activo,
                NOW()
            )
        ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_STR);
        $stmt->bindParam(':servicio', $data['procedure'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':duracion_min', $data['duration'], PDO::PARAM_STR);
        $stmt->bindParam(':costo_base', $data['base_cost'], PDO::PARAM_STR);
        $stmt->bindParam(':requiere_material', $data['requires_material'], PDO::PARAM_INT);
        $stmt->bindParam(':es_procedimiento', $data['is_procedure'], PDO::PARAM_INT);
        $stmt->bindParam(':activo', $data['is_active'], PDO::PARAM_INT);
        $stmt->bindParam(':registro', $data['uid'], PDO::PARAM_INT);

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function getProcedureData(array $data): array {
        $stmt = $this->db->prepare("
           SELECT s.uuid,
                s.servicio,
                s.descripcion,
                s.duracion_min,
                s.costo_base,
                s.requiere_material,
                s.es_procedimiento,
                s.activo
				FROM servicios s
				WHERE s.uuid = :uuid
                    AND s.empresa = :empresa
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organizationId'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateProcedure(array $data) {
        $stmt = $this->db->prepare("
            UPDATE servicios SET
                servicio = :servicio,
                descripcion = :descripcion,
                duracion_min = :duracion_min,
                costo_base = :costo_base,
                requiere_material = :requiere_material,
                es_procedimiento = :es_procedimiento,
                activo = :activo,
                f_actualizacion = NOW()
                WHERE uuid = :uuid
                    AND empresa = :empresa
        ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_STR);
        $stmt->bindParam(':servicio', $data['procedure'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':duracion_min', $data['duration'], PDO::PARAM_STR);
        $stmt->bindParam(':costo_base', $data['base_cost'], PDO::PARAM_STR);
        $stmt->bindParam(':requiere_material', $data['requires_material'], PDO::PARAM_INT);
        $stmt->bindParam(':es_procedimiento', $data['is_procedure'], PDO::PARAM_INT);
        $stmt->bindParam(':activo', $data['is_active'], PDO::PARAM_INT);

        $stmt->execute();
    }

    public function verifyProcedureStaffExists(array $data): ?bool {
        $stmt = $this->db->prepare("
            SELECT id
            FROM personal_servicios
            WHERE personal = :personal
                AND servicio = :servicio
                AND f_baja IS NULL
            LIMIT 1");
        $stmt->bindValue(':personal', $data['staff'], PDO::PARAM_INT);
        $stmt->bindValue(':servicio', $data['procedure'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? true : false;
    }
    
    public function insertProcedureStaff(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO personal_servicios (
                uuid,
                personal,
                servicio,
                costo,
                f_registro
            ) VALUES (
                :uuid,
                :personal,
                :servicio,
                :costo,
                NOW()
            )
        ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':personal', $data['staff'], PDO::PARAM_STR);
        $stmt->bindParam(':servicio', $data['procedure'], PDO::PARAM_STR);
        $stmt->bindParam(':costo', $data['cost'], PDO::PARAM_STR);

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}