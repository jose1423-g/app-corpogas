<?php

require_once('json.php');
require_once('../ria/db_ria.inc.php');
require_once('../php/chrg/charges.inc.php');
require_once('../php/knl/dates.inc.php');

$json = new Services_JSON();
fExecute($_REQUEST['f_name']);


function rcpAseguradora() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
	$show_inactive = (isset($_REQUEST['show_inactive'])) ? $_REQUEST['show_inactive'] : 1; // por ahora si no se indica, se mostraran los inactivos
	$s_mostrar = (isset($_REQUEST['s_mostrar'])) ? $_REQUEST['s_mostrar'] : ""; // vacio es todos, array(1 => 'Seguros', 2 => 'Convenios', 3 => 'Otros Clientes', 4 => 'Seguros y Convenios');
	$show_individual = (isset($_REQUEST['show_individual'])) ? $_REQUEST['show_individual'] : 0;
	$aseguradora_id_master = (isset($_REQUEST['aseguradora_id_master'])) ? $_REQUEST['aseguradora_id_master'] : 0;
	if ($result_type == 'select2') {
		$fld_display = "t1.NombreCorto";
	} else {
		$fld_display = "t1.NombreCorto + '(' + t1.RFC + ')' AS NombreCorto";
	}
	$id = '';
    if (is_numeric($qry_delim)) {
        $cond = "CAST(t1.AseguradoraClienteId AS varchar) LIKE '$qry_delim%'";
		$fld_display = "t1.AseguradoraClienteId + ' ' + t1.NombreCorto AS NombreCorto";
	} elseif (substr($qry_delim, 0, 5) == '__ID_') {
        $id = substr($qry_delim, 5);
        $cond = "t1.AseguradoraClienteId = $id";
    } else {
		$cond = " (t1.NombreCorto LIKE '%$qry_delim%' OR t1.RazonSocial LIKE '%$qry_delim%' OR t1.RFC LIKE '%$qry_delim%') ";
	}
	if ($show_inactive != 1) {
		$cond .= " AND t1.Status = 1";
	}

		
	if (strlen($s_mostrar)) {
		if ($s_mostrar == 1) {
			$cond .= " AND COALESCE(t1.Seguro, 0) = 1";
		}
		if ($s_mostrar == 2) {
			$cond .= " AND COALESCE(t1.Convenio, 0) = 1";
		}
		if ($s_mostrar == 3) {
			$cond .= " AND (COALESCE(t1.Seguro, 0) = 0 AND COALESCE(t1.Convenio, 0) = 0 )";
		}
		if ($s_mostrar == 4) {
			$cond .= " AND (COALESCE(t1.Seguro, 0) = 1 OR COALESCE(t1.Convenio, 0) = 1 )";
		}
	}
	
    $add_individual_slc = "";
    if ($show_individual == 1 and (!strlen($id) or $id == -1 or $id == -2)) {
        $add_individual_slc = "UNION ALL SELECT -1, 'Individual'";
		if ($aseguradora_id_master == ID_ASEGURADORA_ID_PROGRAMA) {
			$add_individual_slc .= "UNION ALL SELECT -2, 'N/A'";
		}
    }
		
    $qry = "SELECT t1.AseguradoraClienteId, $fld_display
            FROM Aseguradoras t1
            WHERE $cond
			$add_individual_slc
            ORDER BY NombreCorto";
	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		dbQrySendJson($qry);
	}
}


