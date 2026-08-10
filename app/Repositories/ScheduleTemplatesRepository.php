<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ScheduleTemplatesRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array
    {
        $sql = "
            SELECT
            WHERE p.empresa = :empresa
        ";

        $params = [];

        $fields = [];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY nombre ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);
        $stmt->bindValue(':empresa', $data['organizationId'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleTemplateData(array $data): array {
        $stmt = $this->db->prepare("
           SELECT ph.id,
                    ph.uuid,
                    e.empresa,
                    ph.nombre,
                    ph.descripcion,
                    r.nombre registro
                    FROM plantillas_horarios ph
                        LEFT JOIN empresas e
                            ON ph.empresa = e.id
                        LEFT JOIN usuarios r
                            ON ph.usuario = r.id
                    WHERE ph.empresa = :empresa
                    ORDER BY ph.f_registro ASC
                    LIMIT 0, 1
        ");

        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?? null;
    }

    public function getScheduleTemplateDetails(array $data): array {
        $stmt = $this->db->prepare("
           SELECT phd.id,
                    phd.uuid,
                    phd.dia_semana,
                    phd.hora_inicio,
                    phd.hora_fin
                    FROM plantillas_horarios_detalles phd
                        INNER JOIN plantillas_horarios ph
                            ON phd.plantilla = ph.id
                    WHERE phd.plantilla = :plantilla
                        AND ph.empresa = :empresa
                    ORDER BY phd.dia_semana, phd.hora_inicio
        ");
        $stmt->bindValue(':plantilla', $data['template'], PDO::PARAM_INT);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);;
    }

    public function insertScheduleTemplate(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO plantillas_horarios (
                uuid,
                empresa,
                nombre,
                descripcion,
                usuario,
                f_registro,
                f_actualizacion
            )
            VALUES (
                :uuid,
                :empresa,
                :nombre,
                :descripcion,
                :usuario,
                NOW(),
                NOW()
            )
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $data['template'], PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $data['uid'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function insertScheduleTemplateDetails(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO plantillas_horarios_detalles (
                plantilla,
                uuid,
                dia_semana,
                hora_inicio,
                hora_fin
            )
            VALUES (
                :plantilla,
                :uuid,
                :dia_semana,
                :hora_inicio,
                :hora_fin
            )
        ");
        $stmt->bindParam(':plantilla', $data['template'], PDO::PARAM_INT);
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':dia_semana', $data['day_week'], PDO::PARAM_INT);
        $stmt->bindParam(':hora_inicio', $data['start'], PDO::PARAM_INT);
        $stmt->bindParam(':hora_fin', $data['end'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}