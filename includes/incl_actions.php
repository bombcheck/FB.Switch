<div data-role="page" id="actions" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Aktionen]">
    
    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e" >Favoriten</a>
            <!--a href="#my-header" data-rel="close" data-role="button" data-theme="b">Favoriten</a-->
            <a href="#devices" data-role="button" data-theme="e">Geräte</a>
            <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <a href="#groups" data-role="button" data-theme="e" >Gruppen</a><?php } ?>
            <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <a href="#rooms" data-role="button" data-theme="e" >Räume</a><?php } ?>
            <?php if($xml->gui->showTimerBtnInMenu == "true") { ?> <a href="#timers" data-role="button" data-theme="e" >Timer</a><?php } ?> 
            <?php if($xml->gui->showActionBtnInMenu == "true") { ?> <a href="#actions" data-role="button" data-theme="e" class="ui-disabled">Aktionen</a><?php } ?>
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
        <h1>Aktionen</h1>
        <div data-type="horizontal" data-role="controlgroup"  class="ui-btn-right"> 
            <!-- <a href="#" id="editButton" data-role="button" data-iconpos="notext" data-icon="edit" onClick="showEditButtons();"></a>
            <a href="#newgroup" id="newGroupButton" data-rel="dialog" data-role="button" data-iconpos="notext" data-icon="plus"></a> -->
            <a href="#" id="reloadbtnguiact" data-role="button" data-iconpos="notext" data-icon="refresh"></a>
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
			<li id="sysalertmsg_actions" style="display:none;background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $SysAlertMsg; ?></h2>
                    </div>
                </div>
            </li> 
            <li id="notimermsg_actions" style="<?php if ($xml->global->timerGlobalRun != "false") echo "display:none;"; ?>background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $NoTimerAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
            <li id="tempmsg_actions" style="display:none;">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <font class="tempmsg_actions_indoor"></font>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <font class="tempmsg_actions_outdoor"></font>
                    </div>
                </div>
            </li>
<?php
    $actionsFound = array();
    foreach($xml->actions->action as $action) {
        $actionsFound[] = $action;
    }
        switch ($xml->gui->sortOrderGroups){
            case "SORT_BY_NAME":
                usort($actionsFound, "compareGroupsByName");
                break;
            case "SORT_BY_ID":
                usort($actionsFound, "compareGroupsByID");
                break;
            default:
                break;
        }
        foreach($actionsFound as $action) {
?>

            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a" style="text-align:left">
                        <h2><?php echo $action->name; ?></h2>
<?php
                        foreach($action->do as $do) {
                            $tmpNameAction="";
                            switch ($do['type']) {
                                case "device":
                                    $devicesFound = $xml->xpath("//devices/device/id[text()='".$do['id']."']/parent::*");
                                    $tmpNameAction = "Gerät: ".$devicesFound[0]->name." (".$devicesFound[0]->room.")";
                                    break;
                                case "group":
                                    $groupsFound = $xml->xpath("//groups/group/id[text()='".$do['id']."']/parent::*");
                                    $tmpNameAction = "Gruppe: ".$groupsFound[0]->name;
                                     break;
                                case "room":
                                    $tmpNameAction = "Raum: ".$do['id'];
                                    break;
                                case "wait":
                                    $tmpNameAction = "Warte: ".$do['id']." Sekunden";
                                    break;
                            }
                            echo "<p>".$tmpNameAction." ";
                            if ($do['action'] == "on") echo "AN";
                            if ($do['action'] == "off") echo "AUS";
                            if ($do['type'] == "device") {
                                if ( ($devicesFound[0]->vendor == "milight" || $devicesFound[0]->vendor == "milight_rgbcct") && $do['action'] == "on") {
                                            if ($do['mode'] != "") {
                                                echo " (vA) @ ";
                                                echo $do['mode'];
                                                
                                                if ($do['mode'] == "Farbe") {
                                                    if ($do['color'] != "") echo " <font color=\"".$do['color']."\">●</font> ";
                                                    else echo " <font color=\"".$devicesFound[0]->milight->color."\">●</font> ";
                                                    if ($do['brightness'] != "") echo $do['brightness']."%";
                                                    else echo $devicesFound[0]->milight->brightnesscolor."%";

                                                    if ($devicesFound[0]->vendor == "milight_rgbcct") {
                                                        if ($do['saturation'] != "") echo " (Sat.: ".$do['saturation']."%)";
                                                        else echo " (Sat.: ".$devicesFound[0]->milight->saturation."%)";
                                                    }
                                                }
                                                elseif ($do['mode'] == "Weiß") {
                                                    if ($do['brightness'] != "") echo " ● ".$do['brightness']."%";
                                                    else echo " ● ".$devicesFound[0]->milight->brightnesswhite."%";

                                                    if ($devicesFound[0]->vendor == "milight_rgbcct") {
                                                        if ($do['temperature'] != "") echo " (".TemperaturePercentToKelvin($do['temperature'])."K)";
                                                        else echo " (".TemperaturePercentToKelvin($devicesFound[0]->milight->temperature)."K)";
                                                    }                                                    
                                                }
                                            }

                                            elseif ($do['mode'] == "") {
                                                echo " @ ";
                                                echo $devicesFound[0]->milight->mode;
                                                
                                                if ($devicesFound[0]->milight->mode == "Farbe") {
                                                    echo " <font color=\"".$devicesFound[0]->milight->color."\">●</font> ";
                                                    echo $devicesFound[0]->milight->brightnesscolor."%";

                                                    if ($devicesFound[0]->vendor == "milight_rgbcct") {
                                                        echo " (Sat.: ".$devicesFound[0]->milight->saturation."%)";
                                                    }
                                                }
                                                elseif ($devicesFound[0]->milight->mode == "Weiß") {
                                                    echo " ● ".$devicesFound[0]->milight->brightnesswhite."%";

                                                    if ($devicesFound[0]->vendor == "milight_rgbcct") {
                                                        echo " (".TemperaturePercentToKelvin($devicesFound[0]->milight->temperature)."K)";
                                                    }                                                    
                                                }
                                            }
                                }
                            }
                            echo "</p>";
                        }
?>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <div class="box-btn-switch">
                            <button data-theme="g"  data-mini="true" data-inline="true" onclick="send_connair('on','action','<?php echo $action->id; ?>')"><?php echo empty($action['buttonLabelRun']) ? 'RUN' : $action['buttonLabelRun']; ?></button>
                        </div>
                        <div class="box-btn-edit hide">
                            <button data-theme="b" data-iconpos="notext" data-icon="edit" data-mini="true" data-inline="true" onclick="edit_action('<?php echo $action->id; ?>')">Bearbeiten</button>
                            <button data-theme="r" data-iconpos="notext" data-icon="delete" data-mini="true" data-inline="true" onclick="delete_action('<?php echo $action->id; ?>')">Löschen</button>
                         </div>
                    </div>
                </div>
            </li>
     
<?php
    }
?>

        </ul>
    </div><!-- /content -->
</div><!-- /page -->
