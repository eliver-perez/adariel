<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\OrganizationsRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\UsersRepository;
use App\Repositories\UsersTypesRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\BillingRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class OrganizationsService extends Service
{
    public function __construct(
        private OrganizationsRepository $organizationsRepository,
        private ScheduleTemplatesRepository $scheduleTemplatesRepository,
        private UsersRepository $usersRepository,
        private UsersTypesRepository $usersTypesRepository,
        private GenderRepository $genderRepository,
        private LocationRepository $locationRepository,
        private BillingRepository $billingRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');

            $datetimeService = new DateTimeService($data['timezone']);

            $organizations_data = $this->organizationsRepository->getAll([
                'search'                        => $data['search'] !== '' ? $data['search'] : null,
                'limit'                         => $data['limit'],
                'offset'                        => $data['offset']
            ]);
            $organizations = array();

            foreach($organizations_data as $d) {
                array_push($organizations, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['clave'],
                    'organization'              => $d['empresa'],
                    'address'                   => $d['domicilio'] ?? '',
                    'phone'                     => $d['telefono'] ?? '',
                    'mobile'                    => $d['movil'] ?? '',
                    'email'                     => $d['email'] ?? '',
                    'manager'                   => $d['encargado'] ?? '',
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            return $organizations;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function GetOrganizationBranches(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeOptionalInt($data['organizationId'] ?? null);

            $datetimeService = new DateTimeService($data['timezone']);

            $user_type = $this->usersRepository->getUserTypeCodeById($uid);
            if($user_type == null)
                throw new RuntimeException("Error al obtener tipo de usuario actual.");

            if($user_type == 'superadmin') {
                $branches_data = $this->organizationsRepository->GetAllOrganizationBranches([
                    'uuid'                          => $this->uuidStringToBinary($data['uuid'])
                ]);
            } else {
                if($organizationId == null)
                    throw new RuntimeException("No se encontraron datos de empresa.");

                $branches_data = $this->organizationsRepository->GetOrganizationBranches([
                    'uuid'                          => $this->uuidStringToBinary($data['uuid']),
                    'organization'                  => $organizationId
                ]);
            }
            $branches = array();

            foreach($branches_data as $d) {
                array_push($branches, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['clave'],
                    'branch'                    => $d['sucursal'],
                    'address'                   => $d['domicilio'] ?? '',
                    'phone'                     => $d['telefono'] ?? '',
                    'mobile'                    => $d['movil'] ?? '',
                    'email'                     => $d['email'] ?? '',
                    'manager'                   => $d['encargado'] ?? '',
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            return $branches;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getOrganizationData(array $data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeOptionalInt($data['organizationId'] ?? null);

            $organizationData = $this->organizationsRepository->getOrganizationData($this->uuidStringToBinary($data['uuid']));

            $datetimeService = new DateTimeService($data['timezone']);

            $branches_data = $this->organizationsRepository->getOrganizationBranches([
                'uuid'                          => $this->uuidStringToBinary($data['uuid']),
                'organization'                  => $organizationData['id']
            ]);
            $branches = array();
            foreach($branches_data as $d) {
                array_push($branches, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['clave'],
                    'organization'              => $d['empresa'],
                    'branch'                    => $d['sucursal'],
                    'address'                   => $d['domicilio'] ?? '',
                    'phone'                     => $d['telefono'] ?? '',
                    'mobile'                    => $d['movil'] ?? '',
                    'email'                     => $d['email'] ?? '',
                    'manager'                   => $d['encargado'] ?? '',
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            $users_data = $this->organizationsRepository->getOrganizationUsers([
                'uuid'                          => $this->uuidStringToBinary($data['uuid']),
                'active'                        => -1
            ]);
            $users = array();
            foreach($users_data as $d) {
                array_push($users, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'email'                     => $d['email'],
                    'name'                      => $d['nombre'],
                    'type'                      => $d['tipo'],
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }
            return [
                'id'                        => $this->uuidBinaryToString($organizationData['uuid']),
                'code'                      => $organizationData['clave'],
                'organization'              => $organizationData['empresa'],
                'address'                   => $organizationData['domicilio'] ?? '',
                'phone'                     => $organizationData['telefono'] ?? '',
                'mobile'                    => $organizationData['movil'] ?? '',
                'email'                     => $organizationData['email'] ?? '',
                'manager'                   => $organizationData['encargado'] ?? '',
                'active'                    => $organizationData['activo'],
                'registered_by'             => $organizationData['registro'],
                'registered_date'           => $datetimeService->fromUtcFormatted($organizationData['f_registro']),
                'branches'                  => $branches,
                'users'                     => $users
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): ?array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');

        $organization = $this->normalizeRequiredText(
            $data['organization'] ?? null,
            'El nombre de la empresa es obligatorio.'
        );

        $manager = $this->normalizeRequiredText(
            $data['manager'] ?? null,
            'El nombre del encargado es obligatorio.'
        );

        $email = $this->normalizeRequiredText(
            $data['email'] ?? null,
            'El correo electronico es obligatorio.'
        );

        $password = $this->normalizeRequiredText(
            $data['password'] ?? null,
            'La contraseña es obligatoria.'
        );

        $street = $this->normalizeOptionalText($data['street'] ?? null);
        $ext_no = $this->normalizeOptionalText($data['ext_no'] ?? null);
        $int_no = $this->normalizeOptionalText($data['int_no'] ?? null);
        $locality = $this->normalizeOptionalInt($data['locality'] ?? null);
        $zip_code = $this->normalizeOptionalInt($data['zip_code'] ?? null);
        $phone = $this->normalizeOptionalText($data['phone'] ?? null);
        $mobile = $this->normalizeOptionalText($data['mobile'] ?? null);

        if($locality != null && !$this->locationRepository->localityExists($locality)) {
            throw new InvalidArgumentException('La colonia seleccionada no existe.');
        }

        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('El correo electrónico no es válido.');
            }
        }
        $settingsService = new SettingsService($this->settingsRepository);

        $conn = $this->organizationsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $organizationPrefix = $settingsService->getGlobal('codigo_empresa', 'E');
            $sucursalPrefix = $settingsService->getGlobal('codigo_sucursal', 'ES');

            $userTypeId = $this->usersTypesRepository->getIdByCode('gerente');

            $organizationUuid = $this->generateUuidBinary();
            $organizationId = $this->organizationsRepository->insertOrganization([
                    'uuid'                          => $organizationUuid,
                    'organization'                  => $organization,
                    'street'                        => $street,
                    'ext_no'                        => $ext_no,
                    'int_no'                        => $int_no,
                    'locality'                      => $locality,
                    'zip_code'                      => $zip_code,
                    'phone'                         => $phone,
                    'mobile'                        => $mobile,
                    'manager'                       => $manager,
                    'email'                         => $email,
                    'uid'                           => $uid
                ]);
            $organizationConsecutive = $this->organizationsRepository->getOrganizationNextConsecutive();
            $organizationCode = $organizationPrefix . '-' . str_pad((string)$organizationConsecutive, 5, '0', STR_PAD_LEFT);

            $this->organizationsRepository->updateOrganizationCode($organizationId, [
                'consecutive'                       => $organizationConsecutive,
                'code'                              => $organizationCode,
            ]);
            
            $sucursalUuid = $this->generateUuidBinary();
            $sucursalId = $this->organizationsRepository->insertSucursal([
                    'uuid'                          => $sucursalUuid,
                    'organization'                  => $organizationId,
                    'sucursal'                      => $organization,
                    'street'                        => $street,
                    'ext_no'                        => $ext_no,
                    'int_no'                        => $int_no,
                    'locality'                      => $locality,
                    'zip_code'                      => $zip_code,
                    'phone'                         => $phone,
                    'mobile'                        => $mobile,
                    'manager'                       => $manager,
                    'email'                         => $email,
                    'uid'                           => $uid
                ]);
            $sucursalConsecutive = $this->organizationsRepository->getSucursalNextConsecutive($organizationId);
            $sucursalCode = $sucursalPrefix . '-' . str_pad((string)$sucursalConsecutive, 3, '0', STR_PAD_LEFT);

            $this->organizationsRepository->updateSucursalCode($sucursalId, [
                'consecutive'                       => $sucursalConsecutive,
                'code'                              => $sucursalCode,
            ]);

            $password_hash = $this->encrypt_hash($password);
            $userUuid = $this->generateUuidBinary();
            $userId = $this->usersRepository->insertUser([
                'uuid'                              => $userUuid,
                'organization'                      => $organizationId,
                'email'                             => $email,
                'name'                              => $manager,
                'password'                          => $password_hash,
                'user_type'                         => $userTypeId,
                'uid'                               => $uid
            ]);

            $this->usersRepository->insertUserBranch([
                'user'                              => $userId,
                'branch'                            => $sucursalId,
                'user_type'                         => $userTypeId,
            ]);

            $this->organizationsRepository->insertOrganizationSettings([
                'organization'                      => $organizationId,
                'uid'                               => $uid
            ]);

            $organizationSchedule = $settingsService->get('agenda_horario_empresa', $organizationId, '[]');
            $schedule = $organizationSchedule;

            if (is_array($schedule)) {
                $weekDays = [
                    'lunes'     => 1,
                    'martes'    => 2,
                    'miercoles' => 3,
                    'jueves'    => 4,
                    'viernes'   => 5,
                    'sabado'    => 6,
                    'domingo'   => 7
                ];

                $scheduleTemplateUuid = $this->generateUuidBinary();
                $scheduleTemplateId = $this->scheduleTemplatesRepository->insertScheduleTemplate([
                    'uuid'                          => $scheduleTemplateUuid,
                    'organization'                  => $organizationId,
                    'template'                      => 'Horario general',
                    'description'                   => 'Plantilla del horario general de la empresa.',
                    'uid'                           => $uid
                ]);

                foreach ($weekDays as $dayName => $dayNumber) {
                    if (!isset($schedule[$dayName])) {
                        continue;
                    }

                    $dia = $schedule[$dayName];

                    /*
                    * Si el día está inactivo, no insertamos detalles.
                    */
                    if (empty($dia['activo'])) {
                        continue;
                    }

                    if (
                        !isset($dia['periodos']) ||
                        !is_array($dia['periodos'])
                    ) {
                        continue;
                    }

                    foreach ($dia['periodos'] as $periodo) {

                        if (
                            empty($periodo['inicio']) ||
                            empty($periodo['fin'])
                        ) {
                            continue;
                        }

                        $scheduleTemplateDetailUuid = $this->generateUuidBinary();

                        $startTime = $periodo['inicio'];
                        $endTime = $periodo['fin'];

                        $this->scheduleTemplatesRepository->insertScheduleTemplateDetails([
                            'uuid'                      => $scheduleTemplateDetailUuid,
                            'template'                  => $scheduleTemplateId,
                            'day_week'                  => $dayNumber,
                            'start'                     => $this->timeToMinutes($startTime),
                            'end'                       => $this->timeToMinutes($endTime)
                        ]);
                    }
                }
            }

            $conn->commit();

            return [
                'uuid' => $this->uuidBinaryToString($organizationUuid),
            ];
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function getMyUsers(array $data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de empresa.');
            $active = $this->normalizeOptionalInt($data['active'] ?? null, true);

            $datetimeService = new DateTimeService($data['timezone']);

            $data = $this->organizationsRepository->getOrganizationUsersById([
                'organizationId'                => $organizationId,
                'active'                        => $active,
            ]);
            $users = array();

            foreach($data as $d) {
                array_push($users, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'email'                     => $d['email'],
                    'name'                      => $d['nombre'],
                    'type'                      => $d['tipo'],
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            return $users;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}