function rcpAseguradoraInf() {
    $AseguradoraId = ($_REQUEST['aseguradora_id']) ? $_REQUEST['aseguradora_id'] : -1;
	$qry = "SELECT NombreCorto, RazonSocial, RFC, DireccionFiscal, Ciudad, Estado, CP, PaisId, FactorId,
            CASE WHEN NotasFactura IS NULL THEN '' ELSE NotasFactura END AS NotasFactura,
            CASE WHEN EsEditaNotas IS NULL THEN 1 ELSE EsEditaNotas END AS EsEditaNotas,
            CASE WHEN DescuentoAdicional IS NULL THEN 0 ELSE DescuentoAdicional END AS DescuentoAdicional, Telefono1,
            COALESCE(Calle, '') AS Calle, COALESCE(NumExterior, '') AS NumExterior,
            COALESCE(NumInterior, '') AS NumInterior, COALESCE(Colonia, '') AS Colonia,
            COALESCE(Localidad, '') AS Localidad, COALESCE(Municipio, '') AS Municipio,
            COALESCE(Email1, '') AS Email1, COALESCE(Email2, '') AS Email2, COALESCE(CuentaCfdi, '') AS CuentaCfdi,
            COALESCE(FormaPagoCfdi, '') AS FormaPagoCfdi, IdCondicionCredito, ListaId, Seguro,
            CASE WHEN t2.SegEscuela IS NULL THEN 0 ELSE 1 END AS TieneEscuela, CdUsoCfdi, Addenda, Convenio, CdCfdiRegimen
			FROM Aseguradoras
            LEFT JOIN (SELECT AseguradoraClienteId AS SegEscuela FROM Escuelas GROUP BY AseguradoraClienteId) t2 ON
                Aseguradoras.AseguradoraClienteId = t2.SegEscuela
			WHERE AseguradoraClienteId = $AseguradoraId";
	dbQrySendJson($qry);
}

function rcpAddendasAseguradora() {
    $AseguradoraId = ($_REQUEST['aseguradora_id']) ? $_REQUEST['aseguradora_id'] : -1;
    $qry = "SELECT t1.Addenda, t2.NombreCorto
            FROM CfdiAddendaCliente t1
            LEFT JOIN CfdiAddendas t2 ON
                t1.Addenda = t2.Addenda
            WHERE t1.AseguradoraClienteId = $AseguradoraId
            UNION ALL
            SELECT  Addenda, Addenda
            FROM CfdiAddendas
            WHERE Addenda = 'addenda_Seguros1'";
    dbQrySendJson($qry);
}

// muestra los grupos empresariales (de aseguradoras)
function rcpAseguradoraGrupoEmp() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
	$aseguradora_id_master = (isset($_REQUEST['aseguradora_id_master'])) ? $_REQUEST['aseguradora_id_master'] : 0;
	if ($result_type == 'select2') {
		$fld_display = "RazonSocial";
	} else {
		$fld_display = "RazonSocial";
	}
	$id = '';
    if (is_numeric($qry_delim)) {
        $cond = "CAST(AseguradoraIdGrupoEmp AS varchar) LIKE '%$qry_delim%'";
		$fld_display = "CAST(AseguradoraIdGrupoEmp AS varchar)+ ' ' + RazonSocial AS RazonSocial";
	} elseif (substr($qry_delim, 0, 5) == '__ID_') {
        $id = substr($qry_delim, 5);
        $cond = "AseguradoraIdGrupoEmp = $id";
    } else {
		$cond = " RazonSocial LIKE '%$qry_delim%' COLLATE Modern_Spanish_CI_AI";
	}
	// if ($show_inactive != 1) {
		$cond .= " AND EsActivo = 1";
	// }

    $add_individual_slc = "";
    // if ((!strlen($id) or $id == -1 or $id == -2 or $id == -3)) {
        // $add_individual_slc = "UNION ALL SELECT -1, 'Individual*'";
		// if ($aseguradora_id_master == ID_ASEGURADORA_ID_PROGRAMA) {
			// $add_individual_slc .= "UNION ALL SELECT -2, 'N/A*'";
		// }
		// $add_individual_slc .= "UNION ALL SELECT -3, 'Pendiente de asignar*'";
    // }
    if (!strlen($id)) {
        $add_individual_slc = "UNION ALL SELECT -1, 'Individual*'";
		if ($aseguradora_id_master == ID_ASEGURADORA_ID_PROGRAMA) {
			$add_individual_slc .= "UNION ALL SELECT -2, 'N/A*'";
		}
		$add_individual_slc .= "UNION ALL SELECT -3, 'Pendiente de asignar*'";
    } else {
		if ($id == -1) {
			$add_individual_slc = "UNION ALL SELECT -1, 'Individual*'";
		} elseif ($id == -2) {
			$add_individual_slc = "UNION ALL SELECT -2, 'N/A*'";
		} elseif ($id == -3) {
			$add_individual_slc = "UNION ALL SELECT -3, 'Pendiente de asignar*'";
		}
	}
		
    $qry = "SELECT AseguradoraIdGrupoEmp, $fld_display
            FROM AseguradorasGrupoEmp
            WHERE $cond
			$add_individual_slc
            ORDER BY RazonSocial";
	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		dbQrySendJson($qry);
	}
}


