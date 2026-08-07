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

    public function getProcedureId(string $uuid): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM servicios
            WHERE uuid = :uuid
            LIMIT 1");
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getProcedureEnabledModules($uuid): array {
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
                ORDER BY scm.orden
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationProcedureModules($uuid): array {
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
                ORDER BY scm.orden
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureStaff($uuid): array {
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
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureStaffData($procedure, $staff): array {
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
                    ORDER BY ps.f_registro DESC
                    LIMIT 1
        ");

        $stmt->bindValue(':procedure', $procedure, PDO::PARAM_LOB);
        $stmt->bindValue('staff', $staff, PDO::PARAM_LOB);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?? null;
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
                1,
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
        $stmt->bindParam(':registro', $data['uid'], PDO::PARAM_INT);

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}