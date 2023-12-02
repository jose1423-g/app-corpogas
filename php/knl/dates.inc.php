<?php

require_once("sys_config.inc.php");

// date_default_timezone_set("America/Mexico_City"); // se toma de sys_config

function DtDbLgToday() {
	$dt_db_lg = date(DT_DB_LG_FORMAT);
	return $dt_db_lg;
}


function DtDbToday() {
    $dt_db = date(DT_DB_FORMAT);
	return $dt_db;
}


function DtDbLgToDb($dt_db) {
    $dt_db = str_replace('-', '', $dt_db);
	$dt_db = str_replace('/', '', $dt_db);
	$dt_db = substr($dt_db, 0, 8); // 8 posiciones de fecha	
	return $dt_db;
}

function DtDbToArray($dt_db) {

	$dt_db = DtDbLgToDb($dt_db);

	$ret = array();
	if (DtDbIsValid($dt_db)) {
		$y = substr($dt_db, 0, 4);
		$m = substr($dt_db, 4, 2);
		$d = substr($dt_db, 6, 2);
		$ret = array('y' => $y, 'm' => $m, 'd' => $d);
	}
	return $ret;
}


function DtDbIsValid($dt_db) {
	if (!is_null($dt_db) and is_numeric($dt_db) and (strlen($dt_db) == 8) and preg_match('/[0-9]{8}/', $dt_db)) {
		return checkdate(substr($dt_db, 4, 2), substr($dt_db, 6, 2), substr($dt_db, 0, 4));
	}
}


function DtDbLgIsValid($dt_db_lg) {
    $dt_db_lg = str_replace('-', '', $dt_db_lg);
	$dt_db_lg = str_replace('/', '', $dt_db_lg);
	$dt_db = substr($dt_db_lg, 0, 8); // 8 posiciones de fecha	
	return DtDbIsValid($dt_db);
}


// devuelve una fecha en formato para mostrar al usuario a partir del formato corto de la BD
// si le envian el parametro hr, devuelve la hora
function DtDbToShow($dt_db, $hr = '', $tm_format = TM_SHOW_FORMAT) {
	$sep = DT_SHOW_SEP;
	$a_dt = DtDbToArray($dt_db);
    $dt_show = "";
    if (count($a_dt) > 0) {
        $dt_show = $a_dt['d'] . $sep . $a_dt['m'] . $sep . $a_dt['y'];
    }
    if (strlen($hr)) {
        $tm = date_create($hr);
        $tm_show = date_format($tm, $tm_format);
        $dt_show = $dt_show . ' ' . $tm_show;
    }
	return $dt_show;
}


// devuelve una fecha en formato para mostrar al usuario a partir de una fecha en formato largo de la B
function DtDbLgToShow($dt_db_lg) {
    $dt_show = $dt_db_lg;
    $dt = date_create($dt_db_lg);
    if ($dt) {
        $dt_show = date_format($dt, DT_SHOW_FORMAT);
    }
    
    return $dt_show;
}


// devuelve la hora en formato para mostrar al usuario a partir de una fecha en formato largo de la BD
function TmDbLgToShow($dt_db_lg, $tm_format = "") {
    $dt = date_create($dt_db_lg);
	if (!strlen($tm_format)) {
		$tm_format = TM_SHOW_FORMAT;
	}
    $tm_show = date_format($dt, $tm_format);
    return $tm_show;
}

// devuelve una hora en formato para base de datos a partir de una fecha en formato largo de la BD
function DtDbLgToTmDb($dt_db_lg) {
    $dt = date_create($dt_db_lg);
    $dt_show = date_format($dt, TM_SHOW_FORMAT_M);
    return $dt_show;
}


// devuelve una fecha y hora en formato para mostrar al usuario a partir de una fecha en formato largo de la BD
function DtTmDbLgToShow($dt_db_lg, $hrs = 12) {
    $dt = date_create($dt_db_lg);
    if ($hrs == 12) {
        $dt_show = date_format($dt, DT_SHOW_FORMAT . ' ' . TM_SHOW_FORMAT);
    } else { // hrs = 24
        $dt_show = date_format($dt, DT_SHOW_FORMAT . ' ' . TM_SHOW_FORMAT_M);
    }
    return $dt_show;
}


function DtShowToday() {
    $dt = DtDbLgToday();
    $dt = DtDbLgToShow($dt);
    return $dt;
}