// muestra los referenciadores de pacientes, debe estar relacionado con los grupos empresariales (solo informativo, porque no se guarda esa relacion previamente en el sistema)
function rcpReferenciador() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
	if ($result_type == 'select2') {
		$fld_display = "Nombre";
	} else {
		$fld_display = "Nombre";
	}
	$id = '';
    if (is_numeric($qry_delim)) {
        $cond = "CAST(IdReferenciador AS varchar) LIKE '$qry_delim%'";
		$fld_display = "IdReferenciador + ' ' + Nombre AS Nombre";
	} elseif (substr($qry_delim, 0, 5) == '__ID_') {
        $id = substr($qry_delim, 5);
        $cond = "IdReferenciador = $id";
    } else {
		$cond = " Nombre LIKE '%$qry_delim%' ";
	}
	// if ($show_inactive != 1) {
		$cond .= " AND EsActivo = 1 AND COALESCE(EsEliminado, 0) = 0";
	// }
	
    $add_na_slc = "";
    if (!strlen($id) or $id == -1) {
		$add_na_slc .= "UNION ALL SELECT -1, 'N/A*'";
    }

    $qry = "SELECT IdReferenciador, $fld_display
            FROM AseguradorasReferenciadores
            WHERE $cond
			$add_na_slc
            ORDER BY Nombre";
	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		dbQrySendJson($qry);
	}
}


function rcpValidaData () {
    $ciudad = ($_REQUEST['ciudad']) ? strtoupper($_REQUEST['ciudad']) : -1;
    $estado = ($_REQUEST['estado']) ? strtoupper($_REQUEST['estado']) : -1;
    $is_approx = ($_REQUEST['is_approx']) ? strtoupper($_REQUEST['is_approx']) : 0;
    if ($is_approx == 1) {
        $slc_ciudad = "LIKE '$ciudad%'";
        $slc_estado = "LIKE '$estado%'";
    } else {
        $slc_ciudad = "= '$ciudad'";
        $slc_estado = "= '$estado'";
    }
    
    if ($estado != -1 and $ciudad != -1) {
    /*$qry = "SELECT t1.delegacion, t2.Estado
            FROM
            (
                SELECT delegacion
                FROM Delegaciones
                WHERE upper(delegacion) $slc_ciudad
            ) t1, 
            (
                SELECT Estado
                FROM Estados
                WHERE upper(Estado) $slc_estado
            ) t2";
     */
    $qry = "SELECT TOP 1 t1.delegacion, t2.Estado
            FROM Delegaciones t1
            LEFT JOIN Estados t2 ON
                t1.idestado = t2.Id
            WHERE upper(t1.delegacion) $slc_ciudad AND
                upper(t2.Estado) $slc_estado";
    } elseif ($ciudad != -1) {
    $qry = "SELECT TOP 1 delegacion, '' AS Estado
            FROM Delegaciones
            WHERE upper(delegacion) $slc_ciudad";
    } elseif ($estado != -1) {
    $qry = "SELECT TOP 1 '' AS delegacion, Estado
            FROM Estados
            WHERE upper(Estado) $slc_estado";
    }
    dbQrySendJson($qry);
}


