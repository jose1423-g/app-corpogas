<?php

// require_once('json.php');
require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
// require_once("$SYS_ROOT/php/chrg/charges.inc.php");
// require_once("$SYS_ROOT/php/knl/dates.inc.php");

$f_name = (isset($_REQUEST['f_name'])) ? $_REQUEST['f_name'] : '';

// function doctorsList() {

    if ($f_name == 'holis') {

        $qry = "SELECT IdUsuario, Nombre FROM seg_usuarios";
        $a_result = DbQryToArray($qry);
        $a_result = utf8ize($a_result);
        echo json_encode($a_result, true);
        // exit();


        // $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
        // $result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
        // $add_other = (isset($_REQUEST['add_other'])) ? $_REQUEST['add_other'] : 0;
        // $id_sucursal = (isset($_REQUEST['id_sucursal'])) ? $_REQUEST['id_sucursal'] : '';
        // $id = '';
        // if (is_numeric($qry_delim)) {
        //     $cond = "CAST(idMedico AS varchar) LIKE '$qry_delim%'";
        //     $fld_doctor_name = "idMedico + ' ' + NombreCompleto AS NombreCompleto";
        // } elseif (substr($qry_delim, 0, 5) == '__ID_') {
        //     $id = substr($qry_delim, 5);
        //     $cond = "idMedico = $id";
        // } else {
        //     $cond = "upper(NombreCompleto) LIKE '%$qry_delim%'";
        // }
        // $add_other_slc = "";
        // if ($add_other == 1 and !strlen($id)) {
        //     $add_other_slc = "UNION ALL SELECT -1, 'OTRO', 0, '', '', 0, '', '', '', 'OTRO', 'OTRO'";
        // }
        // if (strlen($id_sucursal)) {
        //     $cond .= (strlen($cond)) ? " AND " : "";
        //     $cond .= "COALESCE(IdSucursal, $id_sucursal) = $id_sucursal"; // muestra los que no tienen sucursal indicada y los de la sucursal actual
        // }
        // $qry = "SELECT idMedico, NombreCompleto, EsDefault, Cedula, CedulaEspecialidad,
        //             Medicos.IdEspecialidad, Especialidad.Especialidad,
        //             ApellidoP, ApellidoM, Nombre,
        //             CONCAT(COALESCE(Nombre, ''), ' ', COALESCE(ApellidoP, ''), ' ', COALESCE(ApellidoM, ''), ' ') AS NombreCompleto2
        //         FROM Medicos
        //         LEFT JOIN Especialidad ON
        //             Medicos.IdEspecialidad = Especialidad.Id
        //         WHERE Estatus = 1 AND $cond
        //         $add_other_slc
        //         ORDER BY NombreCompleto";
        // if ($result_type == 'select2') {
        //     $a_result = DbQryToArray($qry);
        //     $a_result = utf8ize($a_result);
        //     echo json_encode( $a_result, true );
        // } else {
            // echo "<pre>" . $qry . "</pre>"; exit();
            // dbQrySendJson($qry);
        // }

    }
// }
?>