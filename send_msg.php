<?php
if((!isset($directaccess)) OR (!$directaccess)) die();

//require("config.php");
require("fritzbox.inc.php");
require("includes/incl_milight.php");

function connair_send($device, $msg, $action) {
    debug("Sending Message to Gateway with id '".$device->senderid."'");
    global $debug;
    global $xml;
    global $errormessage;
    $len = strlen($msg);
    if(!($sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        $errormessage.="Couldn't create socket: [$errorcode] $errormsg  \n";
        return;
    }
    $devicesenderid=(string)$device->senderid;
    foreach($xml->connairs->connair as $connair) {
        if(!empty($devicesenderid) && (string)$device->senderid != (string)$connair->id) {
            debug("NOT Sending Message to Gateway [".$connair->id."] ".$connair->address.":".(integer)$connair->port);
            continue;
        }
        if ((string)$connair["type"]=="itgw") {
            $newmsg=str_replace("TXP:","",$msg);
            $newmsg=str_replace("#baud#","26,0",$newmsg);
        } else {
            $newmsg=str_replace("#baud#","25",$msg);
        }
        $connairIP = trim((string)$connair->address);
        if(!filter_var($connairIP, FILTER_VALIDATE_IP)) {
            $connairIPCheck = @gethostbyname(trim((string)$connair->address));
            if($connairIP == $connairIPCheck) {
                $errormessage.="Gateway ".$connairIP." is not availible. Check IP or Hostname.  \n";
                debug($errormessage);
                continue;
            } else {
                debug("Found this IP ".$connairIPCheck." for Gateway ".$connairIP);
                $connairIP = $connairIPCheck;
            }
        }
        if ($device->sendCommandsOnlyOnce == "true") {
            debug("Sending Message '".$newmsg."' ONE TIME to Gateway ".$connairIP.":".(integer)$connair->port);
            $scRET = socket_sendto($sock , $newmsg, $len , 0, $connairIP , (integer)$connair->port);
        }
        else {
            debug("Sending Message '".$newmsg."' to Gateway ".$connairIP.":".(integer)$connair->port);
            for ($sc=0;$sc < 6; $sc++) { $scRET = socket_sendto($sock , $newmsg, $len , 0, $connairIP , (integer)$connair->port); usleep(250000); }
        }

        if( ! $scRET) {
            $errorcode = socket_last_error();
            if($errorcode>0) {
                $errormsg = socket_strerror($errorcode);
                $errormessage.="Could not send data: [$errorcode] $errormsg  \n";
            } else {
                $errormessage.=$device->name. " wurde geschaltet: ".strtoupper($action)."  \n";
            }
        } else {
            $errormessage.=$device->name. " wurde geschaltet: ".strtoupper($action)."  \n";
        }
    }
    if($sock) {
        socket_close($sock);
    }
}

function connair_create_msg_brennenstuhl($device, $action) {
    debug("Create Gateway Message for Brennenstuhl device='".(string)$device->id."' action='".(string)$action."'");  
    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->tx433version)) {
        echo "ERROR: tx433version ist ungültig für device id ".$device->id."\n";
        return;
    }
    $sA=0;
    $sG=0;
    $sRepeat=10;
    $sPause=5600;
    $sTune=350;
    $sBaud="#baud#";
    $sSpeed=32;
    $uSleep=800000;
    if ($device->address->tx433version==1) {
        $txversion=3;
    } else {
        $txversion=1;
    }
    $HEAD="TXP:$sA,$sG,$sRepeat,$sPause,$sTune,$sBaud,";
    $TAIL=",$txversion,1,$sSpeed,;";
    $AN="1,3,1,3,3";
    $AUS="3,1,1,3,1";
    $bitLow=1;
    $bitHgh=3;
    $seqLow=$bitHgh.",".$bitHgh.",".$bitLow.",".$bitLow.",";
    $seqHgh=$bitHgh.",".$bitLow.",".$bitHgh.",".$bitLow.",";
    $bits=$device->address->masterdip;
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {   
        $bit=substr($bits,$i,1);
        if($bit=="0") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    $msgM=$msg;
    $bits=$device->address->slavedip;
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {
        $bit=substr($bits,$i,1);
        if($bit=="0") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    $msgS=$msg;
    if($action=="ON") {
        return $HEAD.$bitLow.",".$msgM.$msgS.$bitHgh.",".$AN.$TAIL;
    } else {
        return $HEAD.$bitLow.",".$msgM.$msgS.$bitHgh.",".$AUS.$TAIL;
    }
}   

function connair_create_msg_intertechno($device, $action) {
    debug("Create Gateway Message for Intertechno device='".(string)$device->id."' action='".(string)$action."'");  
    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip ist ungültig für device id ".$device->id."\n";
        return;
    }
    $sA=0;
    $sG=0;
    $sRepeat=12;
    $sPause=11125;
    $sTune=89;
    $sBaud="#baud#";
    $sSpeed=32; //erfahrung aus dem Forum auf 32 stellen http://forum.power-switch.eu/viewtopic.php?f=15&t=146
    $uSleep=800000;
    $HEAD="TXP:$sA,$sG,$sRepeat,$sPause,$sTune,$sBaud,";
    $TAIL=",1,$sSpeed,;";
    $AN="12,4,4,12,12,4";
    $AUS="12,4,4,12,4,12";
    $bitLow=4;
    $bitHgh=12;
    $seqLow=$bitHgh.",".$bitHgh.",".$bitLow.",".$bitLow.",";
    $seqHgh=$bitHgh.",".$bitLow.",".$bitHgh.",".$bitLow.",";
    $msgM="";
    switch (strtoupper($device->address->masterdip)) {
        case "A":
            $msgM=$seqHgh.$seqHgh.$seqHgh.$seqHgh;
            break;
        case "B":
            $msgM=$seqLow.$seqHgh.$seqHgh.$seqHgh;
            break;   
        case "C":
            $msgM=$seqHgh.$seqLow.$seqHgh.$seqHgh;
            break; 
        case "D":
            $msgM=$seqLow.$seqLow.$seqHgh.$seqHgh;
            break;
        case "E":
            $msgM=$seqHgh.$seqHgh.$seqLow.$seqHgh;
            break;
        case "F":
            $msgM=$seqLow.$seqHgh.$seqLow.$seqHgh;
            break;
        case "G":
            $msgM=$seqHgh.$seqLow.$seqLow.$seqHgh;
            break;
        case "H":
            $msgM=$seqLow.$seqLow.$seqLow.$seqHgh;
            break;
        case "I":
            $msgM=$seqHgh.$seqHgh.$seqHgh.$seqLow;
            break;
        case "J":
            $msgM=$seqLow.$seqHgh.$seqHgh.$seqLow;
            break;
        case "K":
            $msgM=$seqHgh.$seqLow.$seqHgh.$seqLow;
            break;
        case "L":
            $msgM=$seqLow.$seqLow.$seqHgh.$seqLow;
            break;
        case "M":
            $msgM=$seqHgh.$seqHgh.$seqLow.$seqLow;
            break;
        case "N":
            $msgM=$seqLow.$seqHgh.$seqLow.$seqLow;
            break;
        case "O":
            $msgM=$seqHgh.$seqLow.$seqLow.$seqLow;
            break;
        case "P":
            $msgM=$seqLow.$seqLow.$seqLow.$seqLow;
            break;
    }
    $msgS="";   
    switch ($device->address->slavedip){
        case "1":
            $msgS=$seqHgh.$seqHgh.$seqHgh.$seqHgh;
            break;
        case "2":
            $msgS=$seqLow.$seqHgh.$seqHgh.$seqHgh;
            break;   
        case "3":
            $msgS=$seqHgh.$seqLow.$seqHgh.$seqHgh;
            break; 
        case "4":
            $msgS=$seqLow.$seqLow.$seqHgh.$seqHgh;
            break;
        case "5":
            $msgS=$seqHgh.$seqHgh.$seqLow.$seqHgh;
            break;
        case "6":
            $msgS=$seqLow.$seqHgh.$seqLow.$seqHgh;
            break;
        case "7":
            $msgS=$seqHgh.$seqLow.$seqLow.$seqHgh;
            break;
        case "8":
            $msgS=$seqLow.$seqLow.$seqLow.$seqHgh;
            break;
        case "9":
            $msgS=$seqHgh.$seqHgh.$seqHgh.$seqLow;
            break;
        case "10":
            $msgS=$seqLow.$seqHgh.$seqHgh.$seqLow;
            break;
        case "11":
            $msgS=$seqHgh.$seqLow.$seqHgh.$seqLow;
            break;
        case "12":
            $msgS=$seqLow.$seqLow.$seqHgh.$seqLow;
            break;
        case "13":
            $msgS=$seqHgh.$seqHgh.$seqLow.$seqLow;
            break;
        case "14":
            $msgS=$seqLow.$seqHgh.$seqLow.$seqLow;
            break;
        case "15":
            $msgS=$seqHgh.$seqLow.$seqLow.$seqLow;
            break;
        case "16":
            $msgS=$seqLow.$seqLow.$seqLow.$seqLow;
            break;
    }
    if($action=="ON") {
        return $HEAD.$bitLow.",".$msgM.$msgS.$seqHgh.$seqLow.$bitHgh.",".$AN.$TAIL;
    } else {
        return $HEAD.$bitLow.",".$msgM.$msgS.$seqHgh.$seqLow.$bitHgh.",".$AUS.$TAIL;
    }
}

