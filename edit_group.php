<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("config.php");

$r_action = (string)$_POST['action'];
$r_id = (string)$_POST['id'];
$r_name = (string)$_POST['Groupname'];

switch ($r_action) {

    case "add":
		$newid=1;
		foreach($xml->groups->group as $group) {
			$oldid=(integer)$group->id;
			if($oldid >= $newid) {
				$newid = $oldid + 1;
			}
		}
			
        $newgroup = $xml->groups->addChild('group');
        
		
        $newgroup->addChild('id', $newid);
        $newgroup->addChild('name', $r_name);
		unset($_POST['action']);
		unset($_POST['id']);
		unset($_POST['Groupname']); # Entfernen
		
		$string = array_keys($_POST); # Array umdrehen
		
		for($i=0; $i<count($string); $i++){
			$newgroup->addChild('deviceid', $string[$i]);
		}

		echo'ok';
        config_save();
        break;
		
    case "edit":
		$xpath='//group/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath);
		$parent = $res[0];
		$parent[0]->name = $r_name;
		unset($_POST['action']);
		unset($_POST['id']);
		unset($_POST['Groupname']); # Entfernen
		$string = array_keys($_POST); # Array umdrehen
        unset($parent[0]->deviceid);
		foreach($string as $device){
			$parent[0]->addChild('deviceid', $device);
		}
        echo "ok";
        config_save();
        break;
    
    case "delete":
        $xpath='//group/id[.="'.$r_id.'"]/parent::*';
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
