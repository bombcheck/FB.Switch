<script type="text/JavaScript">
$(document).ready(function() {
    $("#vendor").change(function() {
        if ($(this).val() == "brennenstuhl" || $(this).val() == "elro") {
            $("#dip_switch_box").removeClass('hide').addClass('show');
        } else {
            $("#dip_switch_box").removeClass('show').addClass('hide');
        }

		if ($(this).val() == "milight") {
			$("#milight_help").removeClass('hide').addClass('show');
            $("#fbmilight_help").removeClass('show').addClass('hide');
			$("#help").removeClass('show').addClass('hide');
        } else if ($(this).val() == "milight_rgbcct") {
            $("#milight_help").removeClass('show').addClass('hide');
            $("#fbmilight_help").removeClass('hide').addClass('show');
            $("#help").removeClass('show').addClass('hide');
        } else {
            $("#milight_help").removeClass('show').addClass('hide');
            $("#fbmilight_help").removeClass('show').addClass('hide');
			$("#help").removeClass('hide').addClass('show');
        }

    });
});

function resetNewDeviceForm() {
    $('#newdeviceform')[0].reset();
    //$("#vendor").trigger('change');
    $("#milight_help").removeClass('show').addClass('hide');
    $("#fbmilight_help").removeClass('show').addClass('hide');
	$("#help").removeClass('hide').addClass('show');
    $("#dip_switch_box").removeClass('hide').addClass('show');
    $("#dip_switch0").removeClass().addClass('on');
    $("#dip_switch0").children().val('1');
    $("#dip_switch1").removeClass().addClass('on');
    $("#dip_switch1").children().val('1');
    $("#dip_switch2").removeClass().addClass('on');
    $("#dip_switch2").children().val('1');
    $("#dip_switch3").removeClass().addClass('on');
    $("#dip_switch3").children().val('1');
    $("#dip_switch4").removeClass().addClass('on');
    $("#dip_switch4").children().val('1');
    $("#dip_switch5").removeClass().addClass('on');
    $("#dip_switch5").children().val('1');
    $("#dip_switch6").removeClass().addClass('on');
    $("#dip_switch6").children().val('1');
    $("#dip_switch7").removeClass().addClass('on');
    $("#dip_switch7").children().val('1');
    $("#dip_switch8").removeClass().addClass('on');
    $("#dip_switch8").children().val('1');
    $("#dip_switch9").removeClass().addClass('on');
    $("#dip_switch9").children().val('1');
    updateDIPTextField();
}
</script>
<div data-role="page" id="newdevice" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Gerät bearbeiten]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <?php
        if($_GET['mode'] == 'editdevice'){
        echo '<a data-rel="external" data-transition="none" data-direction="reverse" data-role="button" data-theme="r" onClick="window.location.href = \'index.php#devices\'">Abbrechen</a>';
        echo'<h1>Gerät bearbeiten</h1>';
        }else{
        echo '<a href="#devices" data-transition="none" data-direction="reverse" data-role="button" data-theme="r" onClick="resetNewDeviceForm();">Abbrechen</a>';
        echo'<h1>Neues Gerät</h1>';
        } ?>
        <a href="#" id="newdevicesubmit" data-role="button" data-theme="g">Speichern</a>
    </div><!-- /header -->

    <?php
    if($_GET['mode'] == 'editdevice'){
        $xpath='//device/id[.="'.$_GET['id'].'"]/parent::*';
        $res = $xml->xpath($xpath); 
        $parent = $res[0]; 
        $data = array(
            'id' => $parent->id,
            'name' => $parent->name,
            'vendor' => $parent->vendor,
            'masterdip' => $parent->address->masterdip,
            'slavedip' => $parent->address->slavedip,
            'tx433version' => $parent->address->tx433version,
            'sendCommandsOnlyOnce' => $parent->sendCommandsOnlyOnce,
            'rawCodeOn' => $parent->address->rawCodeOn,
            'rawCodeOff' => $parent->address->rawCodeOff,
            'ssh_user' => $parent->address->ssh_user,
            'ssh_password' => $parent->address->ssh_password,
            'ssh_address' => $parent->address->ssh_address,
            'room' => $parent->room,
            'senderid' => $parent->senderid,
            'status' => $parent->status,
            'buttonLabelOn' => $parent->attributes()->buttonLabelOn,
            'buttonLabelOff' => $parent->attributes()->buttonLabelOff,
            'showDeviceStatus' => $parent->showDeviceStatus,
        );
        //echo $data['tx433version'];
        //echo $data['buttonLabelOff'];
        //exit;
    }
    
    ?>
        
    <div data-role="content">
        <form id="newdeviceform" method="post" data-ajax="false">
            <input type="hidden" name="action" id="action" value="<?php echo ($_GET['mode'] == 'editdevice') ? 'edit' : 'add' ?>" />
            <input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
    <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">
        <li data-role="fieldcontain">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" data-clear-btn="true" value="<?php echo $data['name']; ?>" />
        </li>
        <li data-role="fieldcontain">
                <label for="room">Raum:</label>
                <input type="text" name="room" id="room" data-clear-btn="true" value="<?php echo $data['room']; ?>" />
        </li>
        <li data-role="fieldcontain">
                    <label for="vendor">Hersteller:</label>
                    <select name="vendor" id="vendor">
                        <option <?php echo ($data['vendor'] == "brennenstuhl") ? 'selected="selected"' : '' ?>  value="brennenstuhl">Brennenstuhl</option>
                        <option <?php echo ($data['vendor'] == "elro") ? 'selected="selected"' : '' ?>  value="elro">Elro/Mumbi</option>
                        <option <?php echo ($data['vendor'] == "intertechno") ? 'selected="selected"' : '' ?>  value="intertechno">Intertechno</option>
                        <option <?php echo ($data['vendor'] == "quigg") ? 'selected="selected"' : '' ?>  value="quigg">Quigg</option>
                        <option <?php echo ($data['vendor'] == "raw") ? 'selected="selected"' : '' ?>  value="raw">RAW</option>
                        <option <?php echo ($data['vendor'] == "computer") ? 'selected="selected"' : '' ?>  value="computer">Computer</option>
                        <option <?php echo ($data['vendor'] == "url") ? 'selected="selected"' : '' ?>  value="url">URL</option>
                        <option <?php echo ($data['vendor'] == "fbdect200") ? 'selected="selected"' : '' ?>  value="fbdect200">FritzBox DECT200</option>
                        <option <?php echo ($data['vendor'] == "ssh") ? 'selected="selected"' : '' ?>  value="ssh">SSH-Befehl absetzen</option>
                        <option <?php echo ($data['vendor'] == "milight") ? 'selected="selected"' : '' ?>  value="milight">MiLight</option>
                        <option <?php echo ($data['vendor'] == "milight_rgbcct") ? 'selected="selected"' : '' ?>  value="milight_rgbcct">FB.MiLight-Hub (RGBCCT)</option>
                    </select>
        </li>
       <?php
        if($_GET['mode'] == ""){
?>        
<style type="text/css">

.desc, .titles {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    letter-spacing: 0;
    font-size: 11px;
    letter-spacing: 0;
}

.switch {
  margin-left: auto ;
  margin-right: auto ;
    display: block;
    float: center;
    background: #AD2929;
    width: 260px;
    #width: 90%;
    height: 80px;
    padding: 5px;
    border: 1px solid #333;
}

.switch_box {
  margin-left: auto ;
  margin-right: auto ;
    width: 100%;
}

.titles {
    display: block;
    height: 26px;
    font-weight: bold;
    color: #fff;
}

.title_left {
    float: left;
    width: 100px;
}

.title_right {
    float: right;
    text-align: right;
}

.dip {
    float: left;
    margin: 0px 5px;
    width: 16px;
    #width: 7%;
    height: 40px;
    display: block;
    text-align: center;
    color: #ffffff;
    font-weight: bold;
}

.dip_bar {
  margin-left: auto ;
  margin-right: auto ;
    #width: 89%;
}

.dip input {
    border: none;
}

.on, .off {
    float: left;
    display: block;
    height: 12px;
    width: 15px;
    border: 1px solid #999999;
    background: #ffffff;
    margin: 0 0 5px 0;
}

.on  {
    border-bottom: 15px solid #C24949;
}

.off  {
    border-top: 15px solid #C24949;
}

.clear {
    clear: both;
}
</style>
<script type="text/JavaScript">

function updateDIPTextField () {
    var masterdip="";
    masterdip+=$("#dip_switch0").children().val();
    masterdip+=$("#dip_switch1").children().val();
    masterdip+=$("#dip_switch2").children().val();
    masterdip+=$("#dip_switch3").children().val();
    masterdip+=$("#dip_switch4").children().val();
    $("#masterdip").val(masterdip);

    var slavedip="";
    slavedip+=$("#dip_switch5").children().val();
    slavedip+=$("#dip_switch6").children().val();
    slavedip+=$("#dip_switch7").children().val();
    slavedip+=$("#dip_switch8").children().val();
    slavedip+=$("#dip_switch9").children().val();
    $("#slavedip").val(slavedip);
}


$(document).ready(function() {
    $("[name=dip_switch]").each(function() {
        $(this).click(function() {
            var input=$(this).children();
            if ($(this).hasClass('off')) {
                $(this).removeClass().addClass('on');
                input.val("1");
            } else {
                $(this).removeClass().addClass('off');
                input.val("0");
            }
            updateDIPTextField();
        });
    });
    updateDIPTextField();
});

</script>
<li data-role="fieldcontain">

<div id="dip_switch_box" class="show">

<div class="switch_box">
<div class="switch">
    <div class="titles">
        <span class="title_left">ON</span>
        <span class="title_right">DIP</span>
    </div>
    <div class="dip_bar">
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch0"><input type="hidden" name="b[0]" id="b0" value="1" /></div>
            <span class="desc">1</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch1"><input type="hidden" name="b[1]" id="b1" value="1" /></div>
            <span class="desc">2</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch2"><input type="hidden" name="b[2]" id="b2" value="1" /></div>
            <span class="desc">3</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch3"><input type="hidden" name="b[3]" id="b3" value="1" /></div>
            <span class="desc">4</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch4"><input type="hidden" name="b[4]" id="b4" value="1" /></div>
            <span class="desc">5</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch5"><input type="hidden" name="b[5]" id="b5" value="1" /></div>
            <span class="desc">A</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch6"><input type="hidden" name="b[6]" id="b6" value="1" /></div>
            <span class="desc">B</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch7"><input type="hidden" name="b[7]" id="b7" value="1" /></div>
            <span class="desc">C</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch8"><input type="hidden" name="b[8]" id="b8" value="1" /></div>
            <span class="desc">D</span>
        </span>
        <span class="dip">
            <div class="on" name="dip_switch" id="dip_switch9"><input type="hidden" name="b[9]" id="b9" value="1" /></div>
            <span class="desc">E</span>
        </span>
    </div>
</div>
</div>
<div class="clear"></div>
    </div>            
                
        </li>
        <?php
        }
        ?>
        <li data-role="fieldcontain">
                <label for="masterdip">Masterdip:</label>
                <input type="text" name="masterdip" id="masterdip" data-clear-btn="true" value="<?php echo $data['masterdip']; ?>" />
        </li>
        <li data-role="fieldcontain">
         <label for="slavedip">Slavedip:</label>
                <input type="text" name="slavedip" id="slavedip" data-clear-btn="true" value="<?php echo $data['slavedip']; ?>" />
        </li>
        <li data-role="fieldcontain">
                <label for="tx433version">TX433-Version:</label>
                <input type="text" name="tx433version" id="tx433version" data-clear-btn="true" value="<?php echo $data['tx433version']; ?>" />
        </li>
		<li data-role="fieldcontain">
			<div id="milight_help" <?php echo ( $data['vendor'] == 'milight' ) ? 'class="show"' : 'class="hide"' ?> >
				Masterdip: MiLight Bridge-ID
				<br>Slavedip: Auf der Bridge zu schaltende Gruppe (1-4 oder 0 für alle)
				<br>TX433-Version: Lampentyp (1: Weiß - 2: RGB/RGBW)
				<br>Es darf immer nur ein Lampentyp je Gruppe auf der Bridge registriert sein.
				<br><br>Vorhandene MiLight WiFi-Bridges:<br>
				<?php foreach($xml->milightwifis->milightwifi as $milightwifi) {
					echo "<ul>ID: ".$milightwifi->id." (IP: ".$milightwifi->address.")</ul>";
				} ?>
			</div>
            <div id="fbmilight_help" <?php echo ( $data['vendor'] == 'milight_rgbcct' ) ? 'class="show"' : 'class="hide"' ?> >
                Masterdip: FB.MiLight-Hub-ID
                <br>Slavedip: System-Device-ID auf dem FB.MiLight-Hub (z.B. 0x1A2B)
                <br>TX433-Version: Zu schaltende Gruppe (1-4 oder 0 für alle)
                <br>Es darf immer nur ein Lampentyp je Gruppe auf dem Hub registriert sein.
                <br><br>Vorhandene FB.MiLight-Hubs:<br>
                <?php foreach($xml->milighthubs->milighthub as $milighthub) {
                    echo "<ul>ID: ".$milighthub->id." (IP: ".$milighthub->address.")</ul>";
                } ?>
            </div>
			<div id="help" <?php echo ( $data['vendor'] != 'milight' && $data['vendor'] != 'milight_rgbcct' ) ? 'class="show"' : 'class="hide"' ?> >
				TX433-Version sollte in der Regel "2" sein. Ansonsten mit "1" versuchen.
			</div>
		</li>
        <li data-role="fieldcontain">
            <label for="sendCommandsOnlyOnce">Funk-Kommandos immer nur einmal senden:</label>
            <select name="sendCommandsOnlyOnce" id="sendCommandsOnlyOnce" data-role="slider">
                <option value="false" <?php if($data['sendCommandsOnlyOnce'] == "false") { echo "selected"; } ?>>Nein</option>
                <option value="true" <?php if($data['sendCommandsOnlyOnce'] == "true") { echo "selected"; } ?>>Ja</option>
            </select>
        </li>
        <li data-role="fieldcontain">
         <label for="rawCodeOn">RAW On:</label>
                <input type="text" name="rawCodeOn" id="rawCodeOn" data-clear-btn="true" value="<?php echo $data['rawCodeOn']; ?>" />
        </li>
        <li data-role="fieldcontain">
         <label for="rawCodeOff">RAW Off:</label>
                <input type="text" name="rawCodeOff" id="rawCodeOff" data-clear-btn="true" value="<?php echo $data['rawCodeOff']; ?>" />
        </li>
        <li data-role="fieldcontain">
         <label for="ssh_user">SSH-User</label>
                <input type="text" name="ssh_user" id="ssh_user" data-clear-btn="true" value="<?php echo $data['ssh_user']; ?>" />
        </li>
        <li data-role="fieldcontain">
         <label for="ssh_password">SSH-Passwort</label>
                <input type="text" name="ssh_password" id="ssh_password" data-clear-btn="true" value="<?php echo $data['ssh_password']; ?>" />
        </li>
        <li data-role="fieldcontain">
         <label for="ssh_address">SSH-Remoteaddresse</label>
                <input type="text" name="ssh_address" id="ssh_address" data-clear-btn="true" value="<?php echo $data['ssh_address']; ?>" />
        </li>
        <li data-role="fieldcontain">
                        <label for="btnLabelOn">Schalter-Beschriftung EIN:</label>
                        <input type="text" name="btnLabelOn" id="btnLabelOn" data-clear-btn="true" value="<?php echo $data['buttonLabelOn']; ?>" placeholder="EIN"/>
        </li>
        <li data-role="fieldcontain">
            <label for="btnLabelOn">Schalter-Beschriftung AUS:</label>
            <input type="text" name="btnLabelOff" id="btnLabelOff" data-clear-btn="true" value="<?php echo $data['buttonLabelOff']; ?>" placeholder="AUS"/>
        </li>
        <li data-role="fieldcontain">
            <label for="showDeviceStatus">Zeige Geräte-Status:</label>
            <select name="showDeviceStatus" id="showDeviceStatus">
                <option value="OFF" <?php if($data['showDeviceStatus'] == "OFF") { echo "selected"; } ?>>NEIN</option>
                <option value="ROW_COLOR" <?php if($data['showDeviceStatus'] == "ROW_COLOR") { echo "selected"; } ?>>Zeilenfarbe</option>
                <option value="BUTTON_COLOR" <?php if($data['showDeviceStatus'] == "BUTTON_COLOR") { echo "selected"; } ?>>Buttonfarbe</option>
                <option value="BUTTON_ICON" <?php if($data['showDeviceStatus'] == "BUTTON_ICON") { echo "selected"; } ?>>Buttonicon</option>
            </select>
        </li>
        <?php
        if($_GET['mode'] != ""){
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = explode("?", $url);
        ?>
        <li data-role="fieldcontain">
            <label for="Onlink">Onlink</label>
            <input type="text" name="Onlink" id="Onlink" readonly value="http://<?php echo $url[0]; ?>?action=on&type=device&id=<?php echo $_GET['id']; ?>" />
        </li>

        <li data-role="fieldcontain">
            <label for="Offlink">Offlink</label>
            <input type="text" name="Offlink" id="Offlink" readonly value="http://<?php echo $url[0]; ?>?action=off&type=device&id=<?php echo $_GET['id']; ?>" />
        </li>
<?php   }


    $sendercount=@count($xml->connairs->children()) + @count($xml->culs->children());
    if($sendercount > 1 || $xml->global->sendRaspi == "true") {
?>
        <li data-role="fieldcontain">
            <label for="senderid">Sender:</label>
            <select name="senderid" id="senderid">
                <option value="">Alle</option>
            <?php
                foreach($xml->connairs->connair as $connair) {
                    ?>
                    <option <?php echo ($data['senderid'] == (string)$connair->id) ? 'selected="selected"' : '' ?><?php echo ' value="'.$connair->id.'">['.$connair->id.'] 433MHz-GW '.$connair->address; ?> </option>
                    <?php
                }
				
				if($xml->global->sendRaspi == "true"){ ?>
					<option <?php echo ($data['senderid'] == "5") ? 'selected="selected"' : '' ?> value="5">[5] RaspPi GPIO</option>
				<?php }
				
                foreach($xml->culs->cul as $cul) {
                    ?>
                    <option <?php echo ($data['senderid'] == (string)$cul->id) ? 'selected="selected"' : '' ?> value="'.$cul->id.'"><?php echo '['.$cul->id.'] CUL '.$cul->device; ?></option>
                    <?php
                }
            ?>
            </select>
        </li>
        <?php
        }
        ?>
    </ul>
            
        </form>
    </div><!-- /content -->
</div><!-- /page -->
<!-- </div> -->
