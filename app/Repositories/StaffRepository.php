<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class StaffRepository
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
                p.id,
                p.uuid,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) nombre,
                TRIM(
                    CONCAT(
                        COALESCE(p.calle, ''), ' ',
                        COALESCE(p.num_ext, ''), ' ',
                        COALESCE(p.num_int, ', '), ', ',
                        COALESCE(c.colonia, ', '), ', ',
                        COALESCE(m.municipio, ', '), ', ',
                        COALESCE(e.estado, '')
                    )
                ) domicilio,
                COALESCE(DATE_FORMAT(p.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
                COALESCE(p.telefono, '') telefono,
                COALESCE(p.movil, '') movil,
                COALESCE(p.email, '') email,
                COALESCE(u.nombre, '') usuario,
                COALESCE(r.nombre, '') registro,
                pe.estatus,
                pu.puesto,
                p.f_registro,
                p.f_actualizacion
            FROM personal p
                LEFT JOIN personal_estatus pe
                    ON p.estatus = pe.id
                LEFT JOIN colonias c
                    ON p.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados e
                    ON m.estado = e.id
                LEFT JOIN puestos pu
                    ON p.puesto = pu.id
                LEFT JOIN (
                    SELECT *
                    FROM (
                        SELECT 
                            puu.*,
                            ROW_NUMBER() OVER (PARTITION BY personal ORDER BY id DESC) AS rn
                        FROM personal_usuarios puu
                        WHERE puu.activo = 1
                        ORDER BY puu.f_registro
                    ) t
                    WHERE rn = 1
                ) puu ON puu.personal = p.id
                LEFT JOIN usuarios u
                    ON puu.usuario = u.id
                LEFT JOIN usuarios r
                    ON p.registro = r.id
            WHERE p.empresa = :empresa
        ";

        $params = [];

        $fields = ["TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                )",
                "p.email",
                "COALESCE(p.telefono, '')",
                "COALESCE(u.nombre, '')"];

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

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM personal
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                p.id,
                CONCAT(COALESCE(p.nombre, ''),
                        COALESCE(CONCAT(' ', p.paterno), ''),
                        COALESCE(CONCAT(' ', p.materno), '')) nombre,
                CONCAT(COALESCE(p.calle, ''),
                        COALESCE(CONCAT(' ', p.num_ext), ''),
                        COALESCE(CONCAT(', ', p.num_int), ', '),
                        COALESCE(CONCAT(', ', c.colonia), ', '),
                        COALESCE(CONCAT(', ', m.municipio), ', '),
                        COALESCE(e.estado, '')) domicilio,
                p.email,
                p.telefono,
                p.estatus,
                p.f_registro,
                p.f_actualizacion
            FROM personal p
                LEFT JOIN colonias c
                    ON p.colonia = c.id
                LEFT JOIN municipios m
                    ON c.municipio = m.id
                LEFT JOIN estados e
                    ON m.estado = e.id
            WHERE p.id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function getStaffId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM personal
            WHERE uuid = :uuid
                AND empresa = :empresa
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function insertStaff(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO personal (
                uuid,
                empresa,
                rfc,
                nombre,
                paterno,
                materno,
                f_nacimiento,
                calle,
                num_ext,
                num_int,
                colonia,
                email,
                curp,
                telefono,
                movil,
                genero,
                puesto,
                estatus,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :rfc,
                :nombre,
                :paterno,
                :materno,
                :f_nacimiento,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                :email,
                :curp,
                :telefono,
                :movil,
                :genero,
                :puesto,
                1,
                :registro,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'          => $data['uuid'],
            'empresa'       => $data['organizationId'],
            'rfc'           => $data['rfc'],
            'nombre'        => $data['first_name'],
            'paterno'       => $data['last_name'],
            'materno'       => $data['last_name_2'],
            'f_nacimiento'  => $data['dob'],
            'calle'         => $data['street'],
            'num_ext'       => $data['ext_no'],
            'num_int'       => $data['int_no'],
            'colonia'       => $data['locality'],
            'email'         => $data['email'],
            'curp'          => $data['curp'],
            'telefono'      => $data['phone'],
            'movil'         => $data['mobile'],
            'genero'        => $data['gender'],
            'puesto'        => $data['role'],
            'registro'      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertStaffRegistration(int $staffId, array $data): void {
        $stmt = $this->db->prepare("
            INSERT INTO personal_altas (
                personal,
                f_alta,
                f_registro
            ) VALUES (
                :personal,
                :f_alta,
                NOW()
            )
        ");

        $stmt->execute([
            'personal'                  => $staffId,
            'f_alta'                    => $data['f_alta'],
        ]);
    }

    public function insertStaffOrganizationBranch(array $data) {
        $stmt = $this->db->prepare("
            INSERT INTO personal_sucursales (
                uuid,
                personal,
                sucursal,
                principal,
                activo,
                f_registro
            ) VALUES (
                :uuid,
                :personal,
                :sucursal,
                1,
                1,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'          => $data['uuid'],
            'personal'      => $data['staffId'],
            'sucursal'      => $data['branchId'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertStaffProfessional(int $staffId, array $data): void {
        $stmt = $this->db->prepare("
            INSERT INTO personal_profesional (
                personal,
                cedula,
                especialidad,
                universidad,
                egreso,
                universidad_municipio,
                f_registro
            ) VALUES (
                :personal,
                :cedula,
                :especialidad,
                :universidad,
                :egreso,
                :universidad_municipio,
                NOW()
            )
        ");

        $stmt->execute([
            'personal'                  => $staffId,
            'cedula'                    => $data['cedula'],
            'especialidad'              => $data['specialty'],
            'universidad'               => $data['university'],
            'egreso'                    => $data['university_grad_year'],
            'universidad_municipio'     => $data['university_municipality'],
        ]);
    }

    public function userExists(string $email): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function insertUser(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (
                uuid,
                nombre,
                usuario,
                password_hash,
                tipo_usuario,
                activo,
                f_registro
            ) VALUES (
                :uuid,
                :nombre,
                :usuario,
                :password_hash,
                :tipo_usuario,
                1,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                  => $data['uuid'],
            'nombre'                => $data['name'],
            'usuario'               => $data['username'],
            'password_hash'         => $data['password_hash'],
            'tipo_usuario'          => $data['user_role'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertStaffUser(int $staffId, int $userId): void {
        $stmt = $this->db->prepare("
            INSERT INTO personal_usuarios (
                personal,
                usuario,
                f_registro
            ) VALUES (
                :personal,
                :usuario,
                NOW()
            )
        ");

        $stmt->execute([
            'personal'              => $staffId,
            'usuario'               => $userId,
        ]);
    }

    public function insertStaffSalary(int $staffId, array $data): void {
        $stmt = $this->db->prepare("
            INSERT INTO personal_sueldos (
                personal,
                sueldo_actual,
                actualizo,
                f_apartir_de,
                f_actualizacion
            ) VALUES (
                :personal,
                :sueldo_actual,
                :actualizo,
                :f_apartir_de,
                NOW()
            )
        ");

        $stmt->execute([
            'personal'              => $staffId,
            'sueldo_actual'         => $data['salary'],
            'actualizo'             => $data['uid'],
            'f_apartir_de'          => $data['salary_since'],
        ]);
    }
}