function rcpDatosFiscalesPaciente() {
    $id_paciente = ($_REQUEST['id_paciente']) ? $_REQUEST['id_paciente'] : -1;
    $def_rfc = 'XAXX010101000';
	$qry = "SELECT LTRIM(RTRIM(COALESCE(Nombre, '') + ' ' + COALESCE(Apellidos, '') + ' ' + COALESCE(ApellidosM, ''))) AS NombrePaciente,
                COALESCE(RFC, '$def_rfc') AS RFC, COALESCE(Calle, CallePaciente) AS Calle, COALESCE(NumExterior, NumExteriorPaciente) AS NumExterior,
                COALESCE(NumInterior, NumInteriorPaciente) AS NumInterior, COALESCE(Colonia, ColoniaPaciente) AS Colonia,
                COALESCE(Localidad, LocalidadPaciente) AS Localidad, Municipio, COALESCE(edo, EstadoPaciente) AS Estado,
                COALESCE(CP, CPPaciente) AS CP, COALESCE(pais, PaisPaciente) AS PaisId, COALESCE(tel, tel2) AS Telefono,
                email AS Email, CuentaCfdi
			FROM Pacientes
			WHERE id = $id_paciente";
	dbQrySendJson($qry);
}


function rcpUsoCfdiInf() {
	$cd_uso_cfdi = (isset($_REQUEST['cd_uso_cfdi'])) ? $_REQUEST['cd_uso_cfdi'] : '-1';
	$qry = "SELECT EsPersonaFisica, EsPersonaMoral
			FROM KnlSatUsoCfdi
			WHERE CdUsoCfdi = '$cd_uso_cfdi'";
	dbQrySendJson($qry);
}

// se usa para prestamos. Deberia forzar poner mas de 3 digitos de la factura para que no salgan muchas
function rcpFacturasCliente() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
	$aseguradora_id = (isset($_REQUEST['aseguradora_id'])) ? $_REQUEST['aseguradora_id'] : '';
	$aseguradora_id = (strlen($aseguradora_id)) ? $aseguradora_id : -1; // por ahora si no se indica, se mostraran las facturas
	$empresa_id = (isset($_REQUEST['empresa_id'])) ? $_REQUEST['empresa_id'] : '';
	$empresa_id = (strlen($empresa_id)) ? $empresa_id : -1; // -1 por default para que no muestre factras si no viene la empresa

	$fld_display = "t2.FacturaNo";
	if (substr($qry_delim, 0, 5) == '__ID_') {
        $id = substr($qry_delim, 5);
        $cond = "t1.CxcId = $id";
    } else {
		$cond = "t2.FacturaNo LIKE '%$qry_delim%'";
	}
	$cond .= " AND t1.AseguradoraClienteId = $aseguradora_id AND t2.cfdi_type = 'FAC' AND t1.EmpresaID = $empresa_id AND t1.Status IN(1,2)";
	
	$iva_factor = PC_IVA_ALMACEN;
	if (strlen($iva_factor)) {
		$iva_factor = "1.$iva_factor";
		// $iva_factor = "1"; // OJO, no desglosa el iva, muestra el monto completo de la factura como intereses
	} else {
		$iva_factor = "1";
	}
	
	$qry = "SELECT t1.CxcId, $fld_display,
				CASE WHEN COALESCE(t2.Iva, 0) <> 0 THEN (t1.Importe / ($iva_factor)) ELSE t1.Importe END AS Subtotal,
				CASE WHEN COALESCE(t2.Iva, 0) <> 0 THEN (t1.Importe - (t1.Importe / ($iva_factor))) ELSE 0 END AS Impuesto
			FROM CXC t1
			INNER JOIN Facturas t2 ON t1.FacturaId = t2.FacturaId
			WHERE $cond
			ORDER BY t2.FacturaNoAlfa, t2.FacturaNoNum";

	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		dbQrySendJson($qry);
	}
}


