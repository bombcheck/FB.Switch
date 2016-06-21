<style type="text/css">
#sortable { 
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 60%;
}
#sortable li {
    margin: 0 3px 3px 3px;
    padding: 0.4em;
    padding-left: 1.5em;
    font-size: 1.4em;
    height: 18px;
}
#sortable li span {
    position: absolute;
    margin-left: -1.3em;
 }
</style>
<script type="text/JavaScript">

function resetNewGroupForm() {
    $('#newgroupform')[0].reset();
}
</script><div data-role="page" id="newgroup" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Gruppe bearbeiten]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <?php
        if($_GET['mode'] == 'editgroup'){
        echo '<a onClick="window.location.href = \'index.php#groups\'" data-rel="external" data-transition="none" data-direction="reverse" data-role="button" data-theme="r">Abbrechen</a>';
        echo'<h1>Gruppe bearbeiten</h1>';
        }else{
        echo '<a href="#groups" data-transition="none" data-direction="reverse" data-role="button" data-theme="r" onClick="resetNewGroupForm();">Abbrechen</a>';
        echo'<h1>Neue Gruppe</h1>';
        } ?>
        <a href="#" id="newgroupsubmit" data-role="button" data-theme="g">Speichern</a>
    </div><!-- /header -->

    <div data-role="content">
    
<?php
    if($_GET['mode'] == 'editgroup'){
        $xpath='//group/id[.="'.$_GET['id'].'"]/parent::*';
        $res = $xml->xpath($xpath);
        $parent = $res[0];
        $data = array(
            'id' => $parent->id,
            'Groupname' => $parent->name,

        );
        foreach($parent->deviceid as $deviceid) {
                array_push($data, (string)$deviceid);
        }

    }
    ?>
        <form id="newgroupform" method="post">
        <input class="newgroupformaction" type="hidden" name="action" id="action" value="<?php echo ($_GET['mode'] == 'editgroup') ? 'edit' : 'add' ?>" />
        <input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
    <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider ?>" data-inset="false">
        <li data-role="fieldcontain">

            <label for="Groupname">Gruppenname:</label>
            <input type="text" name="Groupname" id="Groupname" data-clear-btn="true" value="<?php echo $data['Groupname'];  ?>" />
        
        </li>
        <li data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-mini="true" data-type="vertical" id ="timerdays_select">
                       <legend>Geräte:</legend>
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
                            foreach($devices as $device ) {
                            ?>
                                <input type="checkbox" name="<?php echo $device->id; ?>" id="<?php echo $device->id; ?>" value="<?php echo $device->id;?>" <?php echo (in_array($device->id, $data)) ? 'checked="checked"' : ''  ?>  />
                                <label for="<?php echo $device->id; ?>"><?php echo "$device->name ($device->room)" ?></label>
                            <?php
                            }
                            ?>
                    </fieldset>
        </li>


    </ul>
        </form>
    </div><!-- /content -->
</div><!-- /page -->
