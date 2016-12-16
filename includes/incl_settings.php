<script type="text/JavaScript">
function resetEditConfigForm() {
    $('#editconfigform')[0].reset();
}
      
function get_location()
{    
    toast('Ermittle Standort...');
    navigator.geolocation.getCurrentPosition(function(position) {
        $('#latitude').val(position.coords.latitude);
        $('#longitude').val(position.coords.longitude);
    
        script_src = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + position.coords.latitude + "," + position.coords.longitude + "&sensor=false";
        $.getJSON(script_src,function(json){
            Address = json.results[0].formatted_address.split(', ');
            town = Address[1].split(' ');
            country = Address[2];
            zip = town[0];
            place = town[1];
            //zip = json.results[0].address_components[7].long_name;
            //country = json.results[0].address_components[6].short_name;
            //country_l = json.results[0].address_components[6].long_name;
            //state = json.results[0].address_components[4].long_name;
            //place = json.results[0].address_components[2].long_name;
            $('#city').val(place);
            $('#plz').val(zip);
            //$.mobile.activePage.find('#country').val(country+" - "+country_l);
            $('#country').val(country);
            toast('Standort ermittelt');
        });
    });
}

function autodetect_gateway()
{
    toast('Suche Gateway...');
    $.getJSON('findgateway.php',function(json){
        status = json.HCGW1.STATUS;
        vendor = json.HCGW1.VC;
        fw = json.HCGW1.FW;
        ip = json.HCGW1.IP;
        port = json.HCGW1.PORT;
        if (status == "OK") {
            $('#connairIP').val(ip);
            $('#connairPort').val(port);
            $('#gwtechdata').val('Vendor: ' + vendor + ' - FW: ' + fw);
            toast('Gateway gefunden auf '+ip+':'+port);
        }
        else {
            $('#connairIP').val('');
            $('#connairPort').val('');
            $('#gwtechdata').val('');
            toast('KEIN Gateway gefunden!');
        }
    });
}

