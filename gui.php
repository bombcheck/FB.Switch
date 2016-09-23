<?php
if((!isset($directaccess)) OR (!$directaccess)) die();

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html>
<head>
<title>FB.Switch</title>
<meta charset="UTF-8">

<link rel="icon" type="image/x-icon" href="favicon.ico?v=<?php echo $FileVer; ?>">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico?v=<?php echo $FileVer; ?>">
<link type="image/x-icon" href="favicon.ico?v=<?php echo $FileVer; ?>">

<link rel="stylesheet" href="css/jquery.mobile-1.3.0.min.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="css/jquery-mobile-red-button-theme.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="css/jquery-mobile-green-button-theme.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="css/fbswitch.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="css/standard.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="<?php echo $theme_desktop; ?>?v=<?php echo $FileVer; ?>" media="screen"/>
<link rel="stylesheet" href="themes/<?php echo $localtheme_bg; ?>.css?v=<?php echo $FileVer; ?>" media="screen"/>
<link rel="stylesheet" href="css/spectrum.css?v=<?php echo $FileVer; ?>" />

<!-- WebApp -->
<link rel="manifest" href="manifest.json?v=<?php echo $FileVer; ?>">
<meta name="format-detection" content="telephone=no">
<!-- standard viewport tag to set the viewport to the device's width -->
<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
<!-- width=device-width causes the iPhone 5  to exclude it for iPhone 5 to allow full screen apps -->
<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1" media="(device-width: 320px) and (device-height: 568px)" />
<meta name="apple-touch-fullscreen" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<!-- iPhone -->
<link href="images/apple-touch-icon-57x57-precomposed.png?v=<?php echo $FileVer; ?>" sizes="57x57" rel="apple-touch-icon">
<link href="images/apple-touch-icon-60x60-precomposed.png?v=<?php echo $FileVer; ?>" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72-precomposed.png?v=<?php echo $FileVer; ?>" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-76x76-precomposed.png?v=<?php echo $FileVer; ?>" sizes="76x76" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114-precomposed.png?v=<?php echo $FileVer; ?>" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-120x120-precomposed.png?v=<?php echo $FileVer; ?>" sizes="120x120" rel="apple-touch-icon">
<link href="images/apple-touch-icon-144x144-precomposed.png?v=<?php echo $FileVer; ?>" sizes="144x144" rel="apple-touch-icon">
<link href="images/apple-touch-icon-152x152-precomposed.png?v=<?php echo $FileVer; ?>" sizes="152x152" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180-precomposed.png?v=<?php echo $FileVer; ?>" sizes="180x180" rel="apple-touch-icon">
</head>

<body class="contentHolder" id="main-body" style="background-image: url(images/splash_body.jpg); background-repeat: no-repeat; background-color: #000000; background-attachment: absolut; background-position: center center">
<div id="loader-wrapper">
	<div id="loader">
		<div class="loading-splash-div" id="loader">
			<div class="loading-splash-container">
				<div class="loading-splash">
					<img src="images/splash.jpg" style="height: 230px"><br><br>
				    <div id="splash-ball" class="ball play-ball"></div>
			    	<div id="splash-ball1" class="ball1 play-ball1"></div>
			   	</div>
			</div>
		</div>
	</div>
	<div class="loader-section section-left"></div>
	<div class="loader-section section-right"></div>
</div>
<div id="audioFiles" style="display:none">
	<audio id="buttonClickSound" src="sounds/button4.wav" autostart="false"></audio>
	<audio id="doneSound" src="sounds/button7.wav" autostart="false"></audio>
	<audio id="errorSound" src="sounds/error.wav" autostart="false"></audio>
</div>

<script type="text/javascript" charset="utf-8" src="js/jquery-1.9.0.min.js?v=<?php echo $FileVer; ?>"></script>
<?php require_once('js/fbswitch_1.js'); ?>
<script type="text/javascript" charset="utf-8" src="js/jquery.mobile-1.3.0.min.js?v=<?php echo $FileVer; ?>"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery.toast.mobile.js?v=<?php echo $FileVer; ?>"></script>
<?php require_once('js/fbswitch_2.js'); ?>
<script type="text/javascript" charset="utf-8" src="js/spectrum.js?v=<?php echo $FileVer; ?>"></script>

<div id="bg"></div>
<?php
// GUI-Komponenten inkludieren
// ---------------------------
// Favoriten
require_once('includes/incl_favs.php');
// Geräte
require_once('includes/incl_devs.php');
// Gruppen
if($xml->gui->showGroupsBtnInMenu == "true") require_once('includes/incl_groups.php');
// Räume
if($xml->gui->showRoomsBtnInMenu == "true") require_once('includes/incl_rooms.php');
// Aktionen
if($xml->gui->showActionBtnInMenu == "true") require_once('includes/incl_actions.php');
// Timer
if($xml->gui->showTimerBtnInMenu == "true") require_once('includes/incl_timer.php');
// Geräteeditor
require_once('includes/incl_edit_devs.php');
// Gruppeneditor
if($xml->gui->showGroupsBtnInMenu == "true") require_once('includes/incl_edit_groups.php');
// Timereditor
if($xml->gui->showTimerBtnInMenu == "true") require_once('includes/incl_edit_timer.php');
// Einstellungen
if($ShowSettingsMenue == "true") require_once('includes/incl_settings.php');
// Desing-Einstellungen
if($ShowSettingsMenue == "true") require_once('includes/incl_settings_design.php');
// Benutzerverwaltung
if($ShowSettingsMenue == "true") require_once('includes/incl_settings_user.php');
// Debug
require_once('includes/incl_debug.php');
?>

</body>
</html>