/*
system code 10111
Dann in Reihenfolge unit code
A 10000
B 01000
E 00001   

Elro AB440D 200W       TXP:0,0,10,5600,350,25   ,16:
Elro AB440D 300W       TXP:0,0,10,5600,350,25   ,16:
Elro AB440ID           TXP:0,0,10,5600,350,25   ,16:
Elro AB440IS           TXP:0,0,10,5600,350,25   ,16:
Elro AB440L            TXP:0,0,10,5600,350,25   ,16:
Elro AB440WD           TXP:0,0,10,5600,350,25   ,16:
*/
function connair_create_msg_elro($device, $action) {
    debug("Create Gateway Message for Elro device='".(string)$device->id."' action='".(string)$action."'");  
    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip ist ungültig für device id ".$device->id."\n";
        return;
    }
    $sA=0;
    $sG=0;
    $sRepeat=10;
    $sPause=5600;
    $sTune=350;
    $sBaud="#baud#";
    $sSpeed=14;
    $uSleep=800000;
    $HEAD="TXP:$sA,$sG,$sRepeat,$sPause,$sTune,$sBaud,";
    $TAIL="1,$sSpeed,;";
    $AN="1,3,1,3,1,3,3,1,";
    $AUS="1,3,3,1,1,3,1,3,";
    $bitLow=1;
    $bitHgh=3;
    $seqLow=$bitLow.",".$bitHgh.",".$bitLow.",".$bitHgh.",";
    $seqHgh=$bitLow.",".$bitHgh.",".$bitHgh.",".$bitLow.",";
    $bits=$device->address->masterdip;
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {   
        $bit=substr($bits,$i,1);
        if($bit=="1") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    $msgM=$msg;
    $bits=$device->address->slavedip;
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {
        $bit=substr($bits,$i,1);
        if($bit=="1") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    $msgS=$msg;
    if($action=="ON") {
        return $HEAD.$msgM.$msgS.$AN.$TAIL;
    } else {
        return $HEAD.$msgM.$msgS.$AUS.$TAIL;
    }
}

//https://github.com/d-a-n/433-codes/blob/master/database.md
function connair_create_msg_quigg($device, $action){
    debug("Create Gateway Message for Quigg device='".(string)$device->id."' action='".(string)$action."'");  
    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip ist ungültig für device id ".$device->id."\n";
        return;
    }
    
    
    $TAIL="16,;";
    $sA=0;
    $sG=0;
    $sRepeat=5;
    $sPause=65535;
    $sTune=667;
    $sBaud="21";
    $HEAD="TXP:$sA,$sG,$sRepeat,$sPause,$sTune,$sBaud,1,";
    
    $seqLow="2,1,";
    $seqHgh="1,2,";
    
    $bits=decbin($device->address->masterdip);
    if(strlen($bits) < 12){
        for($i = strlen($bits); $i < 12; $i++){
            $bits = '0'.$bits;
        }
    }
    
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {   
        $bit=substr($bits,$i,1);
        if($bit=="1") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    if($action == "ON"){
        switch($device->address->slavedip){
            case"1":
                $slavedip = "00010001";
                break;
            case"2":
                $slavedip = "10010011";
                break;
            case"3":
                $slavedip = "11010010";
                break;
            case"4":
                $slavedip = "01010000";
                break;
        }
    }else{
        switch($device->address->slavedip){
            case"1":
                $slavedip = "00000000";
                break;
            case"2":
                $slavedip = "10000010";
                break;
            case"3":
                $slavedip = "11000011";
                break;
            case"4":
                $slavedip = "01000001";
                break;
        }
    }
    
    $msgM=$msg;
    $bits=$slavedip;
    $msg="";
    for($i=0;$i<strlen($bits);$i++) {
        $bit=substr($bits,$i,1);
        if($bit=="1") {
            $msg=$msg.$seqLow;
        } else {
            $msg=$msg.$seqHgh;
        }
    }
    $msgS=$msg;
    if($action=="ON") {
        return $HEAD.$msgM.$msgS.$TAIL;
    } else {
        return $HEAD.$msgM.$msgS.$TAIL;
    }
    
}

function cul_send($device, $msg) {
    debug("Sending Message to CUL");
    global $debug;
    global $xml;
    global $errormessage;
    $len = strlen($msg);
    $devicesenderid=(string)$device->senderid;
    foreach($xml->culs->cul as $cul) {
        if(!empty($devicesenderid) && (string)$device->senderid != (string)$cul->id) {
            debug("NOT Sending Message to CUL [".$cul->id."]".(string)$cul->device);
            continue;
        }
        debug("Sending Message '".$msg."' to CUL [".$cul->id."]".(string)$cul->device);
        if(is_writable((string)$cul->device)) {
            $handle = fopen((string)$cul->device, "wb");
            if(!$handle) {
                $errormessage.="CUL Device ".(string)$cul->device." ist nicht schreibbar!  \n";
                debug($errormessage);
                echo $errormessage;
                continue;
            }
            fwrite($handle, $msg, $len);
            fclose($handle);
            $errormessage.="Befehl an CUL gesendet  \n";
        } else {
            $errormessage.="CUL Device ".(string)$cul->device." ist nicht schreibbar! \n";
            debug($errormessage);
            echo $errormessage;
        }
    }
}

function cul_create_msg_intertechno($device, $action) {
    debug("Create CUL Message for Intertechno device='".(string)$device->id."' action='".(string)$action."'");  
    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip ist ungültig für device id ".$device->id."\n";
        return;
    }
    $AN="FF";
    $AUS="F0";
    switch (strtoupper($device->address->masterdip)) {
        case "A":
            $msgM="0000";
            break;
        case "B":
            $msgM="F000";
            break;   
        case "C":
            $msgM="0F00";
            break; 
        case "D":
            $msgM="FF00";
            break;
        case "E":
            $msgM="00F0";
            break;
        case "F":
            $msgM="F0F0";
            break;
        case "G":
            $msgM="0FF0";
            break;
        case "H":
            $msgM="FFF0";
            break;
        case "I":
            $msgM="000F";
            break;
        case "J":
            $msgM="F00F";
            break;
        case "K":
            $msgM="0F0F";
            break;
        case "L":
            $msgM="FF0F";
            break;
        case "M":
            $msgM="00FF";
            break;
        case "N":
            $msgM="F0FF";
            break;
        case "O":
            $msgM="0FFF";
            break;
        case "P":
            $msgM="FFFF";
            break;
    }
    $msgS="";   
    switch ($device->address->slavedip){
        case "1":
            $msgS="0000";
            break;
        case "2":
            $msgS="F000";
            break;   
        case "3":
            $msgS="0F00";
            break; 
        case "4":
            $msgS="FF00";
            break;
        case "5":
            $msgS="00F0";
            break;
        case "6":
            $msgS="F0F0";
            break;
        case "7":
            $msgS="0FF0";
            break;
        case "8":
            $msgS="FFF0";
            break;
        case "9":
            $msgS="000F";
            break;
        case "10":
            $msgS="F00F";
            break;
        case "11":
            $msgS="0F0F";
            break;
        case "12":
            $msgS="FF0F";
            break;
        case "13":
            $msgS="00FF";
            break;
        case "14":
            $msgS="F0FF";
            break;
        case "15":
            $msgS="0FFF";
            break;
        case "16":
            $msgS="FFFF";
            break;
    }
    if($action=="ON") {
        return "is".$msgM.$msgS."0F".$AN."\n";
    } else {
        return "is".$msgM.$msgS."0F".$AUS."\n";
    }
}

