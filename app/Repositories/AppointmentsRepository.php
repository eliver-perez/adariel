<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class AppointmentsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function appointmentExistsByUuid(array $data): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM citas
            WHERE uuid = :uuid
                AND sucursal = :sucursal
            LIMIT 1
        ");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function changeAppointmentStatus(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas
            SET estatus = :status
            WHERE uuid = :appointment
                AND sucursal = :sucursal");
        $stmt->bindParam(':appointment', $data['appointment'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function finishAppointment(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas
            SET estatus = :status,
                termino_cita = :uid,
                f_termino_cita = NOW()
            WHERE uuid = :uuid
                AND sucursal = :sucursal");
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':uid', $data['uid']);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function cancelAppointmentBlocks(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas_bloques
            SET estatus = :status
            WHERE uuid = :appointment
                AND estatus != :canceled
                AND estatus != :refused
                AND estatus != :no_assistance
                AND estatus != :attended");
        $stmt->bindParam(':appointment', $data['appointment'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']['canceled']);
        $stmt->bindParam(':canceled', $data['status']['canceled']);
        $stmt->bindParam(':refused', $data['status']['refused']);
        $stmt->bindParam(':no_assistance', $data['status']['noAssistance']);
        $stmt->bindParam(':attended', $data['status']['attended']);
        $stmt->execute();
    }

    public function getFirstAppointmentBlock(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT cb.id,
                cb.uuid
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON cb.cita = c.id
            WHERE c.uuid = :appointment
                AND cb.estatus = :status
                AND c.sucursal = :sucursal
            ORDER BY cb.h_inicio ASC
            LIMIT 1
        ");
        $stmt->bindParam(':appointment', $data['appointment'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $appointmentBlock = $stmt->fetch();

        if ($appointmentBlock === false) {
            throw new \RuntimeException('No hay bloques de citas pendientes.');
        }

        return $appointmentBlock;
    }

    public function getFirstReadyAppointmentBlock(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT cb.id,
                cb.uuid
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON cb.cita = c.id
                INNER JOIN citas_bloques_estatus cbe
                    ON cb.estatus = cbe.id
            WHERE c.uuid = :appointment
                AND (cbe.codigo = 'en_espera'
                    OR cbe.codigo = 'en_proceso')
                AND c.sucursal = :sucursal
            ORDER BY cb.h_inicio ASC
            LIMIT 1
        ");
        $stmt->bindParam(':appointment', $data['appointment'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $appointmentBlock = $stmt->fetch();

        return $appointmentBlock != null ? $appointmentBlock : [];
    }

    public function changeAppointmentBlockStatus(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas_bloques
            SET estatus = :status
            WHERE id = :block");
        $stmt->bindParam(':block', $data['block']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();
    }

    public function changeAppointmentBlockStatusByUuid(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas_bloques
            SET estatus = :status
            WHERE uuid = :block");
        $stmt->bindParam(':block', $data['block'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();
    }

    public function finishAppointmentBlock(array $data) {
        $stmt = $this->db->prepare("
            UPDATE citas_bloques
            SET estatus = :status,
                termino_cita = :uid,
                f_termino_cita = NOW()
            WHERE uuid = :block");
        $stmt->bindParam(':block', $data['block'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':uid', $data['uid']);
        $stmt->execute();
    }

    public function appointmentStatus(array $data): string {
        $stmt = $this->db->prepare("
            SELECT
            ce.codigo
            FROM citas c
                INNER JOIN citas_estatus ce
                    ON c.estatus = ce.id
            WHERE uuid = :uuid
                AND c.sucursal = :sucursal
        ");
        
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null ? $data['codigo'] : '';
    }

    public function getAppointmentId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT
            c.id
            FROM citas c
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
        ");
        
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null ? $data['id'] : 0;
    }

    public function getAppointmentAssignment(array $data): ?bool {
        $stmt = $this->db->prepare("
            SELECT
            cb.id
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON cb.cita = c.id
                INNER JOIN personal p
                    ON p.id = cb.personal
                INNER JOIN personal_usuarios pu
                    ON pu.personal = p.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
                AND pu.usuario = :personal
        ");
        
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindParam(':personal', $data['staff'], PDO::PARAM_INT);
        $stmt->execute();
        $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt_data != null ? true : false;
    }

    public function getAppointmentBlockId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT
            cb.id
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON cb.cita = c.id
            WHERE cb.uuid = :uuid
                AND c.sucursal = :sucursal
        ");
        
        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data != null ? $data['id'] : 0;
    }

    public function getUnfinishedAppointmentBlocksCount($data): ?array {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) pendientes
            FROM citas_bloques cb
                INNER JOIN citas c
                    ON cb.cita = c.id
            WHERE c.uuid = :uuid
                AND c.sucursal = :sucursal
            AND cb.estatus <> :status;
            ");

        $stmt->bindParam(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row != null ? $row : null;
    }

    public function getCalendarAppointments(array $data): array {
        $stmt = $this->db->prepare("
            SELECT
            c.uuid,
            TRIM(
                CONCAT(
                    p.nombre, ' ',
                    COALESCE(p.paterno, ''), ' ',
                    COALESCE(p.materno, '')
                )
            ) paciente,
            p.clave clave_paciente,
            COALESCE(DATE_FORMAT(p.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
            p.email,
            p.telefono,
            g.genero,
            ca.asunto,
            c.fecha,
            c.motivo_consulta,
            c.h_inicio,
            c.h_fin,
            ce.codigo estatus_codigo,
            ce.text_color,
            ce.classname,
            ce.background
            FROM citas c
                INNER JOIN citas_estatus ce
                    ON c.estatus = ce.id
                INNER JOIN citas_asuntos ca
                    ON c.asunto = ca.id
                INNER JOIN pacientes p
                    ON c.paciente = p.id
                INNER JOIN generos g
                    ON p.genero = g.id
            WHERE c.fecha >= :start
                AND c.fecha < :end
                AND c.sucursal = :branch
            ");
        $stmt->bindValue(':start', $data['start']);
        $stmt->bindValue(':end', $data['end']);
        $stmt->bindValue(':branch', $data['branch']);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProcedureCost(array $data): float {
        $stmt = $this->db->prepare("
            SELECT ps.costo
            FROM servicios s
                INNER JOIN personal_servicios ps
                    ON s.id = ps.servicio
                INNER JOIN personal p
                    ON ps.personal = p.id
            WHERE s.uuid = :procedure_uuid
                AND p.uuid = :staff_uuid
                AND s.empresa = :empresa
        ");
        $stmt->bindValue(':procedure_uuid', $data['procedureId'], PDO::PARAM_LOB);
        $stmt->bindValue(':staff_uuid', $data['staffId'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $cost = $stmt->fetchColumn();

        if ($cost === false) {
            throw new \RuntimeException('Costo no encontrado');
        }

        return (float)$cost;
    }

    public function getStaffName(array $data): string {
        $stmt = $this->db->prepare("
            SELECT TRIM(
                CONCAT(
                    nombre, ' ',
                    COALESCE(paterno, ''), ' ',
                    COALESCE(materno, '')
                )
            )
            FROM personal
            WHERE uuid = :uuid
                AND empresa = :empresa
        ");
        $stmt->bindValue(':uuid', $data['staffId'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $name = $stmt->fetchColumn();

        if ($name === false) {
            throw new \RuntimeException("Staff no encontrado");
        }

        return $name;
    }

    public function getProcedureName(array $data): string {
        $stmt = $this->db->prepare("
            SELECT servicio
            FROM servicios
            WHERE uuid = :uuid
                AND empresa = :empresa
        ");
        $stmt->bindValue(':uuid', $data['procedureId'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $name = $stmt->fetchColumn();

        if ($name === false) {
            throw new \RuntimeException("Procedimiento no encontrado");
        }

        return $name;
    }

    public function getStaffAvailability(array $data): array {
        // 1. Día de la semana
        $weekday = (int)date('w', strtotime($data['date']));

        /*
            DISPONIBILIDAD BASE
        */
        $stmt = $this->db->prepare("
            SELECT hld.hora_inicio AS start, hld.hora_fin AS end
            FROM horarios_laborales_detalles hld
                JOIN horarios_laborales h
                    ON h.id = hld.horario
                INNER JOIN personal p
                    ON h.personal = p.id
            WHERE p.uuid = :staff_uuid
                AND hld.dia_semana = :day
                AND h.sucursal = :sucursal
                AND h.activo = 1
        ");
        $stmt->bindValue(':staff_uuid', $data['staffId'], PDO::PARAM_LOB);
        $stmt->bindValue(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->bindValue(':day', $weekday);

        $stmt->execute();

        $dayAvailability = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*
            BLOQUEOS
        */
        $stmt = $this->db->prepare("
            SELECT h_inicio AS start, h_fin AS end
            FROM bloqueos_agenda
            WHERE personal = :staff
                AND sucursal = :sucursal
                AND f_inicio <= :date_start
                AND f_fin >= :date_end
        ");

        $stmt->execute([
            'staff' => $data['staffId'],
            'sucursal' => $data['branch'],
            'date_start' => $data['date'],
            'date_end' => $data['date']
        ]);

        $dayUnavailable = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*
            CITAS ACTIVAS
        */
        $stmt = $this->db->prepare("
            SELECT cb.h_inicio AS start, cb.h_fin AS end
            FROM citas c
            JOIN citas_bloques cb ON c.id = cb.cita
            JOIN citas_estatus ce ON c.estatus = ce.id
            WHERE cb.personal = :staff
                AND ce.codigo NOT IN ('cancelada', 'rechazada')
                AND c.sucursal = :sucursal
                AND c.fecha = :date
        ");

        $stmt->execute([
            'staff' => $data['staffId'],
            'sucursal' => $data['branch'],
            'date' => $data['date']
        ]);

        $dayBusy = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /*
            NORMALIZACIÓN
        */
        $free = $this->normalize($dayAvailability);
        $unavailable = $this->clipToWindows($dayUnavailable, $free);
        $busy = $this->clipToWindows($dayBusy, $free);

        $free = $this->subtractIntervals($free, $unavailable);
        $free = $this->subtractIntervals($free, $busy);

        /*
            RECORTE SI ES HOY
        */
        if ($data['date'] === date('Y-m-d')) {
            $nowMinutes = (int)date('H') * 60 + (int)date('i');

            $remainder = $nowMinutes % $data['interval'];
            if ($remainder !== 0) {
                $nowMinutes += ($data['interval'] - $remainder);
            }

            $free = $this->trimPastIntervals($free, $nowMinutes);
        }

        return $free;
    }

    private function normalize(array $intervals): array {
        $valid = [];

        foreach ($intervals as $iv) {
            if ((int)$iv['end'] > (int)$iv['start']) {
                $valid[] = [
                    'start' => (int)$iv['start'],
                    'end' => (int)$iv['end']
                ];
            }
        }

        if (empty($valid)) {
            return [];
        }

        usort($valid, fn($a, $b) =>
            $a['start'] === $b['start']
                ? $a['end'] <=> $b['end']
                : $a['start'] <=> $b['start']
        );

        $result = [$valid[0]];

        foreach ($valid as $current) {
            $last = &$result[count($result) - 1];

            if ($current['start'] <= $last['end']) {
                $last['end'] = max($last['end'], $current['end']);
            } else {
                $result[] = $current;
            }
        }

        return $result;
    }

    private function clipToWindows(array $intervals, array $windows): array {
        $result = [];

        foreach ($intervals as $iv) {
            foreach ($windows as $w) {
                $start = max($iv['start'], $w['start']);
                $end = min($iv['end'], $w['end']);

                if ($end > $start) {
                    $result[] = ['start' => $start, 'end' => $end];
                }
            }
        }

        return $this->normalize($result);
    }

    private function subtractIntervals(array $base, array $remove): array {
        $result = [];

        foreach ($base as $b) {
            $currentStart = $b['start'];
            $currentEnd = $b['end'];

            foreach ($remove as $r) {
                if ($r['end'] <= $currentStart || $r['start'] >= $currentEnd) {
                    continue;
                }

                if ($r['start'] > $currentStart) {
                    $result[] = [
                        'start' => $currentStart,
                        'end' => min($r['start'], $currentEnd)
                    ];
                }

                $currentStart = max($currentStart, $r['end']);

                if ($currentStart >= $currentEnd) {
                    break;
                }
            }

            if ($currentStart < $currentEnd) {
                $result[] = [
                    'start' => $currentStart,
                    'end' => $currentEnd
                ];
            }
        }

        return $this->normalize($result);
    }

    private function trimPastIntervals(array $intervals, int $minStart): array {
        $result = [];

        foreach ($intervals as $iv) {
            if ($iv['end'] <= $minStart) {
                continue;
            }

            if ($iv['start'] < $minStart) {
                $iv['start'] = $minStart;
            }

            $result[] = $iv;
        }

        return $result;
    }

    public function insertAppointment(array $data): ?int {
        $stmt = $this->db->prepare("
            INSERT INTO citas (
                uuid,
                sucursal,
                ejercicio,
                paciente,
                asunto,
                forma,
                motivo_consulta,
                fecha,
                h_inicio,
                duracion,
                h_fin,
                estatus,
                registro,
                costo,
                adeudo,
                pagado,
                bonificacion,
                f_registro,
                f_actualizacion
            ) VALUES (
                :uuid,
                :sucursal,
                YEAR(NOW()),
                :paciente,
                :asunto,
                :forma,
                :motivo_consulta,
                :fecha,
                :h_inicio,
                :duracion,
                :h_fin,
                :estatus,
                :registro,
                :costo,
                :adeudo,
                0,
                0,
                NOW(),
                NOW()
            )");


        $stmt->execute([
            'uuid'                  => $data['uuid'],
            'sucursal'              => $data['branch'],
            'paciente'              => $data['patient'],
            'asunto'                => $data['appointment_type'],
            'forma'                 => $data['booking_channel'],
            'motivo_consulta'       => $data['chief_complaint'],
            'fecha'                 => $data['appointment_date'],
            'h_inicio'              => $data['appointment_start'],
            'duracion'              => $data['appointment_duration'],
            'h_fin'                 => $data['appointment_end'],
            'estatus'               => $data['status'],
            'registro'              => $data['uid'],
            'costo'                 => $data['appointment_cost'],
            'adeudo'                => $data['appointment_cost'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertAppointmentBlock(array $data): void {
        $stmt = $this->db->prepare("
            INSERT INTO citas_bloques (
				uuid,
				cita,
				personal,
				servicio,
				orden,
				h_inicio,
				h_fin,
				duracion,
				estatus
			) VALUES(
                :uuid,
                :cita,
                :personal,
                :servicio,
                :orden,
                :h_inicio,
                :h_fin,
                :duracion,
                :estatus
            )");


        $stmt->execute([
            'uuid'                  => $data['uuid'],
            'cita'                  => $data['appointment'],
            'personal'              => $data['staff'],
            'servicio'              => $data['procedure'],
            'orden'                 => $data['order'],
            'h_inicio'              => $data['start'],
            'h_fin'                 => $data['end'],
            'duracion'              => $data['duration'],
            'estatus'               => $data['status'],
        ]);
    }

    public function insertAppointmentProcedure(array $data): void {
        $stmt = $this->db->prepare("
            INSERT INTO citas_servicios (
				cita,
				servicio,
				personal,
				costo,
				bonificacion
			) VALUES(
                :cita,
                :servicio,
                :personal,
                :costo,
                0
             )");


        $stmt->execute([
            'cita'                  => $data['appointment'],
            'servicio'              => $data['procedure'],
            'personal'              => $data['staff'],
            'costo'                 => $data['cost'],
        ]);
    }

    public function getAppointmentConsecutive(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT
            COALESCE(MAX(consecutivo), 0) + 1 consecutive
            FROM citas c
            WHERE c.sucursal = :sucursal
                AND c.ejercicio = YEAR(NOW())
            FOR UPDATE
        ");
        
        $stmt->bindParam(':sucursal', $data['branch'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function updateAppointmentConsecutive(array $data): void {
        $stmt = $this->db->prepare("
            UPDATE citas SET
				consecutivo = :consecutivo,
				folio = :folio
                WHERE id = :id");
        $stmt->bindParam(':consecutivo', $data['consecutive']);
        $stmt->bindParam(':folio', $data['folio']);
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
    }
}