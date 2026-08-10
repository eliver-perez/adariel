<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ScheduleRepository
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

    public function insertSchedule(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO horarios_laborales (
                uuid,
                sucursal,
                personal,
                consultas,
                plantilla,
                activo,
                registro,
                f_registro,
                f_actualizacion
            )
            VALUES (
                :uuid,
                :sucursal,
                :personal,
                1,
                :plantilla,
                1,
                :registro,
                NOW(),
                NOW()
            )
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindParam(':personal', $data['staff'], PDO::PARAM_INT);
        $stmt->bindParam(':plantilla', $data['template'], PDO::PARAM_INT);
        $stmt->bindParam(':registro', $data['uid'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function insertScheduleDetails(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO horarios_laborales_detalles (
                uuid,
                horario,
                dia_semana,
                hora_inicio,
                hora_fin
            )
            VALUES (
                :uuid,
                :horario,
                :dia_semana,
                :hora_inicio,
                :hora_fin
            )
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':horario', $data['schedule'], PDO::PARAM_INT);
        $stmt->bindParam(':dia_semana', $data['day_week'], PDO::PARAM_INT);
        $stmt->bindParam(':hora_inicio', $data['time_start'], PDO::PARAM_INT);
        $stmt->bindParam(':hora_fin', $data['time_end'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}