// Schaltet Geräte via URL Aufruf aus und ein
function switch_url($device, $action) {
    global $debug;
    debug("switch URL for device='".(string)$device->id."' action='".(string)$action."'");

    if(empty($device->address->rawCodeOn)) {
        echo "ERROR: rawCodeOn (URL An) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->rawCodeOff)) {
        echo "ERROR: rawCodeOff (URL Aus) ist ungültig für device id ".$device->id."\n";
        return;
    }

    if($action == "OFF") {
        $url = $device->address->rawCodeOff;
    } else {
        $url = $device->address->rawCodeOn;
    }
    
    debug("calling url: ".$url);
    $payload = file_get_contents($url);
    debug("response from url: ".$payload);
    
    echo "Befehl ausgeführt \n";

    if($debug == "true") {
        echo "\n Antwort: ".$payload;
    }
    
    return "";
}

// Schaltet Computer aus und ein
function switch_computer($device, $action) {
    debug("switch Computer for device='".(string)$device->id."' action='".(string)$action."'");

    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip (PC-IP) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if(empty($device->address->slavedip)) {
        echo "ERROR: slavedip (MAC Adresse) ist ungültig für device id ".$device->id."\n";
        return;
    }

    $IPpc = $device->address->masterdip;

    // MAC Address des lauschenden Computers
    $mac_addr = $device->address->slavedip;

    if($action == "OFF") {
         // Shutdown eines Windows-PC, muss in den Computerrichtlinien für remote erlaubt werden   
         //exec("shutdown.exe -s -f -m \\\\$IPpc -t 30"); // von einem Windowsserver
         //exec("net rpc shutdown -I $IPpc -U gast%");     // von einem LINUXserver   

         //echo "Shutdown ausgeführt für $IPpc \n";
        echo "PC-Shutdown-Funktion DEAKTIVIERT!! \n";
    } else {
         /* 
         Port number auf die der Computer hört.
         Normalerweise zwischen 1-50000. Standard ist 7 or 9.
         */
         $socket_number = "7";

         //Broadcast ip ermitteln
         $pos = strrpos($IPpc,'.');
         if ($pos !== false) {
                 $IPpc = substr($IPpc,0, $pos).".255";
         }
         WakeOnLan($IPpc, $mac_addr, $socket_number);

         echo "Wake on Lan ausgeführt für $IPpc - $mac_addr \n";
    }

    return "";
}