function autodetect_milight()
{
    toast('Suche MiLight Bridge...');
    $.getJSON('findmilight.php',function(json){
        status = json.MILIGHT10.STATUS;
        mac = json.MILIGHT10.MAC;
        ip = json.MILIGHT10.IP;
        port = json.MILIGHT10.PORT;
        if (status == "OK") {
            $('#milightIP').val(ip);
            $('#milightPort').val(port);
            $('#milightMAC').val(mac);
            toast('MiLight Bridge gefunden auf '+ip+':'+port);
        }
        else {
            $('#milightIP').val('');
            $('#milightPort').val('');
            $('#milightMAC').val('');
            toast('KEINE MiLight Bridge gefunden!');
        }
    });
}
</script>
<div data-role="page" id="configurations" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Einstellungen]">

    <div data-role="panel" id="mypanel" data-position="left" data-display="push" data-animate="<?php echo $menuAnimated; ?>" data-theme="a" data-position-fixed="true">
        <center>
            <a href="#favorites" data-role="button" data-theme="e">Favoriten</a>
            <!--a href="#my-header" data-rel="close" data-role="button" data-theme="b">Favoriten</a-->
            <a href="#devices" data-role="button" data-theme="e">Geräte</a>
            <?php if($xml->gui->showGroupsBtnInMenu == "true") { ?> <a href="#groups" data-role="button" data-theme="e" >Gruppen</a><?php } ?>
            <?php if($xml->gui->showRoomsBtnInMenu == "true") { ?> <a href="#rooms" data-role="button" data-theme="e" >Räume</a><?php } ?>
            <?php if($xml->gui->showTimerBtnInMenu == "true") { ?> <a href="#timers" data-role="button" data-theme="e" >Timer</a><?php } ?> 
            <?php if($xml->gui->showActionBtnInMenu == "true") { ?> <a href="#actions" data-role="button" data-theme="e">Aktionen</a><?php } ?>
            <?php if($ShowSettingsMenue == "true") { ?> <a href="#configurations" data-role="button" data-theme="e" class="ui-disabled" >Einstellungen</a><?php } ?>
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
        <a href="#mypanel" id="menubutton" data-role="button" onClick="resetEditConfigForm();">Menu</a>
        <h1>Einstellungen</h1>
        <a href="#" id="editconfigsubmit" data-role="button" data-theme="g">Speichern</a>
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
                
        <form id="editconfigform" method="post" data-ajax="false">
            <input type="hidden" name="action" id="action" value="edit" />
    <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
        <li data-role="list-divider">
        Design & Benutzer
        </li>
        <li data-role="fieldcontain">
            <!-- <a href="index.php#design" data-rel="external" data-transition="slide">Design</a> -->
            <a href="#design" data-rel="dialog" data-transition="flip">Design</a>
        </li>
        <li data-role="fieldcontain">
            <!-- <a href="index.php#user" data-rel="external" data-transition="slide">Benutzerverwaltung</a> -->
            <a href="#user" data-rel="dialog" data-transition="flip">Benutzerverwaltung</a>
        </li>
        <li>
            <a onClick="window.location.href = 'configeditor.php'" rel="external">config.xml bearbeiten</a>
        </li>
        <li data-role="fieldcontain">
            <label for="multiDeviceSleep">Wartezeit beim Senden (ms):</label>
            <input type="range" name="multiDeviceSleep" id="multiDeviceSleep" value="<?php echo $xml->global->multiDeviceSleep; ?>" min="200" max="5000" step="50" />
        </li>
        <li data-role="fieldcontain">
            <label for="playSounds">Soundeffekte aktiv:</label>
            <select name="playSounds" id="playSounds" data-role="slider">
                <option value="false" <?php if($xml->global->playSounds == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->playSounds == "true" || $xml->global->playSounds == "") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="list-divider">
        Timer
        </li>
        <li data-role="fieldcontain">
            <label for="timerGlobalRun">Timer aktiv:</label>
            <select name="timerGlobalRun" id="timerGlobalRun" data-role="slider">
                <option value="false" <?php if($xml->global->timerGlobalRun == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->timerGlobalRun == "true" || $xml->global->timerGlobalRun == "") { echo "selected"; } ?>>Ja</option>
            </select>
            <div>Die Präsenz- und FBdect200-Prüfung wird von dieser Einstellung nicht beeinflusst.</div>
        </li>
        <li data-role="fieldcontain">
            <label for="timerRunOnce">Timer schaltet nur wenn nötig:</label>
            <select name="timerRunOnce" id="timerRunOnce" data-role="slider">
                <option value="false" <?php if($xml->global->timerRunOnce == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->timerRunOnce == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="timerPingUser">Timer prüft Präsenz der Benutzer:</label>
            <select name="timerPingUser" id="timerPingUser" data-role="slider">
                <option value="false" <?php if($xml->global->timerPingUser == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->timerPingUser == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="timerCheckFBdect200">Timer prüft Status von FBdect200-Geräten:</label>
            <select name="timerCheckFBdect200" id="timerCheckFBdect200" data-role="slider">
                <option value="false" <?php if($xml->global->timerCheckFBdect200 == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->timerCheckFBdect200 == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="list-divider">
        433 MHz-Gateway
        </li>
        <input type="hidden" name="connairID" id="connairID" value="1" />
        <li data-role="fieldcontain">
            <label for="connairIP">IP-Adresse:</label>
            <input name="connairIP" id="connairIP" value="<?php echo $xml->connairs->connair->address; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="connairPort">Port:</label>
            <input name="connairPort" id="connairPort" value="<?php echo $xml->connairs->connair->port; ?>" data-clear-btn="true" type="text" placeholder="49880">
        </li>
        <li data-role="fieldcontain">
            <label for="gwtechdata">Modell:</label>
            <input name="gwtechdata" id="gwtechdata" value="<?php echo $xml->connairs->connair->techdata; ?>" type="text" readonly >
        </li>
        <li data-role="fieldcontain">
            <input name="AutoDetectGW" id="AutoDetectGW" value="Auto-Detect Gateway" data-mini="true" type="button" onclick="autodetect_gateway();">
        </li>
        <!-- <li data-role="fieldcontain">
            <input name="connairreboot" id="connairreboot" value="Gateway rebooten" data-mini="true" data-theme="r" type="button" data-icon="refresh" onclick="reboot_connair();">
        </li> -->
        <li data-role="list-divider">
        MiLight WiFi-Bridge
        </li>
        <input type="hidden" name="milightID" id="milightID" value="10" />
        <li data-role="fieldcontain">
            <label for="milightIP">IP-Adresse:</label>
            <input name="milightIP" id="milightIP" value="<?php echo $xml->milightwifis->milightwifi->address; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="milightPort">Port:</label>
            <input name="milightPort" id="milightPort" value="<?php echo $xml->milightwifis->milightwifi->port; ?>" data-clear-btn="true" type="text" placeholder="8899">
        </li>
        <li data-role="fieldcontain">
            <label for="milightMAC">MAC:</label>
            <input name="milightMAC" id="milightMAC" value="<?php echo $xml->milightwifis->milightwifi->mac; ?>" type="text" readonly >
        </li>
        <li data-role="fieldcontain">
            <input name="AutoDetectML" id="AutoDetectML" value="Auto-Detect Bridge" data-mini="true" type="button" onclick="autodetect_milight();">
        </li>
		<li data-role="list-divider">
        Raspberry Pi
        </li>
		<li data-role="fieldcontain">
            <label for="sendRaspi">GPIO-Port verwenden:</label>
            <select name="sendRaspi" id="sendRaspi" data-role="slider">
                <option value="false" <?php if($xml->global->sendRaspi == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->global->sendRaspi == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="list-divider">
        FRITZ!Box
        </li>
        <li data-role="fieldcontain">
            <label for="fritzboxAddress">IP-Adresse:</label>
            <input name="fritzboxAddress" id="fritzboxAddress" value="<?php echo $xml->fritzbox->address; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="fritzboxUsername">Benutzername:</label>
            <input name="fritzboxUsername" id="fritzboxUsername" value="<?php echo $xml->fritzbox->username; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="fritzboxPassword">Passwort:</label>
            <input name="fritzboxPassword" id="fritzboxPassword" value="<?php echo $xml->fritzbox->password; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="list-divider">
        Standort
        </li>
        <li data-role="fieldcontain">
            <label for="OutdoorTempSource">Sensor für Aussentemperatur (FBdect200):</label>
            <select name="OutdoorTempSource" id="OutdoorTempSource">
                <?php
                    $ActTempSource = $xml->global->OutdoorTempSource;
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
                        <option value="<?php echo $device->id; ?>" <?php if ((int)$ActTempSource == (int)$device->id) { echo "selected"; } ?>><?php echo $device->id.": ".$device->name." (".$device->room.")"; ?></option>
                        <?php
                    }
                    ?>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="longitude">Longitude:</label>
            <input name="longitude" id="longitude" value="<?php echo $xml->global->longitude; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="latitude">Latitude:</label>
            <input name="latitude" id="latitude" value="<?php echo $xml->global->latitude; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="plz">Postleitzahl:</label>
            <input name="plz" id="plz" value="<?php echo $xml->global->plz; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="city">Stadt:</label>
            <input name="city" id="city" value="<?php echo $xml->global->city; ?>" data-clear-btn="true" type="text">
        </li>   
        <li data-role="fieldcontain">
            <label for="country">Land:</label>
            <input name="country" id="country" value="<?php echo $xml->global->country; ?>" data-clear-btn="true" type="text">
        </li>                   
        <li data-role="fieldcontain">
            <label for="timezone">Zeitzone:</label>
            <input name="timezone" id="timezone" value="<?php echo $xml->global->timezone; ?>" data-clear-btn="true" type="text">
        </li>
        <li data-role="fieldcontain">
            <label for="sunrise">Sonnenaufgang:</label>
            <input name="sunrise" id="sunrise"  value="<?php echo date('H:i', $sunrise); ?>" type="text" readonly >
        </li>
        <li data-role="fieldcontain">
            <label for="sunset">Sonnenuntergang:</label>
            <input name="sunset" id="sunset"  value="<?php echo date('H:i', $sunset); ?>" type="text" readonly >
        </li>
        <li data-role="fieldcontain">
            <input name="currentPosition" id="currentPosition" value="Aktuelle Position verwenden" data-mini="true" type="button" onclick="get_location();">
        </li>
        <li data-role="list-divider">
        Debug
        </li>
        <li data-role="fieldcontain">
            <label for="debug">Global:</label>
            <select name="debug" id="debug" data-role="slider">
                <option value="false" <?php if($xml["debug"] == "false") { echo "selected"; } ?>>Aus</option>
                <option value="true" <?php if($xml["debug"] == "true") { echo "selected"; } ?>>An</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="debug_timer">Timer:</label>
            <select name="debug_timer" id="debug_timer" data-role="slider">
                <option value="false" <?php if($xml->timers["debug"] == "false") { echo "selected"; } ?>>Aus</option>
                <option value="true" <?php if($xml->timers["debug"] == "true") { echo "selected"; } ?>>An</option>
            </select>
            <div>Ausgaben in debug.log erscheinen nur wenn der globale Debug-Schalter auch an ist.</div>
        </li>
        <li>
            <a href="#debug">debug.log anzeigen</a>
        </li>
        <li data-role="list-divider">
        System-Informationen
        </li>
        <li data-role="fieldcontain">
            <label for="version">FB.Switch Version:</label>
            <input name="version" id="version" value="<?php echo file_get_contents('VERSION'); ?>" type="text" readonly>
        </li>
        <li data-role="fieldcontain">
            <label for="thxto">Danke an:</label>
            <input name="thxto" id="thxto" value="Original-Version by Mentox, Mod by IchDerTobi & Kleiner Mann" type="text" readonly>
        </li>
        <li data-role="fieldcontain">
            <a href="info.php" rel="external">PHP-Info</a>
        </li>
    </ul>
</form>
    </div><!-- /content -->
</div><!-- /page -->
