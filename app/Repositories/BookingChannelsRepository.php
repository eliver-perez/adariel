<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class BookingChannelsRepository {
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                codigo,
                forma
            FROM citas_formas
            ORDER BY forma ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsById(int $id): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM citas_formas
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function existsByCode(string $code): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM citas_formas
            WHERE codigo = :code
            LIMIT 1
        ");

        $stmt->execute([
            'code' => $code
        ]);

        return (bool) $stmt->fetchColumn();
    }
}