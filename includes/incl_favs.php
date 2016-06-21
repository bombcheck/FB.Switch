<div data-role="page" id="favorites" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Favoriten]">

    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e" class="ui-disabled">Favoriten</a>
            <a href="#devices" data-role="button" data-theme="e" >Geräte</a>
            <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <a href="#groups" data-role="button" data-theme="e" >Gruppen</a><?php } ?>
            <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <a href="#rooms" data-role="button" data-theme="e" >Räume</a><?php } ?>
            <?php if($xml->gui->showTimerBtnInMenu == "true") { ?> <a href="#timers" data-role="button" data-theme="e" >Timer</a><?php } ?>
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
        <h1><?php
                if($active_user == "false"){
                    echo "Favoriten";
                 }else{
                    echo $active_user->name;
                }
            ?>
        </h1>
        <div data-type="horizontal" data-role="controlgroup"  class="ui-btn-right"> 
            <!-- <a href="#" id="editButton" data-role="button" data-iconpos="notext" data-icon="edit" onClick="showEditButtons();"></a>
            <a href="#newdevice" id="newButton" data-transition="none" data-role="button" data-iconpos="notext" data-icon="plus"></a> -->
            <a href="#" id="reloadbtnguifav" data-role="button" data-iconpos="notext" data-icon="refresh"></a>
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
    
    <div data-role="content" id="content">  
        <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
			<li id="sysalertmsg_favs" style="display:none;background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $SysAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
		<li data-role="list-divider" role="heading">
                        <a href="#" id="countdown" onClick="newcountdown();" style="color: #ffffff;">
                                Countdowntimer
                        </a>
                    </li>
                <form id="newcountdown" method="POST"  class="newcountdown hide" data-role="listview" data-ajax="false">
                    <li data-role="fieldcontain">
                        <div id="formmessage">
                        </div>
                        <label for="minutes">Zeitinterval:</label>
                        <select name="minutes" id="minutes">
                            <option value=''>Minuten</option>
                            <?php
                            for($i=1; $i <45; $i++){
                            echo '<option>'. $i .'</option>';
                            }
            ?>
                            <option>60</option>
                            <option>90</option>
                            <option>120</option>
                            <option>180</option>
                        </select>
                        <label for="device">Gerät:</label>
                        <select name="device" id="device">
                            <?php
                            $devices = array();
                            foreach($xml->devices->device as $device) {
                                $devices[] = $device;
                            }
                            switch ($xml->gui->sortOrderDevices){
                                case "SORT_BY_NAME":
                                    usort($devices, "compareDevicesByName");
                                    break;
                                case "SORT_BY_ID":
                                    usort($devices, "compareDevicesByID");
                                    break;
                                default:
                                    break;
                            }
                            foreach($devices as $device) {
                                echo "<option value='".$device->id."'>".$device->name." (".$device->room.")</option>";
                            }
                         ?>
                        </select>
                        <label for="action">Schalten:</label>
                        <select name="action" id="action">
                            <option>ON</option>
                            <option>OFF</option>
                        </select>
                        
                    </li>
                    <li data-role="list-divider" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
                        <input data-mini="true" class="submit_button" type="submit" name="submitbutton" value="Speichern" type="button" >
                    </li>
                </form>
<?php
/*
Prüfen ob die IP-Addresse die die Anfrage schickt gespeichert ist oder ob sie GET daten enthält,
und jeweils passende Config-abfrage machen und die entsprechende Geräte in ein array übergeben...
###Favoriten abfragen start###
*/
if($active_user == "false" AND $_GET['user'] == ""  ){
        $groupsFound = $xml->xpath("//persons/person/name[text()='Standard-Benutzer']/parent::*");
}elseif($_GET['user'] != ""){
        $groupsFound = $xml->xpath("//persons/person/name[text()='".$_GET['user']."']/parent::*");
}else{
        $groupsFound = $xml->xpath("//persons/person/name[text()='".$active_user->name."']/parent::*");
}
        $favorite = $groupsFound['0']->favoritgroups;
        $favorite = explode(',', $favorite);
        $group = array();
        foreach ( $xml->groups->group as $user )   
        {
            if(in_array($user->id, $favorite)){
            array_push($group, $user);
            }
        }
