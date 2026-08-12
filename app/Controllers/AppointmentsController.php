<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\AppointmentsRepository;
use App\Repositories\AppointmentsTypesRepository;
use App\Repositories\BookingChannelsRepository;
use App\Repositories\AppointmentsStatusRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\StaffRepository;
use App\Repositories\ProceduresRepository;
use App\Repositories\SettingsRepository;
use App\Services\AppointmentsService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class AppointmentsController extends Controller
{
    private ?AppointmentsRepository $repository = null;

    private function getService(): AppointmentsService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $appointmentsRepository = new AppointmentsRepository($conn);
        $appointmentsTypeRepository = new AppointmentsTypesRepository($conn);
        $bookingChannelsRepository = new BookingChannelsRepository($conn);
        $appointmentsStatusRepository = new AppointmentsStatusRepository($conn);
        $patientsRepository = new PatientsRepository($conn);
        $staffRepository = new StaffRepository($conn);
        $proceduresRepository = new ProceduresRepository($conn);
        $settingsRepository = new SettingsRepository($conn);

        return new AppointmentsService($appointmentsRepository, $appointmentsTypeRepository, $bookingChannelsRepository, $appointmentsStatusRepository, $patientsRepository, $staffRepository, $proceduresRepository, $settingsRepository);
    }

    private function getRepository(): AppointmentsRepository {
        if ($this->repository === null) {
            $database = new Database();
            $conn = $database->getConnection();

            $this->repository = new AppointmentsRepository($conn);
        }

        return $this->repository;
    }

    public function index(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $repository = $this->getRepository();

            $search = trim((string)$this->request->query('search', ''));

            $data = $repository->getAll($search !== '' ? $search : null);

            return $response->json([
                    'success' => true,
                    'data' => [
                        'appointments' => $data
                    ]
                ], 200);
        } catch (InvalidArgumentException | RuntimeException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => 'No fue posible obtener los paciente.'
                // 'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAppointmentAssignment(Request $request, Response $response, string $id) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $assigned = $service->getAppointmentAssignment([
                'organizationId'                        => $organizationId,
                'branchId'                              => $organizationBranchId,
                'uuid'                                  => $id,
                'uid'                                   => $currentUserId
            ]);

            return $response->json([
                'success' => true,
                'data' => $assigned
            ], 200);

        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function availableSlots(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $date = trim((string)$this->request->input('date', ''));
            $proceduresRaw = $request->input('procedures', '[]');
            $procedures = json_decode((string)$proceduresRaw, true);

            if ($date === '' || !is_array($procedures) || count($procedures) === 0) {
                return $response->json([
                    'success' => false,
                    'message' => 'Información Incompleta'
                ], 400);
            }

            $slots = $service->calculateAppointmentAvailability([
                'organizationId'                        => $organizationId,
                'branchId'                              => $organizationBranchId,
                'date'                                  => $date,
                'procedures'                            => $procedures,
                'uid'                                   => $currentUserId
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'slots' => $slots
                ]
            ], 200);

        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calendar(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();
            
            $start = trim((string)$this->request->query('start', ''));
            $end = trim((string)$this->request->query('end', ''));

            if ($start === '' || $end === '') {
                return $response->json([
                    'success' => false,
                    'message' => 'Información Incompleta'
                ], 400);
            }

            $appointments = $service->getCalendarAppointments([
                'organizationId'                        => $organizationId,
                'branchId'                              => $organizationBranchId,
                'start'                                 => $start,
                'end'                                   => $end,
                'uid'                                   => $currentUserId
            ]);

            return $response->json([
                'success' => true,
                'data' => [
                    'appointments' => $appointments
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function schedule(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();

            $patient = $request->input('patient', '');
            $appointment_type = (int)$request->input('appointment_type', 0);
            $booking_channel = (int)$request->input('booking_channel', 0);
            $chief_complaint = trim((string)$this->request->input('chief_complaint', ''));

            switch($request->input('booking_mode')) {
                case 'quick':
                    $staff = $request->input('staff', '');
                    $procedure = $request->input('procedure', '');
                    $date = $request->input('date', '');
                    $time = $request->input('time', '');

                    if ($patient === ''
                        || $appointment_type === 0
                        || $booking_channel === 0
                        || $staff == ''
                        || $procedure == ''
                        || $date == ''
                        || $time == '') {
                        return $response->json([
                            'success' => false,
                            'message' => 'Información Incompleta'
                        ], 400);
                    }

                    $appointmentId = $service->scheduleAppointmentQuick([
                        'organizationId'            => $organizationId,
                        'branchId'                  => $organizationBranchId,
                        'patient'                   => $patient,
                        'appointment_type'          => $appointment_type,
                        'booking_channel'           => $booking_channel,
                        'staff'                     => $staff,
                        'procedure'                 => $procedure,
                        'date'                      => $date,
                        'time'                      => $time,
                        'chief_complaint'           => $chief_complaint,
                        'uid'                       => $currentUserId
                    ]);
                    break;
                case 'slots':
                    $appointmentRaw = $request->input('appointment', '[]');
                    $appointment = json_decode((string)$appointmentRaw, true);

                    if (!is_array($appointment)
                        || count($appointment) === 0
                        || $patient === ''
                        || $appointment_type === 0
                        || $booking_channel === 0) {
                        return $response->json([
                            'success' => false,
                            'message' => 'Información Incompleta'
                        ], 400);
                    }

                    $appointmentId = $service->scheduleAppointment([
                        'organizationId'            => $organizationId,
                        'branchId'                  => $organizationBranchId,
                        'patient'                   => $patient,
                        'appointment_type'          => $appointment_type,
                        'booking_channel'           => $booking_channel,
                        'appointment'               => $appointment,
                        'chief_complaint'           => $chief_complaint,
                        'uid'                       => $currentUserId
                    ]);
                    break;
            }

            return $response->json([
                'success' => true,
                'message' => 'Cita agendada',
                'data' => [
                    'id' => $appointmentId
                ]
            ], 200);

        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkIn(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }
            $service = $this->getService();
            $appointment = $request->input('appointment', '');
            $service->checkIn([
                    'organizationId'            => $organizationId,
                    'branchId'                  => $organizationBranchId,
                    'appointment'               => $appointment,
                    'uid'                       => $currentUserId
                ]);

            return $response->json([
                'success' => true,
                'message' => 'Cita en espera.',
                'data' => [
                    'appointment' => $appointment
                ]
            ], 200);

        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, Response $response) {
        try {
            $currentUserId = Auth::id();

            if($currentUserId === null) {
                throw new RuntimeException("No autenticado.");
            }
            $organizationId = Auth::organizationId();

            if($organizationId === null) {
                throw new RuntimeException("No se encontraron registros de su empresa.");
            }
            $organizationBranchId = Auth::organizationBranchId();

            if($organizationBranchId === null) {
                throw new RuntimeException("No se encontraron registros de su sucursal.");
            }

            $service = $this->getService();
            $appointment = $request->input('appointment', '');
            $service->cancel([
                    'organizationId'            => $organizationId,
                    'branchId'                  => $organizationBranchId,
                    'appointment'               => $appointment,
                    'uid'                       => $currentUserId
                ]);

            return $response->json([
                'success' => true,
                'message' => 'Cita cancelada.',
                'data' => [
                    'appointment' => $appointment
                ]
            ], 200);

        } catch (InvalidArgumentException $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        } catch (Throwable $e) {
            return $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}