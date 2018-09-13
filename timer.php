<?php
if((!isset($directaccess)) OR (!$directaccess)) die();

//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$debug_timer=empty($xml->timers["debug"]) ? "false" : $xml->timers["debug"];
header("Content-Type: text/plain; charset=utf-8");

function debug_timer($msg) {
    global $debug_timer;
    if($debug_timer=="true") {
        echo $msg."\n";
    }
    debug($msg);
}

function ping($host)
{
	$ret=-1;$out="";$FBping="";
	/* $FBping = exec("/opt/bin/ping -q -c1 ".$host." |grep round-trip");
	$FBping = str_replace("round-trip min/avg/max = ","",$FBping);
	$FBping = explode("/",$FBping);
	if ($FBping[1] != "") return 0;
	else return 1; */
	$FBping = exec("ping -q -c1 ".$host,$out,$ret);
	return $ret;
}

function ping_check(){
    global $xml;
    global $debug_timer;
	
	if($debug_timer=="true") debug_timer("Benutzer-Anwesenheit pruefen:");
	if ($xml->global->timerPingUser == "true") {
		if(@count($xml->persons->children()) > 0 ) {
			if($debug_timer=="true") debug_timer("Standard-Benutzer wird nie auf Anwesenheit geprueft.\n");
			foreach($xml->persons->person as $person){
				if($person->name != "Standard-Benutzer") {
					$ip = $person->pingto;
					$ipaddress = filter_var($ip, FILTER_VALIDATE_IP);
					if($ipaddress != false){
						//exec("ping -c1 -W1 $ip", $output, $status);
						$status = ping($ip);
						
						$xpath='//person/name[.="'.$person->name.'"]/parent::*';
						$res = $xml->xpath($xpath); 
						$parent = $res[0];
						
						$StatusAlt = $parent[0]->status;
						$StatusNeu = "";
						if($status == 0){
							if($debug_timer=="true") debug_timer("$person->name ($ip) ist anwesend");
							$StatusNeu = 'anwesend';
						}else{
							if($debug_timer=="true") debug_timer("$person->name ($ip) ist abwesend");
							$StatusNeu = 'abwesend';
						}

						if ($StatusNeu != $StatusAlt) {
							$parent[0]->status = $StatusNeu;
							config_save('data/config.xml');
						}
					}
				}
			}
			echo"\n\n";
		}else{
			if($debug_timer=="true") debug_timer("Keine Benutzer konfiguriert! \n");
		}
	}else{
		if($debug_timer=="true") debug_timer("Anwesenheitspruefung ist deaktiviert! \n");
	}
}

function fbdect_check(){
    global $xml;
    global $debug_timer;
	
	if($debug_timer=="true") debug_timer("Status von FBdect200-Geraeten pruefen:");
	if ($xml->global->timerCheckFBdect200 == "true") {
		$CntDect=0;
		$CntChng=0;
		$XMLdata = Fritzbox_GetHAactorsInfoXML();
		foreach($xml->devices->device as $device) {
			if($device->vendor == "fbdect200") {
				if ($XMLdata != -1) $ActStatus = Fritzbox_GetHAactorDataFromXML($XMLdata,trim($device->address->masterdip),'state');
				else $ActStatus = Fritzbox_DECT200_SwitchState($device->address->masterdip);
				if ($ActStatus == 0) $ActStatus = "OFF";
				elseif ($ActStatus == 1) $ActStatus = "ON";
				$OldStatus = $device->status;

				if ($ActStatus != $OldStatus && $ActStatus != "-1") {
					$xpath="//devices/device/id[text()='".$device->id."']/parent::*";					
					$res = $xml->xpath($xpath); 
					$parent = $res[0];

					if($debug_timer=="true") debug_timer("Status von ".$device->name." (".$device->address->masterdip.") geaendert: ".$OldStatus." => ".$ActStatus);
					$parent[0]->status = $ActStatus;

					config_save('data/config.xml');
					$CntChng++;
				}
			$CntDect++;
			}
		}
		if($debug_timer=="true" && $CntDect == 0) debug_timer("Keine FBdect200-Geraete vorhanden! \n");
		if($debug_timer=="true" && $CntChng == 0) debug_timer("Keine Statusaenderungen! \n");
		echo "\n\n";
	}else{
		if($debug_timer=="true") debug_timer("FBdect200-Status-Pruefung ist deaktiviert! \n");
	}
}

