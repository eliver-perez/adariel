<?php
	require_once __DIR__.'/../../app/Core/init.php';

    use App\Controllers\MediaController;

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    /*
    * Ejemplo:
    * /media/550e8400-e29b-41d4-a716-446655440000
    */
    $parts = explode('/', trim($uri, '/'));

    $uuid = $parts[count($parts) - 1] ?? null;

    if (!$uuid) {
        http_response_code(404);
        exit;
    }

    $controller = new MediaController();

    $controller->show($uuid);