function DtDbDiff($dt_from, $dt_to, $is_result_array = false) {
	$a_dtf = DtDbToArray($dt_from);
	$a_dtt = DtDbToArray($dt_to);
    $year_diff = 0;
    $month_diff = 0;
    $day_diff = 0;
    if (count($a_dtt) >= 1 and count($a_dtf) >= 1) {
        $year_diff = $a_dtt['y'] - $a_dtf['y'];
        $month_diff = $a_dtt['m'] - $a_dtf['m'];
        $day_diff = $a_dtt['d'] - $a_dtf['d'];
        if ($month_diff < 0 || $day_diff < 0) {
            $year_diff--;
        }
    }
	if ($is_result_array == false) {
		return $year_diff;
	} else {
		if ($month_diff < 0) { $month_diff = 12 + $month_diff;}
		$a_ret = Array('y' => $year_diff, 'm' => $month_diff, 'd' => $day_diff);
		return $a_ret;
	}
}


function DtDbDiffLbl($dt_from, $dt_to, $is_days = false, $is_months = false, $is_years_lbl = true, $show_zeros = true) {
	$ret = "";
	$a_diff = DtDbDiff($dt_from, $dt_to, true);
	$anios = $a_diff['y'];
	$meses = $a_diff['m'];
    $dias = $a_diff['d'];
    $anios = ($anios < 1) ? 0 : $anios;
    $meses = ($meses < 1) ? 0 : $meses;
    $dias = ($dias < 1) ? 0 : $dias;
	if ($is_years_lbl) {
		if ($anios == 1) {
			$lbl_anios = 'a�o';
		} else {
			$lbl_anios = 'a�os';
        }
	} else {
		$lbl_anios = '';
	}
	if ($is_months) {
		if ($meses == 1) {
			$lbl_meses = 'mes';
		} elseif ($meses == 0) {
			$lbl_meses = '';
            if (!$show_zeros) {
                $meses = '';
            }
		} else {
			$lbl_meses = 'meses';
		}
        if ($is_days) {
            if ($dias == 1) {
                $lbl_dias = 'd�a';
            } elseif ($dias == 0) { 
                $lbl_dias = '';
                if (!$show_zeros) {
                    $dias = '';
                }
            } else {
                $lbl_dias = 'd�as';
            }
            $ret = $anios . ' ' . $lbl_anios . ' ' . $meses . ' ' . $lbl_meses . ' ' . $dias . ' ' . $lbl_dias;
        } else {
            $ret = $anios . ' ' . $lbl_anios . ' ' . $meses . ' ' . $lbl_meses;
        }
	} else {
		$ret = $anios . ' ' . $lbl_anios;
	}
  
	$ret = trim($ret);
	return $ret;
}

// recibe 2 fechas en formato de la bd con sus respectivas horas y devuelve el numero de horas entre cada fecha
function DtTmDiff($dt_from, $hr_from, $dt_to, $hr_to){
    $ret = 0;

    $from = "$dt_from $hr_from";
    $to = date_create("$dt_to $hr_to");
    $count = 0;
    while(date_create($from) < $to){
        $from = date("Ymd H:i:s", strtotime("+1 hour", strtotime($from)));
        $count++;
    }
    $ret = $count;
    
    return $ret;
}


