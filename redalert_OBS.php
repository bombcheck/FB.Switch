<?php
ignore_user_abort(true);
sleep(3);

$CONFIG_FILENAME = 'data/config.xml';

//config.xml dateisystem rechte überprüfen
if(!file_exists($CONFIG_FILENAME)) {
    //echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht finden!\n";
    exit(1);
}
if(!is_readable($CONFIG_FILENAME)) {
    //echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht lesen!\n";
    exit(2);
}

//config.xml einlesen
libxml_use_internal_errors(true);
$xml = simplexml_load_file($CONFIG_FILENAME);
if (!$xml) {
    //echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht laden!\n";
    foreach(libxml_get_errors() as $error) {
        //echo "\t", $error->message;
    }
    exit(4);
}

// Abbrechen wenn AlertState NICHT red ist
if ($xml->global->AlertState != "red") exit();


function async_curl($background_process=''){
    $ch = curl_init($background_process);
    curl_setopt_array($ch, array(
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER =>true,
        CURLOPT_NOSIGNAL => 1, //to timeout immediately if the value is < 1000 ms
        CURLOPT_TIMEOUT_MS => 50, //The maximum number of mseconds to allow cURL functions to execute
        CURLOPT_VERBOSE => 1,
        CURLOPT_HEADER => 1
    ));
    $out = curl_exec($ch);
    curl_close($ch);
    return true;
}

foreach($xml->milightwifis->milightwifi as $milightwifi) {
	async_curl('http://localhost'.str_replace('redalert.php', 'redalert_thread.php?bid='.trim($milightwifi->id), $_SERVER[PHP_SELF]));
    //echo 'http://localhost'.str_replace('redalert.php', 'redalert_thread.php?bid='.trim($milightwifi->id), $_SERVER[PHP_SELF])."<br>";
}
?>