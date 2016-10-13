<?php
ignore_user_abort(true);
sleep(2);

require("includes/incl_milight.php");
 $CONFIG_FILENAME = 'data/config.xml';

//config.xml dateisystem rechte überprüfen
if(!file_exists($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht finden!\n";
    exit(1);
}
if(!is_readable($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht lesen!\n";
    exit(2);
}

//config.xml einlesen
libxml_use_internal_errors(true);
$xml = simplexml_load_file($CONFIG_FILENAME);
if (!$xml) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht laden!\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    exit(4);
}

// Abbrechen wenn AlertState NICHT red ist
if ($xml->global->AlertState != "red") exit();


foreach($xml->milightwifis->milightwifi as $milightwifi) {
	$MLbridges[] = $milightwifi->address;
	$MLports[] = $milightwifi->port;
}

for ($y=0; $y < count($MLbridges); $y++) {
    $milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
    $milight->setRgbwActiveGroup(0);
    $milight->rgbwSetColorHexString("#FF0000");
    $milight->rgbwBrightnessPercent(90,0);
    unset($milight);
}
sleep(0.05);

//for ($x=0; $x < 30;$x++) {
do {	
	set_time_limit(30);
	//config.xml einlesen
	unset($xml);
	libxml_use_internal_errors(true);
	$xml = simplexml_load_file($CONFIG_FILENAME);
	if (!$xml) {
	    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht laden!\n";
	    foreach(libxml_get_errors() as $error) {
	        echo "\t", $error->message;
	    }
	    exit(4);
	}

	for ($y=0; $y < count($MLbridges); $y++) {
		$milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
        $milight->rgbwBrightnessPercent(30,0);
        unset($milight);
	}
	sleep(0.2);
	for ($y=0; $y < count($MLbridges); $y++) {
		$milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
        $milight->rgbwBrightnessPercent(90,0);
        unset($milight);
	}
	sleep(0.075);
	
	//$ret=file_put_contents("alert.log", "ALERT\n", FILE_APPEND);
	//sleep(1);
} while ($xml->global->AlertState == "red");
?>