function timer_check() {
    global $xml;
    global $debug_timer;
    global $latitude;
    global $longitude;#$sunset,$sunrise,$latitude,$latitude,$debug_timer,$xml
    global $sunrise;
    global $sunset;
	
	$timeraktivenowon = false;
	$timeraktivenowoff = false;
	$timeraktive = false;
	$pingaktive = false;

	#if($debug_timer=="true") debug_timer("Timer Checking...");
	if(@count($xml->timers->children()) > 0 ) {
		if($debug_timer=="true") debug_timer("latitude: ".$latitude."  longitude: ".$longitude."  sunrise: ".$sunrise."  sunset: ".$sunset." timer schaltet nur wenn noetig (timerrunonce):".$xml->global->timerRunOnce);
        //Aktuelle Zeit ermitteln und Puffer definieren, die beim Timer berücksichtigt werden sollen
		$now = time();
        $timepuffer = 1.0; // Zeitpuffer in Minuten
        $timeWindowStart = $now - (60 * $timepuffer);
        $timeWindowStop = $now;
        //Wochentag ermitteln
        $nowday = date("N") -1;
        $preday = date ("N", time() - ( 24 * 60 * 60)) -1; //Vortag
        // Timer auslesen und bei gefunden Timern Aktionen ausführen
		
		foreach($xml->timers->timer as $timer) {
			if($debug_timer=="true") debug_timer("Timer: \n".$timer->asXML());

			
			if($timer->active == "on") {
				$timeraktive = true;
            }
			$timerday=(string)$timer->day;
			###### Timer ermitteln ################
			// On Timer
			switch ($timer->timerOn) {
				case "SU":
					$OnTimer = $sunrise;
					if(!empty($timer->timerOn['offset'])) {
						$OnTimer += ($timer->timerOn['offset']*60);
					}
					break;
				case "SD":
					$OnTimer = $sunset;
					if(!empty($timer->timerOn['offset'])) {
						$OnTimer += ($timer->timerOn['offset']*60);
					}
					break;
				case "M":
					$OnTimer = 0;
					break;
				default:
					$OnTimer = strtotime($timer->timerOn);
			}
			// Off Timer
			switch ($timer->timerOff) {
					case "SU":
						$OffTimer = $sunrise;
						if(!empty($timer->timerOff['offset'])) {
							$OffTimer += ($timer->timerOff['offset']*60);
						}
						break;
					case "SD":
						$OffTimer = $sunset;
						if(!empty($timer->timerOff['offset'])) {
							$OffTimer += ($timer->timerOff['offset']*60);
						}
						break;
					case "M":
						$OffTimer = 0;
						break;
					default:
						$OffTimer = strtotime($timer->timerOff);
				}
			
			###### Timer On bearbeiten ############
			if(!empty($OnTimer)) {
				// Prüfen, ob aktueller Tag mit dem OnTimer Tag zulässig ist
				$checkDayOn = strpos("MDTWFSS",$timerday[$nowday]);
				if (is_numeric($checkDayOn)) 
				{
					//if($debug_timer=="true") debug_timer("Timer Tag stimmt (ON) ".$timer->id);
					//if($debug_timer=="true") debug_timer("++++TimerID:".$timer->id." OnTimer ".date('H:i', $OnTimer)." Von ".date('H:i', $timeWindowStart)." - ".date('H:i', $timeWindowStop));
					// Tag gültig -> Prüfen, ob On Timer innerhalb des Zeitfensters liegt
					if (($OnTimer >= $timeWindowStart) && ($OnTimer <= $timeWindowStop))
                    {
						// Timer liegt innerhalb des Zeitfensters -> Schaltungen durchführen
						$timeraktivenowon = true;
					}else{
						$timeraktivenowoff = false;
					}
				}
			}
			###### Timer Off bearbeiten ############
            if(!empty($OffTimer)) {
                // Prüfen, ob aktueller Tag mit dem OffTimer Tag zulässig ist
                if ($OffTimer < $OnTimer)				{
                    // OffTimer ist geringer als OnTimer => Für die Zulässigkeitsprüfung wird der PHP Vortag genommen
                    $checkDayOff = strpos("MDTWFSS",$timerday[$preday]);
                } else{
                    // Off Timer ist höher als OnTimer => Für die Zulässigkeitsprüfung wird der aktuelle PHP Tag genommen
                    $checkDayOff = strpos("MDTWFSS",$timerday[$nowday]);
                }
                if (is_numeric($checkDayOff))
				{
                    //if($debug_timer=="true") debug_timer("Timer Tag stimmt (OFF) ".$timer->id);
                    //if($debug_timer=="true") debug_timer("----TimerID:".$timer->id." OffTimer ".date('H:i', $OffTimer)." Von ".date('H:i', $timeWindowStart)." - ".date('H:i', $timeWindowStop));
                    // Tag gültig -> Prüfen, ob On Timer innerhalb des Zeitfensters liegt
                    if (($OffTimer >= $timeWindowStart) && ($OffTimer <= $timeWindowStop))
					{
                        // Timer liegt innerhalb des Zeitfensters -> Schaltungen durchführen
                        //timer_switch($timer, "OFF");
						$timeraktivenowoff = true;
                    }else{
						$timeraktivenowoff = false;
					}
                }
            }
			
			/************** wenn nötig Ping absetzen**************/
			if( $timer->usage == "time_ping" or $timer->usage == "ping" ){
				$ip = $timer->pingto;
				// if(preg_match('[1-9]{1-3}[.][1-9]{1-3}[.][1-9]{1-3}[.][1-9]{1-3}[]?', $ip ) == 'true'){
				$ipaddress = filter_var($ip, FILTER_VALIDATE_IP);
				if($ipaddress != false){
					//exec("ping -c1 -W1 $ip", $output, $status);
					$status = ping($ip);
					if($status == 0){
						$pingaktive = "true";
						if($debug_timer=="true") debug_timer($ip." = anwesend \n");
					}else{
						$pingaktive = "false";
						if($debug_timer=="true") debug_timer($ip." = abwesend \n");
					}
				}elseif(!is_numeric($ip)){
					$xpath='//person/name[.="'.$timer->pingto.'"]/parent::*';
					$res = $xml->xpath($xpath);
					$parenttime = $res[0];
					if($parenttime[0]->status == 'anwesend'){
						$pingaktive = "true";
						if($debug_timer=="true") debug_timer($parenttime[0]->name." anwesend\n");
					}else{
						if($debug_timer=="true") debug_timer($parenttime[0]->name." abwesend\n");
						$pingaktive = "false";
					}
				}
			}
		
			/*****************Variablen durchprüfen und schalten*********************/
			if($timer->usage == "ping" AND $timer->active == "on" AND $pingaktive == "true"){
				timer_switch($timer, $timer->pingstatus);
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet den Ping (anwesend) und schaltet in dieser Minute $timer->pingstatus\n");
			}else if($timer->usage == "ping" AND $timer->active == "on" AND $pingaktive == "false" AND $timer->invertSwitchOnNoPing == "true"){
				$TPinv="";
				if ($timer->pingstatus == "OFF") $TPinv="ON";
				elseif ($timer->pingstatus == "ON") $TPinv="OFF";
				timer_switch($timer, $TPinv);
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet den Ping (abwesend) und schaltet in dieser Minute $TPinv\n");
			}else if($timer->usage == "time_ping" AND $timeraktivenowon == "true"  AND $pingaktive == "true" AND $timer->active == "on"){
				timer_switch($timer, "ON");
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet die Zeit und den Ping und schaltet in dieser Minute an\n");
			}else if($timer->usage == "time_ping" AND $timeraktivenowoff == "true"  AND $pingaktive == "true" AND $timer->active == "on"){
				timer_switch($timer, "OFF");
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet die Zeit und den Ping und schaltet in dieser Minute aus \n");
			}elseif($timer->usage == "time" AND $timeraktivenowon == "true" AND $timer->active == "on"){
				timer_switch($timer, "ON");
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet die Zeit und schaltet in dieser Minute an \n");
			}elseif($timer->usage == "time" AND $timeraktivenowoff == "true" AND $timer->active == "on"){
				timer_switch($timer, "OFF");
				if($debug_timer=="true") debug_timer("Dieser Timer beachtet die Zeit und schaltet in dieser Minute aus \n");
			}else{
				if($debug_timer=="true") debug_timer("Dieser Timer wird nicht geschaltet, keine Vorgaben werden erfuellt \n");
			}
			$timeraktivenowon = false;
			$timeraktivenowoff = false;
			$timeraktive = false;
			$pingaktive = false;
		}
	}
}

