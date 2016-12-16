<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("config.php");

$r_action = (string)$_POST['action'];
$r_debug = (string)$_POST['debug'];
$r_debug_timer = (string)$_POST['debug_timer'];
$r_timezone = (string)$_POST['timezone'];
$r_longitude = $_POST['longitude'];
$r_latitude = $_POST['latitude'];
$r_country = (string)$_POST['country'];
$r_plz = (string)$_POST['plz'];
$r_city = (string)$_POST['city'];
$r_timerGlobalRun = (string)$_POST['timerGlobalRun'];
$r_timerRunOnce = (string)$_POST['timerRunOnce'];
$r_timerPingUser = (string)$_POST['timerPingUser'];
$r_timerCheckFBdect200 = (string)$_POST['timerCheckFBdect200'];
$r_connairID = (string)$_POST['connairID'];
$r_connairIP = (string)$_POST['connairIP'];
$r_connairPort = (string)$_POST['connairPort'];
$r_connairTechData = (string)$_POST['gwtechdata'];
$r_milightID = (string)$_POST['milightID'];
$r_milightIP = (string)$_POST['milightIP'];
$r_milightPort = (string)$_POST['milightPort'];
$r_milightMAC = (string)$_POST['milightMAC'];
$r_fritzboxAddress = (string)$_POST['fritzboxAddress'];
$r_fritzboxUsername = (string)$_POST['fritzboxUsername'];
$r_fritzboxPassword = (string)$_POST['fritzboxPassword'];
$r_multiDeviceSleep = intval($_POST['multiDeviceSleep']);
$r_playSounds = (string)$_POST['playSounds'];
$r_OutdoorTempSource = (string)$_POST['OutdoorTempSource'];
//$r_showDeviceStatus = (string)$_POST['showDeviceStatus'];
$r_showRoomButtonInDevices = (string)$_POST['showRoomButtonInDevices'];
$r_showMenuOnLoad = (string)$_POST['showMenuOnLoad'];
$r_showTimerBtnInMenu = (string)$_POST['showTimerBtnInMenu'];
$r_showActionBtnInMenu = (string)$_POST['showActionBtnInMenu'];
$r_showRoomsBtnInMenu = (string)$_POST['showRoomsBtnInMenu'];
$r_showGroupsBtnInMenu = (string)$_POST['showGroupsBtnInMenu'];
$r_showAllOnOffBtnInMenu = (string)$_POST['showAllOnOffBtnInMenu'];
$r_sortOrderDevices = (string)$_POST['sortOrderDevices'];
$r_sortOrderGroups = (string)$_POST['sortOrderGroups'];
$r_sortOrderRooms = (string)$_POST['sortOrderRooms'];
$r_sortOrderTimers = (string)$_POST['sortOrderTimers'];
$r_sendRaspi = (string)$_POST['sendRaspi'];
//$r_theme_mobile = (string)$_POST['theme_mobile'];
//$r_theme_desktop = (string)$_POST['theme_desktop'];
//$r_theme_desktop_bg = (string)$_POST['theme_desktop_bg'];

switch ($r_action) {
    
    case "edit":
        $xml["debug"] = $r_debug;
        $xml->timers["debug"] = $r_debug_timer;
		$xml->global->sendRaspi = $r_sendRaspi;
        $xml->global->timezone = $r_timezone;
        $xml->global->longitude = $r_longitude;
        $xml->global->latitude = $r_latitude;
		$xml->global->country = $r_country;
		$xml->global->plz = $r_plz;
		$xml->global->city = $r_city;
		$xml->connairs->connair->id = $r_connairID;
        $xml->connairs->connair->address = $r_connairIP;
        $xml->connairs->connair->port = $r_connairPort;
		$xml->connairs->connair->techdata = $r_connairTechData;
        $xml->milightwifis->milightwifi->id = $r_milightID;
        $xml->milightwifis->milightwifi->address = $r_milightIP;
        $xml->milightwifis->milightwifi->port = $r_milightPort;        
        $xml->milightwifis->milightwifi->mac = $r_milightMAC;
		$xml->fritzbox->address = $r_fritzboxAddress;
		$xml->fritzbox->username = $r_fritzboxUsername;
		$xml->fritzbox->password = $r_fritzboxPassword;
        $xml->global->multiDeviceSleep = $r_multiDeviceSleep;
        $xml->global->playSounds = $r_playSounds;
        $xml->global->OutdoorTempSource = $r_OutdoorTempSource;
		$xml->global->timerGlobalRun = $r_timerGlobalRun;
		$xml->global->timerRunOnce = $r_timerRunOnce;
        $xml->global->timerPingUser = $r_timerPingUser;
        $xml->global->timerCheckFBdect200 = $r_timerCheckFBdect200;
		// $xml->global->installpath = __DIR__;
        if(check_config_global()) {
            echo "ok";
            config_save();
        }
        break;
    
	case "editdesign":
        //$xml->gui->showDeviceStatus = $r_showDeviceStatus;
        $xml->gui->showRoomButtonInDevices = $r_showRoomButtonInDevices;
        $xml->gui->showMenuOnLoad = $r_showMenuOnLoad;
		$xml->gui->showTimerBtnInMenu = $r_showTimerBtnInMenu;
		$xml->gui->showActionBtnInMenu = $r_showActionBtnInMenu;
		$xml->gui->showRoomsBtnInMenu = $r_showRoomsBtnInMenu;
		$xml->gui->showGroupsBtnInMenu = $r_showGroupsBtnInMenu;
        $xml->gui->showAllOnOffBtnInMenu = $r_showAllOnOffBtnInMenu;
        $xml->gui->sortOrderDevices = $r_sortOrderDevices;
        $xml->gui->sortOrderGroups = $r_sortOrderGroups;
        $xml->gui->sortOrderRooms = $r_sortOrderRooms;
        $xml->gui->sortOrderTimers = $r_sortOrderTimers;
        //$xml->gui->theme_mobile = $r_theme_mobile;
		//$xml->gui->theme_desktop = $r_theme_desktop;
		//$xml->gui->theme_desktop_bg = $r_theme_desktop_bg;
        if(check_config_global()) {
            echo "ok";
            config_save();
        }
        break;
	    
	case "rebootconnair":
        foreach($xml->connairs->connair as $connair) {
            $server_ip   = (string)$connair->address;
            $server_port = (string)$connair->port;
            $message     = 'rebooting.cgi?restart=true&Submit=REBOOT';

            if ($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
                socket_sendto($socket, $message, strlen($message), 0, $server_ip, $server_port);
                echo "ok";
            }
            else echo "socket_create FAILED";
        }
        break;
    default:
        echo "action unsupported";
        break;
}
?>
