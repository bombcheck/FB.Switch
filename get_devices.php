<?php
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

$ResStr="";
$NoFBdect=FALSE;
if (isset($_GET['nofbdect'])) {
	if ($_GET['nofbdect'] == "true") $NoFBdect=TRUE;
}

foreach($xml->devices->device as $device) {
	if ($NoFBdect == TRUE && $device->vendor == "fbdect200" || $device->vendor == "computer") { }
	else {
		$ResStr .= $device->id.":".$device->name.":".$device->room.":".$device->status.":".$device->showDeviceStatus.":".$device->vendor.":";
		if ($device->milight->color != "") $ResStr .= $device->milight->color.":";
		else $ResStr .= "UNDEF:";
		if ($device->milight->brightnesscolor != "") $ResStr .= $device->milight->brightnesscolor.":";
		else $ResStr .= "0:";
		if ($device->milight->brightnesswhite != "") $ResStr .= $device->milight->brightnesswhite.":";
		else $ResStr .= "0:";
		if ($device->milight->mode != "") $ResStr .= $device->milight->mode.":";
		else $ResStr .= "UNDEF:";
		if ($device->address->tx433version != "") $ResStr .= $device->address->tx433version.":";
		else $ResStr .= "UNDEF:";
		$ResStr .= $xml->global->timerGlobalRun .":";
		if ($device->milight->brightnessdisco != "") $ResStr .= $device->milight->brightnessdisco.":";
		else $ResStr .= "0:";
		$ResStr .= $xml->global->AlertState ."|";
	}
}
echo substr($ResStr, 0, strlen($ResStr)-1);
?>