<?php
if(isset($_GET['id']) AND $_GET['mode'] == "edit")
{
    $xpath='//person/id[.="'.$_GET['id'].'"]/parent::*';
    $res = $xml->xpath($xpath); 
    $parent = $res[0];
    
}elseif($_GET['mode'] == "new"){
    foreach($xml->groups->group as $group) {
        $oldid=(integer)$group->id;
        if($oldid >= $newid) {
            $_GET['id'] = $oldid + 1;
        }
    }
}
?>
<script type="text/javascript">
$(document).ready(function() {
        $('#editpersonsubmit').click(function (e) {
            $.ajax({
                url: "edit_person.php",
                type: "POST",
                data: $('#editpersonform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage()}, 2000);
                        location.href = 'index.php#configurations';
                        toast('Gespeichert');
                        resetNewTimerForm();
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        });
});
</script>
<div data-role="page" id="user" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Einstellungen]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <?php
        if($_GET['mode'] != "edit" && $_GET['mode'] != "new") { ?>
            <!-- <a href="#configurations" data-transition="slide" data-direction="reverse">Einstellungen</a> -->
        <?php } ?>
        <h1>Benutzerverwaltung</h1>
        <?php
        if($_GET['mode'] == "edit") {
                if($parent[0]->name != "Standard-Benutzer"){ echo'<button data-theme="r" data-inline="true" data-icon="delete" data-mini="true" onclick="delete_person('.$_GET['id'].')">Löschen</button>'; }
                //else echo '<a href="index.php#configurations" rel="external" data-role="button" data-theme="r">Abbrechen</a>';
                else echo '<a style="display:none;" href=""></A>';
                echo '<a href="#" id="editpersonsubmit" data-role="button" data-theme="g">Speichern</a>';
        }elseif($_GET['mode'] == "new"){
                echo '<a onClick="window.location.href = \'index.php#configurations\'" rel="external" data-role="button" data-theme="r">Abbrechen</a>';
                echo '<a href="#" id="editpersonsubmit" data-role="button" data-theme="g">Speichern</a>';
        }else{
                echo'
                    <div data-type="horizontal" data-role="controlgroup" class="ui-btn-right"> 
                        <a onClick="window.location.href = \'index.php?mode=new#user\'" rel="external" id="newuserButton" data-role="button" data-iconpos="notext" data-icon="plus"></a>
                    </div>';
        }
        ?>
    </div><!-- /header -->

    <div data-role="content" style="margin: 20px 0px 0px 0px;">
                <form id="editpersonform"  data-ajax="false" style="margin: 0; padding:0;">
                <input type="hidden" id="id" name="id" value="<?php echo $_GET['id']?>" />
                <input type="hidden" id="action" name="action" value="<?php echo $_GET["mode"] == "new" ? 'add' : 'edit'; ?>" />


<?php
            //echo'<ul data-role="listview" data-theme="'.$theme_row.'" data-divider-theme="'.$theme_divider.'" >';
            echo'<ul data-role="listview" data-theme="'.$theme_row.'" data-divider-theme="'.$theme_divider.'" data-inset="false">';

            if(isset($_GET['mode'])){
            
                echo'
                <li data-role="list-divider">
                    '.$parent[0]->name.'
                </li>
                <li data-role="fieldcontain">
                    <label for="name">Name:</label>
                    <input name="name" id="name" value="'.$parent[0]->name.'" type="text"';
                    if($parent[0]->name == "Standard-Benutzer") { echo ' readonly>'; } else { echo ' data-clear-btn="true">'; }
                echo '</li>';

                if($parent[0]->name != "Standard-Benutzer") {
                echo '<li data-role="fieldcontain">
                    <label for="ip">IP-Adresse:</label>
                    <input name="ip" id="ip" value="'.$parent[0]->pingto.'" data-clear-btn="true" type="text" placeholder="'.$_SERVER['REMOTE_ADDR'].'">
                </li>'; }
                
                echo '<li data-role="fieldcontain">
                    <label for="theme">Design:</label>
                    <select name="theme" id="theme">
                        <option value="LIGHT"'; if($parent[0]->theme == "LIGHT") { echo " selected"; } echo '>Hell</option>
                        <option value="DARK"'; if($parent[0]->theme == "DARK") { echo " selected"; } echo'>Dunkel</option>
                    </select>
                </li>';?>
                <li data-role="fieldcontain">
                    <label for="theme_bg">Desktop-Hintergrund:</label>
                    <select name="theme_bg" id="theme_bg">
                        <optgroup label="Bilder">
                        <option value="desktop_bg_asteroids" <?php if($parent[0]->theme_bg == "desktop_bg_asteroids") { echo "selected"; } ?>>Asteroids</option>
                        <option value="desktop_bg_beach" <?php if($parent[0]->theme_bg == "desktop_bg_beach") { echo "selected"; } ?>>Beach</option>
                        <option value="desktop_bg_black_mesa" <?php if($parent[0]->theme_bg == "desktop_bg_black_mesa") { echo "selected"; } ?>>Black Mesa</option>
                        <option value="desktop_bg_black_mesa_skin" <?php if($parent[0]->theme_bg == "desktop_bg_black_mesa_skin") { echo "selected"; } ?>>Black Mesa Skin</option>
                        <option value="desktop_bg_cars" <?php if($parent[0]->theme_bg == "desktop_bg_cars") { echo "selected"; } ?>>Disneys Cars</option>
                        <option value="desktop_bg_library" <?php if($parent[0]->theme_bg == "desktop_bg_library") { echo "selected"; } ?>>Library</option>
                        <option value="desktop_bg_milltown" <?php if($parent[0]->theme_bg == "desktop_bg_milltown") { echo "selected"; } ?>>Milltown</option>
                        <option value="desktop_bg_pirates_grave" <?php if($parent[0]->theme_bg == "desktop_bg_pirates_grave") { echo "selected"; } ?>>Pirates Grave</option>
                        <option value="desktop_bg_rage" <?php if($parent[0]->theme_bg == "desktop_bg_rage") { echo "selected"; } ?>>Rage</option>
                        <option value="desktop_bg_scumm_bar" <?php if($parent[0]->theme_bg == "desktop_bg_scumm_bar") { echo "selected"; } ?>>SCUMM Bar</option>
                        <option value="desktop_bg_shelter" <?php if($parent[0]->theme_bg == "desktop_bg_shelter") { echo "selected"; } ?>>Shelter</option>
                        <option value="desktop_bg_stalker_kitten" <?php if($parent[0]->theme_bg == "desktop_bg_stalker_kitten") { echo "selected"; } ?>>Stalker Kitten</option>
                        <option value="desktop_bg_stalker_kitten_green" <?php if($parent[0]->theme_bg == "desktop_bg_stalker_kitten_green") { echo "selected"; } ?>>Stalker Kitten Camouflage</option>
                        </optgroup>
                        <optgroup label="Strukturen">
                        <option value="desktop_bg_02" <?php if($parent[0]->theme_bg == "desktop_bg_02") { echo "selected"; } ?>>Helles Holz</option>
                        <option value="desktop_bg_03" <?php if($parent[0]->theme_bg == "desktop_bg_03") { echo "selected"; } ?>>Dunkles Holz</option>
                        <option value="desktop_bg_04" <?php if($parent[0]->theme_bg == "desktop_bg_04") { echo "selected"; } ?>>Schwarzes Holz</option>
                        <option value="desktop_bg_05" <?php if($parent[0]->theme_bg == "desktop_bg_05") { echo "selected"; } ?>>Evolution</option>
                        <option value="desktop_bg_07" <?php if($parent[0]->theme_bg == "desktop_bg_07") { echo "selected"; } ?>>Wild Oliva</option>
                        <option value="desktop_bg_08" <?php if($parent[0]->theme_bg == "desktop_bg_08") { echo "selected"; } ?>>Escheresque dunkel</option>
                        </optgroup>
                        <optgroup label="CSS">              
                        <option value="desktop_bg_06" <?php if($parent[0]->theme_bg == "desktop_bg_06") { echo "selected"; } ?>>Cicada Streifen</option>
                        <option value="desktop_bg_09" <?php if($parent[0]->theme_bg == "desktop_bg_09") { echo "selected"; } ?>>Carbon</option>
                        <option value="desktop_bg_black" <?php if($parent[0]->theme_bg == "desktop_bg_black") { echo "selected"; } ?>>Schwarz</option>
                        </optgroup>
                    </select>
                </li>
                <li data-role="fieldcontain">
                    <label for="IndoorTempSource">Sensor für Innentemperatur (FBdect200):</label>
                    <select name="IndoorTempSource" id="IndoorTempSource">
                        <?php
                            $ActSource = $parent[0]->IndoorTempSource;
                            $devices = array();
                            foreach($xml->devices->device as $device) {
                                if ($device->vendor == "fbdect200") $devices[] = $device;
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
                            echo "<option value=\"99999\">Keine Anzeige</option>";
                            foreach($devices as $device ) {
                            	?>
                            	<option value="<?php echo $device->id; ?>" <?php if ((int)$ActSource == (int)$device->id) { echo "selected"; } ?>><?php echo $device->id.": ".$device->name." (".$device->room.")"; ?></option>
                            	<?php
                            }
                            ?>
                    </select>
                </li>
                <li data-role="fieldcontain">
                    <label for="ShowSettingsMenue">Konfig-Menü anzeigen:</label>
                    <select name="ShowSettingsMenue" id="ShowSettingsMenue" data-role="slider">
                        <option value="false" <?php if($parent[0]->ShowSettingsMenue == "false") { echo "selected"; } ?>>Nein</option>
                        <option value="true" <?php if($parent[0]->ShowSettingsMenue == "true") { echo "selected"; } ?>>Ja</option>
                    </select>
                </li>
                <li data-role="fieldcontain">
                    <label for="ShowFBdect200EnergyData">Energiedaten von FBdect200-Geräten anzeigen:</label>
                    <select name="ShowFBdect200EnergyData" id="ShowFBdect200EnergyData" data-role="slider">
                        <option value="false" <?php if($parent[0]->ShowFBdect200EnergyData == "false") { echo "selected"; } ?>>Nein</option>
                        <option value="true" <?php if($parent[0]->ShowFBdect200EnergyData == "true") { echo "selected"; } ?>>Ja</option>
                    </select>
                </li>
                <li data-role="fieldcontain">
                    <label for="AutoRefreshDeviceData">Geräte-Daten dynamisch aktualisieren:</label>
                    <select name="AutoRefreshDeviceData" id="AutoRefreshDeviceData" data-role="slider">
                        <option value="false" <?php if($parent[0]->AutoRefreshDeviceData == "false") { echo "selected"; } ?>>Nein</option>
                        <option value="true" <?php if($parent[0]->AutoRefreshDeviceData == "true") { echo "selected"; } ?>>Ja</option>
                    </select>
                </li>
                <li data-role="list-divider">
                    Favoriten
                </li>
                <li data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-mini="true" data-type="vertical" id = "favoritdevices">
                       <legend>Geräte:</legend>
                        <?php
                            $favoritdevices = explode(",",$parent[0]->favoritdevices);
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
                            foreach($devices as $device ) {
                            ?>
                                <input type="checkbox" name="favoritdevices[]" id="<?php echo $device->id; ?>" value="<?php echo $device->id;?>" <?php echo (in_array($device->id, $favoritdevices)) ? 'checked="checked"' : ''  ?>  />
                                <label for="<?php echo $device->id; ?>"><?php echo "$device->name ($device->room)" ?></label>
                            <?php
                            }
                            ?>
                    </fieldset>
                </li>
                <li data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-mini="true" data-type="vertical" id ="timerdays_select">
                       <legend>Gruppen:</legend>
                        <?php
                            $favoritgroups = explode(",",$parent[0]->favoritgroups);
                            $groups = array();
                            foreach($xml->groups->group as $group) {
                                $groups[] = $group;
                            }
                            switch ($xml->gui->sortOrderDevices){
                                case "SORT_BY_NAME":
                                    usort($groups, "compareDevicesByName");
                                    break;
                                case "SORT_BY_ID":
                                    usort($groups, "compareDevicesByID");
                                    break;
                                default:
                                    break;
                            }
                            foreach($groups as $group ) {
                            ?>
                                <input type="checkbox" name="favoritgroups[]" id="<?php echo $group->id; ?>" value="<?php echo $group->id;?>" <?php echo (in_array($group->id, $favoritgroups)) ? 'checked="checked"' : ''  ?>  />
                                <label for="<?php echo $group->id; ?>"><?php echo "$group->name" ?></label>
                            <?php
                            }
                            ?>
                    </fieldset>
                </li>
                    <?php
                        $favoritactions = explode(",",$parent[0]->favoritactions);
                        $actions = array();
                        foreach($xml->actions->action as $action) {
                            $actions[] = $action;
                        }
                        switch ($xml->gui->sortOrderDevices){
                            case "SORT_BY_NAME":
                                usort($actions, "compareDevicesByName");
                                break;
                            case "SORT_BY_ID":
                                usort($actions, "compareDevicesByID");
                                break;
                            default:
                                break;
                        }
				if (count($actions) > 0) { ?>
                <li data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-mini="true" data-type="vertical" id ="timerdays_select">
                       <legend>Aktionen:</legend>
						<?php foreach($actions as $action ) { ?>
                                <input type="checkbox" name="favoritactions[]" id="<?php echo $action->id; ?>" value="<?php echo $action->id;?>" <?php echo (in_array($action->id, $favoritactions)) ? 'checked="checked"' : ''  ?>  />
                                <label for="<?php echo $action->id; ?>"><?php echo "$action->name" ?></label>
                            <?php
                            }
                            ?>
                    </fieldset>
                </li>
<?php 			}
            }else{
                foreach($xml->persons->person as $person){
                    echo '<li><a onClick="window.location.href =\'index.php?mode=edit&id='.$person->id.'#user\'" rel="external">';
                            if ($xml->global->timerPingUser == "true") {
                                echo '<p class="ui-li-desc ui-li-aside" style="font-size: 16px; font-weight: bold; display: block; margin: .0em 0; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" >';
                                echo $person->status;
                                echo '</p>';
                                echo '<h3>'.$person->name.'</h3>';
                            }
                            else echo '<h3 style="width:80%">'.$person->name.'</h3>';
                            echo '</a>
                        </li>';
                }
            }
?>
        </ul>
                </form>
    </div><!-- /content -->
</div><!-- /page -->
