<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("config.php");

$r_id = (string)$_POST['id'];
$r_action= (string)$_POST['action'];
$r_name = (string)$_POST['name'];
$r_pingto = (string)$_POST['ip'];
$r_theme = (string)$_POST['theme'];
$r_theme_bg = (string)$_POST['theme_bg'];
$r_ShowSettingsMenue = (string)$_POST['ShowSettingsMenue'];
$r_ShowFBdect200EnergyData = (string)$_POST['ShowFBdect200EnergyData'];
$r_AutoRefreshDeviceData = (string)$_POST['AutoRefreshDeviceData'];
$r_favoritgroups = $_POST['favoritgroups'];
$r_favoritactions = $_POST['favoritactions'];
$r_favoritdevices = $_POST['favoritdevices'];

switch ($r_action) {

    case "add":
		$newid=1;
		foreach($xml->persons->person as $person) {
			$oldid=(integer)$person->id;
			if($oldid >= $newid) {
				$newid = $oldid + 1;
			}
		}
		
		$r_favoritdevices = implode(",", $r_favoritdevices);
		$r_favoritgroups = implode(",", $r_favoritgroups);
		$r_favoritactions = implode(",", $r_favoritactions);
		
        $newdevice = $xml->persons->addChild('person');
        $newdevice->addChild('id', $newid);
        $newdevice->addChild('name', $r_name);
        $newdevice->addChild('pingto', $r_pingto);
        $newdevice->addChild('theme', $r_theme);
        $newdevice->addChild('theme_bg', $r_theme_bg);
        $newdevice->addChild('ShowSettingsMenue', $r_ShowSettingsMenue);
        $newdevice->addChild('ShowFBdect200EnergyData', $r_ShowFBdect200EnergyData);
        $newdevice->addChild('AutoRefreshDeviceData', $r_AutoRefreshDeviceData);
        $newdevice->addChild('favoritgroups', $r_favoritgroups);
        $newdevice->addChild('favoritactions', $r_favoritactions);
        $newdevice->addChild('favoritdevices', $r_favoritdevices);
		
        echo "ok";
        config_save();
		break;
		
	case "edit":
	
	    $xpath='//person/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0];

		$r_favoritdevices = implode(",", $r_favoritdevices);
		$r_favoritgroups = implode(",", $r_favoritgroups);
		$r_favoritactions = implode(",", $r_favoritactions);
		
		$parent[0]->id = $r_id;
		$parent[0]->name = $r_name;
        $parent[0]->pingto = $r_pingto;
        if ($r_pingto == "") $parent[0]->status = "";
        $parent[0]->theme = $r_theme;
        $parent[0]->theme_bg = $r_theme_bg;
        $parent[0]->ShowSettingsMenue = $r_ShowSettingsMenue;
        $parent[0]->ShowFBdect200EnergyData = $r_ShowFBdect200EnergyData;
        $parent[0]->AutoRefreshDeviceData = $r_AutoRefreshDeviceData;
        $parent[0]->favoritgroups = $r_favoritgroups;
        $parent[0]->favoritactions = $r_favoritactions;
        $parent[0]->favoritdevices = $r_favoritdevices;

        echo "ok";
	    config_save();
        break;
    
    case "delete":
        $xpath='//person/id[.="'.$r_id.'"]/parent::*';
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
