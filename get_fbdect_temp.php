<?php
$directaccess = true;
require_once('fritzbox.inc.php');

 $CONFIG_FILENAME = 'data/config.xml';
//config.xml dateisystem rechte �berpr�fen
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

$FBnet_SIDsource = $xml->backend->sidsource;
$NewOutdoorTemp = false;

// Alternative Outoor-Temp-Quelle (Backend) fuer bestimmten Actor nehmen?
$UseAltOutdoorTempSource = $xml->global->UseAlternateOutdoorTempSource;

if($UseAltOutdoorTempSource != "" && $UseAltOutdoorTempSource != "false") {
    $AltDat = file_get_contents($UseAltOutdoorTempSource);
    if ($AltDat !== false) {
        $OutdoorTempSource = $xml->global->OutdoorTempSource;
        $NewOutdoorTemp = explode("|",$AltDat);
        $NewOutdoorDate = $NewOutdoorTemp[2];
        $NewOutdoorDateDiff = time() - strtotime($NewOutdoorDate);
        if ($NewOutdoorDateDiff > 1800) {
            $NewOutdoorTemp = -1000;
        } else $NewOutdoorTemp = $NewOutdoorTemp[0];
        if ($NewOutdoorTemp == false || $NewOutdoorTemp == "") $NewOutdoorTemp = -1000;
    }
}

$ResStr="";
if ($FBnet_SIDsource != "" || ($xml->fritzbox->username != "" || $xml->fritzbox->password != "") && $xml->fritzbox->address != "") {
    $XMLdata = Fritzbox_GetHAactorsInfoXML();
    foreach($xml->devices->device as $device) {
    	if ($device->vendor == "fbdect200") {
    		if (Fritzbox_GetHAactorDataFromXML($XMLdata,trim($device->address->masterdip),'present') == 1) {
                if($NewOutdoorTemp != false && trim($device->id) == $OutdoorTempSource) {
                    $ResStr .= trim($device->id).":".trim($NewOutdoorTemp)."|";
                } else {
                    $ResStr .= trim($device->id).":".Fritzbox_GetHAactorDataFromXML($XMLdata,trim($device->address->masterdip),'temperature')."|";
                }
    	   	} else {
    	   		$ResStr .= trim($device->id).":-1000|";
    	   	}
        }
    }
}
echo substr($ResStr, 0, strlen($ResStr)-1);
?>