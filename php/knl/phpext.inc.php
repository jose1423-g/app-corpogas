<?php

require_once("sys_root.inc.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require "$SYS_ROOT/vendor/PHPMailer/Exception.php";
require "$SYS_ROOT/vendor/PHPMailer/PHPMailer.php";
require "$SYS_ROOT/vendor/PHPMailer/SMTP.php";

function getUrlDir($url){
	$slash = "/";
	$url = strrev($url);
   
	$posRev = strpos($url, $slash, 1);
	$url_dir = substr($url, $posRev);
	$url_dir = strrev($url_dir);
	return $url_dir;
}

function redirect($url) {
	if (!headers_sent()) {
		header("Location: $url");
		exit();
	}
}


function is_chrome() {
    return (preg_match("/\bchrome\b/i", $_SERVER['HTTP_USER_AGENT']));
}


function getDecimalsNumber($num, $max_dec = 4, $min_dec = 2) {
    $a_dec = explode(".", $num);
    $decimals = (count($a_dec) > 1) ? $a_dec[1] : '0';
    $decimals_rev = strrev($decimals);
    $decimals_rev = intval($decimals_rev);
    $decimals = strrev($decimals_rev);
    $decimals_number = ($decimals == '0') ? 0 : strlen($decimals);
    if ($decimals_number > $max_dec) {
        $decimals_number = $max_dec;
    }
    if ($decimals_number < $min_dec) {
        $decimals_number = $min_dec;
    }
    return $decimals_number;
}

// devuelve un numero con todos sus decimales
// is_round sirve para redondear la cantidad por "round_dec" decimales
function NumToShowAllDec($num, $is_round = 0, $round_dec = 10) {
	$dec = getDecimalsNumber($num, 10);
	if ($is_round == 1 and strlen($round_dec)) {
		$num = round($num, $round_dec);
	}
	$num_ret = number_format($num, $dec);
	
	return $num_ret;
}


function UrlArrayToQueryArgs(&$a_args) {
    $args = '';
    if (is_array($a_args)) {
        //UrlArrayRemoveSessionIds($a_args);
        foreach ($a_args as $arg_name => $arg_value) {
            $args .= '&' . $arg_name . '=' . htmlentities(urlencode($arg_value));
        }
        $args = substr($args, 1);
    }
    return $args;
}


function UrlArrayRemoveSessionIds(&$a_args) {
    if (is_array($a_args)) {
        unset($a_args['PHPSESSID']);
        unset($a_args['JSESSIONID']);
    }
}


function GetParamValue($arg_name, $return_if_null = '') {
    $arg_value = '';
    if (strlen($arg_name)) {
        $arg_value = (isset($_REQUEST[$arg_name])) ? $_REQUEST[$arg_name] : $return_if_null;
    }
    return $arg_value;
}

function GetPostValue($arg_name, $return_if_null = '') {
    $arg_value = '';
    if (strlen($arg_name)) {
        $arg_value = (isset($_POST[$arg_name])) ? $_POST[$arg_name] : $return_if_null;
    }
    return $arg_value;
}


function GetSessionValue($arg_name) {
    $arg_value = '';
    if (strlen($arg_name)) {
        $arg_value = (isset($_SESSION[$arg_name])) ? $_SESSION[$arg_name] : '';
    }
    return $arg_value;
}


// calcula y regresa IP. La guarda en una variable global
function getClientIp() {
	global $g_dir_ip;
	if ( isset($_SERVER["REMOTE_ADDR"]) )    {
	    $g_dir_ip = $_SERVER["REMOTE_ADDR"];
	} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    {
	    $g_dir_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    {
	    $g_dir_ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	return $g_dir_ip;
}

if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
  }
}



/** Convert accents to equivalent without accents
* @param string $str String to convert accents */
function convertAccents($str) {
    $str_from = '��������������������������������������������������ݴ';
    $str_to =   'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY ';
    return strtr($str, $str_from, $str_to);
}

function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

/**
* convert xml string to php array - useful to get a serializable value
*
* @param string $xmlstr
* @return array
*
* @author Adrien aka Gaarf & contributors
* @see http://gaarf.info/2009/08/13/xml-string-to-php-array/
*/

function xmlstr_to_array($xmlfile) {
  $doc = new DOMDocument();
  $doc->load($xmlfile);
  $root = $doc->documentElement;
  $output = domnode_to_array($root);
  $output['@root'] = $root->tagName;
  return $output;
}

function domnode_to_array($node) {
  $output = array();
  switch ($node->nodeType) {

    case XML_CDATA_SECTION_NODE:
    case XML_TEXT_NODE:
      $output = trim($node->textContent);
    break;

    case XML_ELEMENT_NODE:
      for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
        $child = $node->childNodes->item($i);
        $v = domnode_to_array($child);
        if(isset($child->tagName)) {
          $t = $child->tagName;
          if(!isset($output[$t])) {
            $output[$t] = array();
          }
          $output[$t][] = $v;
        }
        elseif($v || $v === '0') {
          $output = (string) utf8_decode($v);
        }
      }
      if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
        $output = array('@content'=>$output); //Change output into an array.
      }
      if(is_array($output)) {
        if($node->attributes->length) {
          $a = array();
          foreach($node->attributes as $attrName => $attrNode) {
            $a[$attrName] = (string) utf8_decode($attrNode->value);
          }
          $output['@attributes'] = $a;
        }
        foreach ($output as $t => $v) {
          if(is_array($v) && count($v)==1 && $t!='@attributes') {
            $output[$t] = $v[0];
          }
        }
      }
    break;
  }
  return $output;
}