// recibe 2 fechas en formato de la bd con sus respectivas horas y devuelve el numero de minutos de diferencia
// ret: interval, label, long_label, minutos (int)
//    	interval: 	devuelve el intervalo y debe ser formateado por quien reciba el return
//		label:    	devuelve etiqueta creada en esta funcion: se trunca solo a 1 elemento (solo dias o solo a�os o solo minutos o solo horas, no da la diferencia completa)
//		long_label:	misma que label pero con todo el intervalo explicado (ej. 1 a�o, 3 meses, 2 dias, 3 horas 5 minutos)
//		dias:		numero total de dias entre 2 fechas
//		minutos:	numero total de minutos entre 2 fechas
function DtTmMinutesDiff($dt_from, $hr_from, $dt_to, $hr_to, $ret = "label"){
    $from = date_create("$dt_from $hr_from");
    $to = date_create("$dt_to $hr_to");
	
	$interval = date_diff($from, $to);
	if ($ret == "interval") {
		return $interval;
	}
	$dias = $interval->format('%R%a'); // total de dias de la diferencia
	if ($ret == "dias") {
		return $dias;
	}
	$horas = $interval->format('%h%');
	if ($ret == "minutos") {
		if ($dias >= 1) {
			return $dias * 24 * 60;
		}
		if ($horas >= 1) {
			return $horas * 60;
		}
		return $interval->format('%i%');
	}
	
	// para label o long_label
	$min_label = "min";
	$hr_label = "hr";
	$day_label = "d�a";
	$month_label = "mes";
	$year_label = "a�o";
	// pone minutos hora o dias
	$years = $interval->format('%y%');
	$meses = $interval->format('%m%');
	$dias_d = $interval->format('%d%'); // dias dentro de un mismo mes?
	$minutos = $interval->format('%i%');
	
	if ($ret == "label") { // no incluye meses
		if ($years == 0) {
			if ($dias == 0) {
				if ($horas == 0) {
					$pl_label = ($minutos > 1) ? "s" : "";
					$lbl = "$minutos $min_label" . $pl_label;
				} else {
					$pl_label = ($horas > 1) ? "s" : "";
					$lbl = "$horas $hr_label" . $pl_label;
				}
			} else {
				$pl_label = ($dias > 1) ? "s" : "";
				$lbl = "$dias $day_label" . $pl_label;
			}
		}  else {
			$pl_label = ($years > 1) ? "s" : "";
			$lbl = "$years $year_label" . $pl_label;
		}
		return $lbl;
	}
	
	if ($ret == "long_label") {
		$lbl = "";
		$pl_label = ($years > 1) ? "s" : "";
		$lbl .= ($years > 0) ? ($years . " " . $year_label . $pl_label . " ") : "";
		$pl_label = ($meses > 1) ? "es" : "";
		$lbl .= ($meses > 0) ? ($meses . " " . $month_label . $pl_label . " ") : "";
		$pl_label = ($dias_d > 1) ? "s" : "";
		$lbl .= ($dias_d > 0) ? ($dias_d . " " . $day_label . $pl_label . " ") : "";
		$pl_label = ($horas > 1) ? "s" : "";
		$lbl .= ($horas > 0) ? ($horas . " " . $hr_label . $pl_label . " ") : "";
		$pl_label = ($minutos > 1) ? "s" : "";
		$lbl .= ($minutos > 0) ? ($minutos . " " . $min_label . $pl_label . " ") : "";
		
		return trim($lbl);
	}
}


// recibe fecha en formato bd y devuelve la diferencia en dias (con signo)
// puede usar se para fechas o para horas.
function DtDbDiffDays($dt_from, $dt_to){
    $ret = 0;

	$from = date_create($dt_from);
	$to  = date_create($dt_to);

	// days from
    $y = $from->format('Y') - 1;
    $days = $y * 365;
    $z = (int)($y / 4);
    $days += $z;
    $z = (int)($y / 100);
    $days -= $z;
    $z = (int)($y / 400);
    $days += $z;
    $days += $from->format('z');
	$days_from = $days;

	// days to
    $y = $to->format('Y') - 1;
    $days = $y * 365;
    $z = (int)($y / 4);
    $days += $z;
    $z = (int)($y / 100);
    $days -= $z;
    $z = (int)($y / 400);
    $days += $z;
    $days += $to->format('z');
	$days_to = $days;

	return $days_to - $days_from;
}


function DtShowToDb($dt_show) {
    $dt_show = str_replace('-', '', $dt_show);
	$dt_show = str_replace('/', '', $dt_show);
	$dt_show = substr($dt_show, 0, 8); // 8 posiciones de fecha	
    $y = substr($dt_show, 4, 4);
    $y = trim($y);
    // si ponen a�o con 2 digitos trata de convertirlo a 4 digitos con este algoritmo
    if (strlen($y) == 2) {
        if ($y < '40') {
            $y = "20$y";
        } else {
            $y = "19$y";
        }
    }
    $m = substr($dt_show, 2, 2);
    $d = substr($dt_show, 0, 2);
    $dt_db = $y . $m . $d;

    return $dt_db;
}


