<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRoleRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function getRoles(): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.id,
                p.permiso,
                p.descripcion,
                p.f_registro
            FROM permisos p
            WHERE p.id != 'superadmin'
            ORDER BY permiso ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                codigo,
                tipo
            FROM usuarios_tipos
            ORDER BY id ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTypeRoles($id): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.id,
                p.permiso,
                p.descripcion,
                put.uuid,
                COALESCE(DATE_FORMAT(put.f_actualizacion, '%Y-%m-%d %H:%i:%s'), '') f_actualizacion
            FROM permisos p
                INNER JOIN permisos_usuarios_tipo put
                    ON p.id = put.permiso
            WHERE put.tipo = :user_type
                AND put.valor = 1
            ORDER BY id ASC
        ");

        $stmt->bindParam(':user_type', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRoles($id): array {
        $stmt = $this->db->prepare("
            SELECT 
                p.id,
                p.permiso,
                p.descripcion,
                pu.uuid,
                COALESCE(DATE_FORMAT(pu.f_actualizacion, '%Y-%m-%d %H:%i:%s'), '') f_actualizacion
            FROM permisos p
                INNER JOIN permisos_usuarios pu
                    ON p.id = pu.permiso
            WHERE pu.usuario = :user
                AND pu.valor = 1
            ORDER BY id ASC
        ");

        $stmt->bindParam(':user', $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsById(int $id): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM usuarios_tipos
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function checkUserPermission(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM permisos_usuarios
            WHERE permiso = :permission
                AND usuario = :user
                AND valor = 1
            LIMIT 1
        ");

        $stmt->bindValue(':permission', $data['permission'], PDO::PARAM_STR);
        $stmt->bindValue(':user', $data['user'], PDO::PARAM_STR);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function checkUserTypePermission(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM permisos_usuarios_tipo
            WHERE permiso = :permission
                AND tipo = :user_type
                AND valor = 1
            LIMIT 1
        ");

        $stmt->bindValue(':permission', $data['permission'], PDO::PARAM_STR);
        $stmt->bindValue(':user_type', $data['user_type'], PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }
    
    public function addUserTypePermission(array $data) {
        $stmt = $this->db->prepare("
            INSERT INTO permisos_usuarios_tipo (
                uuid,
                permiso,
                tipo,
                valor,
                f_actualizacion
            ) VALUES (
                :uuid,
                :permission,
                :user_type,
                1,
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':permission', $data['permission'], PDO::PARAM_STR);
        $stmt->bindValue(':user_type', $data['user_type'], PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function addUserPermission(array $data) {
        $stmt = $this->db->prepare("
            INSERT INTO permisos_usuarios (
                uuid,
                permiso,
                usuario,
                empresa,
                valor,
                f_actualizacion
            ) VALUES (
                :uuid,
                :permission,
                :user,
                1,
                1,
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':permission', $data['permission'], PDO::PARAM_STR);
        $stmt->bindValue(':user', $data['user'], PDO::PARAM_INT);
        $stmt->execute();
    }
}