// numeros grandes
// convierte un numero decimal en hexadecimal
function bcdechex($dec) {
    $last = bcmod($dec, 16);
    $remain = bcdiv(bcsub($dec, $last), 16);

    if($remain == 0) {
        return dechex($last);
    } else {
        return bcdechex($remain).dechex($last);
    }
}

// converte un numro hexadecimal en su representacion ascii
function hex2str($hex) {
	$hex = preg_replace('/\s/', '', $hex);
    $str = "";
    for($i=0; $i<strlen($hex); $i+=2) {
        $str .= chr(hexdec(substr($hex,$i,2)));
    }
    return $str;
}

// para numero de serie de certificados
function bcdecstr($dec) {
    $hex = bcdechex($dec);
    $str = hex2str($hex);
    return $str;
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else {
        if (is_string($d)) {
            return utf8_encode($d);
        }
    }
    return $d;
}


// genera un archivo comprimido a partir de una lista de archivos (busca los archivos y los agrega en un zip)
// $a_cfdis = array(secuencial => id_factura)
function compressMultiCfdi($a_cfdis, $a_ext = array()) {
	$ret = 0;
	if (count($a_cfdis)) {
		$zip = new ZipArchive;
		$dirname = "../cfdi/"; // para el archivo zip
		
		if (empty($a_ext)) {
			$a_ext = array('xml', 'pdf');
		}
		$inc_pdf = (in_array('pdf', $a_ext)) ? 1 : 0;
		$inc_xml = (in_array('xml', $a_ext)) ? 1 : 0;
		
		// busca lista de archivos
		$facturas_download = implode(",", $a_cfdis);
		$qry = "SELECT CfdiFileNamePath, CfdiFileName, CfdiStatus FROM clientes_facturas WHERE IdFactura IN($facturas_download)";
		$a_files = DbQryToArray($qry, true, false);
		//echo "<pre>" . print_r($a_files, true) . "</pre>"; exit();
		$zip_file = 'facturas_emitidas.zip';
		$zip_file = $dirname . $zip_file;
		
		unlink($zip_file); // trata de eliminarlo si ya existe
		if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
			foreach($a_files AS $a_file) {
				$filename_base = $a_file['CfdiFileNamePath'] . $a_file['CfdiFileName'];
				$cfdi_status = $a_file['CfdiStatus'];

				// pdf
				if ($inc_pdf == 1) {
					$filename_pdf = $filename_base . ".pdf";
					if(is_file($filename_pdf)) {
						$zip->addFile($filename_pdf, basename($filename_pdf));
						$ret = 1;
					}
				}
				
				// xml
				if ($inc_xml == 1) {
					$filename_xml = $filename_base . ".xml";
					if(is_file($filename_xml)) {
						$zip->addFile($filename_xml, basename($filename_xml));
						$ret = 1;
					}
				}
				
				// xml_cancel
				if ($inc_xml == 1) {
					$filename_xml_cancel = "";
					if ($cfdi_status == 2) {// si esta cancelada...
						$filename_xml_cancel = $filename_base . "_CANCELADA.xml";
					}
					if(strlen($filename_xml_cancel) and is_file($filename_xml_cancel)) {
						$zip->addFile($filename_xml_cancel, basename($filename_xml_cancel));
						$ret = 1;
					}
				}
			}
			$zip->close();
		}
	}
	if ($ret == 1 and isset($zip_file)) {
		return $zip_file;
	} else {
		return -1;
	}
}