###Favoriten abfragen stop###
        
    if(!empty($group)){
?>
            <li data-role="list-divider" role="heading" >
                <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?>
                    <a href="#groups" id="favoriteslinks" style="color: #ffffff;">
                    Gruppen
                    </a>
                <?php } else { ?>
                    Gruppen
                <? } ?>
            </li>
<?php
        for($i=0;$i<count($favorite); $i++){
?>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a" style="text-align:left">
                        <h2><?php echo $group[$i]->name; ?></h2>
                    <?php
                        //foreach($group[$i]->deviceid as $deviceid) {
                        //    $devicesFound = $xml->xpath("//devices/device/id[text()='".$deviceid."']/parent::*");
                        //    echo "<p>".$devicesFound[0]->name."</p>";
                        //}
                    ?>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <div class="box-btn-switch">
                            <button data-theme="g"  data-mini="true" data-inline="true" onclick="send_connair('on','group','<?php echo $group[$i]->id; ?>')"><?php echo empty($group[$i]['buttonLabelOn']) ? 'EIN' : $group[$i]['buttonLabelOn']; ?></button>
                            <button data-theme="r"  data-mini="true" data-inline="true" onclick="send_connair('off','group','<?php echo $group[$i]->id; ?>')"><?php echo empty($group[$i]['buttonLabelOff']) ? 'AUS' : $group[$i]['buttonLabelOff']; ?></button>
                        </div>
                        <div class="box-btn-edit hide">
                            <button data-theme="b" data-iconpos="notext" data-icon="edit" data-mini="true" data-inline="true" onClick="window.location.href = 'index.php?mode=editgroup&id=<?php echo $group[$i]->id; ?>#newgroup'">Bearbeiten</button>
                            <button data-theme="r" data-iconpos="notext" data-icon="delete" data-mini="true" data-inline="true" onclick="delete_group('<?php echo $group[$i]->id; ?>')">Löschen</button>
                         </div>
                    </div>
                </div>
            </li>
<?php
        }
    }
?>

<?php
/*
Prüfen ob die IP-Addresse die die Anfrage schickt gespeichert ist oder ob sie GET daten enthält,
und jeweils passende Config-abfrage machen und die entsprechende Geräte in ein array übergeben...
###Favoriten abfragen start###
*/
if($active_user == "false" AND $_GET['user'] == ""  ){
        $actionsFound = $xml->xpath("//persons/person/name[text()='Standard-Benutzer']/parent::*");
}elseif($_GET['user'] != ""){
        $actionsFound = $xml->xpath("//persons/person/name[text()='".$_GET['user']."']/parent::*");
}else{
        $actionsFound = $xml->xpath("//persons/person/name[text()='".$active_user->name."']/parent::*");
}
        $favoritactions = $actionsFound['0']->favoritactions;
        $favoritactions = explode(',', $favoritactions);
        $action = array();
        foreach ( $xml->actions->action as $user )   
        {
            if(in_array($user->id, $favoritactions)){
                array_push($action, $user);
            }
        }

###Favoriten abfragen stop###
    if(!empty($action)){
?>
            <li data-role="list-divider" role="heading">
                <?php if($xml->gui->showActionBtnInMenu == "true") { ?> 
                    <a href="#actions" id="favoriteslinks" style="color: #ffffff;">
                    Aktionen
                    </a>
                <?php } else { ?>
                    Aktionen
                <?php } ?>
            </li>
<?php
        for($i=0;$i<count($favoritactions); $i++){
?>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a" style="text-align:left">
                        <h2><?php echo $action[$i]->name; ?></h2>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <div class="box-btn-switch">
                            <button data-theme="g"  data-mini="true" data-inline="true" onclick="send_connair('on','action','<?php echo $action[$i]->id; ?>')"><?php echo empty($action[$i]['buttonLabelRun']) ? 'RUN' : $action[$i]['buttonLabelRun']; ?></button>
                        </div>
                        <div class="box-btn-edit hide">
                            <button data-theme="b" data-iconpos="notext" data-icon="edit" data-mini="true" data-inline="true" onClick="window.location.href = 'index.php?mode=editaction&id=<?php echo $action[$i]->id; ?>#newaction'">Bearbeiten</button>
                            <button data-theme="r" data-iconpos="notext" data-icon="delete" data-mini="true" data-inline="true" onclick="delete_action('<?php echo $action[$i]->id; ?>')">Löschen</button>
                         </div>
                    </div>
                </div>
            </li>
<?php
        }
    }
?>

