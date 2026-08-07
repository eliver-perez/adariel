<?php
	require_once('config.php');
	require_once('database.php');
	
	use App\Auth\WebSession;

	$session = new WebSession();

	$DatabaseHandler = new DatabaseConnection();
	$conn = $DatabaseHandler->GetDatabaseConnector();
	
	// $tsql = "SELECT up.permiso
	// 				FROM usuarios_s_permisos up
	// 					INNER JOIN permisos p
	// 						ON up.permiso = p.id
	// 					INNER JOIN usuarios_s u
	// 						ON up.usuario = u.id
	// 				WHERE up.valor = 1
	// 					AND up.usuario = ?
	// 		UNION ALL
	// 		SELECT gp.permiso
	// 				FROM grupos_permisos gp
	// 					INNER JOIN permisos p
	// 						ON gp.permiso = p.id
	// 					INNER JOIN usuarios_grupos ug
	// 						ON gp.grupo = ug.grupo
	// 				WHERE gp.valor = 1
	// 					AND ug.usuario = ?";

	// $stmt = $conn->prepare($tsql);
	// $stmt->execute([$_SESSION['$_SESSION['ADARIEL__ERP_ID'], $_SESSION['$_SESSION['ADARIEL__ERP_ID']]);
	// $data = $stmt->fetchAll();

	// $CFG->permisos = array();
	// if($data != null){
	//   foreach($data as $d) {
	//   	array_push($CFG->permisos, $d['permiso']);
	//   }
	// }
?>