// Weckt Computer über LAN auf (Diese Funktion muss im Bios aktiviert sein)
function WakeOnLan($addr, $mac, $socket_number) {

  debug("sende WOL an mac '$mac' IP '$addr'");

  $addr_byte = explode(':', $mac);
  $hw_addr = '';

  for ($a=0; $a <6; $a++) {
    $hw_addr .= chr(hexdec($addr_byte[$a]));
  }
  $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);
  for ($a = 1; $a <= 16; $a++) {
    $msg .= $hw_addr;
  }

  // UDP Socket erstellen    
  $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

  if ($s == false) {
    echo "Fehler bei socket_create!\n";
    echo "Fehlercode ist '".socket_last_error($s)."' - " . socket_strerror(socket_last_error($s))."\n";
    return FALSE;
  } else {
    // Socket Optionen setzen:
    $opt_ret = socket_set_option($s, SOL_SOCKET, SO_BROADCAST, TRUE);
    if($opt_ret <0) {
      echo "setsockopt() fehlgeschlagen, Fehler: " . strerror($opt_ret) . "\n";
      return FALSE;
    }
    // Paket abschicken
    if(socket_sendto($s, $msg, strlen($msg), 0, $addr, $socket_number)) {
      debug("WOL erfolgreich gesendet!");
      socket_close($s);
      return TRUE;
    } else {
      echo "WOL fehlerhaft! \n";
      return FALSE;
    }
  }
}

function wakeup ($mac_addr, $broadcast) {
    if (!$fp = fsockopen('udp://' . $broadcast, 2304, $errno, $errstr, 2))
        return false;
    $mac_hex = preg_replace('=[^a-f0-9]=i', '', $mac_addr);
    $mac_bin = pack('H12', $mac_hex);
    $data = str_repeat("\xFF", 6) . str_repeat($mac_bin, 16);
    fputs($fp, $data);
    fclose($fp);
    return true;
} 

