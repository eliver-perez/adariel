<?php
namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\SettingsRepository;
use App\Repositories\MediaRepository;
use App\Repositories\OrganizationsRepository;
use InvalidArgumentException;
use RuntimeException;

class SettingsService extends Service
{
    public function __construct(
        private SettingsRepository $settingsRepository,
        private MediaRepository $mediaRepository,
        private OrganizationsRepository $organizationsRepository
        ) {}

    private array $cache = [];

    public function getGlobal(string $id, mixed $default = null): mixed
    {
        if (isset($this->cache[$id])) {
            return $this->cache[$id];
        }
        $setting = $this->settingsRepository->getGlobalById($id);

        if (!$setting) {
            return $default;
        }

        $value = $setting['valor'];

        return $this->cache[$id] = $this->castValue($value, $setting['tipo']);
    }

    public function get(string $id, int $organization, mixed $default = null): mixed
    {
        if (isset($this->cache[$id])) {
            return $this->cache[$id];
        }
        $setting = $this->settingsRepository->getById($id, $organization);

        if (!$setting) {
            return $default;
        }

        $value = $setting['valor'];

        return $this->cache[$id] = $this->castValue($value, $setting['tipo']);
    }

    private function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'int' => (int)$value,
            'float', 'money' => (float)$value,
            'boolean' => in_array(strtolower($value), ['1', 'true', 'yes', 'si'], true),
            'json' => json_decode($value, true),
            'string' => $value,
            default => $value,
        };
    }

    function updateLogo($data): ?array {
        $originalPath = '';
        $thumbPath = '';
        try {
            $conn = $this->settingsRepository->getConnection();
            $conn->beginTransaction();

            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $uuid = $this->normalizeRequiredText($data['uuid'] ?? null, 'Error al recibir identificador de la consulta.');
            $type = $this->normalizeOptionalText($data['type'] ?? 'organization');

            if($data['logo'] === null)
                throw new RuntimeException("No se recibio archivo.");

            if ($data['logo']['error'] !== UPLOAD_ERR_OK)
                throw new RuntimeException('Error al subir archivo');

            if ($data['logo']['size'] > 3 * 1024 * 1024)
                throw new RuntimeException('La imagen supera 3MB');

            $organization_uuid = $this->organizationsRepository->getOrganizationUuid($organizationId);
            $organization_branch_uuid = $this->organizationsRepository->getOrganizationBranchUuid($organizationId);

            $organization_uuid_plain = $this->uuidBinaryToString($organization_uuid);
            $organization_branch_uuid_plain = $this->uuidBinaryToString($organization_branch_uuid);

            if($type == 'organization') {
                if($organization_uuid_plain != $uuid)
                    throw new RuntimeException("No es posible hacer el cambio de logotipo.");
            } else {
                if($organization_branch_uuid_plain != $uuid)
                    throw new RuntimeException("No es posible hacer el cambio de logotipo.");
            }

            $tmpPath = $data['logo']['tmp_name'];

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpPath);

            $allowed = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!in_array($mime, $allowed, true))
                throw new RuntimeException('Formato no permitido');

            $imageInfo = getimagesize($tmpPath);

            if ($imageInfo === false)
                throw new RuntimeException('Archivo inválido');

            switch ($mime) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($tmpPath);
                    $extension = 'jpg';
                    break;

                case 'image/png':
                    $source = imagecreatefrompng($tmpPath);

                    imagealphablending($source, false);
                    imagesavealpha($source, true);

                    $extension = 'png';
                    break;

                case 'image/webp':
                    $source = imagecreatefromwebp($tmpPath);

                    imagealphablending($source, false);
                    imagesavealpha($source, true);

                    $extension = 'webp';
                    break;

                default:
                    $source = false;
                    $extension = null;
            }

            if (!$source)
                throw new RuntimeException('No fue posible procesar imagen');
            
            $logo_uuid = $this->generateUuidBinary();
            $logo_uuid_plain = $this->uuidBinaryToString($logo_uuid);

            $relativePath = '/logos/'.$organization_uuid_plain.'/';
            $basePath = STORAGE_PATH . $relativePath;

            if (!is_dir($basePath)) {
                if (!mkdir($basePath, 0775, true) && !is_dir($basePath)) {
                    throw new RuntimeException('No fue posible crear el directorio de evidencia.');
                }
            }

            if (!is_writable($basePath)) {
                throw new RuntimeException('El directorio de evidencia no tiene permisos de escritura.');
            }

            $thumbsRelativePath = $relativePath . 'thumbs/';
            $thumbsPath = $basePath . 'thumbs/';

            if (!is_dir($thumbsPath)) {
                mkdir($thumbsPath, 0775, true);
            }

            $originalName = basename($data['logo']['name']);
            $fileName = $logo_uuid_plain . '.' . $extension;
            $thumbName = $logo_uuid_plain . '_thumb.' . $extension;

            $originalPath = $basePath . $fileName;
            $originalRelativePath = $relativePath . $fileName;
            $thumbPath = $thumbsPath . $thumbName;
            $thumbRelativePath = $thumbsRelativePath . $thumbName;

            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($source, $originalPath, 100);
                    break;

                case 'image/png':
                    imagepng($source, $originalPath, 6);
                    break;

                case 'image/webp':
                    imagewebp($source, $originalPath, 100);
                    break;
            }

            $width = imagesx($source);
            $height = imagesy($source);

            $thumbWidth = 300;
            $thumbHeight = 300;

            $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

            $srcRatio = $width / $height;
            $thumbRatio = $thumbWidth / $thumbHeight;

            if ($srcRatio > $thumbRatio) {
                $newHeight = $height;
                $newWidth = (int)($height * $thumbRatio);
                $srcX = (int)(($width - $newWidth) / 2);
                $srcY = 0;
            } else {
                $newWidth = $width;
                $newHeight = (int)($width / $thumbRatio);
                $srcX = 0;
                $srcY = (int)(($height - $newHeight) / 2);
            }

            if (in_array($mime, ['image/png', 'image/webp'], true)) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                imagefill($thumb, 0, 0, $transparent);
            }
            imagecopyresampled(
                $thumb,
                $source,
                0,
                0,
                $srcX,
                $srcY,
                $thumbWidth,
                $thumbHeight,
                $newWidth,
                $newHeight
            );

            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($thumb, $thumbPath, 80);
                    break;

                case 'image/png':
                    imagepng($thumb, $thumbPath, 6);
                    break;

                case 'image/webp':
                    imagewebp($thumb, $thumbPath, 80);
                    break;
            }

            $size = filesize($originalPath);
            $hash = hash_file('sha256', $originalPath);
            $thumbSize = filesize($thumbPath);
            $thumbHash = hash_file('sha256', $thumbPath);

            $saved_logo = $this->mediaRepository->findByOrganizationType([
                'type'                              => 'ORGANIZATION_LOGO',
                'organization'                      => $organizationId
            ]);

            if($saved_logo != null && $saved_logo['hash'] == $hash) {
                unlink($originalPath);
                unlink($thumbPath);
                throw new RuntimeException("El archivo subido ya se encuentra almacenado.");
            }

            $this->mediaRepository->insert([
                'uuid'                              => $logo_uuid,
                'organization'                      => $organizationId,
                'type'                              => 'ORGANIZATION_LOGO',
                'reference'                         => null,
                'original_name'                     => $originalName,
                'filename'                          => $fileName,
                'basepath'                          => $relativePath,
                'path'                              => $originalRelativePath,
                'mime_type'                         => $mime,
                'hash'                              => $hash,
                'size'                              => $size,
                'uid'                               => $uid
            ]);
            
            $this->settingsRepository->updateOrganizationLogo([
                'uuid'                              => $organization_uuid,
                'logo'                              => $logo_uuid
            ]);

            $conn->commit();

            return [
                'logo'                              => $logo_uuid_plain,
                'image_url'                         => $originalPath,
                'thumb_url'                         => $thumbPath
            ];
        } catch(\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            if($originalPath != '' && file_exists($originalPath))
                unlink($originalPath);
            if($thumbPath != '' && file_exists($thumbPath))
                unlink($thumbPath);
            throw $e;
        }
    }
}