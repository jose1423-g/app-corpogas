<?php

require_once("sys_root.inc.php");
require_once("$SYS_ROOT/php/knl/sys_config.inc.php");


function NumShowToDb($str_number) {

    $d_point = LC_DECIMALS_SEPARATOR;
    $t_sep = LC_THOUSANDS_SEPARATOR;
    $str_num = str_replace($t_sep, '', $str_number);
    $sign = (substr($str_number,0,1) == '-') ? '-' : '';
    $a_num = explode($d_point, $str_num, 2);
    $num_db = preg_replace( '/[^0-9]+/', '', $a_num[0]);
    if (count($a_num) > 1) {
    	$num_db .= '.' . preg_replace( '/[^0-9]+/', '', $a_num[1]);
    }
    return $sign . $num_db;
}


function NumToShow($num) {
    if (is_numeric($num)) {
        return number_format($num, LC_NUMBER_DECIMALS, LC_DECIMALS_SEPARATOR, LC_THOUSANDS_SEPARATOR);
    } else {
        return $num;
    }
}


function NumToCurrency($num) {
    return LC_CURRENCY_SYMBOL . NumToShow($num);;
}


function GetTrans($term_es) {
	$lang_locale = 'es';
	$lcode = (isset($_SESSION['lcode'])) ? $_SESSION['lcode'] : $lang_locale;
	$term = "";
	if (strlen($term_es) and $lcode != $lang_locale) {
		$qry = "SELECT Term_$lcode FROM knl_translations WHERE term_es = '$term_es'";
		$term = DbGetFirstFieldValue($qry);
	}
	if (!strlen($term)) {
		if ($lcode != $lang_locale) {
			$term = "[" . $term_es . "]";
		} else {
			$term = $term_es;
		}
	}
	
	return $term;
}


?>