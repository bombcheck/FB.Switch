<script type="text/JavaScript">
$(document).ready(function() {
    $("#timertype_device").change(function() {
                $("#typeiddevice_box").removeClass('hide').addClass('show');
                $("#typeidgroup_box").removeClass('show').addClass('hide');
                $("#typeidroom_box").removeClass('show').addClass('hide');
    });
    $("#timertype_group").change(function() {
                $("#typeiddevice_box").removeClass('show').addClass('hide');
                $("#typeidgroup_box").removeClass('hide').addClass('show');
                $("#typeidroom_box").removeClass('show').addClass('hide');
    });
    $("#timertype_room").change(function() {
                $("#typeiddevice_box").removeClass('show').addClass('hide');
                $("#typeidgroup_box").removeClass('show').addClass('hide');
                $("#typeidroom_box").removeClass('hide').addClass('show');
    });

    $("#OnTimerType").change(function() {
        var $this = $(this);
        switch($this.val()) {
            case "M":
                $("#timeronmanuell_box").removeClass('hide').addClass('show');
                $("#timerontime_box").removeClass('show').addClass('hide');
                $("#timeronoffset_box").removeClass('show').addClass('hide');
                break;
            case "A":
                $("#timeronmanuell_box").removeClass('show').addClass('hide');
                $("#timerontime_box").removeClass('hide').addClass('show');
                $("#timeronoffset_box").removeClass('show').addClass('hide');
                break;
            case "SU":
                $("#timeronmanuell_box").removeClass('show').addClass('hide');
                $("#timerontime_box").removeClass('show').addClass('hide');
                $("#timeronoffset_box").removeClass('hide').addClass('show');
                break;
            case "SD":
                $("#timeronmanuell_box").removeClass('show').addClass('hide');
                $("#timerontime_box").removeClass('show').addClass('hide');
                $("#timeronoffset_box").removeClass('hide').addClass('show');
                break;
        }
    });
    $("#OffTimerType").change(function() {
        var $this = $(this);
        switch($this.val()) {
            case "M":
                $("#timeroffmanuell_box").removeClass('hide').addClass('show');
                $("#timerofftime_box").removeClass('show').addClass('hide');
                $("#timeroffoffset_box").removeClass('show').addClass('hide');
                break;
            case "A":
                $("#timeroffmanuell_box").removeClass('show').addClass('hide');
                $("#timerofftime_box").removeClass('hide').addClass('show');
                $("#timeroffoffset_box").removeClass('show').addClass('hide');
                break;
            case "SU":
                $("#timeroffmanuell_box").removeClass('show').addClass('hide');
                $("#timerofftime_box").removeClass('show').addClass('hide');
                $("#timeroffoffset_box").removeClass('hide').addClass('show');
                break;
            case "SD":
                $("#timeroffmanuell_box").removeClass('show').addClass('hide');
                $("#timerofftime_box").removeClass('show').addClass('hide');
                $("#timeroffoffset_box").removeClass('hide').addClass('show');
                break;
        }
    });
});

function resetNewTimerForm() {
    $('#newtimerform')[0].reset();
    $("#timerdays").removeClass('hide').addClass('show');
    $("#usageping").removeClass('hide').addClass('show');
    $("#usagetimer").removeClass('hide').addClass('show');
    $("#pingontime_box").removeClass('show').addClass('hide');
    $("#typeiddevice_box").removeClass('hide').addClass('show');
    $("#typeidgroup_box").removeClass('show').addClass('hide');
    $("#typeidroom_box").removeClass('show').addClass('hide');
    $("#timeronoffset_box").removeClass('show').addClass('hide');
    $("#timeroffoffset_box").removeClass('show').addClass('hide');
    $("#timeroffmanuell_box").removeClass('hide').addClass('show');
    $("#timeronmanuell_box").removeClass('hide').addClass('show');
}

function onload() {
    $("#timerdays").removeClass('hide').addClass('show');
    $("#usageping").removeClass('hide').addClass('show');
    $("#usagetimer").removeClass('hide').addClass('show');
    $("#pingontime_box").removeClass('show').addClass('hide');
    $("#typeiddevice_box").removeClass('hide').addClass('show');
    $("#typeidgroup_box").removeClass('show').addClass('hide');
    $("#typeidroom_box").removeClass('show').addClass('hide');
    $("#timeronoffset_box").removeClass('show').addClass('hide');
    $("#timeroffoffset_box").removeClass('show').addClass('hide');
    $("#timeroffmanuell_box").removeClass('show').addClass('hide');
    $("#timeronmanuell_box").removeClass('show').addClass('hide');
};

