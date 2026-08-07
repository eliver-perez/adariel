<?php
	require_once __DIR__.'/../../app/Core/init.php';

	$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
	$segments = explode('/', $uri);

	// adariel/public/staff/123
	// puede venir así, así que buscas 'staff'
	$index = array_search('staff', $segments);

	$action = $segments[$index + 1] ?? null;
	$id     = $segments[$index + 1] ?? null;
	$next   = $segments[$index + 2] ?? null;

	// default
	$view = APP_PATH . '/Views/staff/index.php';

	if (is_numeric($action)) {
		$id = $action;
		$view = APP_PATH . '/Views/staff/view.php';

	} elseif ($action === 'add') {
		// /staff/add
		$view = APP_PATH . '/Views/staff/add.php';

	} elseif ($action === 'edit' && is_numeric($next)) {
		// /staff/123/edit
		$id = $next;
		$view = APP_PATH . '/Views/staff/edit.php';
	}

	require APP_PATH . '/Views/layout/main.php';