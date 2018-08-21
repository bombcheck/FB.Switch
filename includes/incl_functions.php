<?php
function TemperaturePercentToKelvin($percent) {
    if ($percent == "" || $percent < 1 || $percent > 100) return 0;
	global $MilightRgbcctMinKelvin,$MilightRgbcctMaxKelvin;
	$Diff = $MilightRgbcctMaxKelvin - $MilightRgbcctMinKelvin;
	$KelvinPerPercent = $Diff / 100;
	$val = $KelvinPerPercent * $percent;
	return $MilightRgbcctMaxKelvin - $val;
}
function colorHEXtoRGB($hex) {
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return array("R"=>$r, "G"=>$g, "B"=>$b);
}
function compareDevicesByName($a, $b) {
   return strcmp($a->name,$b->name);
}
function compareDevicesByID($a, $b) {
   return strcmp($a->id,$b->id);
}
function compareDevicesByRoom($a, $b) {
   return strcmp($a->room,$b->room);
}
function compareGroupsByName($a, $b) {
   return strcmp($a->name,$b->name);
}
function compareGroupsByID($a, $b) {
   return strcmp($a->id,$b->id);
}
function compareTimersByTypeAndName($a, $b) {
    global $xml;
    switch($a->type) {
        case "device":
            $devicesFound = $xml->xpath("//devices/device/id[text()='".$a->typeid."']/parent::*");
            $deviceA = $devicesFound[0];
            $nameA = $deviceA->name;
            break;
        case "group":
            $groupsFound = $xml->xpath("//groups/group/id[text()='".$a->typeid."']/parent::*");
            $groupA = $groupsFound[0];
            $nameA = $groupA->name;
            break;
        case "room":
            $nameA = $a->typeid;
            break;
        default:
            $nameA = $a->id;
            break;
    }
    switch($b->type) {
        case "device":
            $devicesFound = $xml->xpath("//devices/device/id[text()='".$b->typeid."']/parent::*");
            $deviceB = $devicesFound[0];
            $nameB = $deviceB->name;
            break;
        case "group":
            $groupsFound = $xml->xpath("//groups/group/id[text()='".$b->typeid."']/parent::*");
            $groupB = $groupsFound[0];
            $nameB = $groupB->name;
            break;
        case "room":
            $nameB = $b->typeid;
            break;
        default:
            $nameB = $b->id;
            break;
    }
    return strcmp($nameA,$nameB);
}
function compareTimersByID($a, $b) {
   return strcmp($a->id,$b->id);
}
function compareTimersByType($a, $b) {
    $cmp = strcmp($a->type,$b->type);
    if($cmp == 0) {
        $cmp = compareTimersByName($a, $b);
    }
    return $cmp;
}
function compareTimersByName($a, $b) {
   return strcmp($a->name,$b->name);
}

function send_message_device($deviceid, $action, $ViaTimer = FALSE, $MLMode = "", $MLColor = "", $MLBrightness = "", $ViaAction = FALSE) {
    global $xml;
    $devicesFound = $xml->xpath("//devices/device/id[text()='".$deviceid."']/parent::*");
    $device = $devicesFound[0];
    send_message($device, strtoupper($action), $ViaTimer, $MLMode, $MLColor, $MLBrightness, $ViaAction);
}

function send_message_room($room, $action, $ViaTimer = FALSE, $ViaAction = FALSE) {
    global $xml;
    global $multiDeviceSleep;
    $devicesFound = $xml->xpath("//devices/device/room[text()='".$room."']/parent::*");
    foreach($devicesFound as $device) {
        send_message($device, strtoupper($action), $ViaTimer, "", "", "", $ViaAction);
        usleep($multiDeviceSleep);
    }
}

function send_message_group($groupid, $action, $ViaTimer = FALSE, $ViaAction = FALSE) {
    global $xml;
    global $multiDeviceSleep;
    $groupsFound = $xml->xpath("//groups/group/id[text()='".$groupid."']/parent::*");
    foreach($groupsFound[0]->deviceid as $deviceid) {
        $devicesFound = $xml->xpath("//devices/device/id[text()='".$deviceid."']/parent::*");
        $device = $devicesFound[0];
        if($action == "ON") {
            if(empty($deviceid['onaction'])) {
                send_message($device, strtoupper($action), $ViaTimer, "", "", "", $ViaAction);
            } else {
                switch ($deviceid['onaction']) {
                    case "on":
                        send_message($device, "ON", $ViaTimer, "", "", "", $ViaAction);
                        break;
                    case "off":
                        send_message($device, "OFF", $ViaTimer, "", "", "", $ViaAction);
                        break;
                    case "none":
                        break;
                }
            }
        } else if($action == "OFF") {
            if(empty($deviceid['offaction'])) {
                send_message($device, strtoupper($action), $ViaTimer, "", "", "", $ViaAction);
            } else {
                switch ($deviceid['offaction']) {
                    case "on":
                        send_message($device, "ON", $ViaTimer, "", "", "", $ViaAction);
                        break;
                    case "off":
                        send_message($device, "OFF", $ViaTimer, "", "", "", $ViaAction);
                        break;
                    case "none":
                        break;
                }
            }
        }
        usleep($multiDeviceSleep);
    }
}

function LogToBackend($type,$logtext,$WAnotify,$ViaTimer,$ViaAction) {
    global $xml,$active_user;
    $FBnet_BEurl = $xml->backend->url;
    $FBnet_SIDsource = $xml->backend->sidsource;

    if ($FBnet_BEurl != "" && $FBnet_SIDsource != "" && $xml->backend->logging == "true") {
        $SID = file_get_contents($FBnet_SIDsource);
        $TextToLog = $logtext;
        if ($ViaTimer == TRUE && $ViaAction == FALSE) {
            $TextToLog .= " from TIMER";
        }
        elseif ($ViaTimer == TRUE && $ViaAction == TRUE) {
            $TextToLog .= " from ACTION ".$ViaAction."@";
            if ($active_user != "false") $TextToLog .= $active_user->name;
            else $TextToLog .= $xml->persons->person->name;
        }
        else {
            if ($active_user != "false") $TextToLog .= " from ".$active_user->name;
            else $TextToLog .= " from ".$xml->persons->person->name;
        }
        $task="LogAction";$params=$xml->global->FBnetDeviceID."|".$type."|".urlencode($TextToLog)."|".$WAnotify;

        $ret = file_get_contents($FBnet_BEurl."?task=".$task."&sid=".$SID."&params=".$params);
        if ($ret === FALSE) $ret = "Backend unreachable!";
        if (strpos($ret,'#OK#') !== FALSE) return(TRUE);
        else return($ret);
    }
    else return('Disabled');
}

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
?>