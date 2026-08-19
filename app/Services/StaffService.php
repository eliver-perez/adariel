<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\StaffRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRoleRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SpecialtyRepository;
use InvalidArgumentException;
use RuntimeException;

class StaffService extends Service
{
    public function __construct(
        private StaffRepository $staffRepository,
        private ScheduleRepository $scheduleRepository,
        private ScheduleTemplatesRepository $scheduleTemplatesRepository,
        private UsersRepository $usersRepository,
        private GenderRepository $genderRepository,
        private LocationRepository $locationRepository,
        private UserRoleRepository $userRoleRepository,
        private RoleRepository $roleRepository,
        private SpecialtyRepository $specialtyRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');

            $datetimeService = new DateTimeService($data['timezone']);

            $data = $this->staffRepository->getAll([
                'organizationId'                => $data['organizationId'],
                'search'                        => $data['search'] !== '' ? $data['search'] : null,
                'limit'                         => $data['limit'],
                'offset'                        => $data['offset'],
            ]);
            $staff = array();

            foreach($data as $d) {
                array_push($staff, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'name'                      => $d['nombre'],
                    'address'                   => $d['domicilio'] ?? '',
                    'dob'                       => $d['f_nacimiento'] ?? '',
                    'phone'                     => $d['telefono'] ?? '',
                    'mobile'                    => $d['movil'] ?? '',
                    'email'                     => $d['email'] ?? '',
                    'user'                      => $d['usuario'] ?? '',
                    'status'                    => $d['estatus'] ?? '',
                    'role'                      => $d['puesto'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            return $staff;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): int {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron registros de su sucursal.');

        $firstName = $this->normalizeRequiredText(
            $data['first_name'] ?? null,
            'El nombre es obligatorio.'
        );

        $lastName = $this->normalizeRequiredText(
            $data['last_name'] ?? null,
            'El apellido es obligatorio.'
        );

        $lastName2 = $this->normalizeOptionalText($data['last_name_2'] ?? null);
        $dob = $this->formatDateToSQL($data['dob'] ?? null);

        $genderId = $this->normalizeRequiredText(
            $data['gender'] ?? null,
            'El género es obligatorio.'
        );

        $curp = $this->normalizeOptionalText($data['curp'] ?? null);

        $street = $this->normalizeOptionalText($data['street'] ?? null);
        $ext_no = $this->normalizeOptionalText($data['ext_no'] ?? null);
        $int_no = $this->normalizeOptionalText($data['int_no'] ?? null);
        $locality = $this->normalizeOptionalInt($data['locality'] ?? null);

        $email = $this->normalizeOptionalText($data['email'] ?? null);
        $phone = $this->normalizeOptionalText($data['phone'] ?? null);
        $mobile = $this->normalizeOptionalText($data['mobile'] ?? null);

        $user_uuid = $this->normalizeOptionalText($data['user_uuid'] ?? null);
        $role = $this->normalizeOptionalInt($data['role'] ?? null);

        $cedula = $this->normalizeOptionalText($data['cedula'] ?? null);
        $specialty = $this->normalizeOptionalInt($data['specialty'] ?? null);
        $university = $this->normalizeOptionalText($data['university'] ?? null);
        $university_grad_year = $this->normalizeOptionalInt($data['university_grad_year'] ?? null);
        $university_municipality = $this->normalizeOptionalInt($data['university_municipality'] ?? null);

        $rfc = $this->normalizeOptionalText($data['rfc'] ?? null);
        $salary = $this->normalizeOptionalFloat($data['salary'] ?? null);

        if (!$this->genderRepository->existsById($genderId)) {
            throw new InvalidArgumentException('El género indicado no existe.');
        }

        if($role != null && !$this->roleRepository->existsById($role)) {
            throw new InvalidArgumentException('El puesto no existe.');
        }

        if($specialty != null && !$this->specialtyRepository->existsById($specialty)) {
            throw new InvalidArgumentException('La especialidad no existe.');
        }

        if($locality != null && !$this->locationRepository->localityExists($locality)) {
            throw new InvalidArgumentException('La colonia seleccionada no existe.');
        }

        if($university_municipality != null && !$this->locationRepository->municipalityExists($university_municipality)) {
            throw new InvalidArgumentException('El municipio de la universidad seleccionado no existe.');
        }

        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('El correo electrónico no es válido.');
            }
        }

        $hasMedicalData =
            $cedula !== null ||
            $university !== null ||
            $university_grad_year !== null;

        $isMedical = $hasMedicalData;

        if ($isMedical && $specialty === null) {
            throw new InvalidArgumentException('La especialidad es obligatoria para registrar el perfil profesional.');
        }

        if ($isMedical && $university_municipality === null) {
            throw new InvalidArgumentException('El municipio de la universidad es obligatorio para registrar el perfil profesional.');
        }

        $fullName = $firstName . ' ' . $lastName . ($lastName2 !== null ? ' ' . $lastName2 : '');

        $conn = $this->staffRepository->getConnection();
        $conn->beginTransaction();
        try {
            $staffUuid = $this->generateUuidBinary();
            $staffId = $this->staffRepository->insertStaff([
                    'organizationId'                => $organizationId,
                    'uuid'                          => $staffUuid,
                    'first_name'                    => $firstName,
                    'last_name'                     => $lastName,
                    'last_name_2'                   => $lastName2,
                    'dob'                           => $dob,
                    'gender'                        => $genderId,
                    'curp'                          => $curp,
                    'street'                        => $street,
                    'ext_no'                        => $ext_no,
                    'int_no'                        => $int_no,
                    'locality'                      => $locality,
                    'email'                         => $email,
                    'phone'                         => $phone,
                    'mobile'                        => $mobile,
                    'role'                          => $role,
                    'rfc'                           => $rfc,
                    'uid'                           => $uid,
                ]);
            
            $this->staffRepository->insertStaffRegistration($staffId, [
                'f_alta'                            => date('Y-m-d')
                ]);

            if($isMedical) {
                $this->staffRepository->insertStaffProfessional($staffId, [
                        'cedula'                            => $cedula,
                        'specialty'                         => $specialty,
                        'university'                        => $university,
                        'university_grad_year'              => $university_grad_year,
                        'university_municipality'           => $university_municipality
                    ]);
            }

            if($user_uuid != "N") {
                $user_id = $this->usersRepository->getUserIdByUuid($this->uuidStringToBinary($user_uuid));
                $this->staffRepository->insertStaffUser($staffId, $user_id);
            }

            $staffBranchUuid = $this->generateUuidBinary();
            $this->staffRepository->insertStaffOrganizationBranch([
                'uuid'                                      => $staffBranchUuid,
                'staffId'                                   => $staffId,
                'branchId'                                  => $branchId,
            ]);

            if($salary) {
                $this->staffRepository->insertStaffSalary($staffId, [
                    'salary'                                    => $salary,
                    'uid'                                       => $uid,
                    'salary_since'                              => date('Y-m-d')
                ]);
            }

            $scheduleTemplateData = $this->scheduleTemplatesRepository->getScheduleTemplateData([
                'organization'                      => $organizationId
            ]);

            if($scheduleTemplateData != null) {
                $scheduleTemplateDetails = $this->scheduleTemplatesRepository->getScheduleTemplateDetails([
                    'template'                      => $scheduleTemplateData['id'],
                    'organization'                  => $organizationId,
                ]);

                $scheduleUuid = $this->generateUuidBinary();
                $scheduleId = $this->scheduleRepository->insertSchedule([
                    'uuid'                          => $scheduleUuid,
                    'branch'                        => $branchId,
                    'staff'                         => $staffId,
                    'template'                      => $scheduleTemplateData['id'],
                    'uid'                           => $uid
                ]);

                foreach($scheduleTemplateDetails as $std) {
                    $scheduleDetailsUuid = $this->generateUuidBinary();
                    $scheduleDetailsId = $this->scheduleRepository->insertScheduleDetails([
                        'uuid'                          => $scheduleDetailsUuid,
                        'schedule'                      => $scheduleId,
                        'day_week'                      => $std['dia_semana'],
                        'time_start'                    => $std['hora_inicio'],
                        'time_end'                      => $std['hora_fin']
                    ]);
                }
            }
            $conn->commit();
            return $staffId;
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}