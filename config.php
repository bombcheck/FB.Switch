<?php
if((!isset($directaccess)) OR (!$directaccess)) die();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$CONFIG_FILENAME = __DIR__."/data/config.xml";
$plugin = false;
$SysAlertMsg="FB.NET System Status KRITISCH";
$NoTimerAlertMsg="Timer ist deaktiviert";
$FileVer="2.32";

//config.xml dateisystem rechte überprüfen
if(!file_exists($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht finden!\n";
    exit(1);
}
if(!is_readable($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht lesen!\n";
    exit(2);
}
if(!is_writable($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht schreiben!\n";
    exit(3);
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

//globale variabeln
$debug=empty($xml["debug"]) ? "false" : $xml["debug"];

// Suppress DateTime warnings
date_default_timezone_set(@date_default_timezone_get());

//zeitzone geradeziehen
if(!empty($xml->global->timezone)) {
    date_default_timezone_set($xml->global->timezone);
}

$latitude=(float)$xml->global->latitude;
if(empty($latitude)) {
    $latitude=ini_get("date.default_latitude");
    if(empty($latitude)) {
        $latitude=(float)48.64727;
    }
}
$longitude=(float)$xml->global->longitude;
if(empty($longitude)) {
    $longitude=ini_get("date.default_longitude");
    if(empty($longitude)) {
        $longitude=(float)9.44858;
    }
}

// Sonnenauf- und -untergang für den Timer
$sunrise = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, 90+5/6, date("O")/100);
$sunset = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude, 90+5/6, date("O")/100);


if(empty($xml->global->multiDeviceSleep) || $xml->global->multiDeviceSleep<200) {
    if(!isset($xml->global->multiDeviceSleep)) {
        $xml->global->addChild('multiDeviceSleep',500);
    } else {
        $xml->global->multiDeviceSleep=500;
    }
    $multiDeviceSleep = 500000;
} else {
    $multiDeviceSleep = intval($xml->global->multiDeviceSleep)*1000;
}

$personsFound = $xml->xpath("//persons/person/pingto/parent::*");
$ip = $_SERVER['REMOTE_ADDR'];
foreach($personsFound as $user){
	if($ip == $user->pingto){
		$active_user = $user;
		if(isset($active_user->theme)){
			$localtheme = $active_user->theme;
		}
        else $localtheme = "DARK";

        if(isset($active_user->theme_bg)){
            $localtheme_bg = $active_user->theme_bg;
        }
        else $localtheme_bg = "desktop_bg_asteroids";

        if(isset($active_user->AutoRefreshDeviceData)){
            $AutoRefreshDeviceData = $active_user->AutoRefreshDeviceData;
        }
        else $AutoRefreshDeviceData = "false";

        if(isset($active_user->ShowFBdect200EnergyData)){
            $ShowFBdect200EnergyData = $active_user->ShowFBdect200EnergyData;
        }
        else $ShowFBdect200EnergyData = "false";

        if(isset($active_user->FBnetSysStateAlert)){
            $FBnetSysStateAlert = $active_user->FBnetSysStateAlert;
        }
        else $FBnetSysStateAlert = "false";

        if(isset($active_user->ShowSettingsMenue)){
            $ShowSettingsMenue = $active_user->ShowSettingsMenue;
        }
        else $ShowSettingsMenue = "true";

        if(isset($active_user->IndoorTempSource)){
            $IndoorTempSource = $active_user->IndoorTempSource;
        }
        else $IndoorTempSource = "99999";
        
		break;
	}else{
		$active_user = "false";
		if(isset($xml->persons->person->theme)){
			$localtheme = $xml->persons->person->theme;
		}else{
			$localtheme = "DARK";
		}

        if(isset($xml->persons->person->theme_bg)){
            $localtheme_bg = $xml->persons->person->theme_bg;
        }else{
            $localtheme_bg = "desktop_bg_asteroids";
        }

        if(isset($xml->persons->person->AutoRefreshDeviceData)){
            $AutoRefreshDeviceData = $xml->persons->person->AutoRefreshDeviceData;
        }else{
            $AutoRefreshDeviceData = "false";
        }

        if(isset($xml->persons->person->ShowFBdect200EnergyData)){
            $ShowFBdect200EnergyData = $xml->persons->person->ShowFBdect200EnergyData;
        }else{
            $ShowFBdect200EnergyData = "false";
        }

        if(isset($xml->persons->person->FBnetSysStateAlert)){
            $FBnetSysStateAlert = $xml->persons->person->FBnetSysStateAlert;
        }else{
            $FBnetSysStateAlert = "false";
        }

        if(isset($xml->persons->person->ShowSettingsMenue)){
            $ShowSettingsMenue = $xml->persons->person->ShowSettingsMenue;
        }else{
            $ShowSettingsMenue = "true";
        }

        if(isset($xml->persons->person->IndoorTempSource)){
            $IndoorTempSource = $xml->persons->person->IndoorTempSource;
        }else{
            $IndoorTempSource = "99999";
        }
	}
}
// var_dump ($localtheme);
// exit;

switch ($localtheme) {
    case "DARK":
	    $theme_page = "a";
        $theme_divider = "a";
        $theme_row = "a";
        $theme_desktop = "css/standard_design.css";
        break;
		
			
    case "LIGHT":
	    $theme_page = "c";
        $theme_divider = "a";
        $theme_row = "c";
        $theme_desktop = "css/standard_design.css";
        break;

}

function config_save($CONFIG_FILENAME, $xml) {
	if(!isset($CONFIG_FILENAME)){
		global $CONFIG_FILENAME;
	}
	if(!isset($xml)){
		global $xml;
	}
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($CONFIG_FILENAME);
}

function check_config_global() {
    return true;
}

function check_device($device) {
    if(empty($device->id)) {
        echo "Device-ID darf nicht leer sein!";
        return false;
    }
    if(empty($device->name)) {
        echo "Device-Name darf nicht leer sein!";
        return false;
    }
    if(empty($device->room)) {
        echo "Device-Room darf nicht leer sein!";
        return false;
    }
    if(empty($device->vendor)) {
        echo "Device-Vendor darf nicht leer sein!";
        return false;
    }
    $vendor=strtolower($device->vendor);
    $masterdip=$device->address->masterdip;
    $slavedip=$device->address->slavedip;
    $tx433version=$device->address->tx433version;
    switch($vendor) {
        case "intertechno":
            if(empty($masterdip)) {
                echo "Device-masterdip darf nicht leer sein!";
                return false;
            }        
            if(empty($slavedip)) {
                echo "Device-slavedip darf nicht leer sein!";
                return false;
            }        
            if((strlen($masterdip)!=1) || !(preg_match('/^[A-P]+$/',$masterdip))) {
                echo "Device-masterdip muss ein Buchstabe von A bis P sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$slavedip) || ($slavedip<1) || ($slavedip>16)) {
                echo "Device-slavedip darf nur eine Zahl zwischen 1 und 16 sein!";
                return false;
            }        
            break;
		case "ssh":
        case "raw":
        case "computer":
        case "url":
            break;
        case "fbdect200":
            if(empty($masterdip)) {
                echo "Device-masterdip darf nicht leer sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$masterdip)) {
                echo "Device-masterdip muss eine Zahl sein!";
                return false;
            }        
            break;
        case "milight":
            if($masterdip == "") {
                echo "Device-masterdip (MiLight WiFi-Bridge-ID) darf nicht leer sein!";
                return false;
            }        
            if($slavedip == "") {
                echo "Device-slavedip (Gruppe auf MiLight WiFi-Bridge) darf nicht leer sein!";
                return false;
            }        
            if($tx433version == "") {
                echo "Device-tx433version (MiLight Lampentyp) darf nicht leer sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$masterdip)) {
                echo "Device-masterdip (MiLight WiFi-Bridge-ID) muss eine Zahl sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$slavedip)) {
                echo "Device-slavedip (Gruppe auf MiLight WiFi-Bridge) muss eine Zahl sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$tx433version)) {
                echo "Device-tx433version (MiLight Lampentyp) muss eine Zahl sein!";
                return false;
            }        
            if($slavedip < 0 || $slavedip > 4) {
                echo "Device-slavedip (Gruppe auf MiLight WiFi-Bridge) muss eine Zahl zwischen 0-4 sein!";
                return false;
            }
            if($tx433version < 1 || $tx433version > 2) {
                echo "Device-tx433version (MiLight Lampentyp) muss eine Zahl zwischen 1-2 sein!";
                return false;
            }
            break;
		case "quigg":
			if(empty($masterdip)) {
                echo "Device-masterdip darf nicht leer sein!";
                return false;
            }        
            if(!preg_match('/^[0-9]+$/',$masterdip)) {
                echo "Device-masterdip muss eine Zahl sein!";
                return false;
            }
			if(empty($slavedip)) {
                echo "Device-slavedip darf nicht leer sein!";
                return false;
            }
			if(!preg_match('/^[0-4]+$/',$slavedip)) {
                echo "Device-masterdip muss eine Zahl von 0-4 sein!";
                return false;
            }
			break;
        case "brennenstuhl":
        case "elro":
        default:
            if(empty($masterdip)) {
                echo "Device-masterdip darf nicht leer sein!";
                return false;
            }        
            if(empty($slavedip)) {
                echo "Device-slavedip darf nicht leer sein!";
                return false;
            }        
            if(!preg_match('/^[0-1]+$/',$masterdip) || (strlen($masterdip)!=5)) {
                echo "Device-masterdip darf nur aus 1 und 0 bestehen und muss 5 Stellen haben!";
                return false;
            }        
            if(!preg_match('/^[0-1]+$/',$slavedip) || (strlen($slavedip)!=5)) {
                echo "Device-slavedip darf nur aus 1 und 0 bestehen und muss 5 Stellen haben!";
                return false;
            }        
            break;
    }
    return true;
}

function check_timer($timer) {
    return true;
}

if($xml->global->installpath == "" OR $xml->global->installpath != __DIR__){
		$xml->global->installpath = __DIR__;
        if(check_config_global()) {
			config_save($CONFIG_FILENAME, $xml);
		}
}

if(isset($xml->global->OutdoorTempSource)){
    $OutdoorTempSource = $xml->global->OutdoorTempSource;
}else{
    $OutdoorTempSource = "99999";
}
?>
