<?php
	 $CONFIG_FILENAME = 'data/time.xml';
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
	$time_xml = simplexml_load_file($CONFIG_FILENAME);
	if (!$time_xml) {
		echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht laden!\n";
		foreach(libxml_get_errors() as $error) {
			echo "\t", $error->message;
		}
		exit(4);
	}
	
$timestamp = time();
$now = date("Y-m-d H:i",$timestamp);


if((!isset($directaccess)) OR (!$directaccess)) die();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$debug_timer=empty($xml->timers["debug"]) ? "false" : $xml->timers["debug"];
header("Content-Type: text/plain; charset=utf-8");

function countdowntimer_check() {

	
	global $xml;
	global $time_xml;
    global $debug_timer;
	
	$timeraktivenowon = false;
	$timeraktivenowoff = false;
	$timeraktive = false;
	$pingaktive = false;
	$timestamp = time();
	$now = date("Y-m-d H:i",$timestamp);

	if($debug_timer=="true") debug_timer("Pruefe Countdowntimer...\n");
	
        //Aktuelle Zeit ermitteln und Puffer definieren, die beim Timer berücksichtigt werden sollen
        // Timer auslesen und bei gefunden Timern Aktionen ausführen
		if(@count($time_xml->timer) <= '0' ){
			debug_timer("Kein Countdowntimer gesetzt!\n");
			return;
		}
		foreach ( $time_xml->timer as $user )   
		{
			echo 'Countdowntimer Nr: ' . $user->id . "\n";
			echo 'Name: ' . $user->time . "\n";
			echo 'DevID: ' .$user->device . "\n";
			echo 'Action: '.$user->action . "\n \n";

			$count = $user->time;

			if ($count == $now){
					debug_timer("timer_switch ".$user->device." ".$user->action);
					$devicesFound = $xml->xpath("//devices/device/id[text()='".$user->device."']/parent::*");
					$device = $devicesFound[0];
					send_message($device, $user->action, TRUE);
					$device->status = $user->action;
					config_save("data/config.xml" , $xml);
			}
			if ($count <= $now ){
					$xpath='//timer/id[.="'.$user->id.'"]/parent::*';
					$res = $time_xml->xpath($xpath); 
					$parent = $res[0];
					unset($parent[0]);
					config_save($CONFIG_FILENAME , $time_xml);
					echo "Erfolgreich bereinigt\n";
			}
		}
}

function countdowntimer_switch($timer, $action) {
    global $xml;
    global $debug_timer;
    debug_timer("timer_switch ".$timer->id." ".$action);
    // Timer mit Device
        $devicesFound = $xml->xpath("//devices/device/id[text()='".$timer->typeid."']/parent::*");
        $device = $devicesFound[0];
        send_message($device, $action, TRUE);
}
?>
