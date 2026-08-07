<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\OrganizationsRepository;
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
        private UsersRepository $usersRepository,
        private UsersTypesRepository $usersTypesRepository,
        private GenderRepository $genderRepository,
        private LocationRepository $locationRepository,
        private BillingRepository $billingRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(?string $search = null, int $limit = 10, int $offset = 0): array {
        try {
            $data = $this->organizationsRepository->getAll($search !== '' ? $search : null,
                $limit,
                $offset
            );
            $organizations = array();

            foreach($data as $d) {
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
                    'registered_date'           => $d['f_registro'],
                ));
            }

            return $organizations;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getOrganizationData(array $data): ?array {
        try {
            $organizationData = $this->organizationsRepository->getOrganizationData($this->uuidStringToBinary($data['uuid']));

            $branches_data = $this->organizationsRepository->getOrganizationBranches($this->uuidStringToBinary($data['uuid']));
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
                    'registered_date'           => $d['f_registro'],
                ));
            }

            $users_data = $this->organizationsRepository->getOrganizationUsers($this->uuidStringToBinary($data['uuid']));
            $users = array();
            foreach($users_data as $d) {
                array_push($users, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'email'                     => $d['email'],
                    'name'                      => $d['nombre'],
                    'type'                      => $d['tipo'],
                    'active'                    => $d['activo'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $d['f_registro'],
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
                'registered_date'           => $organizationData['f_registro'],
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

            $options = [
                'cost' => 11
            ];
            $password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
            // throw new RuntimeException($password.'   -   '.$password_hash);
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
}