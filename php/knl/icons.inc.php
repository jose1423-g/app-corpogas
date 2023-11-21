<?php

require_once("sys_root.inc.php");

// iconos
define('ICONS_FILE_EXT', '.gif'); // icons file extensions
define('ICONS_SIZE_DEFAULT', '16');	// icons size default
define('ICONS_ITEM_SEP', ';'); // toolbar item separator char
//define('ICONS_PATH', "$SYS_ROOT/images/icons"); // icons path
define('ICONS_PATH', "$SYS_ROOT_REL/images/icons"); // icons path

//'-------------------------------------------------------------------------------
//' Regresa el código HTML para un icono de navegación
//'-------------------------------------------------------------------------------
function GetIcon($icon_file, $tooltip_text = '', $event = '', $id = '') {
  
    $a_file = explode(".", $icon_file);
    $icon_file = (end($a_file) == 'gif' or end($a_file) == 'png') ? $icon_file : $icon_file . ICONS_FILE_EXT;
	$size_icon = is_numeric(substr($a_file[0], -2)) ? substr($a_file[0], -2) : ICONS_SIZE_DEFAULT;
	$filename = ICONS_PATH . '/' . $icon_file;
    $id = (strlen($id)) ? "id=\"$id\"" : "";

	$ret = "<img class=sphIMG_Icon $id src=\"$filename\" $event ";
	$ret .= "width=\"$size_icon\" height=\"$size_icon\" alt=\"$tooltip_text\" title=\"$tooltip_text\" ";
	$ret .= 'hspace="0" vspace="0" border="0" align="top">';
	return $ret;
}

?>