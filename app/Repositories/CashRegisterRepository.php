<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class CashRegisterRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                uuid,
                codigo,
                caja
            FROM cajas
            WHERE sucursal = :sucursal
                OR sucursal IS NULL
            ORDER BY id ASC
        ");
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdByUuid(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM cajas
            WHERE uuid = :uuid
                AND (sucursal = :sucursal
                OR sucursal IS NULL)
            LIMIT 1
        ");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null ? $data['id'] : 0;
    }
}