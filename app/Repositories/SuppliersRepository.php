<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class SuppliersRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "SELECT p.id,
                        p.uuid,
                        p.clave,
                        p.rfc,
                        p.razon_social,
                        TRIM(
                            CONCAT(
                                COALESCE(p.calle, ''), ' ',
                                COALESCE(p.num_ext, ''), ' ',
                                COALESCE(p.num_int, ', '), ', ',
                                COALESCE(col.colonia, ', '), ', ',
                                COALESCE(m.municipio, ', '), ', ',
                                COALESCE(e.estado, '')
                            )
                        ) domicilio,
                        TRIM(
                            CONCAT(
                                COALESCE(CONCAT(p.telefono_1, ' / '), ''),
                                COALESCE(p.telefono_2, ''), ' '
                            )
                        ) telefono,
                        p.movil,
                        p.email,
                        u.nombre registro,
                        p.adeudos,
                        COALESCE(DATE_FORMAT(p.f_registro, '%Y-%m-%d %H:%i:%s'), '') f_registro
                        FROM proveedores p
                            LEFT JOIN colonias col
                                ON p.colonia = col.id
                            LEFT JOIN municipios m
                                ON col.municipio = m.id
                            LEFT JOIN estados e
                                ON m.estado = e.id
                            LEFT JOIN usuarios u
                                ON p.registro = u.id
                        WHERE p.empresa = :empresa
        ";

        $params = [];

        $fields = ['p.clave',
                    'p.rfc',
                    'p.razon_social',
                    'p.calle',
                    'p.num_ext',
                    'p.num_int',
                    'col.colonia',
                    'm.municipio',
                    'e.estado',
                    'p.telefono_1',
                    'p.telefono_2',
                    'p.movil',
                    'p.email',
                    'u.nombre'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";


        $sql .= "
            ORDER BY p.razon_social ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}