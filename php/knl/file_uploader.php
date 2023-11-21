<?php 
require_once("sys_root.inc.php");

define('IMG_UPLOADED_FILES_PATH', "../images/productos/");
define('DOCS_UPLOADED_FILES_PATH', "../uploaded/");
define('XML_TEMP_UPLOADED_FILES_PATH', "../cfdi_r/_temp/");
define('BNK_UPLOAD_VALID_EXTENSIONS', "jpeg,jpg,gif,png,JPEG,JPG,GIF,PNG");

//ini_set('upload_max_filesize', '20M');// esto no funciona, debe cambiarse el php.ini
//ini_set('post_max_size', '20M'); //

function UploadFile(&$files) {
    //$target_path = IMG_UPLOADED_FILES_PATH;
    $target_path = "../images/productos/";

    $target_path = $target_path . basename($files['uploadedfile']['name']);
	$target_path = str_replace(' ', '_', $target_path);
	
	$fsize = $files["uploadedfile"]["size"];
	if ($fsize == 0 || $fsize > 20971520) {
		$msg = 6205; // "Solo se pueden agregar imagenes de hasta 20MB";
	} else {
		if(move_uploaded_file($files['uploadedfile']['tmp_name'], $target_path)) {
			$msg = 6200; // La imagen se ha subido correctamente ... ori "El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
		} else{
			$msg = 6207; // Hubo un error al subir la imagen, por favor intente de nuevo
		}
	}
    return $msg;
}

function UploadCerFiles(&$files, $path) {
    //$target_path = IMG_UPLOADED_FILES_PATH;
	if (strlen($path)) {
		$target_path = "../" . $path . "/";
	} else {
		$target_path = "../cert/";
	}
	$a_target_dirs = explode("/", $target_path);
	// solo toma el 1 2 y 3 (quita el 0 y el 4)
	$full_dir = "..";
	foreach($a_target_dirs as $dir) {
		if ($dir != '' and $dir != '..') {
			$full_dir .= "/$dir";
			// echo $full_dir . "<br>";
			if (!is_dir($full_dir)) {
				if (!mkdir($full_dir)) {
					$error = "No se pudo crear el directorio $full_dir";
					exit($error);
				}
				chmod($full_dir, 0755);  // octal; valor de modo correcto
			}
		}
	}

    $target_path_1 = $target_path . basename($files['uploadCerFile']['name']);
	$target_path_1 = str_replace(' ', '_', $target_path_1);
	
	$fsize = $files["uploadCerFile"]["size"];
	if ($fsize == 0 || $fsize > 5242880) {
		$msg = 6205; // "Solo se pueden agregar imagenes de hasta 5MB";
	} else {
		if(move_uploaded_file($files['uploadCerFile']['tmp_name'], $target_path_1)) {
			$msg = 6200; // La imagen se ha subido correctamente ... ori "El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
		} else{
			$msg = 6207; // Hubo un error al subir la imagen, por favor intente de nuevo
		}
	}
	
    $target_path_2 = $target_path . basename($files['uploadKeyFile']['name']);
	$target_path_2 = str_replace(' ', '_', $target_path_2);
	
	$fsize = $files["uploadKeyFile"]["size"];
	if ($fsize == 0 || $fsize > 5242880) {
		$msg = 6205; // "Solo se pueden agregar imagenes de hasta 5MB";
	} else {
		if(move_uploaded_file($files['uploadKeyFile']['tmp_name'], $target_path_2)) {
			$msg = 6200; // La imagen se ha subido correctamente ... ori "El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
		} else{
			$msg = 6207; // Hubo un error al subir la imagen, por favor intente de nuevo
		}
	}
    return array('cer_file' => $target_path_1, 'key_file' => $target_path_2, 'msg' => $msg);
}

// sube el archivo al directorio temporal de php para importacion de datos
function UploadFileToImport(&$files) {
	
	$a_ret = array('filename' => '', 'msg' => '');
    $filename = basename($files['uploadedfile']['name']); 
	$path = DOCS_UPLOADED_FILES_PATH;
	$filename = str_replace(' ', '_', $filename);
	$filename = $path . $filename;
	$a_ret['filename'] = $filename;
    if(copy($files['uploadedfile']['tmp_name'], $filename)) {
		// este mensaje no se regresa
        $msg = "El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
    } else{
        $msg = "Hubo un error al subir el archivo, por favor intente de nuevo";
		$a_ret['msg'] = $msg;
    }
    return $a_ret;
}