<?php
if($active_user == "false" AND $_GET['user'] == ""  ){
        $devicesFound = $xml->xpath("//persons/person/name[text()='Standard-Benutzer']/parent::*");
}elseif($_GET['user'] != ""){
        $devicesFound = $xml->xpath("//persons/person/name[text()='".$_GET['user']."']/parent::*");
}else{
        $devicesFound = $xml->xpath("//persons/person/name[text()='".$active_user->name."']/parent::*");
}
        $favorite = $devicesFound['0']->favoritdevices;
        $favorite = explode(',', $favorite);
        $device = array();
        foreach ( $xml->devices->device as $user )   
        {
            if(in_array($user->id, $favorite)){
            array_push($device, $user);
            }
        }
    if(!empty($device)){
?>
        <li data-role="list-divider" role="heading">
            <a href="#devices" id="favoriteslinks" style="color: #ffffff;">
            Geräte
            </a>
        </li>
<?php
    $roomDevices = array();
    foreach($device as $device) {
        $curRoom = (string)$device->room;
        if(!array_key_exists($curRoom, $roomDevices)) {
            $roomDevices[$curRoom] = array();
        }
        $roomDevices[$curRoom][] = $device;
    }
    switch ($xml->gui->sortOrderRooms){
        case "SORT_BY_NAME":
            ksort($roomDevices);
            break;
        default:
            break;
    }
    foreach($roomDevices as $room => $devices) {
        switch ($xml->gui->sortOrderDevices){
            case "SORT_BY_NAME":
                usort($devices, "compareDevicesByName");
                break;
            case "SORT_BY_ID":
                usort($devices, "compareDevicesByID");
                break;
            default:
                break;
        }
?>

<?php
        foreach($devices as $device) {

            // switch ($xml->gui->showDeviceStatus){
            switch ($device->showDeviceStatus){
                case "ROW_COLOR":
                    $rowOnDataTheme="g";
                    $rowOffDataTheme="r";
                    if($device->status=='ON') {
                        $rowDataTheme=$rowOnDataTheme;
                    } else {
                        $rowDataTheme=$rowOffDataTheme;
                    }
                    $btnOnDataTheme="g";
                    $btnOffDataTheme="r";
                    $btnOnIcon="";
                    $btnOnJS="send_connair('on','device','".$device->id."'); switchRowTheme('on','".$device->id."','".$rowOnDataTheme."','".$rowOffDataTheme."');";
                    if ($device->vendor == "milight") $btnOnJS.= " toggle_milight_buttons('on','".$device->id."')";
                    $btnOffJS="send_connair('off','device','".$device->id."'); switchRowTheme('off','".$device->id."','".$rowOnDataTheme."','".$rowOffDataTheme."');";
                    if ($device->vendor == "milight") $btnOffJS.= " toggle_milight_buttons('off','".$device->id."')";
                    break;
                case "BUTTON_COLOR":
                    $rowDataTheme=$theme_row;
                    $btnOnColor="g";
                    $btnOffColor="r";
                    $btnCurColor="e";
                    if($device->status=='ON') {
                        $btnOnDataTheme=$btnOnColor;
                        $btnOffDataTheme=$btnCurColor;
                    } else {
                        $btnOnDataTheme=$btnCurColor;
                        $btnOffDataTheme=$btnOffColor;
                    }
                    $btnOnIcon="";
                    $btnOnJS="send_connair('on','device','".$device->id."'); switchButtonTheme('on','".$device->id."','".$btnOnColor."','".$btnOffColor."','".$btnCurColor."');";
                    if ($device->vendor == "milight") $btnOnJS.= " toggle_milight_buttons('on','".$device->id."')";
                    $btnOffJS="send_connair('off','device','".$device->id."'); switchButtonTheme('off','".$device->id."','".$btnOnColor."','".$btnOffColor."','".$btnCurColor."');";
                    if ($device->vendor == "milight") $btnOffJS.= " toggle_milight_buttons('off','".$device->id."')";
                    break;
                case "BUTTON_ICON":
                    $onIcon="check";
                    $offIcon="off";
                    $rowDataTheme=$theme_row;
                    $btnOnDataTheme="g";
                    $btnOffDataTheme="r";
                    if($device->status=='ON') {
                        $btnOnIcon=$onIcon;
                    } else {
                        $btnOnIcon=$offIcon;
                    }
                    $btnOnJS="send_connair('on','device','".$device->id."'); switchButtonIcon('on','".$device->id."','".$onIcon."','".$offIcon."');";
                    if ($device->vendor == "milight") $btnOnJS.= " toggle_milight_buttons('on','".$device->id."')";
                    $btnOffJS="send_connair('off','device','".$device->id."'); switchButtonIcon('off','".$device->id."','".$onIcon."','".$offIcon."');";
                    if ($device->vendor == "milight") $btnOffJS.= " toggle_milight_buttons('off','".$device->id."')";
                    break;
                default:
                    $rowDataTheme=$theme_row;
                    $btnOnDataTheme="g";
                    $btnOffDataTheme="r";
                    $btnOnIcon="";
                    $btnOnJS="send_connair('on','device','".$device->id."');";
                    if ($device->vendor == "milight") $btnOnJS.= " toggle_milight_buttons('on','".$device->id."')";
                    $btnOffJS="send_connair('off','device','".$device->id."');";
                    if ($device->vendor == "milight") $btnOffJS.= " toggle_milight_buttons('off','".$device->id."')";
                    break;
            }

?>

                <li id="deviceRow<?php echo $device->id; ?>" data-theme="<?php echo $rowDataTheme; ?>">
                    <div class="ui-grid-a">
                     
                        <div class="ui-block-a" style="text-align:left">
                        <?php
                        if(file_exists('data/images/'. $device->id.'.png')) {
                            echo '<div id="picture">
                                <img src="data/images/'. $device->id.'.png" height="60px" width="60px" > </img>
                            </div>
                            <div id="text">
                                <h2>'.$device->name.' </h2>';
                                if($debug != "true") echo "<p> $device->room </p><p><font class=\"device_favs_".$device->id."_energy\"></font></p>";
                            echo '</div>';
                        }else{
                            echo "<h2> $device->name </h2>";
                            if($debug != "true") echo "<p> $device->room </p><p><font class=\"device_favs_".$device->id."_energy\"></font></p>";
                        }
                        ?>
                        
                        <?php 
                            if($debug == "true") {
                                if(file_exists('data/images/'. $device->id.'.png')){
                                    echo "
                                    <div id='picture_debug_text'>
                                    <p>
                                        <i>".$device->id." ".$device->vendor." ".$device->address->masterdip." ".$device->address->slavedip."</i>
                                    </p>
                                    </div>
                                    ";
                                }else{
                                    echo "
                                    <div id='debug_text'>
                                    <p>
                                        <i>".$device->id." ".$device->vendor." ".$device->address->masterdip." ".$device->address->slavedip."</i>
                                    </p>
                                    </div>
                                    ";
                                }
                            }
                        ?>
                        </div>
                        <div class="ui-block-b" style="text-align:right">
                            <div class="box-btn-switch">
                        <?php
                            if($device["hideButtonOn"] != "yes") {
                        ?>
                                <button id="btnOn<?php echo $device->id; ?>" data-theme="<?php echo $btnOnDataTheme; ?>" data-mini="true" data-inline="true" <?php if(!empty($btnOnIcon)) { echo 'data-icon="'.$btnOnIcon.'"'; } ?> onclick="<?php echo $btnOnJS; ?>"><?php echo empty($device['buttonLabelOn']) ? 'EIN' : $device['buttonLabelOn']; ?></button>
                        <?php
                            }
                            if($device["hideButtonOff"] != "yes") {
                        ?>
                                <button id="btnOff<?php echo $device->id; ?>" data-theme="<?php echo $btnOffDataTheme; ?>" data-mini="true" data-inline="true" onclick="<?php echo $btnOffJS; ?>"><?php echo empty($device['buttonLabelOff']) ? 'AUS' : $device['buttonLabelOff']; ?></button>
                        <?php
                            }
                        ?>
                            </div>
                            <div class="box-btn-edit hide">
                                <button data-theme="b" data-iconpos="notext" data-icon="edit" data-mini="true" data-inline="true" onClick="window.location.href = 'index.php?mode=editdevice&id=<?php echo $device->id; ?>#newdevice'">Bearbeiten</button>
                                <button data-theme="r" data-iconpos="notext" data-icon="delete" data-mini="true" data-inline="true" onclick="delete_device('<?php echo $device->id; ?>')">Löschen</button>
                            </div>
                        </div>
                    </div>
                </li>
<?php
        }
    }
}
?>
    </ul>
    </div><!-- /content -->

    </div><!-- /page -->
