<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UsersRepository
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
                u.id,
                u.uuid,
                u.email,
                u.nombre,
                e.empresa,
                ut.tipo,
                u.activo,
                COALESCE(DATE_FORMAT(u.f_registro, '%Y-%m-%d %H:%i:%s'), '') f_registro,
                COALESCE(DATE_FORMAT(u.f_ultima_conexion, '%Y-%m-%d %H:%i:%s'), '') f_ultima_conexion
            FROM usuarios u
                LEFT JOIN empresas e
                    ON u.empresa = e.id
                LEFT JOIN usuarios_sucursales_roles usr
                    ON usr.sucursal = e.id
                        AND usr.usuario = u.id
                LEFT JOIN usuarios_tipos ut
                    ON ut.id = usr.tipo_usuario
        ";

        $params = [];

        if ($data['search'] !== null && $data['search'] !== '') {
            $sql .= " AND usuario LIKE :search";
            $params['search'] = '%' . $data['search'] . '%';
        }

        $sql .= " ORDER BY usuario ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserIdByUuid($uuid): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM usuarios
            WHERE uuid = :uuid
            LIMIT 1");
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getUserTypeCodeById(int $id): ?string {
        $stmt = $this->db->prepare("
            SELECT ut.codigo
            FROM usuarios u
                INNER JOIN usuarios_tipos ut
                    ON u.tipo_usuario = ut.id
            WHERE u.id = :id
            LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function findById(int $id): ?array {
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
                empresa,
                email,
                nombre,
                password_hash,
                tipo_usuario,
                activo,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :email,
                :nombre,
                :password_hash,
                :tipo_usuario,
                1,
                :registro,
                NOW()
            )
        ");

        $stmt->bindParam('uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam('empresa', $data['organization'], PDO::PARAM_STR);
        $stmt->bindParam('email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam('nombre', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam('password_hash', $data['password'], PDO::PARAM_STR);
        $stmt->bindParam('tipo_usuario', $data['user_type'], PDO::PARAM_STR);
        $stmt->bindParam('registro', $data['uid'], PDO::PARAM_STR);

        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }
    
    public function insertUserBranch(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO usuarios_sucursales_roles (
                usuario,
                sucursal,
                tipo_usuario,
                activo,
                f_registro
            ) VALUES (
                :usuario,
                :sucursal,
                :tipo_usuario,
                1,
                NOW()
            )
        ");
        $stmt->bindParam('usuario', $data['user'], PDO::PARAM_INT);
        $stmt->bindParam('sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindParam('tipo_usuario', $data['user_type'], PDO::PARAM_INT);
        $stmt->execute();
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
}