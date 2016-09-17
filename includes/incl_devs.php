<style type="text/css">
#picture {
    position:absolute;
    top:5px;
    left:15px;
    width:60px;
    height:60px;
    z-index:1;
    
    color: #ffffff;
    display: block;
    text-decoration: none; 
}
#text {
    margin: 0 0 0 75px;
    z-index:100;
    clear: left;
    text-decoration: none; 
}
#picture_debug_text {
    margin: 0 0 0 75px;
    z-index:1;
    clear: all;
    display: block;
    text-decoration: none; 
}
#debug_text {
    position:absolute;
    top:40px;
    left:15px;
    width:auto;
    height:auto;
    z-index:1;
    display: block;
    text-decoration: none; 
}
#milightpanel{
    #padding: .5em 15px;
    padding: 0 15px;
    display: block;
    #height: 40px;
    width:98%;
}
.box-btn-switch-milight .ui-btn { 
    height: 45px; 
    width: 80px; 
    margin: 0 auto;
}

<?php if ($localtheme == "DARK") { ?>
.sColorPicker {
    background: #666666;
    border-color: black;
}
<?php } ?>
</style>
<script type="text/javascript">
    function showmilightpanel(milight) {
        $(milight).each(function() {
            $(this).toggle().removeClass('hide').addClass('show');
        });
    }
</script>

<div data-role="page" id="devices" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Geräte]">

    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e" >Favoriten</a>
            <!--a href="#my-header" data-rel="close" data-role="button" data-theme="b">Favoriten</a-->
            <a href="#devices" data-role="button" data-theme="e" class="ui-disabled">Geräte</a>
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
        <h1>Geräte</h1>
        <div data-type="horizontal" data-role="controlgroup"  class="ui-btn-right"> 
            <a href="#" id="editButton" data-role="button" data-iconpos="notext" data-icon="edit" onClick="showEditButtons();"></a>
            <a href="#newdevice" id="newDeviceButton" data-transition="none" data-role="button" data-iconpos="notext" data-icon="plus"></a>
            <!-- <a href="#" id="reloadbtnguidev" data-role="button" data-iconpos="notext" data-icon="refresh"></a>
-->
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
        <!-- <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-filter-theme="<?php echo $theme_divider; ?>" data-inset="false" data-filter="true" data-filter-placeholder="Geräte suchen...">
-->
<ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
			<li id="sysalertmsg_devs" style="display:none;background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $SysAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
            <li id="notimermsg_devs" style="<?php if ($xml->global->timerGlobalRun != "false") echo "display:none;"; ?>background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $NoTimerAlertMsg; ?></h2>
                    </div>
                </div>
            </li>
            <li id="tempmsg_devs" style="display:none;">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <font class="tempmsg_devs_indoor"></font>
                    </div>
                    <div class="ui-block-b" style="text-align:right">
                        <font class="tempmsg_devs_outdoor"></font>
                    </div>
                </div>
            </li>
