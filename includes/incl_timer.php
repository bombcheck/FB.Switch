<style type="text/css">

.timer_activ_on  {
    color: #6AB638;
}

.timer_activ_off  {
    color: #B63737;
}

</style>
<div data-role="page" id="timers" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Timer]">

    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e" >Favoriten</a>
            <!--a href="#my-header" data-rel="close" data-role="button" data-theme="b">Favoriten</a-->
            <a href="#devices" data-role="button" data-theme="e">Geräte</a>
            <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <a href="#groups" data-role="button" data-theme="e" >Gruppen</a><?php } ?>
            <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <a href="#rooms" data-role="button" data-theme="e" >Räume</a><?php } ?>
            <?php if($xml->gui->showTimerBtnInMenu == "true") { ?> <a href="#timers" data-role="button" data-theme="e" class="ui-disabled">Timer</a><?php } ?>
            <?php if($xml->gui->showActionBtnInMenu == "true") { ?> <a href="#actions" data-role="button" data-theme="e">Aktionen</a><?php } ?>
            <?php if($ShowSettingsMenue == "true") { ?> <a href="#configurations" data-role="button" data-theme="e">Einstellungen</a><?php } ?>
            <br />
            <?php if($xml->gui->showAllOnOffBtnInMenu == "true") { ?>
            <div class="ui-grid-a">
                <div class="ui-block-a"><button data-theme="g" data-mini="true" data-rel="close" onclick="send_connair('allon')">Alle an</button></div>
                <div class="ui-block-b"><button data-theme="r" data-mini="true" data-rel="close" onclick="send_connair('alloff')">Alle aus</button></div>     
            </div>
            <br />
            <?php } ?>
            <a href="#favorites" data-role="button" data-mini="true" data-theme="a" data-rel="close">Schliessen</a>
        </center>
    </div><!-- /panel -->
 
       
    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <a href="#mypanel" id="menubutton">Menu</a>
        <h1>Timer</h1>
        <div data-type="horizontal" data-role="controlgroup"  class="ui-btn-right"> 
            <a href="#" id="editButton" data-role="button" data-iconpos="notext" data-icon="edit" onClick="showEditButtons();"></a>
            <a href="#newtimer" id="newTimerButton" data-transition="none" data-role="button" data-iconpos="notext" data-icon="plus" onclick="sessionStorage.TimerID=''"></a>
            <!-- <a href="#" id="reloadbtnguitmr" data-role="button" data-iconpos="notext" data-icon="refresh"></a> -->
        </div>
    </div><!-- /header -->

    <div data-role="navbar" class="navigation" data-iconpos="left">
     <ul>
       <li><a href="#favorites" data-role="button" id="lk_favoriten" data-icon="custom" data-theme="a">Favoriten</a></li>
       <li><a href="#devices" data-role="button" id="lk_geraete" data-icon="custom" data-theme="a">Geräte</a></li>
        <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <li><a href="#groups" data-role="button" id="lk_gruppen" data-icon="custom" data-theme="a">Gruppen</a></li><?php } ?>   
        <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <li><a href="#rooms" data-role="button" id="lk_raeume" data-icon="custom" data-theme="a">Räume</a></li><?php } ?>    
        <?php if($xml->gui->showTimerBtnInMenu == "true") { ?> <li><a href="#timers" data-role="button" id="lk_timer" data-icon="custom" data-theme="a" >Timer</a></li><?php } ?>   
        <?php if($xml->gui->showActionBtnInMenu == "true") { ?> <li><a href="#actions" data-role="button" id="lk_actions" data-icon="custom" data-theme="a" >Aktionen</a></li><?php } ?>    
        <?php if($ShowSettingsMenue == "true") { ?> <li><a href="#configurations" data-role="button" id="lk_einstellungen" data-icon="custom" data-theme="a">Konfig</a></li><?php } ?>
     </ul>
    </div>
    
    <div data-role="content"> 
        <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
			<li id="sysalertmsg_timer" style="display:none;background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $SysAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
            <li id="notimermsg_timer" style="<?php if ($xml->global->timerGlobalRun != "false") echo "display:none;"; ?>background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $NoTimerAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
            <li id="tempmsg_timer" style="display:none;">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <font class="tempmsg_timer_indoor"></font>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <font class="tempmsg_timer_outdoor"></font>
                    </div>
                </div>
            </li>
