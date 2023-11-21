<?php

ini_set("display_errors", 0);
date_default_timezone_set("America/Mexico_City");
// date_default_timezone_set("America/Bogota"); // ojo

// define formatos de fecha
$sep = '/'; // for show dates
define('DT_SHOW_SEP', $sep);
define('DT_DB_LG_FORMAT', 'Y-m-d H:i');
define('DT_DB_FORMAT', 'Ymd');
define('DT_SHOW_FORMAT', 'd' . $sep . 'm' . $sep . 'Y');
define('TM_SHOW_FORMAT', 'h:i:s a');
define('TM_SHOW_FORMAT_M', 'H:i:s');
?>