function UploadXmlFile(&$files) {
    // $target_path = "../cfdi_r/_temp/"; // directorio temporal, despues debe crear el nuevo segun los datos que lea del XML
    $target_path = XML_TEMP_UPLOADED_FILES_PATH;

    $tmp_filename = basename($files['uploadedfile']['name']);
    $tmp_filename = preg_replace("/[^a-zA-Z0-9.]/", "_", $tmp_filename);
    // $target_path = $target_path . basename($files['uploadedfile']['name']);
    $target_path = $target_path . $tmp_filename;
	$target_path = str_replace(' ', '_', $target_path);
    $ret = -1;
	
	$fsize = $files["uploadedfile"]["size"];
	if ($fsize == 0 || $fsize > 20971520) {
		$msg = 6205; // "Solo se pueden agregar archivos de hasta 20MB";
	} else {
		if(move_uploaded_file($files['uploadedfile']['tmp_name'], $target_path)) {
			$msg = 6200; // El archivo se ha subido correctamente ... ori "El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
            $ret = 1;
		} else{
			$msg = 6207; // Hubo un error al subir el archivo, por favor intente de nuevo
		}
	}
    return array('ret' => $ret, 'msg_id' => $msg, 'tmp_filename' => $tmp_filename, 'tmp_filepath' => XML_TEMP_UPLOADED_FILES_PATH);
}


// Devuelve un arreglo con los archivos de un directorio en particular.
// Si recibe el parametro $extension, filtra los archivos solo con esa extension, si no, devuelve los archivos
// que tengan una extension valida, segun BNK_UPLOAD_VALID_EXTENSIONS
function FilesInDir($dir = NULL, $extension = NULL) {
    $dir = (strlen($dir)) ? $dir : IMG_UPLOADED_FILES_PATH;
    $valid_extensions = BNK_UPLOAD_VALID_EXTENSIONS;
    $a_valid_extensions = explode(",", $valid_extensions);
    if (strlen($extension)) {
        $a_valid_extensions = Array($extension);
    }
    if ($handle = opendir($dir)) {
        // Recorrido secuencial del directorio
        while (false !== ($file = readdir($handle))) {
            $a_ext = explode(".", $file);
            if (in_array(end($a_ext), $a_valid_extensions)) {
                if ($file !== '.' and $file !== '..') {
                    $a_files[] =  $file;
                }
            }
        }
        closedir($handle);
    }
    return $a_files;
}


// Elimina un archivo seleccionado de un directorio en particular
// El directorio debe tener permisos de escritura para todos los usuarios.
function DeleteUploadedFile($file_to_delete, $file_path = NULL){
    $file_path = (strlen($file_path)) ? $file_path : IMG_UPLOADED_FILES_PATH;
    $file_to_delete = $file_path . $file_to_delete;
    $fh = fopen($file_to_delete, 'w');
    fclose($fh);
    $result = unlink($file_to_delete);
    return $result;
}


function UploadFileDoc(&$files, $new_name = "", $path = "") {
    //$target_path = "../docs/";
    $target_path = (strlen($path)) ? $path : DOCS_UPLOADED_FILES_PATH;

    $file_ori = $files['uploadedfile']['name'];
    if (strlen($new_name) > 0) {
        // obtiene extension del archivo original
		// OJO aqui renombra el archivo quitando espacios (y espacios dobles) y poniendo guion bajo
		$file_ori = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $file_ori);
		
        $a_ext = explode(".", $file_ori);
        $ext = end($a_ext);
        $new_file_name = basename($new_name) . ".$ext";
        $target_path = $target_path . $new_file_name;
    } else {
        $new_file_name = basename($files['uploadedfile']['name']);
        $target_path = $target_path . basename($files['uploadedfile']['name']);
    }
	
	$fsize = $files["uploadedfile"]["size"];
	// if ($fsize == 0 || $fsize > 20971520) {
	if ($fsize > 47185920) { // ojo 45mb... regresar a 20
		$msg = 6105; //"Solo se pueden agregar archivos de hasta 20MB!!";
		$result = 0;
	} else {
		if(move_uploaded_file($files['uploadedfile']['tmp_name'], $target_path)) {
			$msg = 6100; //"El archivo " .  basename($files['uploadedfile']['name']) . " se ha subido correctamente";
			$result = 1;
		} else{
			$msg = 6107; // "Hubo un error al subir el archivo, por favor intente de nuevo";
			$result = 0;
		}
	}
    $a_result = array('msg' => $msg, 'result' => $result, 'new_file_name' => $new_file_name, 'file_ori' => $file_ori);
    return $a_result;
}

?>