// Schaltet FritzBox DECT 200 aus und ein
/* function switch_fbdect200($device, $action) {
    debug("switch FritzBox DECT 200 for device='".(string)$device->id."' action='".(string)$action."'");

    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip (FB DECT ID) ist ungültig für device id ".$device->id;
        return;
    }

    $wert=-1;
    if($action == "ON") {
        $wert=1;
    } else if($action == "OFF") {
        $wert=0;
    }
    $newwert=Fritzbox_DECT200_Switch($device->address->masterdip, $wert);
    if($newwert == 1) {
        $newwert="ON";
    } else if($newwert == 0) {
        $newwert="OFF";
    }
    if($newwert == $action) {
        echo "FritzBox DECT 200 wurde geschaltet.";
    } else {
        echo "FritzBox DECT 200 wurde nicht geschaltet: ".$newwert;
    }
    //neuer wert wird zurückgegeben und dann in status gespeichert
    return ($newwert);
} */

function switch_fbdect200($device, $action) {
    debug("switch FritzBox DECT 200 for device='".(string)$device->id."' action='".(string)$action."'");

    if(empty($device->address->masterdip)) {
        echo "ERROR: masterdip (FB DECT AIN) ist ungültig für device id ".$device->id."\n";
        return;
    }

    $wert=-1;
    if($action == "ON") {
        $wert=1;
    } else if($action == "OFF") {
        $wert=0;
    }
    $newwert=Fritzbox_DECT200_Switch($device->address->masterdip, $wert);
    if (strlen($newwert) == 1) {
        if($newwert == 1) {
            $newwert="ON";
        } else if($newwert == 0) {
            $newwert="OFF";
        }
    }
    if($newwert == $action) {
        echo $device->name . " wurde geschaltet: ".$newwert."\n";
    } else {
        echo $device->name . " wurde nicht geschaltet: ".$newwert."\n";
    }
    //neuer wert wird zurückgegeben und dann in status gespeichert
    if ($newwert == "ON" || $newwert == "OFF") return ($newwert);
    else return($wert);
}

function switch_milight($device, $action) {
    global $xml;

    if($device->address->masterdip == "") {
        echo "ERROR: masterdip (MiLight WiFi-Bridge-ID) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->slavedip == "") {
        echo "ERROR: slavedip (Gruppe auf MiLight WiFi-Bridge) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->tx433version == "") {
        echo "ERROR: tx433version (MiLight Lampentyp) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->slavedip < 0 || $device->address->slavedip > 4) {
        echo "ERROR: slavedip (Gruppe auf MiLight WiFi-Bridge) muss zwischen 0-4 liegen für device id ".$device->id."\n";
        return;
    }
    if($device->address->tx433version < 1 || $device->address->tx433version > 2) {
        echo "ERROR: tx433version (MiLight Lampentyp) muss zwischen 1-2 liegen für device id ".$device->id."\n";
        return;
    }

    $MiLightBridge = $xml->xpath("//milightwifis/milightwifi/id[text()='".$device->address->masterdip."']/parent::*");
    $MiLightBridge = $MiLightBridge[0];

    $milightIP = trim((string)$MiLightBridge->address);
    if(!filter_var($milightIP, FILTER_VALIDATE_IP)) {
        $milightIPCheck = @gethostbyname(trim((string)$MiLightBridge->address));
        if($milightIP == $milightIPCheck) {
            $msg="MiLight-Bridge ".$milightIP." is not availible. Check IP or Hostname. \n";
            debug($msg);
            echo $msg;
            return;
        } else {
            debug("Found this IP ".$milightIPCheck." for Gateway ".$milightIP);
            $milightIP = $milightIPCheck;
        }
    }

    $BulbType="";$BulbCmd="";
    if ($device->address->tx433version == "1") $BulbType="WHITE";
    elseif ($device->address->tx433version == "2") $BulbType="RGBW";

    debug("Using MiLight-WiFi-Bridge ID: ".$device->address->masterdip." / IP: ".$milightIP.":".$MiLightBridge->port);
    $milight = new Milight($milightIP,(integer)$MiLightBridge->port);

    if($action == "ON") {
        switch($BulbType){
            case "WHITE":
                $milight->whiteSendOnToGroup((integer)$device->address->slavedip);
                break;
            case "RGBW":
                if ($device->milight->mode != "Farbe" && $device->milight->mode != "Weiß" && $device->milight->mode != "Nacht") {
                	$milight->rgbwSendOnToGroup((integer)$device->address->slavedip);
                }
                break;
        }
    }
    elseif($action == "OFF") {
        switch($BulbType){
            case "WHITE":
                $milight->whiteSendOffToGroup((integer)$device->address->slavedip);
                break;
            case "RGBW":
                $milight->rgbwSendOffToGroup((integer)$device->address->slavedip);
                break;
        }
    }
    unset($milight);
    
    debug("Switch MiLight for device='".(string)$device->id."' action='".(string)$action."' BulbType='".$BulbType."' Bridge-Group='".$device->address->slavedip."' Bulb-Cmd='".$BulbCmd."'");
    echo $device->name . " wurde geschaltet: ".$action."\n";
    return($action);
}

