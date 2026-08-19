<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\UsersRepository;
use App\Repositories\OrganizationsRepository;
use InvalidArgumentException;
use RuntimeException;

class UsersService extends Service
{
    public function __construct(
        private UsersRepository $usersRepository,
        private OrganizationsRepository $organizationsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');

            $datetimeService = new DateTimeService($data['timezone']);

            $data = $this->usersRepository->getAll([
                'search'                        => $data['search'] !== '' ? $data['search'] : null,
                'limit'                         => $data['limit'],
                'offset'                        => $data['offset'],
                'status'                        => $data['status']
            ]);
            $users = array();

            foreach($data as $d) {
                array_push($users, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'organization'              => $d['empresa'] ?? '',
                    'email'                     => $d['email'] ?? '',
                    'name'                      => $d['nombre'] ?? '',
                    'type'                      => $d['tipo'] ?? '',
                    'active'                    => $d['activo'] ?? 0,
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                    'last_active_date'          => $d['f_ultima_conexion'] != '' ? $datetimeService->fromUtcFormatted($d['f_ultima_conexion']) : ''
                ));
            }

            return $users;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): ?array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');

        $name = $this->normalizeRequiredText($data['name'] ?? null, 'El nombre es obligatorio.');
        $email = $this->normalizeRequiredText($data['email'] ?? null, 'El email es obligatorio.');
        $password = $this->normalizeRequiredText($data['password'] ?? null, 'La contraseña es obligatoria.');
        $user_type = $this->normalizeRequiredText($data['user_type'] ?? null, 'La contraseña es obligatoria.');
        $organization = $this->normalizeOptionalText($data['organization'] ?? null);
        $branch = $this->normalizeOptionalText($data['branch'] ?? null);

        $user_exists = $this->usersRepository->userExists($email);
        if($user_exists)
            throw new RuntimeException("El correo electronico ya se utiliza en otra cuenta.");

        if($organization != null && $branch != null)
            $registerOrganization = true;
        else
            $registerOrganization = false;
        
        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('El correo electrónico no es válido.');
            }
        }

        $password_hash = $this->encrypt_hash($password);

        if($registerOrganization) {
            $organizationId = $this->organizationsRepository->getOrganizationId([
                'uuid'                          => $this->uuidStringToBinary($organization)
            ]);

            $branchId = $this->organizationsRepository->getOrganizationBranchId([
                'uuid'                          => $this->uuidStringToBinary($branch)
            ]);

            if($organizationId == null || $branchId == null)
                $registerOrganization = false;
        }
        
        $conn = $this->usersRepository->getConnection();
        $conn->beginTransaction();
        try {
            $userUuid = $this->generateUuidBinary();
            $userId = $this->usersRepository->insertUser([
                    'uuid'                          => $userUuid,
                    'organization'                  => $registerOrganization ? $organizationId : null,
                    'email'                         => $email,
                    'name'                          => $name,
                    'password'                      => $password_hash,
                    'user_type'                     => $user_type,
                    'uid'                           => $uid,
                ]);
            
            if($registerOrganization) {
                $this->usersRepository->insertUserBranch([
                    'user'                          => $userId,
                    'branch'                        => $branchId,
                    'user_type'                     => $user_type
                ]);
            }
            $conn->commit();
            return [
                'id'                            => $this->uuidBinaryToString($userUuid)
            ];
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}