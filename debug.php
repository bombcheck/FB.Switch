<?php

if((!isset($directaccess)) OR (!$directaccess)) die();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$DEBUG_FILENAME="data/debug.log";

//debug.log dateisystem rechte überprüfen
if($debug == "true" && !touch($DEBUG_FILENAME)) {
    echo "Kann die Log (".$DEBUG_FILENAME.") nicht anlegen!\n";
    exit(5);
}
if($debug == "true" && !is_writable($DEBUG_FILENAME)) {
    echo "Kann die Log (".$DEBUG_FILENAME.") nicht schreiben!\n";
    exit(6);
}

//funktion um in das debug log zu schreiben
function debug($msg) {
    global $debug;
    global $DEBUG_FILENAME;
    if($debug == "true") {
        $handle = fopen ($DEBUG_FILENAME, 'a');
        fwrite($handle, date("Y-m-d H:i:s")." ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['REQUEST_TIME']."   ".$msg."\r\n");
        fclose($handle);
    }
}

?>
