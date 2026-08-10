<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ProductsRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql ="
            SELECT 
                p.id,
                p.uuid,
                p.clave,
                p.nombre,
                pc.categoria,
                u.unidad,
                p.precio_total,
                p.habilitado_venta
            FROM productos p
                LEFT JOIN productos_categoria pc
                    ON p.categoria = pc.id
                LEFT JOIN unidades u
                    ON p.unidad = u.id
            WHERE p.empresa = :empresa
        ";

        $params = [];

        $fields = ['p.clave', 'p.nombre', 'pc.categoria'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY p.nombre ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':empresa', $data['organizationId'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductCategoryId($uuid): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM productos_categoria
            WHERE uuid = :uuid
            LIMIT 1");
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getCategories(array $data): array {
        $stmt = $this->db->prepare("
            SELECT 
                pc.id,
                pc.uuid,
                pc.categoria
            FROM productos_categoria pc
            WHERE empresa = :empresa
                OR empresa IS NULL
        ");
        $stmt->bindParam(':empresa', $data['organizationId']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insert(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO productos (
                uuid,
                empresa,
                clave,
                codigo_barras,
                nombre,
                nombre_ticket,
                categoria,
                descripcion,
                unidad,
                precio_base,
                porc_impuestos,
                impuestos,
                precio_total,
                habilitado_venta,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :organization,
                :code,
                :bar_code,
                :product,
                :product_short,
                :category,
                :description,
                :unit_measure,
                :base_cost,
                :tax_rate,
                :taxes,
                :total_cost,
                :enable_sale,
                :registered_by,
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':organization', $data['organizationId'], PDO::PARAM_INT);
        $stmt->bindValue(':code', $data['code'], PDO::PARAM_STR);
        $stmt->bindValue(':bar_code', $data['bar_code'], PDO::PARAM_STR);
        $stmt->bindValue(':product', $data['product'], PDO::PARAM_STR);
        $stmt->bindValue(':product_short', $data['product_short'], PDO::PARAM_STR);
        $stmt->bindValue(':category', $data['category'], PDO::PARAM_INT);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':unit_measure', $data['unit_measure'], PDO::PARAM_INT);
        $stmt->bindValue(':base_cost', $data['base_cost'], PDO::PARAM_STR);
        $stmt->bindValue(':tax_rate', $data['tax_rate'], PDO::PARAM_STR);
        $stmt->bindValue(':taxes', $data['taxes'], PDO::PARAM_STR);
        $stmt->bindValue(':total_cost', $data['total_cost'], PDO::PARAM_STR);
        $stmt->bindValue(':enable_sale', $data['enable_sale'], PDO::PARAM_INT);
        $stmt->bindValue(':registered_by', $data['uid'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function insertCost(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO productos_precios (
                uuid,
                producto,
                perfil_impuesto,
                precio_base,
                porc_impuestos,
                impuestos,
                precio_total,
                registro,
                fecha_registro,
                vigente_desde
            ) VALUES (
                :uuid,
                :product,
                :tax_profile,
                :base_cost,
                :tax_rate,
                :taxes,
                :total_cost,
                :registered_by,
                NOW(),
                NOW()
            )
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':product', $data['product'], PDO::PARAM_STR);
        $stmt->bindValue(':tax_profile', $data['tax_profile'], PDO::PARAM_INT);
        $stmt->bindValue(':base_cost', $data['base_cost'], PDO::PARAM_STR);
        $stmt->bindValue(':tax_rate', $data['tax_rate'], PDO::PARAM_STR);
        $stmt->bindValue(':taxes', $data['taxes'], PDO::PARAM_STR);
        $stmt->bindValue(':total_cost', $data['total_cost'], PDO::PARAM_STR);
        $stmt->bindValue(':registered_by', $data['uid'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function getProductId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT p.id
            FROM productos p
            WHERE p.uuid = :uuid
                AND p.empresa = :empresa
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
    
    public function getProductData(array $data) {
        $sql ="
            SELECT 
                p.id,
                p.uuid,
                p.clave,
                p.nombre,
                pc.categoria,
                u.unidad,
                p.precio_base,
                p.porc_impuestos,
                p.impuestos,
                p.precio_total,
                p.habilitado_venta
            FROM productos p
                LEFT JOIN productos_categoria pc
                    ON p.categoria = pc.id
                LEFT JOIN unidades u
                    ON p.unidad = u.id
            WHERE p.uuid = :uuid
                AND p.empresa = :empresa
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}