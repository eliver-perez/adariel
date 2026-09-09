<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class PaymentsStatusRepository {
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                codigo,
                estatus
            FROM pagos_estatus
            ORDER BY id ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsById(int $id): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM pagos_estatus
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function existsByCode(string $code): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM pagos_estatus
            WHERE codigo = :code
            LIMIT 1
        ");

        $stmt->execute([
            'code' => $code
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function getIdByCode(string $code): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM pagos_estatus
            WHERE codigo = :code
            LIMIT 1
        ");

        $stmt->execute([
            'code' => $code
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null ? $data['id'] : 0;
    }
}