$(document).ready(function() {
    $("#timer_time").change(function() {
                $("#timerdays").removeClass('hide').addClass('show');
                $("#usageping").removeClass('show').addClass('hide');
                $("#usagetimer").removeClass('hide').addClass('show');
                $("#pingontime_box").removeClass('show').addClass('hide');
    });
    $("#timer_ping").change(function() {
                $("#timerdays").removeClass('show').addClass('hide');
                $("#usageping").removeClass('hide').addClass('show');
                $("#usagetimer").removeClass('show').addClass('hide');
                $("#pingontime_box").removeClass('hide').addClass('show');
    });
    $("#timer_pingtime").change(function() {
                $("#timerdays").removeClass('hide').addClass('show');
                $("#usageping").removeClass('hide').addClass('show');
                $("#usagetimer").removeClass('hide').addClass('show');
                $("#pingontime_box").removeClass('show').addClass('hide');
    });
});
</script>
<div data-role="page" id="newtimer" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Timer bearbeiten]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <?php
        if($_GET['mode'] == 'edittimer'){
        echo '<a data-rel="external" data-transition="none" data-direction="reverse" data-role="button" data-theme="r" onClick="window.location.href = \'index.php#timers\'">Abbrechen</a>';
        echo'<h1>Timer bearbeiten</h1>';
        }else{
        echo '<a href="#timers" data-transition="none" data-direction="reverse" data-role="button" data-theme="r" onClick="resetNewTimerForm();">Abbrechen</a>';
        echo'<h1>Neuer Timer</h1>';
        } ?>
        <a href="#" id="newtimersubmit" data-role="button" data-theme="g">Speichern</a>
    </div><!-- /header -->

    <?php
    if($_GET['mode'] == 'edittimer'){
        $xpath='//timer/id[.="'.$_GET['id'].'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0]; 
        $data = array(
            'id' => $parent->id,
            'active' => $parent->active,
            'type' => $parent->type,
            'typeid' => $parent->typeid,
            'day' => $parent->day,
            'pingstatus' => $parent->pingstatus,
            'usage' => $parent->usage,
            'pingto' => $parent->pingto,
            'timerOn' => $parent->timerOn,
            'timerOff' => $parent->timerOff,
            'invertSwitchOnNoPing' => $parent->invertSwitchOnNoPing,
        );
    }else{
        $data = array(
            'id' => '',
            'active' => '',
            'type' => 'selected',
            'typeid' => '',
            'day' => '',
            'pingstatus' => 'OFF',
            'usage' => 'selected',
            'pingto' => '',
            'timerOn' => '',
            'timerOff' => '',
            'invertSwitchOnNoPing' => 'false',
        );
    }
    ?>
    
    <div data-role="content">
        <form id="newtimerform" method="post">
        <input class="newtimerformaction" type="hidden" name="action" id="action" value="<?php echo ($_GET['mode'] == 'edittimer') ? 'edit' : 'add' ?>" />
        <input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
    <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false">
        
        <li data-role="fieldcontain">
            <label for="active">Aktiv:</label>
            <select name="active" id="active" data-role="slider">
                <option <?php echo ($data['active'] == "off") ? 'selected="selected"' : '' ?> value="off">Nein</option>
                <option <?php echo ($data['active'] == "on") ? 'selected="selected"' : '' ?> value="on">Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <fieldset id="usage" data-role="controlgroup" data-mini="false" data-type="horizontal">
                <legend>Vorraussetzung:</legend>
                    <input type="radio" name="usage" id="timer_pingtime" value="time_ping" <?php echo ($data['usage'] == "time_ping" or $data['usage'] == "selected") ? 'checked="checked"' : '' ?> >
                    <label for="timer_pingtime">Zeit und Ping</label>
        
                    <input type="radio" name="usage" id="timer_time" value="time" <?php echo ($data['usage'] == "time") ? 'checked="checked"' : '' ?> >
                    <label for="timer_time">Zeit</label>
        
                    <input type="radio" name="usage" id="timer_ping" value="ping" <?php echo ($data['usage'] == "ping") ? 'checked="checked"' : '' ?> >
                    <label for="timer_ping">Ping</label>
        
            </fieldset>
        </li>
        <li data-role="fieldcontain">
                <fieldset id="timertypecontrolgroup" data-role="controlgroup" data-mini="false" data-type="horizontal">
                   <legend>Typ:</legend>
                        <input type="radio" name="timertype" id="timertype_device" value="device" <?php echo ($data['type'] == "device" or $data['type'] == "selected") ? 'checked="checked"' : '' ?> />
                        <label for="timertype_device">Gerät</label>
            
                        <input type="radio" name="timertype" id="timertype_group" value="group" <?php echo ($data['type'] == "group") ? 'checked="checked"' : '' ?> />
                        <label for="timertype_group">Gruppe</label>
            
                        <input type="radio" name="timertype" id="timertype_room" value="room" <?php echo ($data['type'] == "room") ? 'checked="checked"' : '' ?> />
                        <label for="timertype_room">Raum</label>
            
                </fieldset>
            </li>           
        <li data-role="fieldcontain">
            <div data-role="fieldcontain" id="typeiddevice_box" <?php echo ($data['type'] == "device" or $data['type'] == "selected") ? 'class="show"' : 'class="hide"' ?> >
                <label for="typeiddevice">Gerät:</label>
                <select name="typeiddevice" id="typeiddevice" data-mini="false">
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
                            echo "<option "; echo ((string)$device->id == (string)$data['typeid']) ? 'selected="selected"' : ''; echo " value='".$device->id."'>".$device->name ." (".$device->room .")</option>";
                        }
                     ?>
                </select>
            </div>
                        
            <div data-role="fieldcontain" id="typeidgroup_box" <?php echo ($data['type'] == "group") ? 'class="show"' : 'class="hide"' ?> >
                <label for="typeidgroup">Gruppe:</label>
                <select name="typeidgroup" id="typeidgroup" data-mini="false">
                     <?php
                        $groups = array();
                        foreach($xml->groups->group as $group) {
                            $groups[] = $group;
                        }
                        switch ($xml->gui->sortOrderGroups){
                            case "SORT_BY_NAME":
                                usort($groups, "compareGroupsByName");
                                break;
                            case "SORT_BY_ID":
                                usort($groups, "compareGroupsByID");
                                break;
                            default:
                                break;
                        }
                        foreach($groups as $group) {
                            echo "<option "; echo ((string)$group->id == (string)$data['typeid']) ? 'selected="selected"' : ''; echo " value='".$group->id."'>".$group->name."</option>";
                        }
                     ?>
                </select>
            </div>
                        
            <div data-role="fieldcontain" id="typeidroom_box" <?php echo ($data['type'] == "room") ? 'class="show"' : 'class="hide"' ?> >
                <label for="typeidroom">Raum:</label>
                <select name="typeidroom" id="typeidroom" data-mini="false">
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
                            
                            echo "<option "; echo ((string)$room == (string)$data['typeid']) ? 'selected="selected"' : ''; echo " value='".$room."'>".$room."</option>";
                        }
                     ?>
                </select>
            </div>
        </li>
        
