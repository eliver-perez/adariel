<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Repositories\MediaRepository;
use App\Services\MediaService;
use Throwable;

class MediaController
{
    private function getService(): MediaService
    {
        $database = new Database();
        $conn = $database->getConnection();

        $mediaRepository = new MediaRepository($conn);

        return new MediaService($mediaRepository);
    }

    public function show(string $uuid): void {
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

        try {
            $service = $this->getService();

            $file = $service->getFile([
                'uuid'                          => $uuid,
                'organizationId'                => $organizationId,
                'branchId'                      => $organizationBranchId,
                'uid'                           => $currentUserId
            ]);

            if (ob_get_level()) {
                ob_end_clean();
            }

            header(
                'Content-Type: ' . $file['mime_type']
            );

            header(
                'Content-Length: ' .
                filesize($file['full_path'])
            );

            header(
                'Content-Disposition: inline; filename="' .
                addslashes($file['nombre_original']) .
                '"'
            );

            header('X-Content-Type-Options: nosniff');

            header(
                'Cache-Control: private, no-store, no-cache, must-revalidate'
            );

            readfile($file['full_path']);

            exit;

        } catch (\Throwable $e) {
            die($e->getMessage());
            http_response_code(
                $e->getCode() === 404
                    ? 404
                    : 500
            );

            exit;
        }
    }
}