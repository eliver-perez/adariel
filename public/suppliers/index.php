<?php
	require_once __DIR__.'/../../app/Core/init.php';

	$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
	$segments = explode('/', $uri);

	$index = array_search('suppliers', $segments);

	$action = $segments[$index + 1] ?? null;
	$next   = $segments[$index + 2] ?? null;

	$id = null;

	$view = APP_PATH . '/Views/suppliers/index.php';

	if ($action === 'add') {
		$view = APP_PATH . '/Views/suppliers/add.php';
	} elseif ($action === 'edit' && is_uuid($next)) {
		$id = $next;
		$view = APP_PATH . '/Views/suppliers/edit.php';

	} elseif (is_uuid($action)) {
		$id = $action;
		$view = APP_PATH . '/Views/suppliers/view.php';
	}

	require APP_PATH . '/Views/layout/main.php';