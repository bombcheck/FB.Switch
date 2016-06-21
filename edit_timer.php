<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require("config.php");

$r_action = (string)$_POST['action'];
$r_id = (string)$_POST['id'];

switch ($r_action) {

    case "add":


	$r_active = (string)$_POST['active'];
	$r_type = (string)$_POST['timertype'];
	$r_pingstatus = (string)$_POST['pingstatus'];
	$r_invertSwitchOnNoPing = (string)$_POST['invertSwitchOnNoPing'];
	$r_usage = (string)$_POST['usage'];
	$r_pingto = (string)$_POST['pingto'];
	
	switch($_POST['timertype']) {
	    case "device":
		$typeid = (string)$_POST['typeiddevice'];
		break;
	
	    case "group":
		$typeid = (string)$_POST['typeidgroup'];
		break;
	
	    case "room":
		$typeid = (string)$_POST['typeidroom'];
		break;
	
	    default:
		echo "Ungültiger Typer Typ!";
		exit;
	}

	$day = '_______';
	foreach ($_POST['timerday'] as $keyday) {
	    switch($keyday) {
		case 0:
		    $day[$keyday]='M';
		    break;
		case 1:
		    $day[$keyday]='D';
		    break;
		case 2:
		    $day[$keyday]='M';
		    break;
		case 3:
		    $day[$keyday]='D';
		    break;
		case 4:
		    $day[$keyday]='F';
		    break;
		case 5:
		    $day[$keyday]='S';
		    break;
		case 6:
		    $day[$keyday]='S';
		    break;
	    }
	}

	switch($_POST['OnTimerType']) {
	    case "A":
		$onHH=$_POST['OnTimerHH'];
		if($onHH<0 && $onHH>23) {
		    echo "Falsche Stunden";
		    exit;
		}
		$onMM=$_POST['OnTimerMM'];
		if($onHH<0 && $onHH>59) {
		    echo "Falsche Minuten";
		    exit;
		}
		$timerOn = $onHH.':'.$onMM;
		break;
	
	    case "SU":
	    case "SD":
		$timerOn = (string)$_POST['OnTimerType'];
		break;
	
	    default:
	    case "M":
		$timerOn = "";
		break;   
	}
	$r_timeronoffset=intval($_POST['timeronoffset']);
	switch($_POST['OffTimerType']) {
	    case "A":
		$offHH=$_POST['OffTimerHH'];
		if($offHH<0 && $offHH>23) {
		    echo "Falsche Stunden";
		    exit;
		}
		$offMM=$_POST['OffTimerMM'];
		if($offHH<0 && $offHH>59) {
		    echo "Falsche Minuten";
		    exit;
		}
		$timerOff = $offHH.':'.$offMM;
		break;
	
	    case "SU":
	    case "SD":
		$timerOff = (string)$_POST['OffTimerType'];
		break;
	
	    default:
	    case "M":
		$timerOff = "";
		break;   
	}
	$r_timeroffoffset=intval($_POST['timeroffoffset']);

        $newid=1;
        foreach($xml->timers->timer as $timer) {
            $oldid=(integer)$timer->id;
            if($oldid >= $newid) {
                $newid = $oldid + 1;
            }
        }
        $newtimer = $xml->timers->addChild('timer');
        $newtimer->addChild('id', $newid);
        $newtimer->addChild('active', $r_active);
        $newtimer->addChild('type', $r_type);
        $newtimer->addChild('typeid', $typeid);
        $newtimer->addChild('day', $day);
		$newtimer->addChild('pingstatus', $r_pingstatus);
		$newtimer->addChild('invertSwitchOnNoPing', $r_invertSwitchOnNoPing);
		$newtimer->addChild('usage', $r_usage);
		$newtimer->addChild('pingto', $r_pingto);
		
        $timerOnXml=$newtimer->addChild('timerOn', $timerOn);
        if(!empty($r_timeronoffset)) {
            $timerOnXml->addAttribute('offset', $r_timeronoffset);
        }
        $timerOffXml=$newtimer->addChild('timerOff', $timerOff);
        if(!empty($r_timeroffoffset)) {
            $timerOffXml->addAttribute('offset', $r_timeroffoffset);
        }
    
        if(check_timer($newtimer)) {
            echo "ok";
            config_save();
        }
    
        break;
    
    case "edit":
		
		$r_active = (string)$_POST['active'];
		$r_type = (string)$_POST['timertype'];
		$r_pingstatus = (string)$_POST['pingstatus'];
		$r_invertSwitchOnNoPing = (string)$_POST['invertSwitchOnNoPing'];
		$r_usage = (string)$_POST['usage'];
		$r_pingto = (string)$_POST['pingto'];
		
		switch($_POST['timertype']) {
			case "device":
			$typeid = (string)$_POST['typeiddevice'];
			break;
		
			case "group":
			$typeid = (string)$_POST['typeidgroup'];
			break;
		
			case "room":
			$typeid = (string)$_POST['typeidroom'];
			break;
		
			default:
			echo "Ungültiger Typer Typ!";
			exit;
		}

		$day = '_______';
		foreach ($_POST['timerday'] as $keyday) {
			switch($keyday) {
			case 0:
				$day[$keyday]='M';
				break;
			case 1:
				$day[$keyday]='D';
				break;
			case 2:
				$day[$keyday]='M';
				break;
			case 3:
				$day[$keyday]='D';
				break;
			case 4:
				$day[$keyday]='F';
				break;
			case 5:
				$day[$keyday]='S';
				break;
			case 6:
				$day[$keyday]='S';
				break;
			}
		}

		switch($_POST['OnTimerType']) {
			case "A":
			$onHH=$_POST['OnTimerHH'];
			if($onHH<0 && $onHH>23) {
				echo "Falsche Stunden";
				exit;
			}
			$onMM=$_POST['OnTimerMM'];
			if($onHH<0 && $onHH>59) {
				echo "Falsche Minuten";
				exit;
			}
			$timerOn = $onHH.':'.$onMM;
			break;
		
			case "SU":
			case "SD":
			$timerOn = (string)$_POST['OnTimerType'];
			break;
		
			default:
			case "M":
			$timerOn = "";
			break;   
		}
		$r_timeronoffset=intval($_POST['timeronoffset']);
		switch($_POST['OffTimerType']) {
			case "A":
			$offHH=$_POST['OffTimerHH'];
			if($offHH<0 && $offHH>23) {
				echo "Falsche Stunden";
				exit;
			}
			$offMM=$_POST['OffTimerMM'];
			if($offHH<0 && $offHH>59) {
				echo "Falsche Minuten";
				exit;
			}
			$timerOff = $offHH.':'.$offMM;
			break;
		
			case "SU":
			case "SD":
			$timerOff = (string)$_POST['OffTimerType'];
			break;
		
			default:
			case "M":
			$timerOff = "";
			break;   
		}
		$r_timeroffoffset=intval($_POST['timeroffoffset']);


		$xpath='//timer/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath);
        $parent = $res[0];
		$parent[0]->active = $r_active;
		$parent[0]->type = $r_type;
		$parent[0]->typeid = $typeid;
        $parent[0]->day = $day;
        $parent[0]->pingstatus = $r_pingstatus;
        $parent[0]->invertSwitchOnNoPing = $r_invertSwitchOnNoPing;
        $parent[0]->usage = $r_usage;
        $parent[0]->pingto = $r_pingto;
        $parent[0]->timerOn = $timerOn;
		if(!empty($r_timeronoffset)) {
            //$timerOnXml->addAttribute('offset', $r_timeronoffset);
            $parent[0]->timerOn['offset'] = $r_timeronoffset;
        }else{
			unset($parent[0]->timerOn['offset']);
		}
        $parent[0]->timerOff = $timerOff;
		if(!empty($r_timeroffoffset)) {
            $parent[0]->timerOff['offset'] = $r_timeroffoffset;
        }else{
			unset($parent[0]->timerOff['offset']);
		}
		
        echo "ok";
       	$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$dom->save($CONFIG_FILENAME);
		
        break;
    
    case "on":
        $xpath='//timer/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0]; 
        $parent[0]->active="on";
        echo "ok";
        config_save();
	break;
    
    case "off":
        $xpath='//timer/id[.="'.$r_id.'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0]; 
        $parent[0]->active="off";
        echo "ok";
        config_save();
	break;
    
    case "delete":
        $xpath='//timer/id[.="'.$r_id.'"]/parent::*';
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
