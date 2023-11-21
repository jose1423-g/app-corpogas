<?php


// $filename = "../ftdox-xml-send.timer";
$filename = "/etc/systemd/system/ftdox-xml-send.timer";

// read array
$timer_lines = file($filename);
echo "<pre>" . print_r($timer_lines, true) . "</pre>";

// // change OnCalendar
// $flag = 0;
// foreach($timer_lines as $line_no => $line) {
	// $a_line_elems = explode("=", $line);
	// if ($a_line_elems[0] == 'OnCalendar') {
		// $a_line_elems[1] = '08:40:00' . "\n";
		// $line = implode("=", $a_line_elems);
		// $timer_lines[$line_no] = $line;
		// $flag = 1;
	// }
// }
// if ($flag == 1) {
	// file_put_contents($filename, $timer_lines);
	// exec("sudo cp $filename /etc/systemd/system/", $output, $return_var);
	// echo "<pre>" . print_r($output, true) . "</pre>";
	// echo "<pre>" . print_r($return_var, true) . "</pre>";
	// echo "Ok";
// } else {
	// echo "No se realizo ningun cambio en el archivo";
// }


// // call function to save file with changes
// safefilerewrite($filename, $timer_array);

// function safefilerewrite($fileName, $timer_array) {
    // $res = array();
    // foreach($timer_array as $key => $val) {
        // if(is_array($val)){
            // $res[] = "[$key]";
            // foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        // }
        // else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
    // }
	// $dataToSave = implode("\r\n", $res);
	// echo "<pre>" . print_r($dataToSave, true) . "</pre>";
	
	
	
	// if ($fp = fopen($fileName, 'w')) {
        // $startTime = microtime(TRUE);
        // do {
			// $canWrite = flock($fp, LOCK_EX);
			// // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			// if(!$canWrite) usleep(round(rand(0, 100)*1000));
        // } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        // //file was locked so now we can store information
        // if ($canWrite)
        // {
			// fwrite($fp, $dataToSave);
            // flock($fp, LOCK_UN);
        // }
        // fclose($fp);
    // }
// }

?>