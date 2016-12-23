<div data-role="page" id="design" class="ui-responsive-panel" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Einstellungen]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <!-- <a href="#configurations" data-transition="slide" data-direction="reverse">Einstellungen</a> -->
        <h1>Design</h1>
        <a href="#" id="editdesignsubmit" data-role="button" data-theme="g">Speichern</a>
    </div><!-- /header -->

    <div data-role="content">
    <form id="editdesignform" method="post" data-ajax="false">
            <input type="hidden" name="action" id="action" value="editdesign" />
        <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
        <li data-role="list-divider">
        Welche Bereiche anzeigen?
        </li>
        <li data-role="fieldcontain">
            <label for="showGroupsBtnInMenu">Gruppen</label>
            <select name="showGroupsBtnInMenu" class="onOffSwitch" id="showGroupsBtnInMenu" data-role="slider">
                <option value="false" <?php if($xml->gui->showGroupsBtnInMenu == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showGroupsBtnInMenu == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>   
        <li data-role="fieldcontain">
            <label for="showRoomsBtnInMenu">Räume</label>
            <select name="showRoomsBtnInMenu" class="onOffSwitch" id="showRoomsBtnInMenu" data-role="slider">
                <option value="false" <?php if($xml->gui->showRoomsBtnInMenu == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showRoomsBtnInMenu == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>   
        <li data-role="fieldcontain">
            <label for="showTimerBtnInMenu">Timer</label>
            <select name="showTimerBtnInMenu" class="onOffSwitch" id="showTimerBtnInMenu" data-role="slider">
                <option value="false" <?php if($xml->gui->showTimerBtnInMenu == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showTimerBtnInMenu == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="showActionBtnInMenu">Aktionen</label>
            <select name="showActionBtnInMenu" class="onOffSwitch" id="showActionBtnInMenu" data-role="slider">
                <option value="false" <?php if($xml->gui->showActionBtnInMenu == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showActionBtnInMenu == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="list-divider">
        Allgemeine GUI-Einstellungen
        </li>
        <!-- <li data-role="fieldcontain">
            <label for="showDeviceStatus">Zeige Geräte Status:</label>
            <select name="showDeviceStatus" id="showDeviceStatus">
                <option value="OFF" <?php if($xml->gui->showDeviceStatus == "OFF") { echo "selected"; } ?>>NEIN</option>
                <option value="ROW_COLOR" <?php if($xml->gui->showDeviceStatus == "ROW_COLOR") { echo "selected"; } ?>>ROW_COLOR</option>
                <option value="BUTTON_COLOR" <?php if($xml->gui->showDeviceStatus == "BUTTON_COLOR") { echo "selected"; } ?>>BUTTON_COLOR</option>
                <option value="BUTTON_ICON" <?php if($xml->gui->showDeviceStatus == "BUTTON_ICON") { echo "selected"; } ?>>BUTTON_ICON</option>
            </select>
        </li> -->
        <li data-role="fieldcontain">
            <label for="showRoomButtonInDevices">Zeige Raum Schalter in der Geräteübersicht:</label>
            <select name="showRoomButtonInDevices" id="showRoomButtonInDevices" data-role="slider">
                <option value="false" <?php if($xml->gui->showRoomButtonInDevices == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showRoomButtonInDevices == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="showMenuOnLoad">Zeige das Menu beim Starten:</label>
            <select name="showMenuOnLoad" id="showMenuOnLoad" data-role="slider">
                <option value="false" <?php if($xml->gui->showMenuOnLoad == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showMenuOnLoad == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="showAllOnOffBtnInMenu">Zeige Alle EIN/AUS Button im Menu:</label>
            <select name="showAllOnOffBtnInMenu" id="showAllOnOffBtnInMenu" data-role="slider">
                <option value="false" <?php if($xml->gui->showAllOnOffBtnInMenu == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showAllOnOffBtnInMenu == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="playSounds">Soundeffekte aktiv:</label>
            <select name="playSounds" id="playSounds" data-role="slider">
                <option value="false" <?php if($xml->gui->playSounds == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->playSounds == "true" || $xml->gui->playSounds == "") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="showSplashAnimation">Splash-Animation anzeigen:</label>
            <select name="showSplashAnimation" id="showSplashAnimation" data-role="slider">
                <option value="false" <?php if($xml->gui->showSplashAnimation == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($xml->gui->showSplashAnimation == "true" || $xml->gui->showSplashAnimation == "") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="sortOrderDevices" class="select">Sortierung der Geräte:</label>
            <select name="sortOrderDevices" id="sortOrderDevices">
                <option value="SORT_BY_NAME" <?php if($xml->gui->sortOrderDevices == "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_NAME</option>
                <option value="SORT_BY_ID" <?php if($xml->gui->sortOrderDevices == "SORT_BY_ID") { echo "selected"; } ?>>SORT_BY_ID</option>
                <option value="SORT_BY_XML" <?php if($xml->gui->sortOrderDevices != "SORT_BY_NAME" && $xml->gui->sortOrderDevices != "SORT_BY_ID") { echo "selected"; } ?>>SORT_BY_XML</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="sortOrderGroups" class="select">Sortierung der Gruppen:</label>
            <select name="sortOrderGroups" id="sortOrderGroups">
                <option value="SORT_BY_NAME" <?php if($xml->gui->sortOrderGroups == "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_NAME</option>
                <option value="SORT_BY_XML" <?php if($xml->gui->sortOrderGroups != "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_XML</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="sortOrderRooms" class="select">Sortierung der Räume:</label>
            <select name="sortOrderRooms" id="sortOrderRooms">
                <option value="SORT_BY_NAME" <?php if($xml->gui->sortOrderRooms == "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_NAME</option>
                <option value="SORT_BY_XML" <?php if($xml->gui->sortOrderRooms != "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_XML</option>
            </select>
        </li>
        <li data-role="fieldcontain">
            <label for="sortOrderTimers" class="select">Sortierung der Timer:</label>
            <select name="sortOrderTimers" id="sortOrderTimers">
                <option value="SORT_BY_NAME" <?php if($xml->gui->sortOrderTimers == "SORT_BY_NAME") { echo "selected"; } ?>>SORT_BY_NAME</option>
                <option value="SORT_BY_ID" <?php if($xml->gui->sortOrderTimers == "SORT_BY_ID") { echo "selected"; } ?>>SORT_BY_ID</option>
                <option value="SORT_BY_TYPE_AND_NAME" <?php if($xml->gui->sortOrderTimers == "SORT_BY_TYPE_AND_NAME") { echo "selected"; } ?>>SORT_BY_TYPE_AND_NAME</option>
                <option value="SORT_BY_XML" <?php if($xml->gui->sortOrderTimers != "SORT_BY_NAME" && $xml->gui->sortOrderTimers != "SORT_BY_ID" && $xml->gui->sortOrderTimers != "SORT_BY_TYPE_AND_NAME") { echo "selected"; } ?>>SORT_BY_XML</option>
            </select>
        </li>
        </ul>
    </form>
    </div><!-- /content -->
</div><!-- /page -->
