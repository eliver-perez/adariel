<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class POSRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function createFromConsultation(array $data) {
        $sql = "
            INSERT INTO ventas (
                uuid,
                sucursal,
                folio,
                ejercicio,
                consecutivo,
                consulta,
                cita,
                cliente,
                paciente,
                subtotal,
                impuestos,
                total,
                descuento,
                pagado,
                adeudo,
                estatus,
                observaciones,
                registro,
                f_venta,
                f_registro
            )
            SELECT 
                :uuid,
                c.sucursal,
                :folio,
                YEAR(NOW()),
                :consecutive,
                c.id,
                c.cita,
                cp.cliente,
                c.paciente,
                :subtotal,
                0,
                :total,
                :discount,
                0,
                :debt,
                :status,
                :observations,
                :uid,
                NOW(),
                NOW()
            FROM consultas c
                INNER JOIN paciente p
                    ON c.paciente = p.id
                INNER JOIN clientes_pacientes cp
                    ON cp.paciente = cp.id
                        AND cp.principal = 1
                        AND cp.activo = 1
            WHERE c.uuid = :consultation_uuid
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':folio', $data['folio'], PDO::PARAM_STR);
        $stmt->bindValue(':consecutive', $data['consecutive'], PDO::PARAM_INT);
        $stmt->bindValue(':subtotal', $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':total', $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':discount', $data['discount'], PDO::PARAM_STR);
        $stmt->bindValue(':debt', $data['debt'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_INT);
        $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }

    public function createFromConsultationDetails(array $data) {
        $sql = "
            INSERT INTO ventas_detalles (
                uuid,
                venta,
                servicio,
                descripcion,
                cantidad,
                precio_base,
                subtotal,
                impuestos,
                total,
                descuento,
                adeudo,
                f_registro
            ) VALUES (
                :uuid,
                :sale,
                :procedure,
                :description,
                :quantity,
                :unit_price,
                :subtotal,
                0,
                :total,
                :discount,
                :debt,
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sale', $data['sale'], PDO::PARAM_INT);
        $stmt->bindValue(':procedure', $data['procedure'], PDO::PARAM_INT);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $data['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':unit_price', $data['unit_price'], PDO::PARAM_STR);
        $stmt->bindValue(':subtotal', $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':total', $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':discount', $data['discount'], PDO::PARAM_STR);
        $stmt->bindValue(':debt', $data['debt'], PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }
}