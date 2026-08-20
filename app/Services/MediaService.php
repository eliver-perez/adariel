<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\MediaRepository;
use RuntimeException;

class MediaService extends Service
{
    public function __construct(
        private MediaRepository $repository
    ) {}

    public function getFile(array $data): array {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        $uuid = $this->normalizeRequiredText(
            $data['uuid'] ?? null,
            'Error al recibir identificador de plantilla.'
        );

        $file = $this->repository->findByUuid([
            'uuid'                      => $this->uuidStringToBinary($uuid),
            'organization'              => $organizationId
        ]);

        if (!$file) {
            throw new RuntimeException(
                'Archivo no encontrado.',
                404
            );
        }

        /*
         * Si el archivo está asociado a una sucursal,
         * validar acceso.
         */
        if (
            $file['sucursal'] !== null &&
            $data['branchId'] !== null &&
            (int) $file['sucursal'] !== $data['branchId']
        ) {
            throw new RuntimeException(
                'Archivo no encontrado.',
                404
            );
        }

        $path = STORAGE_PATH . $file['ruta_raiz'] . $file['nombre_archivo'];

        // die($path);

        if (!is_file($path)) {
            throw new RuntimeException(
                'Archivo no encontrado.',
                404
            );
        }

        $realStorage = realpath(STORAGE_PATH);
        $realFile = realpath($path);

        if (
            !$realStorage ||
            !$realFile ||
            !str_starts_with(
                $realFile,
                $realStorage . DIRECTORY_SEPARATOR
            )
        ) {
            throw new RuntimeException(
                'Ruta de archivo no válida.',
                404
            );
        }

        $file['full_path'] = $realFile;

        return $file;
    }
}