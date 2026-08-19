<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsTypesRepository;
use App\Repositories\BookingChannelsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class AppointmentsService extends Service
{
    public function __construct(
        private AppointmentsRepository $appointmentsRepository,
        private AppointmentsTypesRepository $appointmentsTypesRepository,
        private BookingChannelsRepository $bookingChannelRepository,
        private AppointmentsStatusRepository $appointmentsStatusRepository,
        private PatientsRepository $patientsRepository,
        private StaffRepository $staffRepository,
        private ProceduresRepository $proceduresRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAvailableSlots(int $organization): array {
        $settingsService = new SettingsService($this->settingsRepository);
        $interval = $settingsService->get('agenda_intervalo_minutos', $organization);
    }

    public function scheduleAppointment(array $data): string {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $datetimeService = new DateTimeService($data['timezone']);

        $settingsService = new SettingsService($this->settingsRepository);
        $appointmentCodePrefix = $settingsService->get('codigo_cita', $organizationId);

        $date = $this->formatDateToSQL($data['appointment']['date'] ?? null);
        $appointmentStart = $this->timeToMinutes($data['appointment']['start']);
        $appointmentEnd = $this->timeToMinutes($data['appointment']['end']);
        $duration = $data['appointment']['duration'];
        $procedures = $data['appointment']['procedures'];

        $status = $this->appointmentsStatusRepository->getIdByCode('agendada');
        $block_status = $this->appointmentsStatusRepository->getBlockIdByCode('agendada');

        if($duration != ($appointmentEnd - $appointmentStart)) {
            throw new InvalidArgumentException('Hay un problema con la duracion de la cita');
        }

        if(!is_array($procedures) || count($procedures) === 0) {
            throw new InvalidArgumentException('No se recibio la lista de procedimientos de la cita');
        }

        $cost = 0;
        $procedures_cost = array();
        foreach($procedures as $p) {
            $procedures_cost[$p['procedure_id'].':'.$p['staff_id']] = $this->appointmentsRepository->getProcedureCost([
                'organization'                      => $organizationId,
                'procedureId'                       => $this->uuidStringToBinary($p['procedure_id']),
                'staffId'                           => $this->uuidStringToBinary($p['staff_id'])
                ]);
            $cost += $procedures_cost[$p['procedure_id'].':'.$p['staff_id']];
        }

        $patientId = $this->normalizeRequiredText(
            $data['patient'] ?? null,
            'La seleccion del paciente es obligatorio.'
        );

        $patientIdInt = $this->patientsRepository->getPatientId([
            'uuid'                          => $this->uuidStringToBinary($patientId),
            'organization'                  => $organizationId
        ]);

        $appointmentType = $this->normalizeRequiredInt(
            $data['appointment_type'] ?? null,
            'El tipo de cita es obligatorio.'
        );

        $bookingChannel = $this->normalizeRequiredInt(
            $data['booking_channel'] ?? null,
            'La opcion de como se agendo la cita es obligatorio.'
        );

        $chiefComplaint = $this->normalizeOptionalText($data['chief_complaint'] ?? null);

        if (!$this->appointmentsTypesRepository->existsById($appointmentType)) {
            throw new InvalidArgumentException('El tipo de cita no existe.');
        }

        if (!$this->bookingChannelRepository->existsById($bookingChannel)) {
            throw new InvalidArgumentException('El metodo como se agendo la cita no existe.');
        }

        $conn = $this->appointmentsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $appointmentUuid = $this->generateUuidBinary();
            $appointmentId = $this->appointmentsRepository->insertAppointment([
                    'uuid'                          => $appointmentUuid,
                    'branch'                        => $branchId,
                    'patient'                       => $patientIdInt,
                    'appointment_type'              => $appointmentType,
                    'booking_channel'               => $bookingChannel,
                    'chief_complaint'               => $chiefComplaint,
                    'appointment_date'              => $date,
                    'appointment_start'             => $appointmentStart,
                    'appointment_duration'          => $duration,
                    'appointment_end'               => $appointmentEnd,
                    'uid'                           => $uid,
                    'appointment_cost'              => $cost,
                    'status'                        => $status
                ]);

            $order = 1;
            foreach($procedures as $p) {
                $block_start = $this->timeToMinutes($p['start']);
                $block_end = $this->timeToMinutes($p['end']);
                $block_duration = $block_end - $block_start;

                $staffIdInt = $this->staffRepository->getStaffId([
                    'uuid'                          => $this->uuidStringToBinary($p['staff_id']),
                    'organization'                  => $organizationId
                ]);
                $procedureIdInt = $this->proceduresRepository->getProcedureId([
                    'uuid'                          => $this->uuidStringToBinary($p['procedure_id']),
                    'organization'                  => $organizationId
                ]);

                $appointmentBlockUuid = $this->generateUuidBinary();
                $this->appointmentsRepository->insertAppointmentBlock([
                    'uuid'                          => $appointmentBlockUuid,
                    'appointment'                   => $appointmentId,
                    'staff'                         => $staffIdInt,
                    'procedure'                     => $procedureIdInt,
                    'order'                         => $order++,
                    'start'                         => $block_start,
                    'end'                           => $block_end,
                    'duration'                      => $block_duration,
                    'status'                        => $block_status
                ]);

                $appointmentConsecutive = $this->appointmentsRepository->getAppointmentConsecutive([
                    'branch'                        => $branchId
                ]);
                $appointmentCode = $appointmentCodePrefix . '-' . str_pad((string)$appointmentConsecutive, 5, '0', STR_PAD_LEFT);

                $this->appointmentsRepository->updateAppointmentConsecutive([
                    'id'                            => $appointmentId,
                    'consecutive'                   => $appointmentConsecutive,
                    'folio'                         => $appointmentCode
                ]);

                $this->appointmentsRepository->insertAppointmentProcedure([
                    'appointment'                   => $appointmentId,
                    'procedure'                     => $procedureIdInt,
                    'staff'                         => $staffIdInt,
                    'cost'                          => $procedures_cost[$p['procedure_id'].':'.$p['staff_id']]
                ]);
            }

            $conn->commit();
            return $this->uuidBinaryToString($appointmentUuid);
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function scheduleAppointmentQuick(array $data): string {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $patientUuid = $this->normalizeRequiredText(
            $data['patient'] ?? null,
            'Es necesario seleccionar un paciente.'
        );
        $appointment_type = $this->normalizeRequiredInt(
            $data['appointment_type'] ?? null,
            'Es necesario seleccionar el tipo de cita.'
        );
        $booking_channel = $this->normalizeRequiredInt(
            $data['booking_channel'] ?? null,
            'Es necesario seleccionar como se agendo la cita.'
        );
        $staffUuid = $this->normalizeRequiredText(
            $data['staff'] ?? null,
            'Es necesario seleccionar quien atiende la cita.'
        );
        $procedureUuid = $this->normalizeRequiredText(
            $data['procedure'] ?? null,
            'Es necesario seleccionar el procedimiento.'
        );
        $date = $this->normalizeRequiredText(
            $data['date'] ?? null,
            'Es necesario capturar la fecha de la cita.'
        );
        $time = $this->normalizeRequiredText(
            $data['time'] ?? null,
            'Es necesario capturar la hora de la cita.'
        );
        $chiefComplaint = $this->normalizeOptionalText($data['chief_complaint'] ?? null);

        $settingsService = new SettingsService($this->settingsRepository);
        $appointmentCodePrefix = $settingsService->get('codigo_cita', $organizationId);

        $patientId = $this->patientsRepository->getPatientId([
            'uuid'                                  => $this->uuidStringToBinary($patientUuid),
            'organization'                          => $organizationId
        ]);

        $staffId = $this->staffRepository->getStaffId([
            'uuid'                                  => $this->uuidStringToBinary($staffUuid),
            'organization'                          => $organizationId
        ]);

        $procedureId = $this->proceduresRepository->getProcedureId([
            'uuid'                          => $this->uuidStringToBinary($procedureUuid),
            'organization'                  => $organizationId
        ]);

        $procedure_data = $this->proceduresRepository->getProcedureStaffData([
            'staff'                                 => $this->uuidStringToBinary($staffUuid),
            'procedure'                             => $this->uuidStringToBinary($procedureUuid),
            'organization'                          => $organizationId
        ]);
        if($procedure_data == null)
            $procedure_data = $this->proceduresRepository->getProcedureData([
                'uuid'                              => $this->uuidStringToBinary($procedureUuid),
                'organization'                      => $organizationId
            ]);

        if($procedure_data == null)
            throw new RuntimeException("No se encontro información del procedimiento");

        // $date = $this->formatDateToSQL($data['date'] ?? null);
        $duration = $procedure_data['duracion_min'];
        $appointmentStart = $this->timeToMinutes($time);
        $appointmentEnd = $this->timeToMinutes($time) + $duration;

        $cost = $procedure_data['costo'] ?? $procedure_data['costo_base'];

        $status = $this->appointmentsStatusRepository->getIdByCode('en_espera');
        $block_status = $this->appointmentsStatusRepository->getBlockIdByCode('en_espera');

        if (!$this->appointmentsTypesRepository->existsById($appointment_type)) {
            throw new InvalidArgumentException('El tipo de cita no existe.');
        }

        if (!$this->bookingChannelRepository->existsById($booking_channel)) {
            throw new InvalidArgumentException('El metodo como se agendo la cita no existe.');
        }

        $conn = $this->appointmentsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $appointmentUuid = $this->generateUuidBinary();
            $appointmentId = $this->appointmentsRepository->insertAppointment([
                    'uuid'                          => $appointmentUuid,
                    'branch'                        => $branchId,
                    'patient'                       => $patientId,
                    'appointment_type'              => $appointment_type,
                    'booking_channel'               => $booking_channel,
                    'chief_complaint'               => $chiefComplaint,
                    'appointment_date'              => $date,
                    'appointment_start'             => $appointmentStart,
                    'appointment_duration'          => $duration,
                    'appointment_end'               => $appointmentEnd,
                    'uid'                           => $uid,
                    'appointment_cost'              => $cost,
                    'status'                        => $status
                ]);

            $appointmentBlockUuid = $this->generateUuidBinary();
            $this->appointmentsRepository->insertAppointmentBlock([
                'uuid'                          => $appointmentBlockUuid,
                'appointment'                   => $appointmentId,
                'staff'                         => $staffId,
                'procedure'                     => $procedureId,
                'order'                         => 1,
                'start'                         => $appointmentStart,
                'end'                           => $appointmentEnd,
                'duration'                      => $duration,
                'status'                        => $block_status
            ]);
            $appointmentConsecutive = $this->appointmentsRepository->getAppointmentConsecutive([
                'branch'                        => $branchId
            ]);
            $appointmentCode = $appointmentCodePrefix . '-' . str_pad((string)$appointmentConsecutive, 5, '0', STR_PAD_LEFT);

            $this->appointmentsRepository->updateAppointmentConsecutive([
                'id'                            => $appointmentId,
                'consecutive'                   => $appointmentConsecutive,
                'folio'                         => $appointmentCode
            ]);

            $this->appointmentsRepository->insertAppointmentProcedure([
                'appointment'                   => $appointmentId,
                'procedure'                     => $procedureId,
                'staff'                         => $staffId,
                'cost'                          => $cost
            ]);

            $conn->commit();
            return $this->uuidBinaryToString($appointmentUuid);
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function getAppointmentAssignment(array $data): array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $uuid = $this->normalizeRequiredText(
            $data['uuid'] ?? null,
            'No se recibio identificador de cita.'
        );

        try {
            $appointmentBlock = $this->appointmentsRepository->getFirstReadyAppointmentBlock([
                'appointment'                           => $this->uuidStringToBinary($uuid),
                'branch'                                => $branchId
            ]);

            $status = $this->appointmentsRepository->appointmentStatus([
                'uuid'                                  => $this->uuidStringToBinary($uuid),
                'branch'                                => $branchId
            ]);

            $assigned = $this->appointmentsRepository->getAppointmentAssignment([
                'branch'                                => $branchId,
                'uuid'                                  => $this->uuidStringToBinary($uuid),
                'staff'                                 => $uid
            ]);

            $html_block = '';
            if($assigned && ($status == 'en_espera' || $status == 'en_proceso')) {
                $text = "";
                $onclick = "window.open('/consultations/".$this->uuidBinaryToString($appointmentBlock['uuid'])."?callBack=calendar', '_self')";
                if($status == 'en_espera') {
                    $text = "Iniciar Consulta";
                } else if($status == 'en_proceso') {
                    $text = "Continuar Consulta";
                }
                $html_block = '<button type="button"
                                    id="btn-appointment-start-consultation"
                                    onclick="'.$onclick.'"
                                    class="px-[30px] h-[34px] mb-[10px] text-white bg-primary border-primary hover:bg-primary-hbr font-medium rounded-4 text-xs xs:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear"
                                    data-te-ripple-init=""
                                    data-te-ripple-color="light">
                                        '.$text.'
                                    </button>';
            }

            return [
                'uuid'                  => $uuid,
                'status'                => $status,
                'assigned'              => $assigned,
                'start'                 => $html_block
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getCalendarAppointments(array $data): array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        try {
            $startDate = new \DateTimeImmutable($data['start']);
            $endDate   = new \DateTimeImmutable($data['end']);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Formato de fechas inválido');
        }

        try {
            $start_date = $startDate->format('Y-m-d');
            $end_date   = $endDate->format('Y-m-d');

            $data = $this->appointmentsRepository->getCalendarAppointments([
                'organization'                          => $organizationId,
                'branch'                                => $branchId,
                'start'                                 => $start_date,
                'end'                                   => $end_date,
                'uid'                                   => $uid
            ]);
            $appointments = array();

            foreach($data as $d) {
                array_push($appointments, array(
                    'id' => $this->uuidBinaryToString($d['uuid']),
                    'title' => $d['paciente'],
                    'start' => $d['fecha'].'T'.$this->minutesToTime($d['h_inicio']).':00',
                    'end' => $d['fecha'].'T'.$this->minutesToTime($d['h_fin']).':00',
                    'className' => $d['classname'] ?? 'primary',
                    'backgroundColor' => $d['background'] ?? '#EAEAEA',
                    'borderColor' => $d['text_color'] ?? '#000000',
                    'textColor' => $d['text_color'] ?? '#FFFFFF',
                    'extendedProps' => [
                        'patient' => $d['paciente'],
                        'patient_code' => $d['clave_paciente'] ?? '',
                        'status' => $d['estatus_codigo'],
                        'appointment_type' => $d['asunto'],
                        'description' => $d['motivo_consulta'] ?? '',
                        'dob' => $d['f_nacimiento'] ?? '',
                        'age' => $d['f_nacimiento'] ? $this->calculateAge($d['f_nacimiento']) : '',
                        'email' => $d['email'] ?? '',
                        'phone' => $d['telefono'] ?? '',
                        'gender' => $d['genero'] ?? '',
                    ],
                ));
            }

            return $appointments;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function calculateAppointmentAvailability(array $data): array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $slots = [];

        $settingsService = new SettingsService($this->settingsRepository);
        $interval = (int)$settingsService->get('agenda_intervalo_minutos', $organizationId);
        
        $date_sql = \DateTime::createFromFormat('d/m/Y', $data['date']);

        if ($interval <= 0) {
            $interval = 15;
        }

        $staffAvailability = [];
        $staffNames = [];
        $procedureCosts = [];

        foreach ($data['procedures'] as $procedure) {
            // $staffId = (int)($procedure['staffId'] ?? 0);
            // $procedureId = (int)($procedure['procedureId'] ?? 0);
            $staffId = $this->uuidStringToBinary($procedure['staffId']);
            $procedureId = $this->uuidStringToBinary($procedure['procedureId']);

            if (isset($staffAvailability[$staffId])) {
                continue;
            }

            $staffNames[$staffId] = $this->appointmentsRepository->getStaffName([
                'organization'                      => $organizationId,
                'staffId'                           => $staffId
            ]);
            $procedureName = $this->appointmentsRepository->getProcedureName([
                'organization'                      => $organizationId,
                'procedureId'                       => $procedureId
            ]);

            $procedureCosts[$procedureId] = $this->appointmentsRepository->getProcedureCost([
                'organization'                      => $organizationId,
                'procedureId'                       => $procedureId,
                'staffId'                           => $staffId
            ]);

            $intervals = $this->appointmentsRepository->getStaffAvailability([
                'organization'                      => $organizationId,
                'branch'                            => $branchId,
                'date'                              => $date_sql->format('Y-m-d'),
                'staffId'                           => $staffId,
                'interval'                          => $interval,
                'uid'                               => $uid
            ]);

            if (count($intervals) === 0) {
                return [];
            }

            $staffAvailability[$staffId] = $intervals;
        }

        $firstProcedure = $data['procedures'][0];
        // $firstStaffId = (int)$firstProcedure['staffId'];
        $firstStaffId = $this->uuidStringToBinary($firstProcedure['staffId']);
        $firstDuration = (int)$firstProcedure['duration'];

        $firstIntervals = $staffAvailability[$firstStaffId];

        foreach ($firstIntervals as $baseInterval) {
            $baseStart = (int)$baseInterval['start'];
            $baseEnd = (int)$baseInterval['end'];

            for ($start = $baseStart; $start + $firstDuration <= $baseEnd; $start += $interval) {
                $currentStart = $start;
                $valid = true;
                $procedureBlocks = [];
                $totalDuration = 0;

                foreach ($data['procedures'] as $procedure) {
                    $procedureId = $this->uuidStringToBinary($procedure['procedureId']); 
                    $staffId = $this->uuidStringToBinary($procedure['staffId']);
                    $duration = (int)$procedure['duration'];

                    $currentEnd = $currentStart + $duration;

                    $intervals = $staffAvailability[$staffId] ?? [];

                    if (!$this->fitsInIntervals($intervals, $currentStart, $currentEnd)) {
                        $valid = false;
                        break;
                    }

                    $procedureBlocks[] = [
                        'procedure_id' => $this->uuidBinaryToString($procedureId),
                        'procedure' => $procedureName,
                        'staff_id' => $this->uuidBinaryToString($staffId),
                        'staff_name' => $staffNames[$staffId] ?? '',
                        'start' => $this->minutesToTime($currentStart),
                        'end' => $this->minutesToTime($currentEnd),
                        'cost' => $procedureCosts[$procedureId] ?? 0,
                    ];

                    $totalDuration += $duration;
                    $currentStart = $currentEnd;
                }

                if ($valid) {
                    $slots[] = [
                        'start' => $this->minutesToTime($start),
                        'end' => $this->minutesToTime($start + $totalDuration),
                        'duration' => $totalDuration,
                        'procedures' => $procedureBlocks,
                    ];
                }
            }
        }

        return $slots;
    }

    private function fitsInIntervals(array $intervals, int $start, int $end): bool {
        foreach ($intervals as $interval) {
            if ($start >= (int)$interval['start'] && $end <= (int)$interval['end']) {
                return true;
            }
        }

        return false;
    }

    public function checkIn(array $data) {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $appointment = $this->normalizeRequiredText(
            $data['appointment'] ?? null,
            'No se recibio informacion de la cita.'
        );

        $appointmentId = $this->uuidStringToBinary($appointment);

        if (!$this->appointmentsRepository->appointmentExistsByUuid([
                'uuid'                      => $appointmentId,
                'branch'                    => $branchId
            ])) {
            throw new InvalidArgumentException('No se encontro informacion de la cita');
        }

        $blockScheduledStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getBlockIdByCode('agendada') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $waitingStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getIdByCode('en_espera') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $blockWaitingStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getBlockIdByCode('en_espera') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $conn = $this->appointmentsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $actual_status = $this->appointmentsRepository->appointmentStatus([
                'uuid'                      => $appointmentId,
                'branch'                    => $branchId
            ]);
            
            if($actual_status != 'agendada')
                throw new RuntimeException('La cita no esta en un estatus valido para hacer check-in.');

            $appointmentBlockId = $this->appointmentsRepository->getFirstAppointmentBlock([
                'appointment'                       => $appointmentId,
                'status'                            => $blockScheduledStatus,
                'branch'                            => $branchId,
            ]);

            $this->appointmentsRepository->changeAppointmentStatus([
                'appointment'                       => $appointmentId,
                'status'                            => $waitingStatus,
                'branch'                            => $branchId,
            ]);

            $this->appointmentsRepository->changeAppointmentBlockStatus([
                'block'                             => $appointmentBlockId['id'],
                'status'                            => $blockWaitingStatus,
            ]);

            $conn->commit();
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function cancel(array $data) {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $appointment = $this->normalizeRequiredText(
            $data['appointment'] ?? null,
            'No se recibio informacion de la cita.'
        );

        $appointmentId = $this->uuidStringToBinary($appointment);

        if (!$this->appointmentsRepository->appointmentExistsByUuid([
                'uuid'                      => $appointmentId,
                'branch'                    => $branchId
            ])) {
            throw new InvalidArgumentException('No se encontro informacion de la cita');
        }

        $status = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getIdByCode('cancelada') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $refusedStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getIdByCode('rechazada') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $attendedStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getIdByCode('finalizada') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $noAssistanceStatus = $this->normalizeRequiredInt(
            $this->appointmentsStatusRepository->getIdByCode('no_presento') ?? null,
            'Ocurrio un error al intentar obtener información.'
        );

        $conn = $this->appointmentsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $actual_status = $this->appointmentsRepository->appointmentStatus([
                'uuid'                      => $appointmentId,
                'branch'                    => $branchId
            ]);
            
            if($actual_status == 'cancelada' || 
                $actual_status == 'rechazada' || 
                $actual_status == 'no_presento' || 
                $actual_status == 'finalizada' ||
                $actual_status == null)
                throw new RuntimeException('La cita no esta en un estatus válido para cancelarse.');

            $this->appointmentsRepository->changeAppointmentStatus([
                    'appointment'                   => $appointmentId,
                    'status'                        => $status,
                    'branch'                            => $branchId,
                ]);

            $this->appointmentsRepository->cancelAppointmentBlocks([
                'appointment'                       => $appointmentId,
                'status'                            => [
                    'canceled'                      => $status,
                    'refused'                       => $refusedStatus,
                    'noAssistance'                  => $noAssistanceStatus,
                    'attended'                      => $attendedStatus,
                ],
            ]);

            $conn->commit();
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}