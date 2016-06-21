<?php
 $CONFIG_FILENAME = 'data/time.xml';
//config.xml dateisystem rechte überprüfen
if(!file_exists($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht finden!\n";
    exit(1);
}
if(!is_readable($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht lesen!\n";
    exit(2);
}
if(!is_writable($CONFIG_FILENAME)) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht schreiben!\n";
    exit(3);
}

//config.xml einlesen
libxml_use_internal_errors(true);
$xml = simplexml_load_file($CONFIG_FILENAME);
if (!$xml) {
    echo "Kann die Konfiguration (".$CONFIG_FILENAME.") nicht laden!\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    exit(4);
}

// Suppress DateTime warnings
date_default_timezone_set('Europe/Berlin');

//zeitzone geradeziehen
if(!empty($xml->global->timezone)) {
    date_default_timezone_set($xml->global->timezone);
}

if(!isset($_GET['mode'])){
		echo'Neuen Timer erstellen!';
		echo'<br>Minuten auswählen:';
		echo'
		<form method="POST" action="erstellen.php?mode=submit">
			<td><td>
				<select name="minutes">
					';
					for($i=1; $i <45; $i++){
					echo '<option>'. $i .'</option>';
					}
					
	echo'
					<option>60</option>
					<option>90</option>
					<option>120</option>
					<option>180</option>
				</select>
				</td><br><td>
				<select name="device">
					';
					for($i=1; $i <45; $i++){
					echo '<option>'. $i .'</option>';
					}
					
	echo'
					<option>60</option>
					<option>90</option>
					<option>120</option>
					<option>180</option>
				</select>
			</td><br>
			<select name="action">
					<option>ON</option>
					<option>OFF</option>
				</select>
			<td colspan="2">
				<input class="submit_button" type="submit" name="submitbutton" value="Speichern">
			</td>
		</form>
		';
}		
	

	if(isset($_GET['mode']) AND $_GET['mode']=="submit" )
	{
        $newid=1;
        foreach($xml->timer as $timer) {
            $oldid=(integer)$timer->id;
            if($oldid >= $newid) {
                $newid = $oldid + 1;
            }
        }
		if(!empty($_POST['minutes'])){
		
		$heute = date("Y-m-d H:i", strtotime("0 week 0 days 0 hours ". $_POST['minutes'] ." minutes"));

		$xmle = $xml->addChild('timer');
		$xmle->id = $newid;
        $xmle->time = $heute;
		$xmle->device = $_POST['device'];
		$xmle->action = $_POST['action'];
       	$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		$dom->save($CONFIG_FILENAME);
        #echo 'Erfolgreich gespeichert!';
		#header( 'location:index.phppp' );
		echo '1';
		
		}else{
			echo '0';
		}
}		
?>