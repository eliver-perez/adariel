<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class MediaRepository {
    public function __construct(private PDO $db) { }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function findByUuid(array $data): ?array {
        $sql = "
            SELECT
                uuid,
                empresa,
                sucursal,
                tipo,
                referencia,
                nombre_original,
                nombre_archivo,
                ruta_raiz,
                ruta,
                mime_type,
                hash,
                tamanio
            FROM archivos
            WHERE uuid = :uuid
                AND empresa = :empresa
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam( ':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam( ':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    public function findByReference(array $data): ?array {
        $sql = "
            SELECT
                uuid,
                empresa,
                sucursal,
                tipo,
                referencia,
                nombre_original,
                nombre_archivo,
                ruta_raiz,
                ruta,
                mime_type,
                hash,
                tamanio
            FROM archivos
            WHERE referencia = :uuid
                AND empresa = :empresa
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam( ':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam( ':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    public function findByOrganizationType(array $data): ?array {
        $sql = "
            SELECT
                uuid,
                empresa,
                sucursal,
                tipo,
                referencia,
                nombre_original,
                nombre_archivo,
                ruta_raiz,
                ruta,
                mime_type,
                hash,
                tamanio
            FROM archivos
            WHERE tipo = :type
                AND empresa = :empresa
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR);
        $stmt->bindParam( ':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        return $file ?: null;
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO archivos (
                uuid,
                empresa,
                tipo,
                referencia,
                nombre_original,
                nombre_archivo,
                ruta_raiz,
                ruta,
                mime_type,
                hash,
                tamanio,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :tipo,
                :referencia,
                :nombre_original,
                :nombre_archivo,
                :ruta_raiz,
                :ruta,
                :mime_type,
                :hash,
                :tamanio,
                :registro,
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':tipo', $data['type'], PDO::PARAM_STR);
        $stmt->bindValue(':referencia', $data['reference'], PDO::PARAM_LOB);
        $stmt->bindValue(':nombre_original', $data['original_name'], PDO::PARAM_STR);
        $stmt->bindValue(':nombre_archivo', $data['filename'], PDO::PARAM_STR);
        $stmt->bindValue(':ruta_raiz', $data['basepath'], PDO::PARAM_STR);
        $stmt->bindValue(':ruta', $data['path'], PDO::PARAM_STR);
        $stmt->bindValue(':mime_type', $data['mime_type'], PDO::PARAM_STR);
        $stmt->bindValue(':hash', $data['hash'], PDO::PARAM_STR);
        $stmt->bindValue(':tamanio', $data['size'], PDO::PARAM_INT);
        $stmt->bindValue(':registro', $data['uid'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function delete(array $data) {
        $stmt = $this->db->prepare("
            DELETE FROM archivos WHERE uuid = :uuid AND empresa = :empresa
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
    }
}