function doctorsList() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2", select2 sirve para el autocomplete de jquery
    $add_other = (isset($_REQUEST['add_other'])) ? $_REQUEST['add_other'] : 0;
    $id_sucursal = (isset($_REQUEST['id_sucursal'])) ? $_REQUEST['id_sucursal'] : '';
	$id = '';
    if (is_numeric($qry_delim)) {
        $cond = "CAST(idMedico AS varchar) LIKE '$qry_delim%'";
        $fld_doctor_name = "idMedico + ' ' + NombreCompleto AS NombreCompleto";
    } elseif (substr($qry_delim, 0, 5) == '__ID_') {
        $id = substr($qry_delim, 5);
        $cond = "idMedico = $id";
    } else {
        $cond = "upper(NombreCompleto) LIKE '%$qry_delim%'";
    }
    $add_other_slc = "";
    if ($add_other == 1 and !strlen($id)) {
        $add_other_slc = "UNION ALL SELECT -1, 'OTRO', 0, '', '', 0, '', '', '', 'OTRO', 'OTRO'";
    }
    if (strlen($id_sucursal)) {
        $cond .= (strlen($cond)) ? " AND " : "";
        $cond .= "COALESCE(IdSucursal, $id_sucursal) = $id_sucursal"; // muestra los que no tienen sucursal indicada y los de la sucursal actual
    }
    $qry = "SELECT idMedico, NombreCompleto, EsDefault, Cedula, CedulaEspecialidad,
                Medicos.IdEspecialidad, Especialidad.Especialidad,
                ApellidoP, ApellidoM, Nombre,
				CONCAT(COALESCE(Nombre, ''), ' ', COALESCE(ApellidoP, ''), ' ', COALESCE(ApellidoM, ''), ' ') AS NombreCompleto2
            FROM Medicos
            LEFT JOIN Especialidad ON
                Medicos.IdEspecialidad = Especialidad.Id
            WHERE Estatus = 1 AND $cond
            $add_other_slc
            ORDER BY NombreCompleto";
	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		// echo "<pre>" . $qry . "</pre>"; exit();
		dbQrySendJson($qry);
	}
}


function rcpPreciosFactoresFamilias() {
    $familia_id = ($_REQUEST['familia_id']) ? $_REQUEST['familia_id'] : '';
    $precio_base = ($_REQUEST['precio_base']) ? $_REQUEST['precio_base'] : 0;
    if (strlen($familia_id)) {
        $qry = "SELECT t1.Campo, 
                    COALESCE(t2.Factor, t1.Factor) * $precio_base AS Precio
                FROM ListasPrecio t1
                LEFT JOIN (SELECT * FROM FactoresPrecioFamilia WHERE Status = 1 AND FamiliaId = $familia_id) t2 ON
                    t1.ListaId = t2.ListaId
                WHERE COALESCE(t2.FamiliaId, $familia_id) = $familia_id AND t1.Status = 1";
        $a_data = dbQryToArrayObj($qry);
        $a_data_new = array();
        foreach($a_data as $a_dt) {
            $campo = $a_dt->Campo;
            $precio = $a_dt->Precio;
            $a_data_new[$campo] = $precio;
        }
        arraySendJson(array($a_data_new));
    }
}


function rcpUsrDateData() {
    $id_user = ($_REQUEST['id_user']) ? $_REQUEST['id_user'] : '';
    if (strlen($id_user)) {
        $nombre = GetUserName($id_user);
    } else {
        $nombre = "";
    }
    $fecha = date('d/m/Y');
    //$dt = date_create($dt_db_lg);
    //$dt_show = date_format($dt, DT_SHOW_FORMAT);
    //$fecha = DtShowToday();
    $a_ret = array();
    $a_ret[] = array('Nombre' => $nombre, 'Fecha' => $fecha);
    arraySendJson($a_ret);
}


function rcpTipoMembresiaInf() {
    $IdTipoMem = ($_REQUEST['id_tipo_mem']) ? $_REQUEST['id_tipo_mem'] : -1;
	$qry = "SELECT IdTipoMem, SolicitaNotas
			FROM TiposMembresias
			WHERE IdTipoMem = $IdTipoMem";
	dbQrySendJson($qry);
}

function rcpIndicacionesAyer() {
    $id_nota_medica_ayer = ($_REQUEST['id_nota_medica']) ? $_REQUEST['id_nota_medica'] : -1;
	$qry = "SELECT IdNotaMedica, Indicacion, Dosis, Horario, Via, NombreComercial, SustanciaActiva,
				Presentacion, Cantidad, Tiempo, Posologia
            FROM NotasMedicasIndicaciones
            WHERE IdNotaMedica = $id_nota_medica_ayer";
	dbQrySendJson($qry);
}

function rcpComoSeEnteroInfo() {
    $id_como_se_entero = ($_REQUEST['id_como_se_entero']) ? $_REQUEST['id_como_se_entero'] : -1;
	$qry = "SELECT id, RequiereEspecificar
			FROM Comoseentero
			WHERE id = $id_como_se_entero";
	dbQrySendJson($qry);
}


