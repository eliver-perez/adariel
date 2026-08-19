<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ConsultationsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "
            SELECT cb.uuid,
                c.folio,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) paciente,
                COALESCE(DATE_FORMAT(p.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
                ca.asunto,
                COALESCE(DATE_FORMAT(c.fecha, '%Y-%m-%d'), '') fecha_cita,
                cb.h_inicio,
                cb.duracion,
                cbe.codigo estatus_codigo,
                cbe.estatus
            FROM citas c
                INNER JOIN citas_bloques cb
                    ON cb.cita = c.id
                INNER JOIN pacientes p
                    ON c.paciente = p.id
                INNER JOIN citas_asuntos ca
                    ON c.asunto = ca.id
                INNER JOIN citas_bloques_estatus cbe
                    ON cb.estatus = cbe.id
                INNER JOIN personal_usuarios pu
                    ON cb.personal = pu.personal
                INNER JOIN usuarios u
                    ON pu.usuario = u.id
            WHERE u.id = :usuario
                AND c.sucursal = :sucursal
        ";

        if($data['status'] != -1)
            $sql .= "
                AND cb.estatus = :status
            ";

        $params = [];

        $fields = ['p.nombre', 'p.paterno', 'p.materno'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY c.fecha, cb.h_inicio ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        if($data['status'] != -1)
            $stmt->bindValue(':status', $data['status'], PDO::PARAM_INT);

        $stmt->bindValue(':usuario', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationPatientId($uuid) {
        $stmt = $this->db->prepare("
            SELECT c.paciente
            FROM consultas c
            WHERE c.uuid = :uuid
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['paciente'] ?: null;
    }

    public function getConsultationStaffId($uuid) {
        $stmt = $this->db->prepare("
            SELECT c.personal
            FROM consultas c
            WHERE c.uuid = :uuid
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['personal'] ?: null;
    }

    public function getConsultationUuidByBlock(array $data) {
        $stmt = $this->db->prepare("
            SELECT c.uuid
            FROM consultas c
                INNER JOIN citas ct
                    ON c.cita = ct.id
                INNER JOIN citas_bloques cb
                    ON cb.cita = ct.id
            WHERE cb.uuid = :uuid
                AND ct.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['uuid'] ?: null;
    }

    public function getConsultation(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT cb.uuid,
                ct.folio,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) paciente,
                p.clave paciente_clave,
                p.telefono,
                p.movil,
                p.email,
                g.genero,
                p.medicamentos,
                p.suplementos,
                p.antecedentes_familiares,
                p.observaciones_generales,
                COALESCE(DATE_FORMAT(p.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
                ca.asunto,
                ct.motivo_consulta,
                c.observacion_inicial,
                c.indicaciones,
                c.diagnostico_resumen,
                COALESCE(DATE_FORMAT(ct.fecha, '%Y-%m-%d'), '') fecha_cita,
                cb.h_inicio,
                cb.duracion,
                cbe.codigo estatus_codigo,
                cbe.estatus
            FROM citas ct
                LEFT JOIN consultas c
                    ON ct.id = c.cita
                INNER JOIN citas_bloques cb
                    ON cb.cita = ct.id
                INNER JOIN pacientes p
                    ON ct.paciente = p.id
                LEFT JOIN generos g
                    ON p.genero = g.id
                INNER JOIN citas_asuntos ca
                    ON ct.asunto = ca.id
                INNER JOIN citas_bloques_estatus cbe
                    ON cb.estatus = cbe.id
                INNER JOIN personal_usuarios pu
                    ON cb.personal = pu.personal
                INNER JOIN usuarios u
                    ON pu.usuario = u.id
            WHERE cb.uuid = :uuid
                AND ct.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getAppointmentUuidByBlock(array $data) {
        $stmt = $this->db->prepare("
            SELECT c.uuid
            FROM citas c
                INNER JOIN citas_bloques cb
                    ON c.id = cb.cita
            WHERE cb.uuid = :uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getAppointmentProcedures(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT s.id,
                s.uuid,
                s.servicio,
                cb.uuid block_uuid,
                cb.duracion,
                cb.h_inicio,
                cb.h_fin,
                cs.costo,
                cs.bonificacion
            FROM citas c
                INNER JOIN citas_bloques cb
                    ON c.id = cb.cita
                INNER JOIN servicios s
                    ON cb.servicio = s.id
                INNER JOIN personal_usuarios pu
                    ON cb.personal = pu.personal
                INNER JOIN usuarios u
                    ON pu.usuario = u.id
                INNER JOIN citas_servicios cs
                    ON cs.cita = c.id
                        AND cs.servicio = s.id
                        and cs.personal = pu.personal
            WHERE c.uuid = :uuid
                AND u.id = :uid
                AND c.sucursal = :sucursal
            ORDER BY cb.h_inicio
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationStatus(array $data) {
        $stmt = $this->db->prepare("
            SELECT c.id,
                c.cita,
                c.paciente,
                c.personal,
                c.estatus,
                ce.id cita_estatus_id,
                ce.codigo cita_estatus_codigo,
                ce.estatus cita_estatus
            FROM consultas c
                INNER JOIN citas ct
                    ON c.cita = ct.id
                INNER JOIN citas_estatus ce
                    ON ct.estatus = ce.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
            LIMIT 1;
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row;
    }

    public function getConsultationUuidByAppointment(array $data)
    {
        $stmt = $this->db->prepare("
            SELECT c.uuid
            FROM consultas c
                INNER JOIN citas ct
                    ON c.cita = ct.id
            WHERE ct.uuid = :uuid
                AND ct.sucursal = :sucursal
            LIMIT 1
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function startConsultation(array $data) {
        $sql = "
            INSERT INTO consultas (
                uuid,
                cita,
                sucursal,
                paciente,
                personal,
                estatus,
                f_inicio,
                registro,
                f_registro
            )
            SELECT
                :uuid,
                c.id,
                c.sucursal,
                c.paciente,
                cb.personal,
                0,
                NOW(),
                :registro,
                NOW()
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON c.id = cb.cita
            WHERE cb.id = :cita_bloque
                AND c.sucursal = :sucursal
                AND NOT EXISTS (
                    SELECT 1
                    FROM consultas co
                    WHERE co.cita = c.id
                        AND co.personal = cb.personal
                )
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':registro', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':cita_bloque', $data['appointment_block_id'], PDO::PARAM_INT);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }
    
    public function updateConsultationInitialObservations(array $data) {
        try {
            $sql = "
                UPDATE consultas SET
                                observacion_inicial = :observations
                                WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function checkIfExistsConsultationProcedure(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM consultas_procedimientos cp
                INNER JOIN consultas c
                    ON cp.consulta = c.id
                INNER JOIN servicios s
                    ON cp.servicio = s.id
            WHERE c.uuid = :consultation_uuid
                AND s.uuid = :procedure_uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':procedure_uuid', $data['procedure_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);

        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function getConsultationProcedureOrigin($uuid): ?string {
        $stmt = $this->db->prepare("
            SELECT origen
            FROM consultas_procedimientos cp
            WHERE cp.uuid = :uuid
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function startConsultationProcedure(array $data) {
        $sql = "
            INSERT INTO consultas_procedimientos (
                uuid,
                consulta,
                servicio,
                cantidad,
                precio_unitario,
                bonificacion,
                total,
                cobrable,
                origen,
                registro,
                f_registro
            )
            SELECT 
                :uuid,
                c.id,
                s.id,
                1,
                cs.costo,
                cs.bonificacion,
                cs.costo,
                :chargeable,
                :origin,
                :uid,
                NOW()
            FROM consultas c
                INNER JOIN citas ct
                    ON c.cita = ct.id
                INNER JOIN citas_bloques cb
                    ON ct.id = cb.cita
                INNER JOIN servicios s
                    ON cb.servicio = s.id
                INNER JOIN personal_usuarios pu
                    ON cb.personal = pu.personal
                INNER JOIN usuarios u
                    ON pu.usuario = u.id
                INNER JOIN citas_servicios cs
                    ON cs.cita = ct.id
                        AND cs.servicio = s.id
                        and cs.personal = pu.personal
            WHERE c.uuid = :consultation_uuid
                AND s.id = :service_id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':origin', $data['origin'], PDO::PARAM_STR);
        $stmt->bindValue(':chargeable', $data['chargeable'], PDO::PARAM_INT);
        $stmt->bindValue(':service_id', $data['service_id'], PDO::PARAM_INT);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return $this->db->lastInsertId();
    }

    public function addConsultationProcedure(array $data) {
        try {
            $sql = "
                INSERT INTO consultas_procedimientos (
                    uuid,
                    consulta,
                    servicio,
                    cantidad,
                    precio_unitario,
                    bonificacion,
                    total,
                    cobrable,
                    origen,
                    registro,
                    f_registro
                ) SELECT 
                    :uuid,
                    c.id,
                    :procedure_id,
                    :quantity,
                    :unit_price,
                    :bonus,
                    :total,
                    :chargeable,
                    :origin,
                    :uid,
                    NOW()
                FROM consultas c
                WHERE c.uuid = :consultation_uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':quantity', $data['quantity'], PDO::PARAM_STR);
            $stmt->bindValue(':unit_price', $data['unit_price'], PDO::PARAM_STR);
            $stmt->bindValue(':bonus', $data['bonus'], PDO::PARAM_STR);
            $stmt->bindValue(':total', $data['total'], PDO::PARAM_STR);
            $stmt->bindValue(':origin', $data['origin'], PDO::PARAM_STR);
            $stmt->bindValue(':chargeable', $data['chargeable'], PDO::PARAM_INT);
            $stmt->bindValue(':procedure_id', $data['procedure_id'], PDO::PARAM_INT);
            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return $this->db->lastInsertId();
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function updateConsultationProcedure(array $data) {
        $sql = "
            UPDATE consultas_procedimientos SET
                cantidad = :quantity,
                bonificacion = :bonus,
                total = :total,
                cobrable = :chargeable,
                origen = :origin
                WHERE uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':quantity', $data['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':bonus', $data['bonus'], PDO::PARAM_STR);
        $stmt->bindValue(':total', $data['total'], PDO::PARAM_STR);
        $stmt->bindValue(':chargeable', $data['chargeable'], PDO::PARAM_INT);
        $stmt->bindValue(':origin', $data['origin'], PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }

    public function removeConsultationProcedure($uuid) {
        $sql = "
            DELETE FROM consultas_procedimientos WHERE uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }

    public function getConsultationProcedures(array $data) {
        $stmt = $this->db->prepare("
            SELECT cp.id,
                cp.uuid,
                s.id servicio_id,
                s.uuid servicio_uuid,
                s.servicio,
                cp.cantidad,
                cp.precio_unitario,
                cp.bonificacion,
                cp.total,
                cp.cobrable,
                cp.origen,
                cp.observaciones
            FROM consultas_procedimientos cp
                INNER JOIN consultas c
                    ON cp.consulta = c.id
                INNER JOIN servicios s
                    ON cp.servicio = s.id
                INNER JOIN usuarios u
                    ON cp.registro = u.id
            WHERE c.uuid = :uuid
                AND s.empresa = :empresa
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationPodiatricExploration(array $data) {
        $stmt = $this->db->prepare("
            SELECT ep.id,
                    ep.uuid,
                    ep.tipo_pie,
                    ep.formula_metatarsal,
                    ep.alteraciones_marcha,
                    ep.pulso_pedio_izquierdo,
                    ep.pulso_pedio_derecho,
                    ep.sensibilidad_izquierdo,
                    ep.sensibilidad_derecho,
                    ep.temperatura_pies,
                    ep.coloracion_pies,
                    ep.observaciones,
                    ep.recomendaciones
                    FROM exploracion_podologica ep
                        INNER JOIN consultas c
                            ON ep.consulta = c.id
                    WHERE c.uuid = :uuid
                        AND c.sucursal = :sucursal
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProcedureData($data) {
        $stmt = $this->db->prepare("
            SELECT s.id,
                s.uuid,
                s.servicio,
                s.costo_base,
                ps.costo
            FROM servicios s
                LEFT JOIN personal_servicios ps
                    ON s.id = ps.servicio
                    AND ps.personal = :uid
            WHERE s.uuid = :uuid
                AND s.empresa = :empresa
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getConsultationDiagnostics(array $data) {
        $stmt = $this->db->prepare("
            SELECT cd.id,
                cd.uuid,
                d.uuid diagnostico_uuid,
                d.codigo diagnostico_codigo,
                d.diagnostico,
                dt.id tipo_id,
                dt.codigo tipo_codigo,
                dt.tipo tipo,
                c.diagnostico_resumen,
                cd.observaciones
            FROM consultas_diagnosticos cd
                INNER JOIN consultas c
                    ON cd.consulta = c.id
                INNER JOIN diagnosticos d
                    ON cd.diagnostico_catalogo = d.id
                INNER JOIN diagnosticos_tipos dt
                    ON cd.tipo_diagnostico = dt.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConsultationDiagnosticsSummary(array $data) {
        $stmt = $this->db->prepare("
            SELECT c.diagnostico_resumen
            FROM consultas c
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getDiagnosticData($data) {
        $stmt = $this->db->prepare("
            SELECT d.id,
                d.uuid,
                d.codigo,
                d.diagnostico
            FROM diagnosticos d
            WHERE d.uuid = :uuid
                AND (d.empresa = :empresa
                    OR d.empresa IS NULL)
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkIfExistsConsultationDiagnostic(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM consultas_diagnosticos cd
                INNER JOIN consultas c
                    ON cd.consulta = c.id
                INNER JOIN diagnosticos d
                    ON cd.diagnostico_catalogo = d.id
            WHERE c.uuid = :consultation_uuid
                AND d.uuid = :diagnostic_uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':diagnostic_uuid', $data['diagnostic_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);

        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }
    
    public function addConsultationDiagnostic(array $data) {
        try {
            $sql = "
                INSERT INTO consultas_diagnosticos (
                    uuid,
                    consulta,
                    tipo_diagnostico,
                    diagnostico_catalogo,
                    diagnostico,
                    observaciones,
                    registro,
                    f_registro
                ) SELECT 
                    :uuid,
                    c.id,
                    :diagnostic_type,
                    :diagnostic,
                    :diagnostic_name,
                    :observations,
                    :uid,
                    NOW()
                FROM consultas c
                WHERE c.uuid = :consultation_uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':diagnostic_type', $data['diagnostic_type'], PDO::PARAM_INT);
            $stmt->bindValue(':diagnostic', $data['diagnostic'], PDO::PARAM_INT);
            $stmt->bindValue(':diagnostic_name', $data['diagnostic_name'], PDO::PARAM_STR);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
            $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return $this->db->lastInsertId();
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateConsultationDiagnostic(array $data) {
        try {
            $sql = "
                UPDATE consultas_diagnosticos SET
                                        tipo_diagnostico = :diagnostic_type,
                                        diagnostico = :diagnostic_name,
                                        observaciones = :observations
                                        WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':diagnostic_type', $data['diagnostic_type'], PDO::PARAM_INT);
            $stmt->bindValue(':diagnostic_name', $data['diagnostic_name'], PDO::PARAM_STR);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateConsultationDiagnosticObservations(array $data) {
        try {
            $sql = "
                UPDATE consultas SET
                        diagnostico_resumen = :observations
                        WHERE uuid = :uuid
                            AND sucursal = :sucursal
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function removeConsultationDiagnostic($uuid) {
        $sql = "
            DELETE FROM consultas_diagnosticos WHERE uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }
    
    public function getConsultationSores(array $data) {
        $stmt = $this->db->prepare("
            SELECT clu.id,
                clu.uuid,
                tl.id tipo_lesion_id,
                tl.codigo tipo_lesion_codigo,
                tl.tipo tipo_lesion,
                l.id lateralidad_id,
                l.lateralidad,
                clu.ubicacion,
                clu.largo_cm,
                clu.ancho_cm,
                clu.profundidad_cm,
                gw.id grado_wagner_id,
                gw.grado grado_wagner,
                tt.id tipo_tejido_id,
                tt.tipo tipo_tejido,
                te.id tipo_evolucion_id,
                te.tipo tipo_evolucion,
                tex.id tipo_exudado_id,
                tex.tipo tipo_exudado,
                ce.id color_exudado_id,
                ce.color color_exudado,
                clu.signos_infeccion,
                clu.dolor,
                clu.observaciones
            FROM consultas_lesiones_ulceras clu
                INNER JOIN consultas c
                    ON clu.consulta = c.id
                LEFT JOIN tipos_lesiones tl
                    ON clu.tipo_lesion = tl.id
                LEFT JOIN lateralidades l
                    ON clu.lateralidad = l.id
                LEFT JOIN grado_wagner gw
                    ON clu.grado_wagner = gw.id
                LEFT JOIN tipos_tejido tt
                    ON clu.tipo_tejido = tt.id
                LEFT JOIN tipos_evolucion te
                    ON clu.tipo_evolucion = te.id
                LEFT JOIN tipos_exudado tex
                    ON clu.tipo_exudado = tex.id
                LEFT JOIN color_exudado ce
                    ON clu.color_exudado = ce.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkIfExistsConsultationSore(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM consultas_lesiones_ulceras clu
                INNER JOIN consultas c
                    ON clu.consulta = c.id
            WHERE c.uuid = :consultation_uuid
                AND clu.uuid = :sore_uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sore_uuid', $data['sore_uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);

        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }
    
    public function addConsultationSore(array $data) {
        try {
            $sql = "
                INSERT INTO consultas_lesiones_ulceras (
                    uuid,
                    consulta,
                    tipo_lesion,
                    lateralidad,
                    ubicacion,
                    largo_cm,
                    ancho_cm,
                    profundidad_cm,
                    grado_wagner,
                    tipo_tejido,
                    tipo_evolucion,
                    tipo_exudado,
                    color_exudado,
                    signos_infeccion,
                    dolor,
                    observaciones,
                    registro,
                    f_registro
                ) SELECT 
                    :uuid,
                    c.id,
                    :sore,
                    :laterality,
                    :location,
                    :length,
                    :width,
                    :depth,
                    :wagner_scale,
                    :tissue,
                    :evolution,
                    :exudate,
                    :exudate_color,
                    :infection_signs,
                    :pain_scale,
                    :observations,
                    :uid,
                    NOW()
                FROM consultas c
                WHERE c.uuid = :consultation_uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':sore', $data['sore'], PDO::PARAM_INT);
            $stmt->bindValue(':laterality', $data['laterality'], PDO::PARAM_INT);
            $stmt->bindValue(':location', $data['location'], PDO::PARAM_STR);
            $stmt->bindValue(':length', $data['length'], PDO::PARAM_STR);
            $stmt->bindValue(':width', $data['width'], PDO::PARAM_STR);
            $stmt->bindValue(':depth', $data['depth'], PDO::PARAM_STR);
            $stmt->bindValue(':wagner_scale', $data['wagner_scale'], PDO::PARAM_INT);
            $stmt->bindValue(':tissue', $data['tissue'], PDO::PARAM_INT);
            $stmt->bindValue(':evolution', $data['evolution'], PDO::PARAM_INT);
            $stmt->bindValue(':exudate', $data['exudate'], PDO::PARAM_INT);
            $stmt->bindValue(':exudate_color', $data['exudate_color'], PDO::PARAM_INT);
            $stmt->bindValue(':infection_signs', $data['infection_signs'], PDO::PARAM_INT);
            $stmt->bindValue(':pain_scale', $data['pain_scale'], PDO::PARAM_INT);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
            $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return $this->db->lastInsertId();
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateConsultationSore(array $data) {
        try {
            $sql = "
                UPDATE consultas_lesiones_ulceras SET
                                        ubicacion = :location,
                                        observaciones = :observations
                                        WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':location', $data['location'], PDO::PARAM_STR);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function removeConsultationSore($uuid) {
        $sql = "
            DELETE FROM consultas_lesiones_ulceras WHERE uuid = :uuid
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uuid', $uuid, PDO::PARAM_LOB);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }

        return true;
    }
    
    public function updateConsultationIndications(array $data) {
        try {
            $sql = "
                UPDATE consultas SET
                                indicaciones = :indications
                                WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':indications', $data['indications'], PDO::PARAM_STR);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getPodiatricExplorationUuid(array $data) {
        $stmt = $this->db->prepare("
            SELECT ep.uuid
            FROM exploracion_podologica ep
                INNER JOIN consultas c
                    ON ep.consulta = c.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_LOB);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row != null ? $row['uuid'] : null;
    }

    public function insertConsultationPodiatricExploration(array $data) {
        try {
            $sql = "
                INSERT INTO exploracion_podologica(
                                                uuid,
                                                paciente,
                                                consulta,
                                                personal,
                                                tipo_pie,
                                                formula_metatarsal,
                                                alteraciones_marcha,
                                                pulso_pedio_derecho,
                                                pulso_pedio_izquierdo,
                                                sensibilidad_derecho,
                                                sensibilidad_izquierdo,
                                                temperatura_pies,
                                                coloracion_pies,
                                                observaciones,
                                                recomendaciones,
                                                f_exploracion,
                                                f_actualizacion,
                                                registro)
                                        SELECT
                                                :uuid,
                                                c.paciente,
                                                c.id,
                                                c.personal,
                                                :foot_type,
                                                :metatarsal_formula,
                                                :gait_disorder,
                                                :left_pulse_type,
                                                :right_pulse_type,
                                                :left_sensitivity_type,
                                                :right_sensitivity_type,
                                                :temperature_type,
                                                :foot_color_type,
                                                :observations,
                                                :advice,
                                                NOW(),
                                                NOW(),
                                                :uid
                                                FROM consultas c
                                                WHERE c.uuid = :consultation_uuid
            ";
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':foot_type', $data['foot_type'], PDO::PARAM_INT);
            $stmt->bindValue(':metatarsal_formula', $data['metatarsal_formula'], PDO::PARAM_INT);
            $stmt->bindValue(':gait_disorder', $data['gait_disorder'], PDO::PARAM_STR);
            $stmt->bindValue(':left_pulse_type', $data['left_pulse_type'], PDO::PARAM_INT);
            $stmt->bindValue(':right_pulse_type', $data['right_pulse_type'], PDO::PARAM_INT);
            $stmt->bindValue(':left_sensitivity_type', $data['left_sensitivity_type'], PDO::PARAM_INT);
            $stmt->bindValue(':right_sensitivity_type', $data['right_sensitivity_type'], PDO::PARAM_INT);
            $stmt->bindValue(':temperature_type', $data['temperature_type'], PDO::PARAM_INT);
            $stmt->bindValue(':foot_color_type', $data['foot_color_type'], PDO::PARAM_INT);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':advice', $data['advice'], PDO::PARAM_STR);
            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateConsultationPodiatricExploration(array $data) {
        try {
            $sql = "
                UPDATE exploracion_podologica SET
                                                tipo_pie = :foot_type,
                                                formula_metatarsal = :metatarsal_formula,
                                                alteraciones_marcha = :gait_disorder,
                                                pulso_pedio_derecho = :left_pulse_type,
                                                pulso_pedio_izquierdo = :right_pulse_type,
                                                sensibilidad_derecho = :left_sensitivity_type,
                                                sensibilidad_izquierdo = :right_sensitivity_type,
                                                temperatura_pies = :temperature_type,
                                                coloracion_pies = :foot_color_type,
                                                observaciones = :observations,
                                                recomendaciones = :advice,
                                                f_actualizacion = NOW()
                                WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':foot_type', $data['foot_type'], PDO::PARAM_INT);
            $stmt->bindValue(':metatarsal_formula', $data['metatarsal_formula'], PDO::PARAM_INT);
            $stmt->bindValue(':gait_disorder', $data['gait_disorder'], PDO::PARAM_STR);
            $stmt->bindValue(':left_pulse_type', $data['left_pulse_type'], PDO::PARAM_INT);
            $stmt->bindValue(':right_pulse_type', $data['right_pulse_type'], PDO::PARAM_INT);
            $stmt->bindValue(':left_sensitivity_type', $data['left_sensitivity_type'], PDO::PARAM_INT);
            $stmt->bindValue(':right_sensitivity_type', $data['right_sensitivity_type'], PDO::PARAM_INT);
            $stmt->bindValue(':temperature_type', $data['temperature_type'], PDO::PARAM_INT);
            $stmt->bindValue(':foot_color_type', $data['foot_color_type'], PDO::PARAM_INT);
            $stmt->bindValue(':observations', $data['observations'], PDO::PARAM_STR);
            $stmt->bindValue(':advice', $data['advice'], PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                die('false');
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function insertConsultationEvidence(array $data) {
        try {
            $sql = "
                INSERT INTO consultas_evidencia(consulta,
                                                uuid,
                                                tipo,
                                                extension,
                                                ancho,
                                                alto,
                                                peso,
                                                peso_miniatura,
                                                hash,
                                                hash_miniatura,
                                                raiz,
                                                archivo,
                                                raiz_miniatura,
                                                miniatura,
                                                registro,
                                                f_registro)
                                            SELECT
                                                c.id,
                                                :uuid,
                                                :type,
                                                :extension,
                                                :width,
                                                :height,
                                                :size,
                                                :thumb_size,
                                                :hash,
                                                :thumb_hash,
                                                :base_path,
                                                :file_name,
                                                :thumb_base_path,
                                                :thumb_name,
                                                :uid,
                                                NOW()
                                            FROM consultas c
                                            WHERE c.uuid = :consultation_uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
            $stmt->bindValue(':type', $data['type'], PDO::PARAM_STR);
            $stmt->bindValue(':extension', $data['extension'], PDO::PARAM_STR);
            $stmt->bindValue(':width', $data['width'], PDO::PARAM_INT);
            $stmt->bindValue(':height', $data['height'], PDO::PARAM_INT);
            $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
            $stmt->bindValue(':thumb_size', $data['thumb_size'], PDO::PARAM_INT);
            $stmt->bindValue(':hash', $data['hash'], PDO::PARAM_STR);
            $stmt->bindValue(':thumb_hash', $data['thumb_hash'], PDO::PARAM_STR);
            $stmt->bindValue(':base_path', $data['base_path'], PDO::PARAM_STR);
            $stmt->bindValue(':file_name', $data['file_name'], PDO::PARAM_STR);
            $stmt->bindValue(':thumb_base_path', $data['thumb_base_path'], PDO::PARAM_STR);
            $stmt->bindValue(':thumb_name', $data['thumb_name'], PDO::PARAM_STR);
            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
            $stmt->bindValue(':consultation_uuid', $data['consultation_uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return $this->db->lastInsertId();
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getConsultationEvidence(array $data) {
        $stmt = $this->db->prepare("
            SELECT ce.id,
                ce.uuid,
                ce.tipo,
                ce.extension,
                ce.ancho,
                ce.alto,
                ce.peso,
                ce.peso_miniatura,
                ce.hash,
                ce.hash_miniatura,
                ce.raiz,
                ce.archivo,
                ce.raiz_miniatura,
                ce.miniatura,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) personal
            FROM consultas_evidencia ce
                INNER JOIN consultas c
                    ON cd.consulta = c.id
                INNER JOIN usuarios u
                    ON ce.registro = u.id
                INNER JOIN personal_usuarios pu
                    ON cb.usuario = pu.usuario
                INNER JOIN personal p
                    ON pu.personal = p.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkIfConsultationPodriaticExplorationExists(array $data): bool {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM exploracion_podologica ep
                INNER JOIN consultas c
                    ON ep.consulta = c.id
            WHERE c.uuid = :consultation_uuid
                AND c.sucursal = :sucursal
            LIMIT 1
        ");
        $stmt->bindValue(':consultation_uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    
    public function finishConsultation(array $data) {
        try {
            $sql = "
                UPDATE consultas SET
                                estatus = 1,
                                finalizo = :uid,
                                f_fin = NOW()
                                WHERE uuid = :uuid
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':uid', $data['uid'], PDO::PARAM_INT);
            $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}