function DtShowToDbLg($dt_show, $hora = '') {
    $sep = DT_SHOW_SEP;
    $dt_show = str_replace('-', '', $dt_show);
	$dt_show = str_replace('/', '', $dt_show);
	$dt_show = substr($dt_show, 0, 8); // 8 posiciones de fecha	
    $y = substr($dt_show, 4, 4);
    $y = trim($y);
    // si ponen a�o con 2 digitos trata de convertirlo a 4 digitos con este algoritmo
    if (strlen($y) == 2) {
        if ($y < '40') {
            $y = "20$y";
        } else {
            $y = "19$y";
        }
    }

    $m = substr($dt_show, 2, 2);
    $d = substr($dt_show, 0, 2);
    if (!strlen($hora)) {
        $hora = TmDbStamp();
    }
    $dt_db_lg = $y . $sep . $m . $sep . $d . ' ' . $hora;
    return $dt_db_lg;
}


function DtDbAddDays($dt_db, $ndias) {
	$a_dt = DtDbToArray($dt_db);
	$dt_ts_new = DtTsDateSerial($a_dt['y'], $a_dt['m'], $a_dt['d'] + $ndias);
	$dt_db_new = DtTsToDb($dt_ts_new);
	
    return $dt_db_new;
}

function DtAddDays($dt, $no_days) {
	return mktime(0, 0, 0, date("n", $dt), date("j", $dt) + $no_days, date("Y", $dt));
}

function DtTsDateSerial($year, $month, $day) {
	return mktime(0, 0, 0, $month, $day, $year);
}

function DtLastDayOfMonth($year, $month) {
    return DtAddDays(DtTsDateSerial($year, $month + 1, 1), -1);
}


function DtYearMonthAddMonths($year, $month, $no_months) {
	$day = 1;
    $dt_ts = mktime(0, 0, 0, $month + $no_months, $day, $year);
    $dt_db = DtTsToDb($dt_ts);
    $a_dt = DtDbToArray($dt_db);
	$dt_year = $a_dt['y'];
	$dt_month = $a_dt['m'];

    return (array('year' => $dt_year, 'month' => $dt_month));
}

// devuelve la diferencia en meses de 2 pares de valores a�o-mes.
// el sufijo "to" indica la fecha que se asume es la mayor. Si "_from" es mayor, el resultado debe ser negativo �?
function DtYearMonthDiffMonths($year_from, $month_from, $year_to, $month_to) {
	if (function_exists('date_diff')) {
		$day = 1;
		$dt_from = date_create("$year_from-$month_from-$day");
		$dt_to = date_create("$year_to-$month_to-$day");
		$dif = date_diff($dt_from, $dt_to);
		$month_diff = $dif->format('%m');
	} else {
		// en versiones de php anteriores a 5.3 no existe la funcion date_diff, se hace manualmente
		$year_diff = $year_to - $year_from;
		$months_added = ($year_diff * 12);
		$month_diff = ($month_to + $months_added) - $month_from;
	}
	
	return $month_diff;
}

function DtTsToDb($dt_ts) {
//	if (DtTsIsValid($dt_ts)) {
		return date(DT_DB_FORMAT, $dt_ts);
//	} else {
//		return DATE_INVALID_DT_DB;
//	}
}


function TmDbStamp() {
    return date('H:i:s');
}


// recibe una hora en el formato de la base de datos, tipo smalldate y le agrega un cierto numero de horas
function DtTmDbLgAddHours($fecha, $duracion_intervalo) {
    $interval = new DateInterval($duracion_intervalo);
    $dt = date_create($fecha);
    date_add($dt, $interval);
    $dt_new = date_format($dt, DT_DB_LG_FORMAT);
    return $dt_new;
}


// formato de fecha largo
function DtDbLgToShowLong($dt_db_lg, $show_week_day = true) {
    $dt = date_create($dt_db_lg);
    $a_days = array("Domingo", "Lunes", "Martes", "Mi�rcoles", "Jueves", "Viernes", "S�bado");
    $a_months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    
    $long = (($show_week_day) ? ($a_days[date_format($dt, 'w')] . ' ') : '') . date_format($dt, 'd') . ' de ' . $a_months[date_format($dt, 'n')] . ' de ' . date_format($dt, 'Y');
    return $long;
}


function getMonthName($month, $is_short = false) {
	$month_name = "";
	$a_month_names = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
	$a_month_short_names = array(1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic');
	if (strlen($month)) {
		if ($is_short) {
			$month_name = (isset($a_month_short_names[$month])) ? $a_month_short_names[$month] : $month;
		} else {
			$month_name = (isset($a_month_names[$month])) ? $a_month_names[$month] : $month;
		}
	}
	
	return $month_name;
}
?>