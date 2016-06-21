<?php
$directaccess = true;

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require('config.php');
require('debug.php');

function read_file($file, $lines) {
    global $DEBUG_FILENAME;
    $text = array();
    if(!is_readable($DEBUG_FILENAME)) {
        $text[] = "Kann die Log (".$DEBUG_FILENAME.") nicht lesen!\n";
        return $text; 
    }
    $handle = fopen($file, "r");
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true; break; 
            }
            $t = fgetc($handle);
            $pos --;
        }
        $linecounter --;
        if($beginning) rewind($handle);
        $text[$lines-$linecounter-1] = fgets($handle);
        if($beginning) break;
    }
    fclose ($handle);
    // array_reverse is optional: you can also just return the $text array which consists of the file's lines.
    return array_reverse($text); 
    //return $text; 
}


if (isset($_GET['taillog'])) {
    header("Content-Type: text/plain; charset=utf-8");
    foreach(read_file($DEBUG_FILENAME, 50) as $line) {
        echo $line;
    }
} else {
    header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FB.Switch [Debug-Log]</title>

<link rel="stylesheet" href="jquery.mobile-1.3.0.min.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="jquery-mobile-red-button-theme.css?v=<?php echo $FileVer; ?>" />
<link rel="stylesheet" href="jquery-mobile-green-button-theme.css?v=<?php echo $FileVer; ?>" />
<script type="text/javascript" charset="utf-8" src="jquery-1.9.0.min.js?v=<?php echo $FileVer; ?>"></script>
<script type="text/javascript">
    function load_debug() {
        alert('load_debug');
        $('#debugtext').val('');
        $.ajax({
            type:'GET', 
            url: 'gui_debug.php?taillog=yes', 
            async: true,
            success: function(response) {
                $('#debugtext').val(response);
            },
            error: function(response) {
                $('#debugtext').val(response);
            }
        });
    }
    /*
    function refreshPage() {
        location.reload();
    }
    */
    $(document).ready(function() {
        $(document).delegate('#debug', 'pageshow', function () {
            load_debug();
        });
        $("#reloadbtn").click(function() {
            load_debug();
        });
    });
</script>
<script type="text/javascript" charset="utf-8" src="jquery.mobile-1.3.0.min.js?v=<?php echo $FileVer; ?>"></script>
<script type="text/javascript" charset="utf-8" src="jquery.toast.mobile.js?v=<?php echo $FileVer; ?>"></script>

<!-- WebApp -->
<!-- standard viewport tag to set the viewport to the device's width -->
<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
<!-- width=device-width causes the iPhone 5  to exclude it for iPhone 5 to allow full screen apps -->
<meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1" media="(device-width: 320px) and (device-height: 568px)" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

</head>
<body>

<div data-role="page" id="debug" data-theme="<?php echo $theme_page; ?>">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <a href="index.php#configurations" data-transition="slide" data-direction="reverse">Einstellungen</a>
        <h1>Debug</h1>
        <a href="#" id="reloadbtn">Reload</a>
    </div><!-- /header -->

    <div data-role="content" id="content">  
        <ul data-role="listview" data-theme="<?php echo $theme_row; ?>" data-divider-theme="<?php echo $theme_divider; ?>" data-inset="false">

            <li data-role="list-divider" role="heading">
                debug.log
            </li>

            <li>
                <textarea id="debugtext">
                </textarea>
            </li>     

        </ul>
    </div><!-- /content -->
</div><!-- /page -->

</body>
</html>

<?php } ?>
