<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class SettingsRepository
{
    public function __construct(private PDO $db) {}

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getGlobalById(string $id): ?array
    {
        $sql = "
            SELECT 
                a.id,
                a.valor_defecto valor,
                at.codigo AS tipo
            FROM ajustes a
            INNER JOIN ajustes_tipo at ON a.tipo = at.id
            WHERE a.id = :id
              AND a.activo = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getById(string $id, int $organization): ?array
    {
        $sql = "
            SELECT 
                ae.id,
                ae.valor,
                at.codigo AS tipo
            FROM ajustes_empresas ae
                INNER JOIN ajustes a
                    ON ae.ajuste = a.id
                INNER JOIN ajustes_tipo at
                    ON a.tipo = at.id
            WHERE ae.ajuste = :id
                AND ae.empresa = :empresa
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':empresa', $organization);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function updateOrganizationLogo(array $data): void {
        $stmt = $this->db->prepare("
            UPDATE empresas
            SET logo = :logo
            WHERE uuid = :uuid
        ");
        $stmt->bindValue(':logo', $data['logo'], PDO::PARAM_LOB);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();
    }

    public function updateOrganizationBranchLogo(array $data): void {
        $stmt = $this->db->prepare("
            UPDATE sucursales
            SET logo = :logo
            WHERE id = :id
        ");

        $stmt->execute([
            'logo' => $data['logo'],
            'id' => $data['uuid'],
        ]);
    }
}