function toggle_milight($id, $cmd, $value) {
    global $xml;
    $DryMode = false;

    $device = $xml->xpath("//devices/device/id[text()='".$id."']/parent::*");
    $device = $device[0];

    if($device->address->masterdip == "") {
        echo "ERROR: masterdip (MiLight WiFi-Bridge-ID) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->slavedip == "") {
        echo "ERROR: slavedip (Gruppe auf MiLight WiFi-Bridge) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->tx433version == "") {
        echo "ERROR: tx433version (MiLight Lampentyp) ist ungültig für device id ".$device->id."\n";
        return;
    }
    if($device->address->slavedip < 0 || $device->address->slavedip > 4) {
        echo "ERROR: slavedip (Gruppe auf MiLight WiFi-Bridge) muss zwischen 0-4 liegen für device id ".$device->id."\n";
        return;
    }
    if($device->address->tx433version < 1 || $device->address->tx433version > 2) {
        echo "ERROR: tx433version (MiLight Lampentyp) muss zwischen 1-2 liegen für device id ".$device->id."\n";
        return;
    }

    $MiLightBridge = $xml->xpath("//milightwifis/milightwifi/id[text()='".$device->address->masterdip."']/parent::*");
    $MiLightBridge = $MiLightBridge[0];

    $milightIP = trim((string)$MiLightBridge->address);
    if(!filter_var($milightIP, FILTER_VALIDATE_IP)) {
        $milightIPCheck = @gethostbyname(trim((string)$MiLightBridge->address));
        if($milightIP == $milightIPCheck) {
            $msg="MiLight-Bridge ".$milightIP." is not availible. Check IP or Hostname. \n";
            debug($msg);
            echo $msg;
            return;
        } else {
            debug("Found this IP ".$milightIPCheck." for Gateway ".$milightIP);
            $milightIP = $milightIPCheck;
        }
    }

    $BulbType="";
    if ($device->address->tx433version == "1") $BulbType="WHITE";
    elseif ($device->address->tx433version == "2") $BulbType="RGBW";

    debug("Using MiLight-WiFi-Bridge ID: ".$device->address->masterdip." / IP: ".$milightIP.":".$MiLightBridge->port);
    $milight = new Milight($milightIP,(integer)$MiLightBridge->port);

    switch($BulbType){
        case "WHITE":
            // NOCH NICHT IMPLEMENTIERT
            break;
        case "RGBW":
            if (!$DryMode) {
                $milight->setRgbwActiveGroup((integer)$device->address->slavedip);
            
                if ($cmd == "SetColor") {
                    $milight->rgbwSetColorHexString(trim($value));
                    $milight->rgbwBrightnessPercent((integer)$device->milight->brightnesscolor,(integer)$device->address->slavedip);
                    $device->milight->color = trim($value);
                    $device->milight->mode = "Farbe";
                }
                elseif ($cmd == "SetBrightness") {
                    $milight->rgbwBrightnessPercent((integer)$value,(integer)$device->address->slavedip);
                    if ($device->milight->mode == "Farbe") $device->milight->brightnesscolor = trim($value);
                    elseif ($device->milight->mode == "Weiß") $device->milight->brightnesswhite = trim($value);
                    elseif ($device->milight->mode == "Programm") $device->milight->brightnessdisco = trim($value);
                }
                elseif ($cmd == "SetToWhite") {
                    $milight->rgbwSetGroupToWhite((integer)$device->address->slavedip);
                    $milight->rgbwBrightnessPercent((integer)$device->milight->brightnesswhite,(integer)$device->address->slavedip);
                    $device->milight->mode = "Weiß";
                }
                elseif ($cmd == "SetToNightMode") {
                    $milight->rgbwSendOffToGroup((integer)$device->address->slavedip);
                    $milight->command("rgbwGroup".(integer)$device->address->slavedip."NightMode");
                    $device->milight->mode = "Nacht";
                }
                elseif ($cmd == "rgbwDiscoMode" || $cmd == "rgbwDiscoSlower" || $cmd == "rgbwDiscoFaster") {
                    if ($cmd == "rgbwDiscoMode" || $device->milight->mode == "Programm") { 
	                    $milight->rgbwSendOnToActiveGroup();
	                    $milight->command(trim($cmd));
                        $device->milight->mode = "Programm";
                        
                        if ($cmd == "rgbwDiscoMode") {
                            sleep(1);
                            $milight->setRgbwActiveGroup((integer)$device->address->slavedip);
                            $milight->rgbwBrightnessPercent((integer)$device->milight->brightnessdisco,(integer)$device->address->slavedip);
	                    }
	                }
                }
            }
            break;
    }
    unset($milight);
    
    debug("MiLight-Command for device='".$id."': Cmd='".$cmd."' / Value='".$value."' - BulbType='".$BulbType."' Bridge-Group='".$device->address->slavedip."'");
    //echo $device->name . " wurde geschaltet: ".$action."  ";
    if (!$DryMode) return("#OK#");
    else return("#".$id."#".$cmd."#".$value."#");
}