<?php
    $roomDevices = array();
    foreach($xml->devices->device as $device) {
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

            <li data-role="list-divider">
                    <div class="ui-grid-a">
                        <div class="ui-block-a" style="text-align:left">
                            <?php echo $room; ?>
                        </div>
                        <div class="ui-block-b" style="text-align:right">
                        <?php
                            if($xml->gui->showRoomButtonInDevices == "true") {
                        ?>
                            <div class="box-btn-switch">
                                <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_connair('on','room','<?php echo $room; ?>')">EIN</button>
                                <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_connair('off','room','<?php echo $room; ?>')">AUS</button>
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
            </li>

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
                            echo '
                            <div id="picture"';
                                if ($device->vendor == "milight") echo ' style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showmilightpanel(milight_'.$device->id.'_panel);"';
                                    echo '><img src="data/images/'. $device->id.'.png" height="60px" width="60px" ></img>
                            </div>
                            <div id="text"';
                            if ($device->vendor == "milight") echo ' style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showmilightpanel(milight_'.$device->id.'_panel);"';
                                echo '><h2>';
                                    echo $device->name.'</h2>';
                                if($debug != "true") {
                                	echo "<p";
                                	if ($device->vendor == "milight") echo ' style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showmilightpanel(milight_'.$device->id.'_panel);"';
                                	echo "><font class=\"device_devs_".$device->id."_energy\"></font></p>";
                                }
                            echo '</div>';
                        }else{
                            echo '<h2';
                                if ($device->vendor == "milight") echo ' style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showmilightpanel(milight_'.$device->id.'_panel);"';
                                    echo '>'.$device->name.'</h2>';
                            if($debug != "true") {
                            	echo "<p";
                            	if ($device->vendor == "milight") echo ' style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showmilightpanel(milight_'.$device->id.'_panel);"';
                            	echo "><font class=\"device_devs_".$device->id."_energy\"></font></p>";
                            }
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

            <?php if ($device->vendor == "milight") { ?>

                <?php if ($device->address->tx433version == "2") {
                    $MlBridgesFound = $xml->xpath("//milightwifis/milightwifi/id[text()='".$device->address->masterdip."']/parent::*"); 
                    $MlBridgeFound = $MlBridgesFound[0];
                    ?>
                    <script type="text/JavaScript">
                        $(document).ready(function() {
                            <?php if ($device->milight->mode != "" && $device->status == "ON") { ?>
                                var dev_mode_text = $('.device_devs_<?php echo $device->id; ?>_energy');
                                var fav_mode_text = $('.device_favs_<?php echo $device->id; ?>_energy');
                                var BRtext = '';
                                <?php if ($device->milight->mode == "Weiß") { ?>
                                     BRtext = ' @ <?php echo $device->milight->brightnesswhite; ?>%';
                                <?php } ?>
                                <?php if ($device->milight->mode == "Farbe") { ?>
                                     BRtext = ' @ <?php echo $device->milight->brightnesscolor; ?>%';
                                <?php } ?>

                                dev_mode_text.eq(0 % dev_mode_text.length).text('Modus: <?php echo $device->milight->mode; ?>' + BRtext);
                                fav_mode_text.eq(0 % fav_mode_text.length).text('Modus: <?php echo $device->milight->mode; ?>' + BRtext);
                            <?php } ?>

                            $("#milight_<?php echo $device->id; ?>_Modus_Farbe").css("background","<?php echo $device->milight->color; ?>");

                            $("#milight_<?php echo $device->id; ?>_Modus_Farbe").spectrum({
                                color: "<?php echo $device->milight->color; ?>",
                                showPaletteOnly: true,
                                togglePaletteOnly: true,
                                hideAfterPaletteSelect: false,
                                togglePaletteMoreText: 'Farbauswahl >>',
                                togglePaletteLessText: '<< Einklappen',
                                chooseText: "Schliessen",
                                cancelText: "",
                                showInitial: true,
                                showButtons: true,
                                containerClassName: 'sColorPicker',
                                preferredFormat: "hsl",
                                palette: [
                                    ['hsl 0 1 0.5', 'hsl 6 1 0.5', 'hsl 12 1 0.5', 'hsl 17 1 0.5', 'hsl 23 1 0.5', 'hsl 29 1 0.5', 'hsl 34 1 0.5', 'hsl 40 1 0.5'],
                                    ['hsl 46 1 0.5', 'hsl 51 1 0.5', 'hsl 57 1 0.5', 'hsl 63 1 0.5', 'hsl 68 1 0.5', 'hsl 74 1 0.5', 'hsl 80 1 0.5', 'hsl 85 1 0.5'],
                                    ['hsl 91 1 0.5', 'hsl 96 1 0.5', 'hsl 102 1 0.5', 'hsl 108 1 0.5', 'hsl 113 1 0.5', 'hsl 119 1 0.5', 'hsl 125 1 0.5', 'hsl 130 1 0.5'],
                                    ['hsl 136 1 0.5', 'hsl 142 1 0.5', 'hsl 147 1 0.5', 'hsl 153 1 0.5', 'hsl 159 1 0.5', 'hsl 164 1 0.5', 'hsl 170 1 0.5', 'hsl 176 1 0.5'],
                                    ['hsl 181 1 0.5', 'hsl 187 1 0.5', 'hsl 192 1 0.5', 'hsl 198 1 0.5', 'hsl 204 1 0.5', 'hsl 209 1 0.5', 'hsl 215 1 0.5', 'hsl 221 1 0.5'],
                                    ['hsl 226 1 0.5', 'hsl 232 1 0.5', 'hsl 238 1 0.5', 'hsl 243 1 0.5', 'hsl 249 1 0.5', 'hsl 255 1 0.5', 'hsl 260 1 0.5', 'hsl 266 1 0.5'],
                                    ['hsl 272 1 0.5', 'hsl 277 1 0.5', 'hsl 283 1 0.5', 'hsl 288 1 0.5', 'hsl 294 1 0.5', 'hsl 300 1 0.5', 'hsl 305 1 0.5', 'hsl 311 1 0.5'],
                                    ['hsl 317 1 0.5', 'hsl 322 1 0.5', 'hsl 328 1 0.5', 'hsl 334 1 0.5', 'hsl 339 1 0.5', 'hsl 345 1 0.5', 'hsl 351 1 0.5', 'hsl 357 1 0.5']
                                ],
                                hide: function(color) {
                                    send_milight('<?php echo $device->id; ?>','SetColor',color.toHexString());
                                },
                                move: function(color) {
                                    milight_colorswipe({ hue: Math.round(color.toHsl()['h']), mlgroup: '<?php echo $device->address->slavedip; ?>', mlip: '<?php echo $MlBridgeFound->address; ?>', mlport: '<?php echo $MlBridgeFound->port; ?>' }, EVENT_HUE);
                                }

                            });
                        });
                    </script>
                <?php } ?>

                <div id="milight_<?php echo $device->id; ?>_panel" class="hide">
                    <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-filter-theme="<?php echo $theme_divider; ?>" data-inset="false">
                        <li data-role="fieldcontain" data-filter="false" data-inset="false" id="deviceRow<?php echo $device->id; ?>" data-theme="<?php echo $rowDataTheme; ?>">
                            <div class="ui-grid-a" id="milightpanel" style="width:98%;">
    		                    <div class="ui-block-a <?php echo ( $device->status == 'OFF' ) ? 'show' : 'hide' ?>" id="milight_<?php echo $device->id; ?>_offtext" style="width:98%;text-align:left">
    		                        <h2 style="font-size:13px">Gerät einschalten, um Modi zu ändern.<br><br></h2>
    		                    </div>
                                    <div class="ui-block-a <?php echo ( $device->status == 'ON' ) ? 'show' : 'hide' ?>" id="milight_<?php echo $device->id; ?>_buttons" style="width:98%;text-align:left; font-size: 8px;!important" >
                                        <?php if ($device->address->tx433version == "2") { ?>
                                           <div class="box-btn-switch-milight" style="width:98%;">
                                           		<!-- FIRST LINE: Modi -->
                                                <button name="milight_<?php echo $device->id; ?>_Modus_Weiss" id="milight_<?php echo $device->id; ?>_Modus_Weiss" data-icon="<?php echo ( $device->milight->mode == 'Weiß' ) ? 'check' : 'off' ?>" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_milight('<?php echo $device->id; ?>','SetToWhite','')">Weiß</button>&nbsp;&nbsp;
                                                <button name="milight_<?php echo $device->id; ?>_Modus_Farbe" id="milight_<?php echo $device->id; ?>_Modus_Farbe" data-icon="<?php echo ( $device->milight->mode == 'Farbe' ) ? 'check' : 'off' ?>" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true">Farbe</button>&nbsp;&nbsp;
                                                <button name="milight_<?php echo $device->id; ?>_Modus_Nacht" id="milight_<?php echo $device->id; ?>_Modus_Nacht" data-icon="<?php echo ( $device->milight->mode == 'Nacht' ) ? 'check' : 'off' ?>" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_milight('<?php echo $device->id; ?>','SetToNightMode','')">Nacht</button>

                                                <br><br>
												<!-- SECOND LINE: Disco-Mode -->
                                                <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_milight('<?php echo $device->id; ?>','rgbwDiscoSlower','')">S-</button>&nbsp;&nbsp;
                                                <button name="milight_<?php echo $device->id; ?>_Modus_Programm" id="milight_<?php echo $device->id; ?>_Modus_Programm" data-icon="<?php echo ( $device->milight->mode == 'Programm' ) ? 'check' : 'off' ?>" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_milight('<?php echo $device->id; ?>','rgbwDiscoMode','')">Disko</button>&nbsp;&nbsp;
                                                <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_milight('<?php echo $device->id; ?>','rgbwDiscoFaster','')">S+</button>

												<!-- THIRD LINE: Brightness -->
                                                <div id="milight_<?php echo $device->id; ?>_brightness_controls" class="<?php echo ( $device->milight->mode == 'Farbe' || $device->milight->mode == 'Weiß' ) ? 'show' : 'hide' ?>">
                                                    <br><br>
                                                    <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="if (parseInt(document.getElementById('milight_<?php echo $device->id; ?>_brightness').value) > 5) { $('#milight_<?php echo $device->id; ?>_brightness').attr('value',(parseInt(document.getElementById('milight_<?php echo $device->id; ?>_brightness').value) - 5) + '%').button().button('refresh'); }">Dunkler</button>&nbsp;&nbsp;
                                                    <?php
                                                        $ThisBrightness = 0;
                                                        if ($device->milight->mode == 'Farbe') $ThisBrightness = $device->milight->brightnesscolor;
                                                        elseif ($device->milight->mode == 'Weiß') $ThisBrightness = $device->milight->brightnesswhite;
                                                        if ($ThisBrightness == "") $ThisBrightness = 0;
                                                    ?>
                                                    <button name="milight_<?php echo $device->id; ?>_brightness" id="milight_<?php echo $device->id; ?>_brightness" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" value="<?php echo $ThisBrightness; ?>%" onclick="send_milight('<?php echo $device->id; ?>','SetBrightness',parseInt(this.value))"></button>&nbsp;&nbsp;
                                                    <button data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="if (parseInt(document.getElementById('milight_<?php echo $device->id; ?>_brightness').value) < 100) { $('#milight_<?php echo $device->id; ?>_brightness').attr('value',(parseInt(document.getElementById('milight_<?php echo $device->id; ?>_brightness').value) + 5) + '%').button().button('refresh'); }">Heller</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                            </div>
                        </li>
                    </ul>
                  <br>
                </div>
            <?php }
        }
    }
?>
    </ul>
    </div><!-- /content -->
    
</div><!-- /page -->
