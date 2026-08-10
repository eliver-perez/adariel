<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\PatientsRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ScheduleTemplatesRepository;
use App\Repositories\GenderRepository;
use App\Repositories\LocationRepository;
use App\Repositories\BillingRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class PatientsService extends Service
{
    public function __construct(
        private PatientsRepository $patientsRepository,
        private ScheduleRepository $scheduleRepository,
        private ScheduleTemplatesRepository $scheduleTemplatesRepository,
        private GenderRepository $genderRepository,
        private LocationRepository $locationRepository,
        private BillingRepository $billingRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');

            $data = $this->patientsRepository->getAll([
                'organizationId'                => $organizationId,
                'search'                        => $data['search'],
                'limit'                         => $data['limit'],
                'offset'                        => $data['offset'],
            ]);
            $patients = array();

            foreach($data as $d) {
                array_push($patients, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['clave'],
                    'name'                      => $d['nombre'],
                    'dob'                       => $d['f_nacimiento'],
                    'gender'                    => $d['genero'],
                    'phone'                     => $d['telefono'],
                    'mobile'                    => $d['movil'],
                    'registered_date'           => $d['f_registro'],
                    'last_visit_date'           => $d['f_ultima_visita'],
                ));
            }

            return $patients;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): ?array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');

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

        $general_observations = $this->normalizeOptionalText($data['general_observations'] ?? null);
        $current_medications = $this->normalizeOptionalText($data['current_medications'] ?? null);
        $supplements = $this->normalizeOptionalText($data['supplements'] ?? null);
        $family_medical_history = $this->normalizeOptionalText($data['family_medical_history'] ?? null);

        $add_billing = $this->normalizeOptionalInt($data['add_billing'] ?? null);
        $billing_rfc = $this->normalizeOptionalText($data['billing_rfc'] ?? null);
        $billing_name = $this->normalizeOptionalText($data['billing_name'] ?? null);
        $billing_regimen = $this->normalizeOptionalInt($data['billing_regimen'] ?? 0);
        $billing_zip_code = $this->normalizeOptionalText($data['billing_zip_code'] ?? null);
        $billing_street = $this->normalizeOptionalText($data['billing_street'] ?? null);
        $billing_ext_no = $this->normalizeOptionalText($data['billing_ext_no'] ?? null);
        $billing_int_no = $this->normalizeOptionalText($data['billing_int_no'] ?? null);
        $billing_locality = $this->normalizeOptionalInt($data['billing_locality'] ?? 0);
        $billing_email = $this->normalizeOptionalText($data['billing_email'] ?? null);
        $billing_phone = $this->normalizeOptionalText($data['billing_phone'] ?? null);

        if (!$this->genderRepository->existsById($genderId)) {
            throw new InvalidArgumentException('El género indicado no existe.');
        }

        if($locality != null && !$this->locationRepository->localityExists($locality)) {
            throw new InvalidArgumentException('La colonia seleccionada no existe.');
        }

        if($add_billing != 'off' && !$this->billingRepository->existsRegimenById($billing_regimen)) {
            throw new InvalidArgumentException('El puesto no existe.');
        }

        if($add_billing != 'off' && !$this->locationRepository->localityExists($billing_locality)) {
            throw new InvalidArgumentException('El tipo de usuario no existe.');
        }

        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('El correo electrónico no es válido.');
            }
        }
        $settingsService = new SettingsService($this->settingsRepository);

        $fullName = $firstName . ' ' . $lastName . ($lastName2 !== null ? ' ' . $lastName2 : '');

        $conn = $this->patientsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $patientPrefix = $settingsService->getGlobal('codigo_paciente', 'P');
            $clientPrefix = $settingsService->getGlobal('codigo_cliente', 'C');

            $relationship = $this->patientsRepository->getRelationshipIdByCode('self');

            if($relationship === null) {
                throw new RuntimeException('Ocurrio un error al obtener datos para el registro.');
            }

            $patientUuid = $this->generateUuidBinary();
            $patientId = $this->patientsRepository->insertPatient([
                    'uuid'                          => $patientUuid,
                    'organizationId'                => $organizationId,
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
                    'current_medications'           => $current_medications,
                    'supplements'                   => $supplements,
                    'family_medical_history'        => $family_medical_history,
                    'general_observations'          => $general_observations,
                    'uid'                           => $uid,
                ]);
            $patientConsecutive = $this->patientsRepository->getPatientNextConsecutive();
            $patientCode = $patientPrefix . '-' . str_pad((string)$patientConsecutive, 5, '0', STR_PAD_LEFT);

            $this->patientsRepository->updatePatientCode($patientId, [
                'consecutive'                       => $patientConsecutive,
                'code'                              => $patientCode,
            ]);
            
            $clientUuid = $this->generateUuidBinary();
            $clientId = $this->patientsRepository->insertClient([
                    'uuid'                          => $clientUuid,
                    'organizationId'                => $organizationId,
                    'first_name'                    => $firstName,
                    'last_name'                     => $lastName,
                    'last_name_2'                   => $lastName2,
                    'curp'                          => $curp,
                    'gender'                        => $genderId,
                    'dob'                           => $dob,
                    'street'                        => $street,
                    'ext_no'                        => $ext_no,
                    'int_no'                        => $int_no,
                    'locality'                      => $locality,
                    'email'                         => $email,
                    'phone'                         => $phone,
                    'mobile'                        => $mobile,
                    'uid'                           => $uid,
                ]);
            $clientConsecutive = $this->patientsRepository->getClientNextConsecutive();
            $clientCode = $clientPrefix . '-' . str_pad((string)$clientConsecutive, 5, '0', STR_PAD_LEFT);

            $this->patientsRepository->updateClientCode($clientId, [
                'consecutive'                       => $clientConsecutive,
                'code'                              => $clientCode,
            ]);

            $clientPatientUuid = $this->generateUuidBinary();
            $this->patientsRepository->insertClientPatient($clientId, $patientId, [
                'uuid'                          => $clientPatientUuid,
                'relationship'                  => $relationship,
                'uid'                           => $uid,
            ]);

            if($add_billing != 'off') {
                $this->patientsRepository->insertClientBilling($clientId, [
                    'uuid'                          => $clientPatientUuid,
                    'client'                        => $clientId,
                    'regimen'                       => $billing_regimen,
                    'rfc'                           => $billing_rfc,
                    'name'                          => $billing_name,
                    'zip_code'                      => $billing_zip_code,
                    'street'                        => $billing_street,
                    'ext_no'                        => $billing_ext_no,
                    'int_no'                        => $billing_int_no,
                    'locality'                      => $billing_locality,
                    'email'                         => $billing_email,
                    'phone'                         => $billing_phone,
                    'uid'                           => $uid,
                ]);
            }

            $conn->commit();

            return [
                'id' => $patientId,
                'uuid' => $this->uuidBinaryToString($patientUuid),
            ];
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}