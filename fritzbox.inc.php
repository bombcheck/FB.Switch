<?php
if((!isset($directaccess)) OR (!$directaccess)) die();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

function Fritzbox_DECT200_Switch($deviceain, $wert) {
    global $xml;
    $SID=Fritzbox_login();
    
    $FBnet_BEurl = $xml->backend->url;
    if ($FBnet_BEurl != "") {
        if ($wert == 1) $SV = 0;
        elseif ($wert == 0) $SV = 1;
        $task="ToggleActor";$params=$deviceain."|".$SV."|".$xml->global->FBnetDeviceID."|FALSE";
        debug("Fritzbox_DECT200 (via BE-Call): ".$params);

        $ret = file_get_contents($FBnet_BEurl."?task=".$task."&sid=".$SID."&params=".$params);
        if ($ret === FALSE) $ret = "Backend unreachable!";
        if (strpos($ret,'#OK#') !== FALSE) return($wert);
        else return($ret);
    }
    else {
        $fritzbox_address = $xml->fritzbox->address;
        $SwitchCMD="";
        if ($wert == 1) $SwitchCMD="setswitchon";
        elseif ($wert == 0) $SwitchCMD="setswitchoff";
    
        debug("Fritzbox_DECT200 (via Direct-Call): ".$SID);
        if($SID <> "Fehler: Login fehlgeschlagen" && $SID != "") {
            $Value=Fritzbox_GetSetHAactor($fritzbox_address,$SwitchCMD,$SID,$deviceain);
        } else {
            $Value=$SID;
        }
        return($Value);
    }        
}

/* function Fritzbox_DECT200_Energie($deviceid, $Zeit) {
    global $xml;
    $fritzbox_address = $xml->fritzbox->address;
    $Daten="";
    $SID=Fritzbox_login();
    if ($SID <> "Fehler: Login fehlgeschlagen") {
        switch($Zeit) {
            case 1:      // Abfrage der Messwerte der letzten 10 min
                $Daten= file("http://".$fritzbox_address."/net/home_auto_query.lua?sid=". $SID. "&command=EnergyStats_10&id=". $deviceid. "&xhr=1");
                break;
            case 2:      // Abfrage der Messwerte der letzten 24h
                $Daten= file("http://".$fritzbox_address."/net/home_auto_query.lua?sid=". $SID. "&command=EnergyStats_24h&id=". $deviceid. "&xhr=1");
                break;
        }
        if($Daten <>"") {
            $Daten=explode('" , "', $Daten[1]);
            $x=count($Daten)-1;
            $temp=explode('" ,"', $Daten[$x]);
            foreach ($temp as $tem) {
                $Daten[$x]=$tem;
                $x++;
            }
        } else {
            $Daten[0]="Keine Werte vorhanden";
        }
        return ($Daten);
    }
} */

function Fritzbox_GetHAactorsInfoXML()
{
    global $xml;
    $SID=Fritzbox_login();
    $fritzbox_address = $xml->fritzbox->address;

    $URL = "http://".$fritzbox_address."/webservices/homeautoswitch.lua?switchcmd=".urlencode('getdevicelistinfos')."&sid=".urlencode($SID);
    $ret = file_get_contents($URL);
    if (trim($ret) == "inval" || $ret === FALSE || $ret == "") $ret = "-1";
    return trim($ret);
}

function Fritzbox_GetHAactorDataFromXML($XML,$ain,$mode)
{
    $FBdectVal=-1000;
    if ($XML == -1) return $FBdectVal;

    $FBdevices = new SimpleXMLElement($XML);
    if ($mode == "id") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices['id'][0];
            }
        }
    } elseif ($mode == "fwversion") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=$devices['fwversion'][0];
            }
        }
    } elseif ($mode == "manufacturer") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=$devices['manufacturer'][0];
            }
        }
    } elseif ($mode == "productname") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=$devices['productname'][0];
            }
        }
    } elseif ($mode == "present") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->present[0];
            }
        }
    } elseif ($mode == "name") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=$devices->name[0];
            }
        }
    } elseif ($mode == "state") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->switch->state[0];
            }
        }
    } elseif ($mode == "lock") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->switch->lock[0];
            }
        }
    } elseif ($mode == "power") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->powermeter->power[0];
            }
        }
    } elseif ($mode == "energy") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->powermeter->energy[0];
            }
        }
    } elseif ($mode == "temperature") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->temperature->celsius[0];
            }
        }
    } elseif ($mode == "tempoffset") {
        foreach ($FBdevices->device as $devices) {
            if (str_replace(" ", "", $devices['identifier'][0]) == $ain) {
                $FBdectVal=(int)$devices->temperature->offset[0];
            }
        }
    } 

    unset($FBdevices);
    return trim($FBdectVal);
}

