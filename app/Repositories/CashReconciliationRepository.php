<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class CashReconciliationRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "
            SELECT c.uuid,
                c.folio,
                u.nombre abierto_por,
                COALESCE(DATE_FORMAT(c.f_abierta, '%Y-%m-%d %H:%i:%s'), '') f_abierta,
                COALESCE(DATE_FORMAT(c.f_cierre, '%Y-%m-%d %H:%i:%s'), '') f_cierre,
                c.total_venta
            FROM cortes c
                LEFT JOIN usuarios u
                    ON c.abierta_por = u.id
                LEFT JOIN sucursales s
                    ON c.sucursal = s.id
            WHERE
        ";

        if($data['search_by'] == 'branch')
            $sql .= 'c.sucursal = :sucursal';
        else 
            $sql .= 's.empresa = :empresa';

        $params = [];

        $fields = ['c.folio', 'u.nombre'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY c.f_abierta DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        if($data['search_by'] == 'branch')
            $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        else 
            $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCashReconciliationId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT c.id
            FROM cortes c
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function verifyIfExistsOpen(array $data) {
        $stmt = $this->db->prepare("
            SELECT
                c.uuid
            FROM cortes c
            WHERE c.abierta_por = :uid
                AND c.sucursal = :sucursal
            AND c.f_cierre IS NULL
            LIMIT 1
        ");

        $stmt->bindParam(':uid', $data['uid'], PDO::PARAM_INT);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id != null ? $id : null;
    }

    public function getCashReconciliationData(array $data) {
        $stmt = $this->db->prepare("
            SELECT
                c.uuid,
                ca.caja,
                c.consecutivo consecutive,
                c.folio,
                opened.uuid opened_by_id,
                opened.nombre opened_by_name,
                COALESCE(DATE_FORMAT(c.f_abierta, '%Y-%m-%d %H:%i:%s'), '') opened_date,
                c.monto_apertura opened_amount,
                closed.uuid closed_by_id,
                closed.nombre closed_by_name,
                COALESCE(DATE_FORMAT(c.f_cierre, '%Y-%m-%d %H:%i:%s'), '') closed_date,
                c.monto_cierre closed_amount,
                c.otros_medios other_payment_methods,
                c.efectivo cash,
                c.total_venta total_sale,
                c.efectivo_esperado expected_cash,
                c.retiros cash_withdrawals,
                c.depositos cash_deposits,
                c.diferencia cash_difference,
                c.cancelado cancelled,
                ce.codigo status_code,
                ce.estatus status,
                c.observaciones,
                COALESCE(DATE_FORMAT(c.f_registro, '%Y-%m-%d %H:%i:%s'), '') registered_date
            FROM cortes c
                LEFT JOIN cajas ca
                    ON c.caja = ca.id
                LEFT JOIN usuarios opened
                    ON c.abierta_por = opened.id
                LEFT JOIN usuarios closed
                    ON c.cerrada_por = closed.id
                LEFT JOIN cortes_estatus ce
                    ON c.estatus = ce.id
                LEFT JOIN sucursales s
                    ON c.sucursal = s.id
            WHERE c.uuid = :uuid
                AND s.empresa = :empresa
        ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row;
    }

    public function getCashReconciliationClosingData(array $data) {
        $stmt = $this->db->prepare("
            SELECT
                c.uuid,
                c.monto_apertura,
                c.efectivo,
                c.otros_medios,
                c.efectivo_esperado,
                c.retiros,
                c.depositos,
                c.diferencia

                c.uuid,
                ca.caja,
                opened.uuid opened_by_id,
                opened.nombre opened_by_name,
                COALESCE(DATE_FORMAT(c.f_abierta, '%Y-%m-%d %H:%i:%s'), '') opened_date,
                c.monto_apertura opened_amount,
                closed.uuid closed_by_id,
                closed.nombre closed_by_name,
                COALESCE(DATE_FORMAT(c.f_cierre, '%Y-%m-%d %H:%i:%s'), '') closed_date,
                c.monto_cierre closed_amount,
                c.efectivo_esperado expected_cash,
                c.retiros cash_withdrawals,
                c.depositos cash_deposits,
                c.diferencia cash_difference,
                ce.codigo status_code,
                ce.estatus status,
                c.observaciones,
                COALESCE(DATE_FORMAT(c.f_registro, '%Y-%m-%d %H:%i:%s'), '') registered_date
            FROM cortes c
                LEFT JOIN cajas ca
                    ON c.caja = ca.id
                LEFT JOIN usuarios opened
                    ON c.abierta_por = opened.id
                LEFT JOIN usuarios closed
                    ON c.cerrada_por = closed.id
                LEFT JOIN cortes_estatus ce
                    ON c.estatus = ce.id
            WHERE c.uuid = :uuid
        ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row;
    }

    public function updateCashReconciliationCash(array $data) {
        $stmt = $this->db->prepare("
            UPDATE cortes SET efectivo = efectivo + :cash_1, efectivo_esperado = efectivo_esperado + :cash_2, total_venta = total_venta + :cash_3 WHERE uuid = :uuid
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':cash_1', $data['cash'], PDO::PARAM_STR);
        $stmt->bindValue(':cash_2', $data['cash'], PDO::PARAM_STR);
        $stmt->bindValue(':cash_3', $data['cash'], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updateCashReconciliationOther(array $data) {
        $stmt = $this->db->prepare("
            UPDATE cortes SET otros_medios = otros_medios + :amount, total_venta = total_venta + :amount_2 WHERE uuid = :uuid
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
        $stmt->bindValue(':amount_2', $data['amount'], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO cortes (
                uuid,
                sucursal,
                ejercicio,
                consecutivo,
                folio,
                caja,
                abierta_por,
                f_abierta,
                monto_apertura,
                efectivo_esperado,
                diferencia,
                estatus,
                observaciones,
                f_registro
            ) VALUES (
                :uuid,
                :branch,
                :year,
                :consecutive,
                :folio,
                :cash_register,
                :initialized_by,
                NOW(),
                :initialize_amount,
                :expected_cash,
                0,
                :status,
                '',
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':branch', $data['branch'], PDO::PARAM_INT);
        $stmt->bindValue(':year', $data['year'], PDO::PARAM_INT);
        $stmt->bindValue(':consecutive', $data['consecutive'], PDO::PARAM_INT);
        $stmt->bindValue(':folio', $data['folio'], PDO::PARAM_STR);
        $stmt->bindValue(':cash_register', $data['cash_register'], PDO::PARAM_INT);
        $stmt->bindValue(':initialized_by', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':initialize_amount', $data['initialize_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':expected_cash', $data['initialize_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function close(array $data) {
        $stmt = $this->db->prepare("
            UPDATE cortes SET estatus = :status,
                                f_cierre = NOW(),
                                monto_cierre = :amount_1,
                                diferencia = :amount_2 - efectivo_esperado,
                                cerrada_por = :uid,
                                observaciones = :observations
                                WHERE uuid = :uuid
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_INT);
        $stmt->bindValue(':amount_1', $data['amount'], PDO::PARAM_STR);
        $stmt->bindValue(':amount_2', $data['amount'], PDO::PARAM_STR);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
        $stmt->execute();
    }
}