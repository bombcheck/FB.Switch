<?php
require("includes/incl_milight.php");

$result = "ERR";

$milight = new Milight($_GET['mlip'], (integer)$_GET['mlport']);
$milight->setDelay(0);
$milight->setRepeats(1);
$milight->setRgbwActiveGroup((integer)$_GET['mlgroup']);

if (isset($_GET['off'])) {
    $milight->rgbwSendOffToGroup($_GET['off']);
	$result = "#OK#";
}
if (isset($_GET['on'])) {
	$milight->setActiveGroupSend(1);
    $milight->rgbwSendOnToGroup($_GET['on']);
	$milight->setActiveGroupSend(0);
	$result = "#OK#";
}
if (isset($_GET['white'])) {
	$milight->rgbwSetGroupToWhite($_GET['white']);
	$result = "#OK#";
}
if (isset($_GET['night'])) {
	$milight->rgbwSetGroupToNightMode($_GET['night']);
	$result = "#OK#";
}
if (isset($_GET['hue'])) {
	//$result	= $_GET['mlip'].":".$_GET['mlport']." ".$_GET['mlgroup'].":".$_GET['hue'];
	$milight->rgbwSetColorHsv([$_GET['hue'], 1, 0.5]);
	$result = "#OK#";
}
if (isset($_GET['brightness'])) {
	//echo $_GET['hue'];
	$milight->rgbwBrightnessPercent(min(100,$_GET['brightness']));
	$result = "#OK#";
}

unset($milight);
echo $result;
?>