function Fritzbox_DECT200_Energie($deviceAIN) {
        global $xml;
        $SID=Fritzbox_login();
        $fritzbox_address = $xml->fritzbox->address;
        $SwitchCMD="getswitchenergy";
    
        if($SID <> "Fehler: Login fehlgeschlagen" && $SID != "") {
            $Value=Fritzbox_GetSetHAactor($fritzbox_address,$SwitchCMD,$SID,$deviceAIN);
        } else {
            $Value=$SID;
        }
        return($Value);
}

function Fritzbox_DECT200_Power($deviceAIN) {
        global $xml;
        $SID=Fritzbox_login();
        $fritzbox_address = $xml->fritzbox->address;
        $SwitchCMD="getswitchpower";
    
        if($SID <> "Fehler: Login fehlgeschlagen" && $SID != "") {
            $Value=Fritzbox_GetSetHAactor($fritzbox_address,$SwitchCMD,$SID,$deviceAIN);
        } else {
            $Value=$SID;
        }
        return($Value);
}

function Fritzbox_DECT200_SwitchState($deviceAIN) {
        global $xml;
        $SID=Fritzbox_login();
        $fritzbox_address = $xml->fritzbox->address;
        $SwitchCMD="getswitchstate";
    
        if($SID <> "Fehler: Login fehlgeschlagen" && $SID != "") {
            $Value=Fritzbox_GetSetHAactor($fritzbox_address,$SwitchCMD,$SID,$deviceAIN);
        } else {
            $Value=$SID;
        }
        return($Value);
}

function Fritzbox_login() {
    global $xml;
    $FBnet_SIDsource = $xml->backend->sidsource;

    if ($FBnet_SIDsource != "") {
        $SID = file_get_contents($FBnet_SIDsource);
        return $SID;
    }
    else {
        $fritzbox_address = $xml->fritzbox->address;
        $fritzbox_username = $xml->fritzbox->username;
        $fritzbox_password = $xml->fritzbox->password;
 
        $ch = curl_init('http://'.$fritzbox_address.'/login_sid.lua');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $login = curl_exec($ch);
        $session_status_simplexml = simplexml_load_string($login);
        if ($session_status_simplexml->SID != '0000000000000000') {
            $SID = $session_status_simplexml->SID;
        } else {
            $challenge = $session_status_simplexml->Challenge;
            $response = $challenge . '-' . md5(mb_convert_encoding($challenge . '-' . $fritzbox_password, "UCS-2LE", "UTF-8"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "response={$response}&page=/login_sid.lua&username={$fritzbox_username}");
            $sendlogin = curl_exec($ch);
            $session_status_simplexml = simplexml_load_string($sendlogin);

            if ($session_status_simplexml->SID != '0000000000000000') {
                $SID = $session_status_simplexml->SID;
            } else {
                $SID= "Fehler: Login fehlgeschlagen";
            }
        }
        curl_close($ch);
        return $SID;
    }
}

function Fritzbox_GetSetHAactor($host,$cmd,$sid,$ain)
{
    $URL = "http://".$host."/webservices/homeautoswitch.lua?switchcmd=".urlencode($cmd)."&sid=".urlencode($sid);
    if ($ain != "") $URL .= "&ain=".urlencode($ain);
    $ret = file_get_contents($URL);
    if (trim($ret) == "inval") $ret = "-1";
    return trim($ret);
}
?>