<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("config.php");

$r_action = (string)$_POST['action'];
$r_id = (string)$_POST['id'];
$r_name = (string)$_POST['name'];
$r_room = (string)$_POST['room'];
$r_vendor = (string)$_POST['vendor'];
$r_masterdip = (string)$_POST['masterdip'];
$r_slavedip = (string)$_POST['slavedip'];
$r_sendCommandsOnlyOnce = (string)$_POST['sendCommandsOnlyOnce'];
$r_rawCodeOn = (string)$_POST['rawCodeOn'];
$r_rawCodeOff = (string)$_POST['rawCodeOff'];
$r_tx433version = (string)$_POST['tx433version'];
$r_btnLabelOn = (string)$_POST['btnLabelOn'];
$r_btnLabelOff = (string)$_POST['btnLabelOff'];
$r_senderid = (string)$_POST['senderid'];
$r_ssh_address = (string)$_POST['ssh_address'];
$r_ssh_user = (string)$_POST['ssh_user'];
$r_ssh_password = (string)$_POST['ssh_password'];
$r_showDeviceStatus = (string)$_POST['showDeviceStatus'];

switch ($r_action) {

    case "add":
		

		$newid=1;
		foreach($xml->devices->device as $device) {
			$oldid=(integer)$device->id;
			if($oldid >= $newid) {
				$newid = $oldid + 1;
			}
		}
			
        $newdevice = $xml->devices->addChild('device');
        
        if(!empty($r_btnLabelOn)) {
            $newdevice->addAttribute('buttonLabelOn', $r_btnLabelOn);
        }
        if(!empty($r_btnLabelOff)) {
            $newdevice->addAttribute('buttonLabelOff', $r_btnLabelOff);
        }
        
        $newdevice->addChild('id', $newid);
        $newdevice->addChild('name', $r_name);
        $newdevice->addChild('vendor', $r_vendor);
        $newdevice->addChild('sendCommandsOnlyOnce', $r_sendCommandsOnlyOnce);

        $newdeviceaddress = $newdevice->addChild('address');
        $newdeviceaddress->addChild('masterdip', $r_masterdip);
        $newdeviceaddress->addChild('slavedip', $r_slavedip);
        $newdeviceaddress->addChild('tx433version', $r_tx433version);
        
        if($r_vendor == "url") {
            $newdeviceaddress->addChild('rawCodeOn', str_replace('&','&amp;',$r_rawCodeOn));
            $newdeviceaddress->addChild('rawCodeOff', str_replace('&','&amp;',$r_rawCodeOff));
        } else {
            $newdeviceaddress->addChild('rawCodeOn', $r_rawCodeOn);
            $newdeviceaddress->addChild('rawCodeOff', $r_rawCodeOff);
        }
		if(!empty($r_ssh_address)){
			$newdeviceaddress->addChild('ssh_address', $r_ssh_address);
			$newdeviceaddress->addChild('ssh_user', $r_ssh_user);
			$newdeviceaddress->addChild('ssh_password', $r_ssh_password);
		}
		
        $newdevice->addChild('room', $r_room);
        $newdevice->addChild('senderid', $r_senderid);
        $newdevice->addChild('status', 'OFF');
        $newdevice->addChild('showDeviceStatus', $r_showDeviceStatus);

        if(check_device($newdevice)) {
            echo "ok";
            config_save();
        }
		break;
    
    case "edit":
	
        $xpath='//device/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0];
		

		if(!empty($r_btnLabelOn)) {
            #$parent[0]->addAttribute('buttonLabelOn', $r_btnLabelOn);
			$parent[0]['buttonLabelOn']= $r_btnLabelOn;
        }else{
			unset ($parent[0]['buttonLabelOn']);
		}
        if(!empty($r_btnLabelOff)) {
            #$parent[0]->addAttribute('buttonLabelOff', $r_btnLabelOff);
			$parent[0]['buttonLabelOff']= $r_btnLabelOff;
        }else{
			unset ($parent[0]['buttonLabelOff']);
		}
		$parent[0]->name = $r_name;
		$parent[0]->vendor = $r_vendor;
		$parent[0]->address->masterdip = $r_masterdip;
        $parent[0]->address->slavedip = $r_slavedip;
        $parent[0]->address->tx433version = $r_tx433version;
        $parent[0]->sendCommandsOnlyOnce = $r_sendCommandsOnlyOnce;
		if($r_vendor == "url") {
            $parent[0]->address->rawCodeOn = str_replace('&','&amp;',$r_rawCodeOn);
            $parent[0]->address->rawCodeOff = str_replace('&','&amp;',$r_rawCodeOff);
        } else {
            $parent[0]->address->rawCodeOn = $r_rawCodeOn;
            $parent[0]->address->rawCodeOff = $r_rawCodeOff;
        }
        if(!empty($r_ssh_address)){
			$parent[0]->address->ssh_address = $r_ssh_address;
			$parent[0]->address->ssh_user = $r_ssh_user;
			$parent[0]->address->ssh_password = $r_ssh_password;
		}
        $parent[0]->room = $r_room;
        $parent[0]->senderid = $r_senderid;
        $parent[0]->status = 'OFF';
        $parent[0]->showDeviceStatus = $r_showDeviceStatus;

        if(check_device($parent[0])) {
            echo "ok";
            config_save();
        }

        break;
    
    case "delete":
        $xpath='//device/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0]; 
        unset($parent[0]);
        echo "ok";
        config_save();
	break;
    
    default:
        echo "unsupported";
        break;
}
?>
