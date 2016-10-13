<?php
$directaccess = true;
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
require("config.php");
require("debug.php");

$authentificated=false;
$menuAnimated="true";
$errormessage="";

require_once('includes/incl_functions.php');


// Über Tastenfunktion -> POST
if (isset($_POST['action'])) {
    $r_action = (string)$_POST['action'];
    $r_type = (string)$_POST['type'];
    $r_id = (string)$_POST['id'];
}
// Über Linkfunktion -> GET
if (isset($_GET['action'])) {
    $r_action = (string)$_GET['action'];	
    $r_type = (string)$_GET['type'];
    $r_id = (string)$_GET['id'];
	if($r_action == "toggle" and $r_type == "device"){
		$xpath='//device/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0];
		if($parent[0]->status == "OFF"){
			$r_action = "on";
		}elseif($parent[0]->status == "ON"){
			$r_action = "off";
		}
	}
}
if (isset($_POST['todo'])) {
    if ($_POST['todo'] == "sendmilight") {
        $r_MLid = (string)$_POST['id'];
        $r_MLcmd = (string)$_POST['command'];
        $r_MLval = (string)$_POST['value'];
    }
}

// Über Timerfunktion -> GET
if (isset($_GET['timerrun'])) {
    require("send_msg.php");
    require("timer.php");
	include("countdowntimer.php");
    if ($xml->global->timerGlobalRun != "false") timer_check();
	ping_check();
	countdowntimer_check();
    fbdect_check();
    exit();
}

if (isset($r_action)) {
    debug("Running in action='".$r_action."'");  

    require("send_msg.php");

    if (($r_action)=="alloff") {
        foreach($xml->devices->device as $device) {
            send_message($device, "OFF");
            usleep($multiDeviceSleep);
        }
        //echo str_replace("\n","<br>",$errormessage);
        echo $errormessage;

    } else if (($r_action)=="allon") {
        foreach($xml->devices->device as $device) {
            send_message($device, "ON");
            usleep($multiDeviceSleep);
        }
        //echo str_replace("\n","<br>",$errormessage);
        echo $errormessage;

    } else {
        if (($r_action)=="on") { 
            $action="ON"; 
        }else { 
            $action="OFF";
        }
        
        if (($r_type)=="device") {
            send_message_device($r_id, $action);

        } else if (($r_type)=="room") {
            send_message_room($r_id, $action);

        } else if (($r_type)=="group") { 
            send_message_group($r_id, $action);

        } else if (($r_type)=="action") { 
            $actionsFound = $xml->xpath("//actions/action/id[text()='".$r_id."']/parent::*");
            foreach($actionsFound[0]->do as $do) {
                debug("Action: type:".$do['type']." id:".$do['id']." action:".$do['action']);
                switch ($do['type']) {
                    case "device":
                        send_message_device($do['id'], $do['action'], TRUE, $do['mode'], $do['color'], $do['brightness'], $actionsFound[0]->name);
                        usleep($multiDeviceSleep);
                        break;
                    case "room":
                        send_message_room($do['id'], $do['action'], TRUE, $actionsFound[0]->name);
                        break;
                    case "group":
                        send_message_group($do['id'], strtoupper($do['action']), TRUE, $actionsFound[0]->name);
                        break;
                    case "wait":
                        debug("Action: Schlafe jetzt ".$do['id']." Sekunden");
                        sleep(intval($do['id']));
                        debug("Action: Wieder wach!");
                        break;
                }
            }
        } else if (($r_type)=="timerglobalrun") {
            if ($action == "ON") {
                if ($xml->global->timerGlobalRun == "true") $errormessage = "FEHLER: Globaler Timer ist bereits aktiviert!";
                else { $xml->global->timerGlobalRun = "true"; $errormessage = "Globaler Timer wurde aktiviert!"; }
            }
            else if ($action == "OFF") {
                if ($xml->global->timerGlobalRun == "false") $errormessage = "FEHLER: Globaler Timer ist bereits deaktiviert!";
                else { $xml->global->timerGlobalRun = "false"; $errormessage = "Globaler Timer wurde deaktiviert!"; }
            }
        } else if (($r_type)=="alertstate") {
            if ($action == "ON") {
                if ($xml->global->AlertState == "red") $errormessage = "FEHLER: System-Alarm wurde bereits ausgelöst!";
                else { $xml->global->AlertState = "red"; $errormessage = "System-Alarm wurde ausgelöst!"; async_curl('http://localhost'.str_replace('index.php', 'redalert.php', $_SERVER[PHP_SELF])); }
            }
            else if ($action == "OFF") {
                if ($xml->global->AlertState == "green") $errormessage = "FEHLER: System-Alarm wurde bereits aufgehoben!";
                else { $xml->global->AlertState = "green"; $errormessage = "System-Alarm wurde aufgehoben!"; }
            }
        }
        //echo str_replace("\n","<br>",$errormessage);
        echo $errormessage;
    }
    config_save(); 
}
elseif (isset($r_MLcmd)) {
    require("send_msg.php");
    debug("Executing MiLight-Command '".$r_MLcmd."' for Device-ID '".$r_MLid."'");  
    echo toggle_milight($r_MLid,$r_MLcmd,$r_MLval);
    config_save();
}
else {
    debug("Sending HTML Site");  
    require("gui.php");
} 
    //debug("END");  
?> 
