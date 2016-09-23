<?php
$directaccess = true;
require_once('fritzbox.inc.php');

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
$XMLdata = Fritzbox_GetHAactorsInfoXML();
foreach($xml->devices->device as $device) {
	if ($device->vendor == "fbdect200") {
	   $ResStr .= trim($device->id).":".Fritzbox_GetHAactorDataFromXML($XMLdata,trim($device->address->masterdip),'temperature')."|";
    }
}
echo substr($ResStr, 0, strlen($ResStr)-1);
?>