function rcpAreaInfo() {
    $area_id = ($_REQUEST['area_id']) ? $_REQUEST['area_id'] : -1;
	
	// lista de precios de urgencias (siempre se lee)
	$qry = "SELECT TOP 1 ListaId FROM ListasPrecio WHERE EsUrgencias = 1";
	$lista_id = dbQryFirstValue($qry);
	$lista_id = (strlen($lista_id)) ? $lista_id : -1;
	
	// lista de precios particular (siempre se lee)
	$qry = "SELECT TOP 1 ListaId FROM ListasPrecio WHERE EsParticular = 1";
	$lista_id_particular = dbQryFirstValue($qry);
	$lista_id_particular = (strlen($lista_id_particular)) ? $lista_id_particular : -1;
	
	$qry = "SELECT AreaId, Nombre, RequiereAnticipo, SinAnticipoOpcional, SinAnticipoLabel, EsUrgencias, $lista_id AS ListaIdUrgencias, $lista_id_particular AS ListaIdParticular
			FROM Areas
			WHERE AreaId = $area_id";
	dbQrySendJson($qry);
}

function listaDiagnosticos() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
    if (substr($qry_delim, 0, 5) == '__ID_') {
        $clave = substr($qry_delim, 5);
        $cond = "Clave = '$clave'";
    } else {
        $cond = "Clave LIKE '%$qry_delim%' OR Diagnostico LIKE '%$qry_delim%'";
    }
    $qry = "SELECT Clave, Diagnostico
            FROM TiposDiagnosticos
            WHERE $cond
            ORDER BY Clave";
    dbQrySendJson($qry);
}


function listaEscuelas() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
    $aseguradora_id = (isset($_REQUEST['aseguradora_id'])) ? $_REQUEST['aseguradora_id'] : "";
    if (substr($qry_delim, 0, 5) == '__ID_') {
        $clave = substr($qry_delim, 5);
        $cond = "IdEscuela = '$clave'";
    } else {
        $cond = "NombreEscuela LIKE '%$qry_delim%'";
        // if (strlen($aseguradora_id)) {
            // $cond .= " AND IdEscuela IN (SELECT IdEscuela FROM Escuelas WHERE AseguradoraClienteId = $aseguradora_id)";
        // }
    }
    // $qry = "SELECT -1 AS IdEscuela, ' OTRA' AS NombreEscuela
            // UNION ALL
            // SELECT IdEscuela, NombreEscuela + 
                // CASE WHEN COALESCE(Nivel, '') <> '' THEN (' ( ' + Nivel + ')') ELSE '' END +
                // CASE WHEN COALESCE(Municipio, '') <> '' THEN Municipio ELSE '' END AS NombreEscuela
            // FROM Escuelas
            // WHERE $cond
            // ORDER BY NombreEscuela";
    $qry = "SELECT IdEscuela, NombreEscuela + 
				CASE WHEN COALESCE(Nivel, '') + COALESCE(Municipio, '') + COALESCE(Turno, '') <> '' THEN ' (' ELSE '' END +
                LTRIM(RTRIM(CASE WHEN COALESCE(Municipio, '') <> '' THEN Municipio ELSE '' END + ' ' +
                CASE WHEN COALESCE(Nivel, '') <> '' THEN Nivel ELSE ' ' END + ' ' +
                CASE WHEN COALESCE(Turno, '') <> '' THEN Turno ELSE ' ' END)) +
				CASE WHEN COALESCE(Nivel, '') + COALESCE(Municipio, '') + COALESCE(Turno, '') <> '' THEN ')' ELSE '' END
				AS NombreEscuela
            FROM Escuelas
            WHERE $cond
            ORDER BY NombreEscuela";
    dbQrySendJson($qry);
}

