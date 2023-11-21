<?php

require_once("db.inc.php");

function LogMsg($msg, $is_file = true) {
	if ($_SERVER['REMOTE_ADDR'] == '100.100.999.999') {
	// return;
		if (!$is_file) {
			if (is_object($msg) or is_array($msg)) {
				$msg = "'" . print_r($msg, true) . "'";
			} else {
				$msg = var_export($msg, true);
			}
			//$msg = addslashes($msg);
			// nota: al parecer, si el tipo de dato es texto, le agrega automaticamente la comillas simples
			$qry = "INSERT INTO logmsg (msg)
				VALUES ($msg)";
			DbExecute($qry);
			DbCommit(); // ojo, si se manda a llamar LogMsg en cualquier parte del programa, se hara el commit de todas las transacciones pendientes
		} else {
			$ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'localhost'; // ojo
			$filename = "logmsg.txt";
			$fp = fopen($filename, 'a');
			$mt = microtime(true);
			$date = date('d/m/Y H:i:s');
			fwrite($fp, "$ip $date $mt $msg\n");
			fclose($fp);
		}
	}
}

function LogMsg_ofm($msg, $is_file = true) {
    if (!$is_file) {
        if (is_object($msg) or is_array($msg)) {
            $msg = "'" . print_r($msg, true) . "'";
        } else {
            $msg = var_export($msg, true);
        }
        //$msg = addslashes($msg);
        // nota: al parecer, si el tipo de dato es texto, le agrega automaticamente la comillas simples
        $qry = "INSERT INTO logmsg (msg)
            VALUES ($msg)";
        DbExecute($qry);
        DbCommit(); // ojo, si se manda a llamar LogMsg en cualquier parte del programa, se hara el commit de todas las transacciones pendientes
    } else {
		$ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'localhost'; // ojo
		if ($ip == "187.150.157.182") {
			$filename = "logmsg_ofm.txt";
			$fp = fopen($filename, 'a');
			$mt = microtime(true);
			$date = date('d/m/Y H:i:s');
			fwrite($fp, "$ip $date $mt $msg\n");
			fclose($fp);
		}
    }
}

?>