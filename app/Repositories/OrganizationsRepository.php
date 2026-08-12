<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class OrganizationsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(?string $search = null, int $limit = 10, int $offset = 0): array {
        $sql = "
            SELECT
                e.id,
                e.uuid,
                e.clave,
                e.empresa,
                TRIM(
                    CONCAT(
                        e.calle, ' ',
                        COALESCE(e.num_ext, ''), ' ',
                        COALESCE(e.num_int, ''), ', ',
                        COALESCE(c.colonia, ''), ', ',
                        COALESCE(m.municipio, ''), ', ',
                        COALESCE(es.estado, '')
                    )
                ) domicilio,
                e.telefono,
                e.movil,
                e.email,
                e.encargado,
                e.activo,
                COALESCE(r.nombre, 'N/D') registro,
                COALESCE(DATE_FORMAT(e.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM empresas e
                LEFT JOIN colonias c
                    ON e.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados es
                    ON m.estado = es.id
                LEFT JOIN usuarios r
                    ON e.registro = r.id
            WHERE 1 = 1
        ";

        $params = [];

        $fields = ['e.empresa', 'e.telefono', 'e.movil', 'e.email', 'e.encargado', 'r.nombre'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $search . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY e.empresa ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganizationData(string $uuid): ?array {
        $sql = "
            SELECT
                e.id,
                e.uuid,
                e.clave,
                e.empresa,
                TRIM(
                    CONCAT(
                        e.calle, ' ',
                        COALESCE(e.num_ext, ''), ' ',
                        COALESCE(e.num_int, ''), ', ',
                        COALESCE(c.colonia, ''), ', ',
                        COALESCE(m.municipio, ''), ', ',
                        COALESCE(es.estado, '')
                    )
                ) domicilio,
                e.telefono,
                e.movil,
                e.email,
                e.encargado,
                e.activo,
                COALESCE(r.nombre, 'N/D') registro,
                COALESCE(DATE_FORMAT(e.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM empresas e
                LEFT JOIN colonias c
                    ON e.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados es
                    ON m.estado = es.id
                LEFT JOIN usuarios r
                    ON e.registro = r.id
            WHERE e.uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllOrganizationBranches(array $data) {
        $sql = "
            SELECT
                s.id,
                s.uuid,
                s.clave,
                s.sucursal,
                TRIM(
                    CONCAT(
                        s.calle, ' ',
                        COALESCE(s.num_ext, ''), ' ',
                        COALESCE(s.num_int, ''), ', ',
                        COALESCE(c.colonia, ''), ', ',
                        COALESCE(m.municipio, ''), ', ',
                        COALESCE(es.estado, '')
                    )
                ) domicilio,
                s.telefono,
                s.movil,
                s.email,
                s.encargado,
                s.activo,
                COALESCE(r.nombre, 'N/D') registro,
                COALESCE(DATE_FORMAT(s.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM sucursales s
                INNER JOIN empresas e
                    ON s.empresa = e.id
                LEFT JOIN colonias c
                    ON s.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados es
                    ON m.estado = es.id
                LEFT JOIN usuarios r
                    ON s.registro = r.id
            WHERE e.uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganizationBranches(array $data) {
        $sql = "
            SELECT
                s.id,
                s.uuid,
                s.clave,
                s.sucursal,
                TRIM(
                    CONCAT(
                        s.calle, ' ',
                        COALESCE(s.num_ext, ''), ' ',
                        COALESCE(s.num_int, ''), ', ',
                        COALESCE(c.colonia, ''), ', ',
                        COALESCE(m.municipio, ''), ', ',
                        COALESCE(es.estado, '')
                    )
                ) domicilio,
                s.telefono,
                s.movil,
                s.email,
                s.encargado,
                s.activo,
                COALESCE(r.nombre, 'N/D') registro,
                COALESCE(DATE_FORMAT(s.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM sucursales s
                INNER JOIN empresas e
                    ON s.empresa = e.id
                LEFT JOIN colonias c
                    ON s.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados es
                    ON m.estado = es.id
                LEFT JOIN usuarios r
                    ON s.registro = r.id
            WHERE e.uuid = :uuid
                AND e.id = :empresa
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganizationUsers(array $data) {
        $sql = "
            SELECT
                u.uuid,
                u.email,
                u.nombre,
                ut.tipo,
                u.activo,
                r.nombre registro,
                COALESCE(DATE_FORMAT(u.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM usuarios u
                INNER JOIN usuarios_tipos ut
                    ON u.tipo_usuario = ut.id
                INNER JOIN empresas e
                    ON u.empresa = e.id
                LEFT JOIN usuarios r
                    ON u.registro = r.id
            WHERE e.uuid = :uuid
        ";

        if($data['active'] != -1)
            $sql .= "AND u.activo = :active";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        if($data['active'] != -1)
            $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganizationUsersById(array $data) {
        $sql = "
            SELECT
                u.uuid,
                u.email,
                u.nombre,
                ut.tipo,
                u.activo,
                r.nombre registro,
                COALESCE(DATE_FORMAT(u.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM usuarios u
                INNER JOIN usuarios_tipos ut
                    ON u.tipo_usuario = ut.id
                INNER JOIN empresas e
                    ON u.empresa = e.id
                LEFT JOIN usuarios r
                    ON u.registro = r.id
            WHERE e.id = :id
        ";

        if($data['active'] != -1)
            $sql .= "AND u.activo = :active";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $data['organizationId'], PDO::PARAM_LOB);
        if($data['active'] != -1)
            $stmt->bindParam(':active', $data['active'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrganizationId($data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM empresas
            WHERE uuid = :uuid
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getOrganizationUuid($id) {
        $stmt = $this->db->prepare("
            SELECT uuid
            FROM empresas
            WHERE id = :id
            LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getOrganizationBranchId($data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM sucursales
            WHERE uuid = :uuid
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getOrganizationBranchUuid($id) {
        $stmt = $this->db->prepare("
            SELECT uuid
            FROM sucursales
            WHERE id = :id
            LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getOrganizationNextConsecutive(): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(consecutivo), 0) + 1 AS consecutivo
            FROM empresas
            FOR UPDATE
        ");

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function updateOrganizationCode(int $patientId, array $data): void {
        $stmt = $this->db->prepare("
            UPDATE empresas
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

    public function getSucursalNextConsecutive(int $organization): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(consecutivo), 0) + 1 AS consecutivo
            FROM sucursales
            WHERE empresa = :empresa
            FOR UPDATE
        ");

        $stmt->execute([
            'empresa' => $organization
        ]);

        return (int)$stmt->fetchColumn();
    }

    public function updateSucursalCode(int $clientId, array $data): void {
        $stmt = $this->db->prepare("
            UPDATE sucursales
            SET consecutivo = :consecutivo,
                clave = :clave
            WHERE id = :id
        ");

        $stmt->execute([
            'consecutivo' => $data['consecutive'],
            'clave' => $data['code'],
            'id' => $clientId,
        ]);
    }

    public function insertOrganization(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO empresas (
                uuid,
                empresa,
                calle,
                num_ext,
                num_int,
                colonia,
                cp,
                telefono,
                movil,
                email,
                encargado,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                :cp,
                :telefono,
                :movil,
                :email,
                :encargado,
                :registro,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'empresa'                       => $data['organization'],
            'calle'                         => $data['street'],
            'num_ext'                       => $data['ext_no'],
            'num_int'                       => $data['int_no'],
            'colonia'                       => $data['locality'],
            'cp'                            => $data['zip_code'],
            'telefono'                      => $data['phone'],
            'movil'                         => $data['mobile'],
            'email'                         => $data['email'],
            'encargado'                     => $data['manager'],
            'registro'                      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertSucursal(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO sucursales (
                uuid,
                empresa,
                sucursal,
                calle,
                num_ext,
                num_int,
                colonia,
                cp,
                telefono,
                movil,
                email,
                encargado,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :sucursal,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                :cp,
                :telefono,
                :movil,
                :email,
                :encargado,
                :registro,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'empresa'                       => $data['organization'],
            'sucursal'                      => $data['sucursal'],
            'calle'                         => $data['street'],
            'num_ext'                       => $data['ext_no'],
            'num_int'                       => $data['int_no'],
            'colonia'                       => $data['locality'],
            'cp'                            => $data['zip_code'],
            'telefono'                      => $data['phone'],
            'movil'                         => $data['mobile'],
            'email'                         => $data['email'],
            'encargado'                     => $data['manager'],
            'registro'                      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertOrganizationSettings(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO ajustes_empresas (
                empresa,
                ajuste,
                valor,
                registro,
                f_registro
            ) SELECT :empresa,
                    id,
                    valor_defecto,
                    :registro,
                    NOW()
                    FROM ajustes a
                    WHERE a.activo = 1
        ");
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_LOB);
        $stmt->bindParam(':registro', $data['uid'], PDO::PARAM_LOB);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
}