function listaPacientes() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$add_other = (isset($_REQUEST['add_other'])) ? $_REQUEST['add_other'] : 0;
	$result_type = (isset($_REQUEST['result_type'])) ? $_REQUEST['result_type'] : ""; // <<vacio>> o "select2"
	$id_paciente = '';
    if (substr($qry_delim, 0, 5) == '__ID_') {
        $id_paciente = substr($qry_delim, 5);
        $cond = "id = $id_paciente";
    } else {
		if (strlen(trim($qry_delim)) < 3) {
			exit();
		}
		$cond = getWhereForName($qry_delim);
    }
    $add_other_slc = "";
    if ($add_other == 1 and !strlen($id_paciente)) {
        $add_other_slc = "UNION ALL SELECT -1, 'OTRO', '', '', '', '', ''";
    }
    $qry = "SELECT id, LTRIM(RTRIM(COALESCE(Nombre, '') + ' ' + COALESCE(Apellidos, '') + ' ' + COALESCE(ApellidosM, ''))) AS Paciente,
				Nombre, Apellidos, ApellidosM, cumpleanios, sexo
            FROM Pacientes
            WHERE $cond
			$add_other_slc
            ORDER BY Apellidos, ApellidosM, Nombre, id";
	if ($result_type == 'select2') {
		$a_result = DbQryToArray($qry);
		$a_result = utf8ize($a_result);
		echo json_encode( $a_result, true );
	} else {
		dbQrySendJson($qry);
	}
}

function fechaNacimientoShow() {
	$fecha_nacimiento = (isset($_REQUEST['fecha_nacimiento'])) ? $_REQUEST['fecha_nacimiento'] : '';
	if (strlen($fecha_nacimiento)) {
		$fecha_nacimiento = DtDbToShow($fecha_nacimiento);
	}
	$a_ret = array('fecha_nacimiento' => $fecha_nacimiento);
	echo json_encode($a_ret, true);
}


function listaEventos() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$ver_todos = (isset($_REQUEST['ver_todos'])) ? $_REQUEST['ver_todos'] : 1;
	$id_paciente_base = (isset($_REQUEST['id_paciente_base'])) ? $_REQUEST['id_paciente_base'] : ''; // este es el bebe, al que se le esta buscando a su mama (paciente relacionado)
	
    if (substr($qry_delim, 0, 5) == '__ID_') {
        $id_cargo = substr($qry_delim, 5);
        $cond = "id = $id_cargo"; // PENDIENTE
    } else {
		if (strlen(trim($qry_delim)) < 3) { // minimo 3 caracteres
			exit();
		}
		$cond = getWhereForName($qry_delim);
    }
	// si tiene paciente bebe, toma los apellidos para buscar un evento abierto con esos datos
	$qr1 = '';
	if (strlen($id_paciente_base)) {
		$qry = "SELECT Apellidos, ApellidosM FROM Pacientes WHERE id = $id_paciente_base";
		$a_paciente_base = DbQryToRow($qry);
		$apellido_paterno = $a_paciente_base['Apellidos'];
		$apellido_materno = $a_paciente_base['ApellidosM'];
		$qr1 = "SELECT t101.id, t101.idpaciente, LTRIM(RTRIM(COALESCE(t102.Nombre, '') + ' ' + COALESCE(t102.Apellidos, '') + ' ' + COALESCE(t102.ApellidosM, ''))) + ' (Evento ' + CAST(t101.id AS VARCHAR) + ')' AS Paciente
				FROM Cargos t101
				LEFT JOIN Pacientes t102 ON t101.idpaciente = t102.id
				LEFT JOIN PacientesEstatusRutas t103 ON t101.id = t103.CargosId
				WHERE t101.Status <> 0 AND Apellidos LIKE '%$apellido_paterno%' AND ApellidosM LIKE '%$apellido_materno%' AND t103.TipoMovimiento <> 3 AND $cond
				UNION
				";
	}
	
	
    $cond1 = " AND t1.Status = 1 AND t3.TipoMovimiento <> 3"; // solo eventos abiertos
    if ($ver_todos == 1) {
        $cond1 = "";
    }
	$qry = $qr1;
    $qry .= "SELECT t1.id, t1.idpaciente, LTRIM(RTRIM(COALESCE(t2.Nombre, '') + ' ' + COALESCE(t2.Apellidos, '') + ' ' + COALESCE(t2.ApellidosM, ''))) + ' (Evento ' + CAST(t1.id AS VARCHAR) + ')' AS Paciente
			FROM Cargos t1
			LEFT JOIN Pacientes t2 ON t1.idpaciente = t2.id
			LEFT JOIN PacientesEstatusRutas t3 ON t1.id = t3.CargosId
            WHERE $cond $cond1
            ORDER BY Paciente, id, idpaciente";
    dbQrySendJson($qry);
}


