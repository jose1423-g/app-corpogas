<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/db.inc.php");
require_once("$SYS_ROOT/php/knl/locale.inc.php");
require_once("$SYS_ROOT/php/knl/dates.inc.php");
require_once("$SYS_ROOT/php/knl/phpext.inc.php");
require_once("$SYS_ROOT/php/cxc/cxc.inc.php");
	
// SessionStart();

// agrega / edita / elimina conceptos de gastos de cxp

// recupera datos
// header
$id_user = SessGetUserId();
session_write_close();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';
$id_proveedor = (isset($_REQUEST['id_proveedor'])) ? $_REQUEST['id_proveedor'] : ''; 
$id_proveedor_save = (isset($_REQUEST['idproveedor'])) ? $_REQUEST['idproveedor'] : '';

$razon_social = (isset($_REQUEST['RazonSocial'])) ? $_REQUEST['RazonSocial'] : '';
$rfc = (isset($_REQUEST['rfc'])) ? $_REQUEST['rfc'] : '';
$id_cif = (isset($_REQUEST['IdCif'])) ? $_REQUEST['IdCif'] : '';
$nombre_corto = (isset($_REQUEST['NombreCorto'])) ? $_REQUEST['NombreCorto'] : '';
$estatus = (isset($_REQUEST['Estatus'])) ? 1 : 0; 
$es_default = (isset($_REQUEST['EsEfDefault'])) ? 1 : 0;
$dias_credito = (isset($_REQUEST['diascredito'])) ? $_REQUEST['diascredito'] : ''; 
$conceptos_default = (isset($_REQUEST['ConceptosDefault'])) ? $_REQUEST['ConceptosDefault'] : array(); 
$ISR = (isset($_REQUEST['PcISRRet'])) ? $_REQUEST['PcISRRet'] : ''; 
$cd_regimen_fiscal = (isset($_REQUEST['CdRegimenFiscal'])) ? $_REQUEST['CdRegimenFiscal'] : '';
$forma_pago = (isset($_REQUEST['ForPag'])) ? $_REQUEST['ForPag'] : '';
$direccion = (isset($_REQUEST['direccion'])) ? $_REQUEST['direccion'] : ''; 
$contacto = (isset($_REQUEST['contacto'])) ? $_REQUEST['contacto'] : ''; 
$ciudad = (isset($_REQUEST['ciudad'])) ? $_REQUEST['ciudad'] : ''; 
$estado = (isset($_REQUEST['estado'])) ? $_REQUEST['estado'] : ''; 
$cp = (isset($_REQUEST['cp'])) ? $_REQUEST['cp'] : ''; 
$pais = (isset($_REQUEST['pais'])) ? $_REQUEST['pais'] : ''; 
$tel = (isset($_REQUEST['tel'])) ? $_REQUEST['tel'] : ''; 
$ext = (isset($_REQUEST['ext'])) ? $_REQUEST['ext'] : ''; 
$tel2_fax = (isset($_REQUEST['fax'])) ? $_REQUEST['fax'] : ''; 
$email = (isset($_REQUEST['email'])) ? $_REQUEST['email'] : '';
$notas = (isset($_REQUEST['notas'])) ? $_REQUEST['notas'] : ''; 
$descuento = (isset($_REQUEST['descuento'])) ? $_REQUEST['descuento'] : ''; 
$tipo_proveedor = (isset($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : ''; 
$credito = (isset($_REQUEST['credito'])) ? $_REQUEST['credito'] : '';
$cuenta_contable = (isset($_REQUEST['CuentaContable'])) ? $_REQUEST['CuentaContable'] : ''; 
$cuenta_contable_2 = (isset($_REQUEST['CuentaContable2'])) ? $_REQUEST['CuentaContable2'] : ''; 
$cuenta_contable_3 = (isset($_REQUEST['CuentaContable3'])) ? $_REQUEST['CuentaContable3'] : ''; 
$cd_prov_terceros = (isset($_REQUEST['CdProvTerceros'])) ? $_REQUEST['CdProvTerceros'] : ''; 
$cd_prov_terceros_2 = (isset($_REQUEST['CdProvTerceros2'])) ? $_REQUEST['CdProvTerceros2'] : '';

$cif_rfc = (isset($_REQUEST['cif_rfc'])) ? $_REQUEST['cif_rfc'] : '';
$cif_id_cif = (isset($_REQUEST['cif_IdCif'])) ? $_REQUEST['cif_IdCif'] : ''; // numerito de la Cif
$cif_qr_search = (isset($_REQUEST['cif_qr_search'])) ? $_REQUEST['cif_qr_search'] : ''; // url completa del QR

 
$msg = '';
$result = 0;
$dias_credito_changed_msg  = '';
$a_rfc_generico = array('XAXX010101000', 'XEXX010101000');

if ($op == 'load') {
	if (!strlen($id_proveedor)) {
		$msg = 'Hubo un error, el proveedor no fue seleccionado correctamente';
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
	} else {
		//diascredito,ISR,credito,descuento
		$qry = "SELECT idproveedor, RazonSocial, rfc, NombreCorto, Estatus, EsEfDefault, diascredito, PcISRRet, CdRegimenFiscal,
					forpag, direccion, contacto, ciudad, estado, cp, pais, tel, ext, fax, email, notas, descuento, tipo, credito,
					CuentaContable, CuentaContable2, CuentaContable3, CdProvTerceros, CdProvTerceros2,
					COALESCE(ConceptosDefault, '') AS ConceptosDefault, IdCif
				FROM Proveedores
				WHERE idproveedor = $id_proveedor";
		$a_proveedor = DbQryToRow($qry, null, true);
		if (is_string($a_proveedor)) {
			$a_ret['result'] = 0;
			$a_ret['msg'] = $a_proveedor; // viene como string con el error en la BD
		} else {
			$a_proveedor = utf8ize($a_proveedor);

			$a_proveedor['diascredito'] = ($a_proveedor['diascredito'] == 0) ? "" : number_format($a_proveedor['diascredito'], 0);
			$a_proveedor['PcISRRet'] = ($a_proveedor['PcISRRet'] == 0) ? "" : number_format($a_proveedor['PcISRRet'], 4);
			$a_proveedor['credito'] = ($a_proveedor['credito'] == 0) ? "" : number_format($a_proveedor['credito'], 2);
			$a_proveedor['descuento'] = ($a_proveedor['descuento'] == 0) ? "" : number_format($a_proveedor['descuento'], 2);

			$a_ret = $a_proveedor;
			$a_ret['result'] = 1;
			$a_ret['msg'] = $msg;
		}
		echo json_encode($a_ret);
		exit();
	}
} elseif ($op == 'delete') {
	if (!strlen($id_proveedor)) {
		$msg = 'Hubo un error, el proveedor no fue seleccionado correctamente'. $id_proveedor;
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
	} else {
		$qry = "DELETE FROM Proveedores WHERE idproveedor = $id_proveedor";
		$res = DbExecute($qry, true);
		DbCommit();
		if (is_string($res)) {
			$msg = "No se pudo eliminar el proveedor: " . $res;
		} else {
			if (!$res) {
				$msg = "No se pudo eliminar el proveedor";
			} else {
				$msg = "Se elimino el proveedor";
				$result = 1;
			}
		}
	}
	//SAVE
} elseif ($op == 'save') {
	$id_proveedor = $id_proveedor_save; // compatibility
	if (!strlen($razon_social)) {
		$msg = 'El campo razon social es requerido';
	} elseif (!strlen($rfc)) {
		$msg = 'El campo RFC es requerido';
	} elseif(!strlen($nombre_corto)){
		$msg = 'El campo Nombre corto es requerido';
	} elseif (!strlen($cuenta_contable)) {
		$msg = 'El campo Cuenta contable es requerido';
	} elseif (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	} else {
		// transformaciones
		$cuenta_contable = utf8_decode($cuenta_contable);
		$cuenta_contable_2 = utf8_decode($cuenta_contable_2);
		$cuenta_contable_3 = utf8_decode($cuenta_contable_3);
		$cuenta_contable = preg_replace("/[^A-Za-z0-9]/",'', $cuenta_contable);
		$cuenta_contable_2 = preg_replace("/[^A-Za-z0-9]/",'', $cuenta_contable_2);
		$cuenta_contable_3 = preg_replace("/[^A-Za-z0-9]/",'', $cuenta_contable_3);
		$razon_social = utf8_decode($razon_social);
		$ciudad = utf8_decode($ciudad);
		$estado = utf8_decode($estado);
		$tel = utf8_decode($tel);
		$ext = utf8_decode($ext);
		$tel2_fax = utf8_decode($tel2_fax);
		$email = utf8_decode($email);
		$notas = utf8_decode($notas);
		$descuento = (strlen($descuento)) ? NumShowToDb($descuento) : "NULL";
		$estatus = (strlen($estatus)) ? $estatus : 0;
		$es_default = (strlen($es_default)) ? $es_default : 0;
		$conceptos_default = implode(",", $conceptos_default);
		$conceptos_default = (strlen($conceptos_default)) ? $conceptos_default : '';
		$credito = (strlen($credito)) ? NumShowToDb($credito) : 'NULL';
		$dias_credito_input = (strlen($dias_credito)) ? $dias_credito : '';
		$dias_credito = (strlen($dias_credito_input)) ? NumShowToDb($dias_credito_input) : 'NULL';

		if (strlen($id_proveedor)) {

			if (!in_array($rfc, $a_rfc_generico)) {

				$qry = "SELECT * FROM proveedores WHERE rfc = '$rfc' AND idproveedor <> $id_proveedor";
				$id_proveedor_existe = DbGetFirstFieldValue($qry);

				if(strlen($id_proveedor_existe)){
					$msg = 'EL RFC ingresado se encuentra utilizado por otro proveedor';
					$result = -1;
					$a_ret = array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					exit();
				} 
			} 
			
			//leer el valor de diascredito de la bd
			$qry = "SELECT diascredito FROM Proveedores WHERE idproveedor = $id_proveedor";
			$dias_credito_ori = DbGetFirstFieldValue($qry);
			
			$qry = "UPDATE Proveedores
					SET RazonSocial = '$razon_social',
						rfc = '$rfc',
						NombreCorto = '$nombre_corto',
						Estatus = $estatus,
						EsEfDefault = $es_default,
						diascredito = $dias_credito,
						ConceptosDefault = '$conceptos_default',
						PcISRRet = '$ISR',
						CdRegimenFiscal = '$cd_regimen_fiscal',
						forpag = '$forma_pago',
						direccion = '$direccion',
						contacto = '$contacto',
						ciudad = '$ciudad',
						estado = '$estado',
						cp = '$cp',
						pais = '$pais',
						tel = '$tel',
						ext = '$ext',
						fax = '$tel2_fax',
						email = '$email',
						notas = '$notas',
						descuento = $descuento,
						tipo = '$tipo_proveedor',
						credito = $credito,
						CuentaContable = '$cuenta_contable',
						CuentaContable2 = '$cuenta_contable_2',
						CuentaContable3 = '$cuenta_contable_3',
						CdProvTerceros = '$cd_prov_terceros',
						CdProvTerceros2 = '$cd_prov_terceros_2',
						IdCif = '$id_cif'
						WHERE idproveedor = $id_proveedor";
			$res_upd = DbExecute($qry, true);
			DbCommit();
			if (is_string($res_upd)) {
				$msg = 'No se pudo actualizar el proveedor:' . $res_upd;
			} else {
				if (!$res_upd) {
					$msg = 'Error al actualizar el proveedor';
				} else {
					$msg = 'Proveedor actualizado con exito';
					$result = 1;

					if ($dias_credito_ori != $dias_credito_input) {
						$dias_credito_changed_msg = "Ha cambiado los dias de crédito ('$dias_credito_ori','$dias_credito_input'. Haga <a href=\"cxp_proveedores_venc_update.php?idproveedor=$id_proveedor\">click aqui</a> para cambiar la fecha de vencimiento en las provisiones pendientes de pago";
					}
				}
			}
		} else {
			
			if (!in_array($rfc, $a_rfc_generico)) {
				$qry = "SELECT * FROM proveedores WHERE rfc = '$rfc'";
				$id_proveedor_existe = DbGetFirstFieldValue($qry);
				if(strlen($id_proveedor_existe)){
					$msg = 'EL RFC ingresado ya se encuentra utilizado por otro proveedor';
					$result = -1;
					$a_ret = array('result' => $result, 'msg' => $msg);
					echo json_encode($a_ret);
					exit();
				} 
			}	

			$qry = "INSERT INTO Proveedores (RazonSocial, rfc, NombreCorto, Estatus, EsEfDefault, diascredito, PcISRRet, CdRegimenFiscal, 
				forpag, direccion, contacto, ciudad, estado, cp, pais, tel, ext, fax, email, notas, descuento,
				tipo, credito, CuentaContable, CuentaContable2, CuentaContable3, CdProvTerceros, CdProvTerceros2,
				ConceptosDefault, IdCif)
			VALUES('$razon_social', '$rfc', '$nombre_corto', $estatus, $es_default, $dias_credito, '$ISR', '$cd_regimen_fiscal',
				'$forma_pago', '$direccion', '$contacto','$ciudad', '$estado', '$cp', '$pais', '$tel', '$ext', '$tel2_fax', '$email', '$notas', $descuento,
				'$tipo_proveedor', $credito, '$cuenta_contable','$cuenta_contable_2', '$cuenta_contable_3', '$cd_prov_terceros', '$cd_prov_terceros_2', '$conceptos_default', '$id_cif')";
			$res_ins = DbExecute($qry, true);

			DbCommit();
			if (is_string($res_ins)) {
				$msg = 'Error al agregar el proveedor: ' . $res_ins . "". $qry;
			} else {
				if (!$res_ins) {
					$msg = 'Error al agregar el proveedor';
				} else {
					$id_proveedor = LastIdAutoTable('Proveedores');
					$msg = 'Proveedor agregado con exito';
					$result = 1;
				}
			}
		}
	}
} elseif ($op == 'getDataFromCif') {
	$a_cif_data = array();
	$msg_add = "";
	// $api_php_81_get_cif_url = API_PHP_81_GET_CIF_URL;
	if (!strlen($id_user)) {
		$msg = 'Su sesion ha expirado';
		$result = -1;
	// } elseif (!strlen($api_php_81_get_cif_url)) {
		// $msg = 'No esta configurada la lectura de datos del SAT';
		// $result = -1;
	} else {
		// valida si es la url completa o solo los datos separados
		if (base64_encode(mb_substr(mb_strtoupper($cif_qr_search), 0, 23)) == 'SFRUUFM6Ly9TSUFULlNBVC5HT0IuTVg=') { // ojo con el padding
			// si tiene la url, parte los datos
			$a_url = parse_url($cif_qr_search);
			$query = $a_url['query'];
			parse_str($query, $a_params);
			$data = $a_params['D3'];
			$a_data = explode("_", $data);
			$cif_rfc = trim($a_data[1]);
			$cif_id_cif = ($a_data[0]);
			// echo "<pre>" . "Cif: $cif_id_cif, RFC: $cif_rfc" . "</pre>"; exit();
		}
			
		// si vienen ambos datos prueba con ellos
		if (strlen($cif_rfc) and strlen($cif_id_cif) and is_numeric($cif_id_cif)) {
			// echo '"C:/Program Files/php81/php" "../../dbqry/get_cif_data.php"' . " \"$cif_rfc\" \"$cif_id_cif\"";
			exec('"C:/Program Files/php81/php" "../../dbqry/get_cif_data.php"' . " \"$cif_rfc\" \"$cif_id_cif\"", $output, $return_var);
			$respuesta = end($output);
			
			// // usando curl
			// $a_post_data = array('rfc' => $cif_rfc, 'idCif' => $cif_id_cif);
			// $json_post_data = json_encode($a_post_data);
			// $header = array(
				// "Content-Type:application/json",
				// "Content-Length: " . strlen($json_post_data)
			// );

			// //Parametros de la conexion al webservice y URL del servicio
			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_URL, $api_php_81_get_cif_url);
			// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
			// curl_setopt($ch, CURLOPT_TIMEOUT,        500);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			// curl_setopt($ch, CURLOPT_POST,           true );
			// curl_setopt($ch, CURLOPT_POSTFIELDS,     $json_post_data);
			// curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
			// // $info = curl_getinfo($ch);
			
			// // Respuesta del webservice
			// $respuesta = curl_exec($ch);
			// curl_close($ch);
			
			$a_person = json_decode($respuesta);
			// echo "<pre>" . print_r($a_person, true) . "</pre>"; exit();
			
			// construye respuesta con datos del proveedor, no se actualiza automaticamente
			if ($a_person->isMoral == 1) {
				$nombre = $a_person->razon_social;
			} else {
				$nombre = $a_person->nombre . ' ' . $a_person->apellido_paterno . ' ' . $a_person->apellido_materno;
			}
			$nombre = ucwordss($nombre);
			$result = 1;
			$msg = "Ok";
			
			$a_cif_data['cif'] = $a_person->id_cif;
			$a_cif_data['nombre'] = $nombre;
			$a_cif_data['rfc'] = $a_person->rfc;
			$a_cif_data['curl'] = (isset($a_person->curp)) ? $a_person->curp : '';
			$regimen = reset($a_person->regimenes);
			$a_cif_data['cd_regimen'] = $regimen->regimen_id;
			$a_cif_data['codigo_postal'] = $a_person->codigo_postal;
			$a_cif_data['email'] = $a_person->correo_electronico;
			$a_cif_data['entidad_federativa'] = $a_person->entidad_federativa;
			$a_cif_data['municipio_delegacion'] = $a_person->municipio_delegacion;
			// $a_cif_data['colonia'] = $a_person->colonia;
			// $a_cif_data['tipo_vialidad'] = $a_person->tipo_vialidad;
			// $a_cif_data['nombre_vialidad'] = $a_person->nombre_vialidad;
			// $a_cif_data['numero_exterior'] = $a_person->numero_exterior;
			// $a_cif_data['numero_interior'] = $a_person->numero_interior;
			$a_cif_data['direccion'] = $a_person->nombre_vialidad . " " .
				$a_person->numero_exterior . " " . $a_person->numero_interior . " " . $a_person->colonia;
			
			$a_cif_data['situacion_contribuyente'] = $a_person->situacion_contribuyente;
			if ($a_cif_data['situacion_contribuyente'] != "ACTIVO" and $a_cif_data['situacion_contribuyente'] != "REACTIVADO") {
				$msg_add = "El contribuyente no esta activo";
			}
			
		} else {
			$result = -1;
			if (!strlen($cif_rfc)) {
				$msg = 'Debe proporcionar el RFC';
			} else {
				$msg = 'No se puede obtener la informacion del RFC con los datos proporcionados';
			}
		}
	}
	$a_ret = array('result' => $result, 'msg' => $msg, 'msg_add' => $msg_add, 'cif_data' => $a_cif_data);
	echo json_encode($a_ret);
	exit();
}

$a_ret = array('result' => $result, 'msg' => $msg, 'idproveedor' => $id_proveedor, 'dias_credito_changed_msg' => $dias_credito_changed_msg);
echo json_encode($a_ret);
?>