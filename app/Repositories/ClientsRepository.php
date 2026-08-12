<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ClientsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "
            SELECT
                c.id,
                c.uuid,
                c.clave,
                TRIM(
                    CONCAT(
                        c.nombre, ' ',
                        COALESCE(c.paterno, ''), ' ',
                        COALESCE(c.materno, '')
                    )
                ) nombre,
                TRIM(
                    CONCAT(
                        COALESCE(c.calle, ''), ' ',
                        COALESCE(c.num_ext, ''), ' ',
                        COALESCE(c.num_int, ', '), ', ',
                        COALESCE(col.colonia, ', '), ', ',
                        COALESCE(m.municipio, ', '), ', ',
                        COALESCE(e.estado, '')
                    )
                ) domicilio,
                COALESCE(DATE_FORMAT(c.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
                g.genero,
                c.telefono,
                c.movil,
                c.email,
                COALESCE(DATE_FORMAT(c.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(c.ultimo_pago, '%d/%m/%Y %r'), '') ultimo_pago
            FROM clientes c
                LEFT JOIN generos g
                    ON c.genero = g.id
                LEFT JOIN colonias col
                    ON c.colonia = col.id
                LEFT JOIN municipios m
                    ON col.municipio = m.id
                LEFT JOIN estados e
                    ON m.estado = e.id
            WHERE c.empresa = :empresa
                OR c.empresa IS NULL
        ";

        $params = [];

        $fields = ['c.clave', 'c.nombre', 'c.paterno', 'c.materno', 'c.telefono', 'c.movil'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY c.nombre ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDefaultClientId(): ?int {
        $stmt = $this->db->prepare("
            SELECT c.id
            FROM clientes c
            WHERE c.empresa IS NULL
            LIMIT 1");
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getClientId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT c.id
            FROM clientes c
            WHERE c.uuid = :uuid
                AND (c.empresa = :empresa
                OR c.empresa IS NULL)
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getClientName(array $data): ?string {
        $stmt = $this->db->prepare("
            SELECT 
                TRIM(
                    CONCAT(
                        c.nombre, ' ',
                        COALESCE(c.paterno, ''), ' ',
                        COALESCE(c.materno, '')
                    )
                ) nombre
            FROM clientes c
            WHERE c.uuid = :uuid
                AND (c.empresa = :empresa
                OR c.empresa IS NULL)
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $name = $stmt->fetchColumn();

        return $name !== false ? (string) $name : null;
    }

    public function getClientNextConsecutive(): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(consecutivo), 0) + 1 AS consecutivo
            FROM clientes
            FOR UPDATE
        ");

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function updateClientCode(int $patientId, array $data): void {
        $stmt = $this->db->prepare("
            UPDATE clientes
            SET consecutivo = :consecutivo,
                clave = :clave
            WHERE id = :id
        ");

        $stmt->execute([
            'consecutivo' => $data['consecutive'],
            'clave' => $data['code'],
            'id' => $patientId,
        ]);
    }
}