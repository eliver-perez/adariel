<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class PaymentsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "
            SELECT
                p.id,
                p.uuid,
                p.folio,
                p.consecutivo,
                CASE WHEN c.id IS NOT NULL
                    THEN TRIM(
                                CONCAT(
                                    c.nombre, ' ',
                                    COALESCE(c.paterno, ''), ' ',
                                    COALESCE(c.materno, '')
                                )
                            )
                    ELSE 'Publico en General'
                END cliente,
                mp.metodo metodo_pago,
                p.monto_pago,
                r.nombre registro,
                p.estatus,
                COALESCE(DATE_FORMAT(p.f_pago, '%d/%m/%Y %r'), '') f_pago,
                COALESCE(DATE_FORMAT(p.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(p.f_actualizacion, '%d/%m/%Y %r'), '') f_actualizacion
            FROM pagos p
                LEFT JOIN clientes c
                    ON p.cliente = c.id
                LEFT JOIN metodos_pago mp
                    ON p.metodo_pago = mp.id
                LEFT JOIN usuarios r
                    ON p.registro = r.id
                INNER JOIN cortes
                    ON p.corte = cortes.id
                LEFT JOIN sucursales s
                    ON p.sucursal = s.id
            WHERE 
        ";

        if($data['search_by'] == 'branch')
            $sql .= 'p.sucursal = :sucursal';
        else 
            $sql .= 's.empresa = :empresa';

        $params = [];

        $fields = ['p.folio', 'mp.metodo', 'r.nombre', 'c.nombre', 'c.paterno', 'c.materno'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        if($data['status'] != 0)
            $sql .= "
                AND p.estatus = :status";

        $sql .= "
            ORDER BY p.f_pago DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        if($data['status'] != 0)
            $stmt->bindValue(':status', $data['status'], PDO::PARAM_INT);
        if($data['search_by'] == 'branch')
            $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        else 
            $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPayment(array $data): array {
        $sql = "
            SELECT
                p.id,
                p.uuid,
                p.folio,
                cortes.folio folio_corte,
                p.consecutivo,
                CASE WHEN c.id IS NOT NULL
                    THEN TRIM(
                                CONCAT(
                                    c.nombre, ' ',
                                    COALESCE(c.paterno, ''), ' ',
                                    COALESCE(c.materno, '')
                                )
                            )
                    ELSE 'Publico en General'
                END cliente,
                mp.metodo metodo_pago,
                p.monto_pago,
                r.nombre registro,
                p.estatus,
                COALESCE(DATE_FORMAT(p.f_pago, '%d/%m/%Y %r'), '') f_pago,
                COALESCE(DATE_FORMAT(p.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(p.f_actualizacion, '%d/%m/%Y %r'), '') f_actualizacion
            FROM pagos p
                LEFT JOIN clientes c
                    ON p.cliente = c.id
                LEFT JOIN metodos_pago mp
                    ON p.metodo_pago = mp.id
                LEFT JOIN usuarios r
                    ON p.registro = r.id
                INNER JOIN cortes
                    ON p.corte = cortes.id
                LEFT JOIN sucursales s
                    ON p.sucursal = s.id
            WHERE p.uuid = :uuid
                AND s.empresa = :empresa
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaymentDetails(array $data): array {
        $sql = "
            SELECT p.id,
                    pvd.uuid,
                    CASE WHEN vd.producto IS NOT NULL
                        THEN 'Producto'
                        ELSE 'Procedimiento'
                    END tipo,
                    v.folio folio_venta,
                    vd.descripcion,
                    vd.cantidad,
                    vd.precio_base,
                    vd.subtotal,
                    vd.descuento,
                    vd.impuestos,
                    vd.total,
                    vd.pagado,
                    vd.adeudo,
                    pvd.adeudo_anterior,
                    pvd.monto_pago,
                    pvd.adeudo_actual
                    FROM pagos_ventas_detalles pvd
                        INNER JOIN pagos p
                            ON pvd.pago = p.id
                        INNER JOIN ventas v
                            ON pvd.venta = v.id
                        INNER JOIN ventas_detalles vd
                            ON pvd.venta_detalle = vd.id
                        INNER JOIN sucursales s
                            ON p.sucursal = s.id
                    WHERE p.uuid = :uuid
                        AND s.empresa = :empresa
                    ORDER BY v.folio
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSalesByPayment(array $data): array {
        $sql = "
            SELECT v.id,
                    v.uuid,
                    v.folio,
                    CASE WHEN clientes.id IS NOT NULL
                        THEN TRIM(
                                    CONCAT(
                                        clientes.nombre, ' ',
                                        COALESCE(clientes.paterno, ''), ' ',
                                        COALESCE(clientes.materno, '')
                                    )
                                )
                        ELSE 'Publico en General'
                    END cliente,
                    CASE WHEN pacientes.id IS NOT NULL
                        THEN TRIM(
                                    CONCAT(
                                        pacientes.nombre, ' ',
                                        COALESCE(pacientes.paterno, ''), ' ',
                                        COALESCE(pacientes.materno, '')
                                    )
                                )
                        ELSE ''
                    END paciente,
                    v.subtotal,
                    v.impuestos,
                    v.total,
                    v.descuento,
                    v.pagado,
                    v.adeudo,
                    pv.adeudo_anterior,
                    pv.monto_pago,
                    pv.adeudo_actual,
                    ve.estatus,
                    r.nombre registro,
                    COALESCE(DATE_FORMAT(v.f_venta, '%d/%m/%Y %r'), '') f_venta,
                    COALESCE(DATE_FORMAT(p.f_registro, '%d/%m/%Y %r'), '') f_registro,
                    COALESCE(DATE_FORMAT(p.f_actualizacion, '%d/%m/%Y %r'), '') f_actualizacion
                    FROM pagos p
                        INNER JOIN pagos_ventas pv
                            ON pv.pago = p.id
                        INNER JOIN ventas v
                            ON pv.venta = v.id
                        LEFT JOIN clientes
                            ON v.cliente = clientes.id
                        LEFT JOIN pacientes
                            ON v.paciente = pacientes.id
                        LEFT JOIN ventas_estatus ve
                            ON v.estatus = ve.id
                        LEFT JOIN usuarios r
                            ON v.registro = r.id
                        LEFT JOIN consultas
                            ON v.consulta = consultas.id
                        LEFT JOIN citas
                            ON v.cita = citas.id
                        LEFT JOIN sucursales s
                            ON p.sucursal = s.id
                    WHERE p.uuid = :uuid
                        AND s.empresa = :empresa
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCashReconciliationPayments(array $data): array {
        $sql = "
            SELECT
                p.id,
                p.uuid,
                p.folio,
                p.consecutivo,
                CASE WHEN c.id IS NOT NULL
                    THEN TRIM(
                                CONCAT(
                                    c.nombre, ' ',
                                    COALESCE(c.paterno, ''), ' ',
                                    COALESCE(c.materno, '')
                                )
                            )
                    ELSE 'Publico en General'
                END cliente,
                mp.metodo metodo_pago,
                p.monto_pago,
                r.nombre registro,
                p.estatus,
                COALESCE(DATE_FORMAT(p.f_pago, '%d/%m/%Y %r'), '') f_pago,
                COALESCE(DATE_FORMAT(p.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(p.f_actualizacion, '%d/%m/%Y %r'), '') f_actualizacion
            FROM pagos p
                LEFT JOIN clientes c
                    ON p.cliente = c.id
                LEFT JOIN metodos_pago mp
                    ON p.metodo_pago = mp.id
                LEFT JOIN usuarios r
                    ON p.registro = r.id
                INNER JOIN cortes
                    ON p.corte = cortes.id
                INNER JOIN sucursales s
                    ON p.sucursal = s.id
            WHERE cortes.uuid = :uuid
                AND s.empresa = :empresa
        ";

        $params = [];

        $fields = ['p.folio', 'mp.metodo', 'r.nombre', 'c.nombre', 'c.paterno', 'c.materno'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY p.f_pago DESC
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function registerPayment(array $data) {
        $sql = "
            INSERT INTO pagos (
                uuid,
                sucursal,
                ejercicio,
                folio,
                consecutivo,
                cliente,
                corte,
                metodo_pago,
                referencia,
                observaciones,
                adeudo_anterior,
                monto_pago,
                adeudo,
                estatus,
                registro,
                f_pago,
                f_registro
            ) VALUES (
                :uuid,
                :sucursal,
                :ejercicio,
                :folio,
                :consecutive,
                :client,
                :cash_reconciliation,
                :payment_method,
                :reference,
                '',
                :balance_due_before,
                :payment_amount,
                :balance_due,
                1,
                :uid,
                NOW(),
                NOW()
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindValue(':ejercicio', $data['year'], PDO::PARAM_INT);
        $stmt->bindValue(':folio', $data['folio'], PDO::PARAM_STR);
        $stmt->bindValue(':consecutive', $data['consecutive'], PDO::PARAM_INT);
        $stmt->bindValue(':client', $data['client'], PDO::PARAM_INT);
        $stmt->bindValue(':cash_reconciliation', $data['cash_reconciliation'], PDO::PARAM_INT);
        $stmt->bindValue(':payment_method', $data['payment_method'], PDO::PARAM_INT);
        $stmt->bindValue(':reference', $data['reference'], PDO::PARAM_STR);
        $stmt->bindValue(':balance_due_before', $data['balance_due_before'], PDO::PARAM_STR);
        $stmt->bindValue(':payment_amount', $data['payment_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':balance_due', $data['balance_due'], PDO::PARAM_STR);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }
    
    public function registerSalePayment(array $data) {
        $sql = "
            INSERT INTO pagos_ventas (
                uuid,
                pago,
                venta,
                adeudo_anterior,
                monto_pago,
                adeudo_actual,
                f_registro
            )
            SELECT
                :uuid,
                :payment,
                v.id,
                :balance_due_before,
                :payment_amount,
                :balance_due,
                NOW()
            FROM ventas v
            WHERE v.uuid = :sale_uuid
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':payment', $data['payment'], PDO::PARAM_INT);
        $stmt->bindValue(':sale_uuid', $data['sale'], PDO::PARAM_LOB);
        $stmt->bindValue(':balance_due_before', $data['balance_due_before'], PDO::PARAM_STR);
        $stmt->bindValue(':payment_amount', $data['payment_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':balance_due', $data['balance_due'], PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }
    
    public function registerSaleDetailPayment(array $data) {
        $sql = "
            INSERT INTO pagos_ventas_detalles (
                uuid,
                pago,
                venta,
                venta_detalle,
                adeudo_anterior,
                monto_pago,
                adeudo_actual
            )
            SELECT
                :uuid,
                :payment,
                vd.venta,
                vd.id,
                :balance_due_before,
                :payment_amount,
                :balance_due
            FROM ventas_detalles vd
            WHERE vd.uuid = :sale_detail_uuid
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':payment', $data['payment'], PDO::PARAM_INT);
        $stmt->bindValue(':sale_detail_uuid', $data['sale_detail'], PDO::PARAM_LOB);
        $stmt->bindValue(':balance_due_before', $data['balance_due_before'], PDO::PARAM_STR);
        $stmt->bindValue(':payment_amount', $data['payment_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':balance_due', $data['balance_due'], PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }
}