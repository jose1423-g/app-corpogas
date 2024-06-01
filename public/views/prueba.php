<?php
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");

$qry = "SELECT * FROM estaciones WHERE IdEstacion >= 106";
$a_producto = DbQryToArray($qry);	

$a_data = array(
'4185',
'5176',
'5960',
'6242',
'6621',
'7353',
'7930',
'8020',
'8873',
'9365',
'9425',
'9760',
'9767',
'11258',
'13629',
'13830',
'13836',
'13895'
);

$a_valores = array();

foreach ($a_data as $row) { 
    $qry = "SELECT IdEstacion FROM estaciones WHERE NoEstacion = $row";
    $value = DbGetFirstFieldValue($qry);
    if (isset($value)) {
        $qry = "INSERT INTO seg_estacionesusuario (IdUsuario_fk, IdEstacion_fk) VALUES (1092, $value)";
        array_push($a_valores, $qry);
    }
}

foreach ($a_valores as $row) {
    echo $row.";"."<br>";
}

// foreach ($a_producto as $row) {
//     $estacion = $row['NoEstacion'];
//     $id  = $row['IdEstacion'];
//     // echo $row['EmailSupervisor'];   
//     echo "UPDATE estaciones SET EmailSupervisor = 'es$estacion@gogas.com.mx' WHERE IdEstacion = $id AND NoEstacion = $estacion".";<br>";
// }
