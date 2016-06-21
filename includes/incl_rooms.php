<style type="text/css">

.room_switch_button {
    color: #ffffff;
    height: 10px;!important
}
#roomdevices{
    #padding: .5em 15px;
    padding: 0 15px;
    display: block;
    #height: 40px;
}
</style>
<script type="text/JavaScript">
        function showroom(room) {
                $(room).each(function() {
                    $(this).toggle().removeClass('hide').addClass('show');
                });
        }
</script>
<div data-role="page" id="rooms" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Räume]">

    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e" >Favoriten</a>
            <a href="#devices" data-role="button" data-theme="e">Geräte</a>
            <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <a href="#groups" data-role="button" data-theme="e" >Gruppen</a><?php } ?>
            <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <a href="#rooms" data-role="button" data-theme="e" class="ui-disabled">Räume</a><?php } ?>
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
        <h1>Räume</h1>
        <div data-type="horizontal" data-role="controlgroup"  class="ui-btn-right"> 
            <a href="#" id="reloadbtnguiroom" data-role="button" data-iconpos="notext" data-icon="refresh"></a>
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
			<li id="sysalertmsg_rooms" style="display:none;background-color:#B63737;-moz-border-radius:.5em;-webkit-border-radius:.5em;border-radius:.5em;">
                <div class="ui-grid-a" style="width:100%;background-color:#B63737;">
                    <div class="ui-block-a" style="width:100%;background-color:#B63737;text-align:left">
                        <h2><?php echo $SysAlertMsg; ?></h2>
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
                        <div class="ui-block-a" style="cursor:pointer;-webkit-tap-highlight-color: rgba(0,0,0,0);" onClick="showroom(<?php echo $room; ?>hide);">
                            <?php echo $room; ?>
                        </div>
                        <div class="ui-block-b" style="text-align:right" id="room_switch_button">
                    <?php
                      #  if($xml->gui->showRoomButtonInDevices == "true") {
                    ?>
                            <div class="box-btn-switch" id="room_switch_button">
                                <button data-theme="g" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_connair('on','room','<?php echo $room; ?>')">EIN</button>
                                <button data-theme="r" data-theme="<?php echo $theme_row; ?>" data-mini="true" data-inline="true" onclick="send_connair('off','room','<?php echo $room; ?>')">AUS</button>
                            </div>
                    <?php
                       # }
                    ?>
                        </div>
                    </div>
                
            </li>

        <div id="<?php echo $room; ?>hide" class="hide">
            <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-filter-theme="<?php echo $theme_divider; ?>" data-inset="false">
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
                $btnOnJS="send_connair('on','device','".$device->id."'); switchRowTheme('on','".$device->id."','".$rowOnDataTheme."','".$rowOffDataTheme."')";
                $btnOffJS="send_connair('off','device','".$device->id."'); switchRowTheme('off','".$device->id."','".$rowOnDataTheme."','".$rowOffDataTheme."')";
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
                $btnOnJS="send_connair('on','device','".$device->id."'); switchButtonTheme('on','".$device->id."','".$btnOnColor."','".$btnOffColor."','".$btnCurColor."')";
                $btnOffJS="send_connair('off','device','".$device->id."'); switchButtonTheme('off','".$device->id."','".$btnOnColor."','".$btnOffColor."','".$btnCurColor."')";
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
                $btnOnJS="send_connair('on','device','".$device->id."'); switchButtonIcon('on','".$device->id."','".$onIcon."','".$offIcon."')";
                $btnOffJS="send_connair('off','device','".$device->id."'); switchButtonIcon('off','".$device->id."','".$onIcon."','".$offIcon."')";
            break;
            default:
                $rowDataTheme=$theme_row;
                $btnOnDataTheme="g";
                $btnOffDataTheme="r";
                $btnOnIcon="";
                $btnOnJS="send_connair('on','device','".$device->id."')";
                $btnOffJS="send_connair('off','device','".$device->id."')";
            break;
        }

?>
                <li data-role="fieldcontain" data-filter="false" data-inset="false" id="deviceRow<?php echo $device->id; ?>" data-theme="<?php echo $rowDataTheme; ?>" >
                    <div class="ui-grid-a" id="roomdevices">
                        <div class="ui-block-a" style="text-align:left; font-size: 8px;!important" >
                            <h2><?php echo $device->name; php?></h2>
                        <?php 
                            if($debug == "true") {
                                echo "<p><i>".$device->id." ".$device->vendor." ".$device->address->masterdip." ".$device->address->slavedip."</i></p>";
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
        ?>
        
                </ul>
             <br><br>
            </div>
        <?php
    }
?>
         </ul>
    </div><!-- /content -->

</div><!-- /page -->