<?php
    $timers = array();
    foreach($xml->timers->timer as $timer) {
        $timers[] = $timer;
    }
    switch ($xml->gui->sortOrderTimers){
        case "SORT_BY_NAME":
            usort($timers, "compareTimersByName");
            break;
        case "SORT_BY_ID":
            usort($timers, "compareTimersByID");
            break;
        case "SORT_BY_TYPE_AND_NAME":
            usort($timers, "compareTimersByTypeAndName");
            break;
        default:
            break;
    }
    foreach($timers as $timer) {
?>

               <!-- <li><a href="#newtimer" data-transition="slide" onclick="sessionStorage.TimerID=<?php //echo $timer->id; ?>"> -->
                <li>
                <div class="ui-grid-a">
                <div class="ui-block-a" style="text-align:left">
<?php
    switch ($timer->active) {
        case "on":
            echo "<h2 class='timer_activ_on'>";
            break;
        case "off":
        default:
            echo "<h2 class='timer_activ_off'>";
            break;
    }

    if($timer->type=="device") {
        foreach($xml->devices->device as $tmp_device) {
            //echo $timer->tid."-".$tmp_device->id."<br>";
            if ((string)$timer->typeid === (string)$tmp_device->id) {
                echo $tmp_device->name;
                $tmp_room = $tmp_device->room;
                $tmp_vendor = $tmp_device->vendor;
                $tmp_milight_mode = $tmp_device->milight->mode;
                $tmp_milight_color = $tmp_device->milight->color;
                $tmp_milight_brightnesscolor = $tmp_device->milight->brightnesscolor;
                $tmp_milight_brightnesswhite = $tmp_device->milight->brightnesswhite;
            }      
        }
    }
    if($timer->type=="group") {
        foreach($xml->groups->group as $tmp_group) {
            //echo $timer->tid."-".$tmp_device->id."<br>";
            if ((string)$timer->typeid === (string)$tmp_group->id) {
                echo $tmp_group->name;
            }      
        }
    }
    if($timer->type=="room") {
        echo $timer->typeid;
    }
?>
</h2>
                     <p><b>ID: </b>
<?php
    echo $timer->id
?>
                    </p>
                     <p><b>Aktiv: </b>
<?php
    switch ($timer->active) {
        case "on":
            echo "Ja";
            break;
        case "off":
        default:
            echo "Nein";
            break;
    }
?>
                    </p>
                    <p><b>Abhängigkeit: </b>
<?php
    switch ($timer->usage) {
        case "time":
            echo "Zeit";
            break;
        case "ping":
            echo "Ping";
            break;
        case "time_ping":
            echo "Zeit und Ping";
            break;
        default:
            echo "unbekannt";
            break;
    }
?>
                    </p>
                    <p><b>Typ: </b>
<?php
    switch ($timer->type) {
        case "device":
            echo "Gerät";
            break;
        case "group":
            echo "Gruppe";
            break;
        case "room":
            echo "Raum";
            break;
        default:
            echo "unbekannt";
            break;
    }
?>
<?php 
                    if($timer->type=="device") {
                       echo "<p><b>Raum: </b>".$tmp_room."</p>";
                    }
?>
                    </p>
<?php
    if ($timer->usage != "ping") {
                    echo "<p><b>Tage: </b>";
                    echo $timer->day;           
                    echo "</p>";
    }

    if ($timer->usage != "ping") {
        echo "<p><b>An: </b>";

        switch ($timer->timerOn) {
            case "SD":
                echo "Sonnenuntergang (".date('H:i', $sunset).")";
                if(!empty($timer->timerOn["offset"])) { echo "  <i>[".$timer->timerOn["offset"]." Minuten]</i>"; }
                break;
            case "SU":
                echo "Sonnenaufgang (".date('H:i', $sunrise).")";
                if(!empty($timer->timerOn["offset"])) { echo "  <i>[".$timer->timerOn["offset"]." Minuten]</i>"; }
                break;
            default:
                if ($timer->timerOn != "") echo $timer->timerOn." Uhr";
                else echo "MANUELL";
                break;
        }
        echo "</p>";
    }

    if ($timer->type == "device" && ($tmp_vendor == "milight" || $tmp_vendor == "milight_rgbcct")) {
    	if ($timer->milight->mode != "") {
            echo "<p><b>Modus (vT): </b>";
	        echo $timer->milight->mode;
	        
	        if ($timer->milight->mode == "Farbe") {
	            if ($timer->milight->color != "") echo " <font color=\"".$timer->milight->color."\">●</font> ";
	            else echo " <font color=\"".$tmp_milight_color."\">●</font> ";
	            if ($timer->milight->brightness != "") echo $timer->milight->brightness."%";
	            else echo $tmp_milight_brightnesscolor."%";
	        }
	        elseif ($timer->milight->mode == "Weiß") {
	            if ($timer->milight->brightness != "") echo " ● ".$timer->milight->brightness."%";
	            else echo " ● ".$tmp_milight_brightnesswhite."%";
	        }
	    }

    	elseif ($timer->milight->mode == "") {
            echo "<p><b>Modus: </b>";
	        echo $tmp_milight_mode;
	        
	        if ($tmp_milight_mode == "Farbe") {
	            echo " <font color=\"".$tmp_milight_color."\">●</font> ";
	            echo $tmp_milight_brightnesscolor."%";
	        }
	        elseif ($tmp_milight_mode == "Weiß") {
	            echo " ● ".$tmp_milight_brightnesswhite."%";
	        }
	    }

        echo "</p>";
    }

    if ($timer->usage != "ping") {
        echo "<p><b>Aus: </b>";

        switch ($timer->timerOff) {
            case "SD":
                echo "Sonnenuntergang (".date('H:i', $sunset).")";
                if(!empty($timer->timerOff["offset"])) { echo "  <i>[".$timer->timerOff["offset"]." Minuten]</i>"; }
                break;
            case "SU":
                echo "Sonnenaufgang (".date('H:i', $sunrise).")";
                if(!empty($timer->timerOff["offset"])) { echo "  <i>[".$timer->timerOff["offset"]." Minuten]</i>"; }
                break;
            default:
                if ($timer->timerOff != "") echo $timer->timerOff." Uhr";
                else echo "MANUELL";
                break;
        }
        echo "</p>";
    }

if(!empty($timer->pingto)){
            echo '<p><b>PingIP/Person: </b>';
            echo $timer->pingto;
            echo "</p>";
}
if ($timer->usage == "ping") {
            echo '<p><b>Wenn erreichbar: </b>';
            echo $timer->pingstatus;
            echo "</p>";
            echo '<p><b>Invertieren wenn unerreichbar: </b>';
            if ($timer->invertSwitchOnNoPing == "false") echo "NEIN";
            elseif ($timer->invertSwitchOnNoPing == "true") echo "JA";
            echo "</p>";
}
?>                  
                </div>
                <div class="ui-block-b" style="text-align:right">
                    <div class="box-btn-switch">
                        <button data-theme="g"  data-mini="true" data-inline="true" onclick="edit_timer('<?php echo $timer->id; ?>','EIN')">EIN</button>
                        <button data-theme="r"  data-mini="true" data-inline="true" onclick="edit_timer('<?php echo $timer->id; ?>','AUS')">AUS</button>
                    </div>
                    <div class="box-btn-edit hide">
                        <button data-theme="b" data-iconpos="notext" data-icon="edit" data-mini="true" data-inline="true" onClick="window.location.href = 'index.php?mode=edittimer&id=<?php echo $timer->id; ?>#newtimer'">Bearbeiten</button>
                        <button data-theme="r" data-iconpos="notext" data-icon="delete" data-mini="true" data-inline="true" onclick="delete_timer('<?php echo $timer->id; ?>')">Löschen</button>
                     </div>
                </div>
                </div>
                </li>

<?php
    }
?>
   
         </ul>
    </div><!-- /content -->
</div>
