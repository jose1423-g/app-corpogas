<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/sys_config.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");

// reset autoincrement con el maximo valor de la tabla
$qry = "SHOW TABLE STATUS";
$a_db = DbQryToArray($qry);

$cant_seq = 0;
foreach ($a_db AS $a_table) {
	$auto_increment = $a_table['Auto_increment'];
	if (strlen($auto_increment)) {
		$table_name = $a_table['Name'];
		$qry = "SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'";
		$table_info = DbQryToRow($qry);
		$primary_name = $table_info['Column_name'];
		$qry = "SELECT MAX($primary_name) FROM $table_name";
		$max_used_value = DbGetFirstFieldValue($qry);
		
		// actualiza a ese valor mas 1
		$new_auto_increment_value = $max_used_value + 1;
		$qry = "ALTER TABLE $table_name AUTO_INCREMENT = $new_auto_increment_value";
		DbExecute($qry);
		$cant_seq++;
	}
}

echo "Terminó de ejecutar reinicio de $cant_seq secuencias";

?>