<?php
$day  = array();
$string = (string)$data['day'];

for ($i = 0; $i < strlen($string); $i++) {
    $day[] = $string{$i};
}
?>
    <div id="timerdays" <?php echo ($data['usage'] != "ping") ? 'class="show"' : 'class="hide"' ?> class="usagetimer" data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false" >            
        <li data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-mini="true" data-type="horizontal" id ="timerdays_select">
                       <legend>Tage:</legend>
                            <input type="checkbox" name="timerday[]" id="timerday-1" value="0" <?php echo ($day['0'] == "M") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-1">M</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-2" value="1" <?php echo ($day['1'] == "D") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-2">D</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-3" value="2" <?php echo ($day['2'] == "M") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-3">M</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-4" value="3" <?php echo ($day['3'] == "D") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-4">D</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-5" value="4" <?php echo ($day['4'] == "F") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-5">F</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-6" value="5" <?php echo ($day['5'] == "S") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-6">S</label>
                
                            <input type="checkbox" name="timerday[]" id="timerday-7" value="6" <?php echo ($day['6'] == "S") ? 'checked="checked"' : ''  ?> />
                            <label for="timerday-7">S</label>
                    </fieldset>
        </li>
    </div>

        <div id="usagetimer" <?php echo ($data['usage'] != "ping") ? 'class="show"' : 'class="hide"' ?> class="usagetimer" data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false" >
            <li data-role="fieldcontain">
                    <label for="OnTimerType">An:</label>
                    <select name="OnTimerType" id="OnTimerType" data-mini="false">
                        <option value="M" <?php echo ( $data['timerOn'] == '' ) ? 'selected="selected"' : '' ?> >Manuell</option>
                        <option value="A" <?php echo ( ($data['timerOn'] != 'SD') && ($data['timerOn'] != 'SU') && ($data['timerOn'] != "") )  ? 'selected="selected"' : '' ?> >Automatik</option>
                        <option value="SU" <?php echo ( $data['timerOn'] == 'SU' )  ? 'selected="selected"' : '' ?> >Sonnenaufgang (<?php echo date('H:i', $sunrise); ?>)</option>
                        <option value="SD" <?php echo ( $data['timerOn'] == 'SD' )  ? 'selected="selected"' : '' ?> >Sonnenuntergang (<?php echo date('H:i', $sunset); ?>)</option>
                    </select>
            </li>
            <li data-role="fieldcontain">
                <div id="timeronmanuell_box" <?php echo ( $data['timerOn'] == '' ) ? 'class="show"' : 'class="hide"' ?> >
                    Dieser Timer schaltet nicht ein.
                </div>
                <div data-role="fieldcontain" id="timerontime_box" <?php echo (  ($data['timerOn'] != 'SD') && ($data['timerOn'] != 'SU') && ($data['timerOn'] != "")  ) ? 'class="show"' : 'class="hide"' ?> >
                    <fieldset id="timerontime" data-role="controlgroup" data-type="horizontal">
                        <legend>Uhrzeit:</legend>
                   
                        <label for="OnTimerHH">Stunden</label>
                        <select name="OnTimerHH" id="OnTimerHH" data-mini="false">
                            <option>Stunden</option>
                            <?php
                            $string = substr($data['timerOn'], -5 ,-3);
                            for ($i = 0; $i <= 23; $i++) {
                                ?>
                                <option <? echo(intval($string) == sprintf ("%02d", $i) ) ? 'selected="selected"': '' ?> value="<?php echo sprintf ("%02d", $i)?>"> <?php echo sprintf ("%02d", $i); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                        <label for="OnTimerMM">Minuten</label>
                        <select name="OnTimerMM" id="OnTimerMM" data-mini="false">
                            <option>Minuten</option>
                            <?php
                            $string = substr($data['timerOn'], 3 ,5);
                            for ($i = 0; $i <= 59; $i++) {
                                ?>
                                <option <? echo(intval($string) == sprintf ("%02d", $i) ) ? 'selected="selected"': '' ?> value="<?php echo sprintf ("%02d", $i)?>"> <?php echo sprintf ("%02d", $i); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                    </fieldset>
                </div>
                <div data-role="fieldcontain" id="timeronoffset_box" <?php echo (  ($data['timerOn'] == 'SD') or ($data['timerOn'] == 'SU') && ($data['timerOff'] != "")  ) ? 'class="show"' : 'class="hide"' ?> >
                           <label for="timeronoffset">Offset:</label>
                           <input type="range" name="timeronoffset" id="timeronoffset" value="<?echo (isset($data['timerOn']['offset'])) ? $data['timerOn']['offset'] : '0' ?>" min="-240" max="240" step="5" />
                </div>
            </li>
            <li data-role="fieldcontain">
                    <label for="OffTimerType">Aus:</label>
                    <select name="OffTimerType" id="OffTimerType" data-mini="false">
                        <option value="M" <?php echo ( $data['timerOff'] == '' ) ? 'selected="selected"' : '' ?> >Manuell</option>
                        <option value="A" <?php echo ( ($data['timerOff'] != 'SD') && ($data['timerOff'] != 'SU') && ($data['timerOff'] != "") )  ? 'selected="selected"' : '' ?> >Automatik</option>
                        <option value="SU" <?php echo ( $data['timerOff'] == 'SU' )  ? 'selected="selected"' : '' ?> >Sonnenaufgang (<?php echo date('H:i', $sunrise); ?>)</option>
                        <option value="SD" <?php echo ( $data['timerOff'] == 'SD' )  ? 'selected="selected"' : '' ?> >Sonnenuntergang (<?php echo date('H:i', $sunset); ?>)</option>
                    </select>
            </li>
            <li data-role="fieldcontain">
                <div id="timeroffmanuell_box" <?php echo ( $data['timerOff'] == '' ) ? 'class="show"' : 'class="hide"' ?> >
                    Dieser Timer schaltet nicht aus.
                </div>
                <div data-role="fieldcontain" id="timerofftime_box" <?php echo (  ($data['timerOff'] != 'SD') && ($data['timerOff'] != 'SU') && ($data['timerOff'] != "")  ) ? 'class="show"' : 'class="hide"' ?> >
                     <fieldset id="timerofftime" data-role="controlgroup" data-type="horizontal">
                        <legend>Uhrzeit:</legend>
                   
                        <label for="OffTimerHH">Stunden</label>
                        <select name="OffTimerHH" id="OffTimerHH" data-mini="false">
                            <option>Stunden</option>
                            <?php
                            $string = substr($data['timerOff'], -5 ,-3);
                            for ($i = 0; $i <= 23; $i++) {
                                ?>
                                <option <? echo(intval($string) == sprintf ("%02d", $i) ) ? 'selected="selected"': '' ?> value="<?php echo sprintf ("%02d", $i)?>"> <?php echo sprintf ("%02d", $i); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                   
                        <label for="OffTimerMM">Minuten</label>
                        <select name="OffTimerMM" id="OffTimerMM" data-mini="false">
                            <option>Minuten</option>
                            <?php
                            $string = substr($data['timerOff'], 3 ,5);
                            for ($i = 0; $i <= 59; $i++) {
                                ?>
                                <option <? echo(intval($string) == sprintf ("%02d", $i) ) ? 'selected="selected"': '' ?> value="<?php echo sprintf ("%02d", $i)?>"> <?php echo sprintf ("%02d", $i); ?> </option>
                                <?php
                            }
                            ?>
                        </select>
                          
                    </fieldset>
                </div>
                <div data-role="fieldcontain" id="timeroffoffset_box" <?php echo (  ($data['timerOff'] == 'SD') or ($data['timerOff'] == 'SU')  ) ? 'class="show"' : 'class="hide"' ?> >
                               <label for="timeroffoffset">Offset:</label>
                               <input type="range" name="timeroffoffset" id="timeroffoffset" value="<?echo (isset($data['timerOff']['offset'])) ? $data['timerOff']['offset'] : '0' ?>" min="-240" max="240" step="5" />
                </div>
            </li>
        </div>
        <div id="usageping" <?php echo ($data['usage'] != "time") ? 'class="show"' : 'class="hide"' ?> class="usageping"  data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false" >
            <li data-role="fieldcontain">
                <label for="pingto">Abhängige IP oder Name einer Person:</label>
                <input name="pingto" id="pingto" value="<?php echo $data['pingto']; ?>" data-clear-btn="true" type="text" placeholder="<?php echo "Zum Beispiel: ".$xml->persons->person->name; ?>">
            </li>
        </div>
        <div id="pingontime_box" <?php echo ($data['usage'] == "ping") ? 'class="show"' : 'class="hide"' ?> class="usageping"  data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false">
            <li data-role="fieldcontain">
                    <fieldset id="pingstatus" data-role="controlgroup" data-mini="false" data-type="horizontal">
                       <legend>Schaltzustand wenn erreichbar:</legend>
                            <input type="radio" name="pingstatus" id="pingon" value="ON" <?php echo ($data['pingstatus'] == "ON") ? 'checked="checked"' : '' ?>/>
                            <label for="pingon">AN</label>
                
                            <input type="radio" name="pingstatus" id="pingoff" value="OFF" <?php echo ($data['pingstatus'] == "OFF") ? 'checked="checked"' : '' ?>/>
                            <label for="pingoff">AUS</label>
                    </fieldset>
            </li>
            <li data-role="fieldcontain">
                <label for="invertSwitchOnNoPing">Schaltzustand bei Unerreichbarkeit invertieren:</label>
                <select name="invertSwitchOnNoPing" class="onOffSwitch" id="invertSwitchOnNoPing" data-role="slider">
                    <option value="false" <?php if($data['invertSwitchOnNoPing'] == "false") { echo "selected"; } ?>>Nein</option>
                    <option value="true" <?php if($data['invertSwitchOnNoPing'] == "true") { echo "selected"; } ?>>Ja</option>
                </select>
            </li>
        </div>
        <li data-role="fieldcontain" class="hide" id="timerdelete_box" >
            <label for="timerdelete_box" class="ui-select">Aktion:</label>
            <div name="timerdelete_box" class="ui-select">
                <a href="#newtimer" name="timerdelete_bt" id="newtimersubmit" data-icon="delete" data-iconpos="right" <?php if($IsMobile == "true") { ?>data-transition="none"<?php } else{?>data-transition="none"<?php }?> data-mini="true" data-role="button" data-theme="r" onClick="$('.newtimerformaction').val('delete');resetNewTimerForm();">Löschen</a>
            </div>
        </li>
    </ul>
        </form>
    </div><!-- /content -->
</div><!-- /page -->