function multi_attach_mail($to, $files, $sender_mail, $sender_name, $subject, $message){
    
    // ini set (redundante)
    ini_set("sendmail_from", $sender_mail);
	//if ($SYS_ROOT == "C:/Inetpub/vhosts/barbetinfosistemas.com/httpdocs/holding") {
		ini_set("sendmail_path","C:\Inetpub\vhosts\barbetinfosistemas.com\httpdocs\holding\sendmail\sendmail.exe -t");
	//} elseif ($SYS_ROOT == "C:/Inetpub/vhosts/barbetinfosistemas.com/httpdocs/demo") {
	//	ini_set("sendmail_path","C:\Inetpub\vhosts\barbetinfosistemas.com\httpdocs\demo\sendmail\sendmail.exe -t");
	//} else {
	//	ini_set("sendmail_path","C:\Inetpub\vhosts\barbetinfosistemas.com\httpdocs\demo\sendmail\sendmail.exe -t");
	//}
	//ini_set("sendmail_path","C:\Inetpub\vhosts\barbetinfosistemas.com\httpdocs\demo\sendmail\sendmail.exe -t");
	//$sys_root_sendmail = str_replace('/', '\\', $SYS_ROOT);
	//ini_set("sendmail_path","$sys_root_sendmail\sendmail\sendmail.exe -t");	
    
    // email fields: to, from, subject, and so on
    $from = $sender_name . " <" . $sender_mail . ">"; 
    //$headers = "From: $from";
    $headers = "From: $sender_mail" . "\r\n" .
               "Reply-To: $sender_mail" . "\r\n" .
               'X-Mailer: PHP/' . phpversion();	
 
    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 
    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // multipart boundary 
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
 
    // preparing attachments
    for($i=0; $i<count($files); $i++) {
        if(is_file($files[$i])) {
            $message .= "--{$mime_boundary}\n";
            $fp = @fopen($files[$i],"rb");
            $data = @fread($fp,filesize($files[$i]));
            @fclose($fp);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: application/octet-stream; name=\"" . basename($files[$i]) . "\"\n" . 
                "Content-Description: " . basename($files[$i])."\n" .
                "Content-Disposition: attachment;\n" . " filename=\"" . basename($files[$i]) . "\"; size=" . filesize($files[$i]) . ";\n" .
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        }
    }
    $message .= "--{$mime_boundary}--";
    $returnpath = "-f" . $sender_mail;
    $ok = @mail($to, $subject, $message, $headers, $returnpath); 
    if($ok) {
        return $i;
    } else {
        return 0;
    }
}