/* function send_timer($debug_timer,$timerday,$timer,$OffTimer,$OnTimer,$sunrise,$sunset,$timerday,$nowday,$timeWindowStop,$timeWindowStart){
	timer_switch($timer, "OFF");
} */

function timer_switch($timer, $action) {
    global $xml;
    global $debug_timer;
    global $multiDeviceSleep;
    if($debug_timer=="true") debug_timer("Timer: ID:".$timer->id." Action:".$action);
    // Timer mit Device
    if (($timer->type)=="device") {
        $devicesFound = $xml->xpath("//devices/device/id[text()='".$timer->typeid."']/parent::*");
        $device = $devicesFound[0];
        timer_send_message($device, $action, $timer->milight->mode, $timer->milight->color, $timer->milight->brightness);
    }
    // Timer mit Room
    if (($timer->type)=="room") {
        $devicesFound = $xml->xpath("//devices/device/room[text()='".$timer->typeid."']/parent::*");
        foreach($devicesFound as $device) {
            timer_send_message($device, $action);
        }
    }
    // Timer mit Group
    if (($timer->type)=="group") {
        $groupsFound = $xml->xpath("//groups/group/id[text()='".$timer->typeid."']/parent::*");
        foreach($groupsFound[0]->deviceid as $deviceid) {
            $devicesFound = $xml->xpath("//devices/device/id[text()='".$deviceid."']/parent::*");
            $device = $devicesFound[0];
            $deviceaction = strtolower($action);
            if($debug_timer=="true") debug_timer("Device ".$deviceid." wird '".$deviceaction."'geschaltet");

			if($action == "ON") {
				if(empty($deviceid['onaction'])) {
					send_message($device, strtoupper($action), TRUE);
				} else {
					switch ($deviceid['onaction']) {
						case "on":
							send_message($device, "ON", TRUE);
							break;
						case "off":
							send_message($device, "OFF", TRUE);
							break;
						case "none":
							break;
					}
				}
			} else if($action == "OFF") {
				if(empty($deviceid['offaction'])) {
					send_message($device, strtoupper($action), TRUE);
				} else {
					switch ($deviceid['offaction']) {
						case "on":
							send_message($device, "ON", TRUE);
							break;
						case "off":
							send_message($device, "OFF", TRUE);
							break;
						case "none":
							break;
					}
				}
			}
            usleep($multiDeviceSleep);
        }
    }
	
	if (($timer->type)=="short") {
        $devicesFound = $xml->xpath("//devices/device/id[text()='".$timer->typeid."']/parent::*");
        $device = $devicesFound[0];
        send_message($device, $action, TRUE);
        usleep($multiDeviceSleep);
    }
    config_save('data/config.xml');
}


function timer_send_message($device, $action, $MLMode = "", $MLColor = "", $MLBrightness = "") {
    global $xml;
    global $debug_timer;
    global $multiDeviceSleep;
    if($xml->global->timerRunOnce == "false" || ($xml->global->timerRunOnce == "true" && $action != $device->status)) {
        send_message($device, $action, TRUE, $MLMode, $MLColor, $MLBrightness);
        usleep($multiDeviceSleep);
    } else if($debug_timer=="true") {
        debug_timer("Schalte nicht da vermutlich schon im richtigen Status. Device ".$device->id." mit Action '".$action."' timerRunOnce=".$xml->global->timerRunOnce);
    }
}
?>