function listaEventosPaciente() {
    $qry_delim = strtoupper(utf8_decode($_REQUEST['query']));
	$ver_todos = (isset($_REQUEST['ver_todos'])) ? $_REQUEST['ver_todos'] : 1;
	$id_paciente = (isset($_REQUEST['id_paciente'])) ? $_REQUEST['id_paciente'] : '';
	$year_month = (isset($_REQUEST['year_month'])) ? $_REQUEST['year_month'] : ''; // para filtrar los eventos cuya fecha de ingreso este en el mes indicado
	
    if (substr($qry_delim, 0, 5) == '__ID_') {
        $id_cargo = substr($qry_delim, 5);
        $cond = "t1.id = $id_cargo"; // PENDIENTE
    } else {
		if (strlen(trim($qry_delim)) < 3) { // minimo 3 caracteres
			$cond = "2 = 1";
		}
		$cond = "t1.id LIKE '%$qry_delim%'";
    }

	if (strlen($id_paciente)) {
		$cond .= " AND t1.idpaciente = $id_paciente";
	} else {
		$cond .= " AND 2 = 1";
	}
	if (strlen($year_month)) {
		$fecha_show = "01/" . $year_month;
		$fecha_db = DtShowToDb($fecha_show);
		$year_month = substr($fecha_db, 0, 6);
		$cond .= " AND FORMAT(t1.FechaIngreso, 'yyyyMM') = $year_month";
	}
	
	// query
	$qry = "SELECT t1.id, 'Evento: ' + CAST(t1.id AS VARCHAR) + ', Fec. Ingreso: ' + FORMAT(t1.FechaIngreso, 'dd/MM/yyyy') + CASE WHEN t1.Status = 1 AND t2.ingresado = 1 THEN ' Abierto' ELSE '' END  AS DescEvento
			FROM Cargos t1
			LEFT JOIN Pacientes t2 ON t1.idpaciente = t2.id
			WHERE $cond
			ORDER BY FechaIngreso";
	$a_result = DbQryToArray($qry, true);
	$a_result = utf8ize($a_result);
	echo json_encode( $a_result, true );
}


function rcpMedicosPorTipo() {
    $id_tipo_medico = ($_REQUEST['id_tipo_medico']) ? $_REQUEST['id_tipo_medico'] : '';
	$where = "WHERE Estatus = 1";
	$where .= (strlen($id_tipo_medico)) ? " AND IdTipoMedico = $id_tipo_medico" : "";
    $qry = "SELECT IdMedico, 
				COALESCE(ApellidoP, '') + ' ' + COALESCE(ApellidoM, '') + ' ' +  COALESCE(Nombre, '') + ' - ' + CASE WHEN IdTipoMedico = 1 THEN 'Medico General' WHEN IdTipoMedico = 2 THEN 'Especialista' WHEN IdTipoMedico = 3 THEN 'Tecnico Radiologo' ELSE '' END AS Medico 
			FROM Medicos
			$where
			ORDER BY COALESCE(ApellidoP, '') + ' ' + COALESCE(ApellidoM, '') + ' ' +  COALESCE(Nombre, '')";
	$a_rows = dbQryToArrayObj($qry);
	foreach($a_rows as $row) {
		$row->Medico = utf8_encode($row->Medico);
	}
	$data_json = "{\"data\":" . json_encode($a_rows, true) . '}';
	echo $data_json;
    // dbQrySendJson($qry);
}



function recordUnlockAjax() {
    $id_cuenta = ($_REQUEST['id_cuenta']) ? $_REQUEST['id_cuenta'] : -1;
    $qry = "DELETE FROM RecordLock WHERE IdCuenta = $id_cuenta";
    DbExecute($qry);
    DbCommit();
}
?>