function multi_attach_mail_new($mail_to, $files, $mail_from, $mail_from_name, $mail_subject, $mail_html_body, $mail_text_body = "", $mail_host = "", $mail_port = "", $mail_username = "", $mail_passwd = "", $mail_smtp_secure = "", $mail_firma_url = "", $mail_backup = "", $zp = 0) {
    
	$mail = new PHPMailer;
	
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Port = $mail_port;
	$mail->Host = $mail_host;  							  // Specify main and backup server
	//$mail->SMTPDebug = 1;
	if (strlen($mail_username) and strlen($mail_passwd)) {
		$mail->SMTPAuth = true;                           // Enable SMTP authentication
		$mail->Username = $mail_username;                 // SMTP username
		$mail->Password = $mail_passwd;                   // SMTP password
	}
	if (strlen($mail_smtp_secure)) {
		$mail->SMTPSecure = $mail_smtp_secure;            // Enable encryption, 'ssl' also accepted
		
		// shambler ssl options...
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		// end of shambler ssl options
	}
	$debug = 0;
	if ($debug == 1) {
		$mail->SMTPDebug = 2;
		$mail->Debugoutput = 'html';
	}
    // echo "<pre>" .print_r($mail, true) . "</pre>";
    //LogMsg(print_r($mail, true));

	$mail->setFrom($mail_from, $mail_from_name);
	// $mail->FromName = $mail_from_name;
	if (strlen($mail_to)) {
		// $a_mail_to = preg_split("/[\s,;\t\n]+/", $mail_to); // acepta multiples separadores
		$a_mail_to = explode(",", $mail_to);
		if (count($a_mail_to) > 0) {
			foreach ($a_mail_to AS $mail_to_add) {
				$mail->addAddress($mail_to_add);          // Add a recipient
			}
		} else {
			$mail->addAddress($mail_to);  			      // Add a recipient
		}
	}
	if (strlen($mail_backup)) {
		$a_mail_backup = explode(",", $mail_backup);
		if (count($a_mail_backup) > 0) {
			foreach ($a_mail_backup AS $mail_to_add) {
				$mail->addAddress($mail_to_add);          // Add a recipient for backup
			}
		} else {
			$mail->addAddress($mail_backup);			  // Add a recipient for backup
		}
		
	}
	$mail->addReplyTo($mail_from);

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Timeout = 5;
	$mail->Subject = $mail_subject;
	// $mail->Body    = $mail_html_body;
	$mail->msgHTML($mail_html_body);
	$mail->AltBody = $mail_text_body;
	
    // prepara adjuntos (attachments)
    // envia un archivo comprimido
    if ($zp == 1) {
        $zip = new ZipArchive;
        $dirname = "../cfdi";
        if (count($files)) {
            // pone como directorio el mismo del primer archivo zip (se asume que es writeable)
            $a_path = pathinfo($files[0]);
            $dirname = $a_path['dirname'];
        }
        $dirname .= (strlen($dirname)) ? "/" : "";
        
		$randon_file_name = random_string(5);
        $zip_file = "attatchment_" . $randon_file_name . ".zip";
        $zip_file = $dirname . $zip_file;
        unlink($zip_file); // trata de eliminarlo si ya existe
        if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
            $i = 0;
            for($i=0; $i<count($files); $i++) {
                if(is_file($files[$i])) {
                    $zip->addFile($files[$i],  basename($files[$i]));
                }
            }
            $zip->close();
        }
        $mail->addAttachment($zip_file,  basename($zip_file));         // Add attachment zip file
    } else {
    // envia archivos sin comprimir
        $i = 0;
        for($i=0; $i<count($files); $i++) {
			if (isset($files[$i])) {
				if (strlen($files[$i])) {
					if(is_file($files[$i])) {
						$mail->addAttachment($files[$i],  basename($files[$i]));         // Add attachments
					}
				}
			}
		}
    }
	
	// si tiene firma, la adjunta tambien
	if (strlen($mail_firma_url)) {
		$mail->addAttachment($mail_firma_url);
	}

	// trata de enviar el correo
    if(!$mail->send()) {
        /*
        echo 'El mensaje no pudo ser enviado';
	    echo 'Error: ' . $mail->ErrorInfo;
	    exit;
        */
        // return $mail->ErrorInfo;

        if (isset($zip_file)) {
            unlink($zip_file);
        }
        return 0;
    } else {
        if (isset($zip_file)) {
            unlink($zip_file);
        }
        return 1;
    }
}

function ucwordss($str, $always_lower = array('de', 'y', 'en', 'del', 'o', 'por', 'para', 'con', 'sin', 'a', 'x', 'la', 'tu', 'su', 'lo', 'que'), $always_upper = array('s.a.', 'c.v.', 'sa', 'cv', 'cv.', 's.a', 'c.v', 's.c', 's', 'rl', 'r.l.', 'sc', 's.c.', 'ii', 'sapi', 's.a.p.i.', 'sab', 's.a.b.', 'ac', 'a.c.', 'a.c', 'srl')) {
	$out = "";
	$str = strtolower($str);
	foreach (explode(" ", $str) as $word) {
		if (!in_array($word, $always_lower) and !in_array($word, $always_upper)) {
			$out .= ucfirst($word) . " ";
		} elseif (in_array($word, $always_upper)) {
			$out .= strtoupper($word) . " ";
		} elseif (in_array($word, $always_lower)) {
			$out .= strtolower($word) . " ";
		} else {
			$out .= $word . " ";
			exit($word);
		}
	}
	// return ucfirst(rtrim($out));
	return ucfirst(rtrim($out));
}

?>