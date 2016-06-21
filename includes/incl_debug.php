<style type="text/css">
#debugtext {
    height: 580px;
 }
</style>
<script type="text/javascript">
    function load_debug() {
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
    $(document).ready(function() {
        $(document).delegate('#debug', 'pageshow', function () {
            load_debug();
        });
        $("#reloadbtn").click(function() {
            load_debug();
        });
    });
</script>
<div data-role="page" id="debug" data-theme="<?php echo $theme_page; ?>" data-title="FB.Switch [Debug-Log]">

    <div data-role="header" data-position="fixed" data-tap-toggle="false">
        <a href="#configurations" data-transition="none" data-direction="reverse">Einstellungen</a>
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
