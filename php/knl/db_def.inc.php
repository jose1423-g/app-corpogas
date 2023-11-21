<?php

global $db_connection;

$db_connection = array (
    "barbet_db" => array(
        "Type" => "MySQL",
        "DBLib" => "MySQL",
        "Database" => "pruebascorpogas",
        "Host" => "localhost",
        "Port" => "3306",
        "User" => "root",
        "Password" => "cubo1423",
        "Persistent" => false,
        "DateFormat" => array("dd", "/", "mm", "/", "yyyy", " ", "HH", ":", "nn", ":", "ss"),
        "BooleanFormat" => array(1, 0, ""),
        "Uppercase" => false
    )
);

$session_name = 'UserID_pruebascorpogas';
function SessGetUserID() {
	global $session_name;
	$id_user = '';
	if (isset($_SESSION)) {
		if (isset($_SESSION[$session_name])) {
			$id_user = $_SESSION[$session_name];
		}
	}
	return $id_user;
}

function SessSetUserId($id_user) {
	global $session_name;
	if (isset($_SESSION)) {
		$_SESSION[$session_name] = $id_user;
		// echo "<pre>" . print_r($_SESSION, true) . "</pre>"; exit();
	}
}

function SessDelUserId() {
	global $session_name;
	if (isset($_SESSION)) {
		unset($_SESSION[$session_name]);
	}
}

?>