function raspiasconnair($device, $action){

    global $debug;

	switch($action) {
		case "ON":
			$action = '1';
                break;
		case "OFF":
			$action = '0';
                break;
	}
	
	$vendor = $device->vendor;
	switch($vendor){
		case "elro":
		
			$pos = strpos( $device->address->slavedip , '1');
			switch($pos){
				case "0":
					$pos = "1";
					break;
				case "1":
					$pos = "2";
					break;
				case "2":
					$pos = "3";
					break;
				case "3":
					$pos = "4";
					break;
				case "4":
					$pos = "5";
					break;
			}
			
			$msg = "sudo /opt/rcswitch-pi/send ". $device->address->masterdip ." ". $pos ." ". $action;
			break;
		case "intertechno":
			
			/************Gruppencode errechnen*************/
			switch($device->address->slavedip){
				case"1":
				case"2":
				case"3":
				case"4":
					$group = "1";
					break;
					
				case"5":
				case"6":
				case"7":
				case"8":
					$group = "2";
					break;
				
				case"9":
				case"10":
				case"11":
				case"12":
					$group = "3";
					break;
				
				case"13":
				case"14":
				case"15":
				case"16":
					$group = "4";
					break;
				default:
					echo"Fehler im Slavedip! \n";
					exit;
			}
			
			
			/************Slavedip in Abhängigkeit zum Gruppencode errechnen*************/
			switch($device->address->slavedip){
				case"1":
				case"5":
				case"9":
				case"13":
					$lslavedip = "1";
					break;
					
				case"2":
				case"6":
				case"10":
				case"14":
					$lslavedip = "2";
					break;
				
				case"3":
				case"7":
				case"11":
				case"15":
					$lslavedip = "3";
					break;
				
				case"4":
				case"8":
				case"12":
				case"16":
					$lslavedip = "4";
					break;
				default:
					echo"Fehler im Slavedip! \n";
					exit;
			}
			
			$msg = "sudo /opt/rcswitch-pi/send ". strtolower($device->address->masterdip) ." ".$group." ". $lslavedip ." ". $action;
			break;
		default:
			$msg = "Nicht unterstütztes Gerät!!";
	}
	
	
	debug("Sending Message '".$msg."' to IO-pin ");
	if($debug == "true") {
		$fail = shell_exec ($msg);
		if(!empty($fail)){
			var_dump($fail);
		}
		 echo "$msg \n";
    }else{
		shell_exec ($msg);
		echo "Befehl gesendet \n";
	}
}

function remote_ssh_exec($device, $action){

    if($action == "OFF") {
        $command = $device->address->rawCodeOff;
    } else {
        $command = $device->address->rawCodeOn;
    }
    $ssh_address = $device->address->ssh_address;
    $ssh_user = $device->address->ssh_user;
    $ssh_password = $device->address->ssh_password;

    $connection = ssh2_connect($ssh_address, 22);
    ssh2_auth_password($connection, $ssh_user , $ssh_password);
    ssh2_exec($connection, $command);
    echo 'Befehl gesendet \n';
}
    
