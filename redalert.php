<?php
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

foreach($xml->milightwifis->milightwifi as $milightwifi) {
	$MLbridges[] = $milightwifi->address;
	$MLports[] = $milightwifi->port;
}

for ($y=0; $y < count($MLbridges); $y++) {
    $milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
    $milight->setRgbwActiveGroup(0);
    $milight->rgbwSetColorHexString("#FF0000");
    $milight->rgbwBrightnessPercent(100,0);
    unset($milight);
}
sleep(0.05);

for ($x=0; $x < 30;$x++) {
	for ($y=0; $y < count($MLbridges); $y++) {
		$milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
        $milight->rgbwBrightnessPercent(30,0);
        unset($milight);
	}
	sleep(0.1);
	for ($y=0; $y < count($MLbridges); $y++) {
		$milight = new Milight($MLbridges[$y],(integer)$MLports[$y]);
        $milight->rgbwBrightnessPercent(100,0);
        unset($milight);
	}
	sleep(0.05);
}
?>