function send_message($device, $action, $ViaTimer = FALSE, $TimerMLMode = "", $TimerMLColor = "", $TimerMLBrightness = "", $ViaAction = FALSE) {
    debug("Send Message for device='".(string)$device->id."' action='".(string)$action."'");
    global $xml;
    $vendor=strtolower($device->vendor);
    switch($vendor) {
        case "computer":
            switch_computer($device, $action);
            //if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = $action;
            //hier nicht connair und cul ansprechen
            return;
        case "url":
            switch_url($device, $action);
            if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = $action;
            //hier nicht connair und cul ansprechen
            return;
        case "fbdect200":
            if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = switch_fbdect200($device, $action);
            //hier nicht connair und cul ansprechen
            return;
        case "ssh":
            remote_ssh_exec($device, $action);
            if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = $action;
            //hier nicht connair und cul ansprechen
            return;
        case "milight":
        	$SMstat=''; $LogMLMode="";
            // Action in BE loggen wenn Action != aktueller Status
        	if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
			// Gerät schalten, Rueckgabe (neuer Status) erst mal zwischenspeichern
            $SMstat = switch_milight($device, $action);			

            // Wenn es eine RGB(W)-Lampe ist UND diese EIN-geschaltet werden soll
            if ($device->address->tx433version == "2" && $action == "ON") {

                // Wenn die Lampe bereits AN ist UND die Action NICHT vom Timer kommt: Modi durchschalten
				if ($device->status == "ON" && $ViaTimer == FALSE) {

					if ($device->milight->mode == "Weiß") {    // Aktueller Modus: WEISS: Auf FARBE schalten und loggen
                        $MR = toggle_milight($device->id,"SetColor",$device->milight->color);
                        $LogMLMode = "Farbe <font color=\"".$device->milight->color."\">●</font> ".$device->milight->brightnesscolor."%";
                    }
					elseif ($device->milight->mode == "Farbe") {   // Aktueller Modus: FARBE: Auf NACHT schalten und loggen
                        $MR = toggle_milight($device->id,"SetToNightMode",'');
                        $LogMLMode = "Nacht";
                    }
					elseif ($device->milight->mode == "Nacht") {   // Aktueller Modus: NACHT: Auf WEISS schalten und loggen
                        $MR = toggle_milight($device->id,"SetToWhite",'');
                        $LogMLMode = "Weiß ● ".$device->milight->brightnesswhite."%";
                    }
                    elseif ($device->milight->mode == "Programm") {   // Aktueller Modus: PROGRAMM: Auf FARBE schalten und loggen
                        $MR = toggle_milight($device->id,"SetColor",$device->milight->color);
                        $LogMLMode = "Farbe <font color=\"".$device->milight->color."\">●</font> ".$device->milight->brightnesscolor."%";
                    }
				}

                // Wenn die Lampe derzeit AUS ist und die Action NICHT vom Timer kommt
                // ODER die Action vom Timer kommt und im Timer kein Modus definiert ist: Gespeicherten Modus setzen
                if ( ($device->status == "OFF" && $ViaTimer == FALSE) || ($ViaTimer == TRUE && $TimerMLMode == "") ) {
                    
                    if ($device->milight->mode == "Weiß") {
                        $MR = toggle_milight($device->id,"SetToWhite",'');
                        $LogMLMode = "Weiß ● ".$device->milight->brightnesswhite."%";
                    }
                    elseif ($device->milight->mode == "Farbe") {
                        //usleep(500000);
                        $MR = toggle_milight($device->id,"SetColor",$device->milight->color);
                        $LogMLMode = "Farbe <font color=\"".$device->milight->color."\">●</font> ".$device->milight->brightnesscolor."%";
                    }
                    elseif ($device->milight->mode == "Nacht") {
                        $MR = toggle_milight($device->id,"SetToNightMode",'');
                        $LogMLMode = "Nacht";
                    }
                    elseif ($device->milight->mode == "Programm") {
                        sleep(1);
                        $MR = toggle_milight($device->id,"SetBrightness",$device->milight->brightnessdisco);
                        $LogMLMode = "Programm ● ".$device->milight->brightnessdisco."%";
                    }
                }

                // Wenn die Action vom Timer kommt und im Timer ein Modus definiert ist: Definierten Modus setzen
                if ($ViaTimer == TRUE && $TimerMLMode != "") {

                    if ($TimerMLMode == "Weiß") {   // Modus WEISS im Timer definiert: Setzen und loggen
                    	$MR = toggle_milight($device->id,"SetToWhite",'');
                        $LogMLMode = "Weiß ● ";
                    	
                    	if ($TimerMLBrightness != "") {    // Wurde auch eine Helligkeit definiert? Wenn JA: Diese setzen
                    		$MRB = toggle_milight($device->id,"SetBrightness",$TimerMLBrightness);
                    		$LogMLMode .= $TimerMLBrightness."%";
                    	}
                    	else $LogMLMode .= $device->milight->brightnesswhite."%";
                    }
                    elseif ($TimerMLMode == "Farbe") {  // Modus FARBE im Timer definiert: Setzen und loggen
                       	if ($TimerMLColor != "") {  // Wurde auch eine Farbe definiert? Wenn JA: Diese setzen, sonst die gespeicherte Farbe setzen
                    		$MR = toggle_milight($device->id,"SetColor",$TimerMLColor);
                    		$LogMLMode = "Farbe <font color=\"".$TimerMLColor."\">●</font> ";
                    	}
                    	else {
                    		$MR = toggle_milight($device->id,"SetColor",$device->milight->color);
                    		$LogMLMode = "Farbe <font color=\"".$device->milight->color."\">●</font> ";
                    	}

                    	if ($TimerMLBrightness != "") {    // Wurde auch eine Helligkeit definiert? Wenn JA: Setzen
                    		$MRB = toggle_milight($device->id,"SetBrightness",$TimerMLBrightness);
                    		$LogMLMode .= $TimerMLBrightness."%";
                    	}
                    	else $LogMLMode .= $device->milight->brightnesscolor."%";
                    }
                    elseif ($TimerMLMode == "Nacht") {  // Modus NACHT im Timer definiert: Setzen und loggen
                        $MR = toggle_milight($device->id,"SetToNightMode",'');
                        $LogMLMode = "Nacht";
                    }
                }
			}

            // Rueckgabe aus Schaltvorgang für Geraet speichern
			$device->status = $SMstat;
            // BE-Log schreiben
			if ($LogMLMode != "") $retLog = LogToBackend('info',$device->name.' ('.$device->room.') switched to mode '.strtoupper($LogMLMode),'false',$ViaTimer,$ViaAction);
            //hier nicht connair und cul ansprechen
            return;
    }
    //wenn connairs configuriert senden
    if(@count($xml->connairs->children()) > 0) {
        $msg="";
        switch($vendor) {
            case "raw":
                if ($action=="ON") {
                    $msg = $device->address->rawCodeOn;
                } else {
                    $msg = $device->address->rawCodeOff;
                }
                break;
            case "brennenstuhl":
                $msg = connair_create_msg_brennenstuhl($device, $action);
                break;
            case "intertechno":
                $msg = connair_create_msg_intertechno($device, $action);
                break;
            case "elro":
                $msg = connair_create_msg_elro($device, $action);
                break;
            case "quigg":
                $msg = connair_create_msg_quigg($device, $action);
                break;              
        }
        if(!empty($msg)) {
            connair_send($device, $msg, $action);
			if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = $action;
        }
    }
    //wenn CULS Configuriert auch über diese senden
    if(@count($xml->culs->children()) > 0) {
        $msg="";
        switch($vendor) {
            case "intertechno":
                $msg = cul_create_msg_intertechno($device, $action);
                break;
        }
        if(!empty($msg)) {
            cul_send($device, $msg);
			if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
            $device->status = $action;
        }
    }

	#Wenn Configuriert dann über IO senden
	if($xml->global->sendRaspi == "true" AND $device->senderid == "5"){
		raspiasconnair($device, $action);
		if ($action != $device->status) $retLog = LogToBackend('info',$device->name.' ('.$device->room.') turned '.strtoupper($action),'false',$ViaTimer,$ViaAction);
		$device->status = $action;
	}
}
?>
