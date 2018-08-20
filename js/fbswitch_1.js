<script type="text/javascript">
    var FBdectTimer = "";
    var AlertState = "green";
    var AlertSoundTimer = "";

    $(document).bind("mobileinit", function(){
        $.support.touchOverflow = true;
        $.mobile.touchOverflowEnabled = true;
        //$.mobile.fixedToolbars.setTouchToggleEnabled(false);
        $.mobile.defaultPageTransition = 'none';
        //$.mobile.page.prototype.options.domCache = true;
    });

    $(document).ready(function() {
    
        if( window.innerWidth < 1100) { $IsMobile="true"; } 
        if( window.innerWidth >= 1100) { $IsMobile="false"; }
        
        //$.event.special.swipe.scrollSupressionThreshold=10;
        //$.event.special.swipe.durationThreshold=1000;
        //$.event.special.swipe.horizontalDistanceThreshold=150;
        //$.event.special.swipe.verticalDistanceThreshold=20;
        //$(document).on( 'swiperight', swiperightHandler );
        //function swiperightHandler( event ){
        //    $.mobile.activePage.find('#mypanel').panel( "open" );
        //}
        //$(document).on( 'swipeleft', swipeleftHandler );
        //function swipeleftHandler( event ){
        //    $.mobile.activePage.find('#mypanel').panel( "close" );
        //}
        <?php if ($xml->gui->showMenuOnLoad=="true") { ?>
        if($IsMobile=="true")  {
            setTimeout(function() {
                $.mobile.activePage.find('#mypanel').panel( "open" );
            }, 500);
        }
        <?php } ?>

        ShowIndoorOutdoorTemp();
        var FBDTEMP = setInterval(function () { ShowIndoorOutdoorTemp()}, 300000);

        <?php if ($ShowFBdect200EnergyData == "true") { ?>
            GetFBdectEnergy();
        <?php } ?>

        <?php if ($AutoRefreshDeviceData == "true") { ?>
            var CDSvar = setInterval(function () { CheckDeviceStatus()}, 15000);
            <?php if ($ShowFBdect200EnergyData == "true") { ?>
                var GFEvar = setInterval(function () { GetFBdectEnergy()}, 60000);
            <?php } ?>
        <?php } else { ?>
            CheckDeviceStatus();
        <?php } ?>
        
		<?php if ($FBnetSysStateAlert == "true") { ?>
            CheckFBnetSysAlert();
            var FBNST = setInterval(function () { CheckFBnetSysAlert()}, 60000);
        <?php } ?>

        
        $('#newdevicesubmit').click(function (e) {
            $.ajax({
                url: "edit_device.php",
                type: "POST",
                data: $('#newdeviceform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#devices';
                        $.mobile.changePage('#devices', {
                            transition: "none",
                            reverse: true
                        });
                        toast('Gespeichert');
                        resetNewDeviceForm();                        
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        });

        $('#newgroupsubmit').click(function (e) {
            $.ajax({
                url: "edit_group.php",
                type: "POST",
                data: $('#newgroupform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#groups';
                        $.mobile.changePage('#groups', {
                            transition: "none",
                            reverse: true
                        });
                        toast('Gespeichert');
                        resetNewGroupForm();
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        });

        $('#editconfigsubmit').click(function (e) {
            $.ajax({
                url: "edit_config.php",
                type: "POST",
                data: $('#editconfigform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#configurations';                        
                        toast('Gespeichert');
                    } else {
                       toast('response:'+response);
                    }
                }
            });
        });
        
        $('#editdesignsubmit').click(function (e) {
            $.ajax({
                url: "edit_config.php",
                type: "POST",
                data: $('#editdesignform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#configurations';
                        $.mobile.changePage('#configurations', {
                            transition: "flip",
                            reverse: true
                        });
                        toast('Gespeichert');
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        });
        
        $('#newtimersubmit').click(function (e) {
            $.ajax({
                url: "edit_timer.php",
                type: "POST",
                data: $('#newtimerform').serialize(),
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#timers';
                        $.mobile.changePage('#timers', {
                            transition: "none",
                            reverse: true
                        });
                        toast('Gespeichert');
                        resetNewTimerForm();
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        });

        $("#reloadbtnguifav").click(function() { 
           BusyAni('show');
            refreshPage();
        });
        $("#reloadbtnguidev").click(function() {
            BusyAni('show');
            refreshPage();
        });
        $("#reloadbtnguigroup").click(function() {
            BusyAni('show');
            refreshPage();
        });
        $("#reloadbtnguiroom").click(function() { 
            BusyAni('show');
            refreshPage();
        });
        $("#reloadbtnguiact").click(function() {
            BusyAni('show');
            refreshPage();
        });
        $("#reloadbtnguitmr").click(function() {
            BusyAni('show');
            refreshPage();
        });

    });

    $(window).load(function() {
        //window.setTimeout(function(){
            $('#splash-ball').removeClass('play-ball');
            $('#splash-ball1').removeClass('play-ball1');
            //$("#loader").hide();
            $('body').addClass('loaded');
            $("#main-body").removeAttr('style');
        //},2000);
    });

        function ShowIndoorOutdoorTemp() {
            $.ajax({
                url: 'get_fbdect_temp.php',
                success: function (response) {
                    var DevicesTemp = response.split('|');
                    var IndoorTemp = "";
					var OutdoorTemp = "";
                    var ShowTemp = false;

                    if (DevicesTemp.length > 0) {
                        for (tIndex = 0; tIndex < DevicesTemp.length; ++tIndex) {
                            tempvar = DevicesTemp[tIndex].split(':');
                            
                            if (tempvar[0] == <?php echo $IndoorTempSource; ?>) {
                                IndoorTemp = parseInt(tempvar[1]) / 10;
                                if (IndoorTemp == -100) IndoorTemp = '---';
                                else IndoorTemp = IndoorTemp.toFixed(1) + ' °C';
                                ShowTemp = true;
                            }

                            if (tempvar[0] == <?php echo $OutdoorTempSource; ?>) {
                                OutdoorTemp = parseInt(tempvar[1]) / 10;
                                if (OutdoorTemp == -100) OutdoorTemp = '---';
                                else OutdoorTemp = OutdoorTemp.toFixed(1) + ' °C';
                                ShowTemp = true;
                            }
                        } 
                        if (IndoorTemp == "") IndoorTemp = '---';
                        if (OutdoorTemp == "") OutdoorTemp = '---';
                        
	                    //var OutdoorTemp1 = DevicesTemp[0].split(':');
						//OutdoorTemp1 = parseInt(OutdoorTemp1[1]);
	                    //var OutdoorTemp2 = DevicesTemp[1].split(':');
	                    //OutdoorTemp2 = parseInt(OutdoorTemp2[1]);

	                    //if (OutdoorTemp1 == -1000 && OutdoorTemp2 != -1000)
                        //    {
                        //        var OutdoorTemp = OutdoorTemp2 / 10;
                        //        OutdoorTemp = OutdoorTemp.toFixed(1);
                        //    }
                        //else if (OutdoorTemp1 != -1000 && OutdoorTemp2 == -1000)
                        //    {
                        //        var OutdoorTemp = OutdoorTemp1 / 10;
                        //        OutdoorTemp = OutdoorTemp.toFixed(1);
                        //    }
                        //else if (OutdoorTemp1 != -1000 && OutdoorTemp2 != -1000)
                        //    {
                        //        var OutdoorTemp = (OutdoorTemp1 + OutdoorTemp2) / 10 / 2;
                        //        OutdoorTemp = OutdoorTemp.toFixed(1);
                        //    }
                        //else if (OutdoorTemp1 == -1000 && OutdoorTemp2 == -1000)
                        //    {
                        //        var OutdoorTemp = '---';
                        //    }

	                    //if (IndoorTemp != '---' || OutdoorTemp != '---') {
                        if (ShowTemp == true) {
	                        $('#tempmsg_favs').fadeIn('fast');
	                        $('#tempmsg_devs').fadeIn('fast');
	                        $('#tempmsg_groups').fadeIn('fast');
	                        $('#tempmsg_rooms').fadeIn('fast');
	                        $('#tempmsg_timer').fadeIn('fast');
	                        $('#tempmsg_actions').fadeIn('fast');

	                        var tempmsg_favs_indoor = $('.tempmsg_favs_indoor');
	                        tempmsg_favs_indoor.eq(0 % tempmsg_favs_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_favs_outdoor = $('.tempmsg_favs_outdoor');
	                        tempmsg_favs_outdoor.eq(0 % tempmsg_favs_outdoor.length).text('Aussen: ' + OutdoorTemp);

	                        var tempmsg_devs_indoor = $('.tempmsg_devs_indoor');
	                        tempmsg_devs_indoor.eq(0 % tempmsg_devs_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_devs_outdoor = $('.tempmsg_devs_outdoor');
	                        tempmsg_devs_outdoor.eq(0 % tempmsg_devs_outdoor.length).text('Aussen: ' + OutdoorTemp);

	                        var tempmsg_groups_indoor = $('.tempmsg_groups_indoor');
	                        tempmsg_groups_indoor.eq(0 % tempmsg_groups_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_groups_outdoor = $('.tempmsg_groups_outdoor');
	                        tempmsg_groups_outdoor.eq(0 % tempmsg_groups_outdoor.length).text('Aussen: ' + OutdoorTemp);

	                        var tempmsg_rooms_indoor = $('.tempmsg_rooms_indoor');
	                        tempmsg_rooms_indoor.eq(0 % tempmsg_rooms_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_rooms_outdoor = $('.tempmsg_rooms_outdoor');
	                        tempmsg_rooms_outdoor.eq(0 % tempmsg_rooms_outdoor.length).text('Aussen: ' + OutdoorTemp);

	                        var tempmsg_timer_indoor = $('.tempmsg_timer_indoor');
	                        tempmsg_timer_indoor.eq(0 % tempmsg_timer_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_timer_outdoor = $('.tempmsg_timer_outdoor');
	                        tempmsg_timer_outdoor.eq(0 % tempmsg_timer_outdoor.length).text('Aussen: ' + OutdoorTemp);

	                        var tempmsg_actions_indoor = $('.tempmsg_actions_indoor');
	                        tempmsg_actions_indoor.eq(0 % tempmsg_actions_indoor.length).text('Innen: ' + IndoorTemp);
	                        var tempmsg_actions_outdoor = $('.tempmsg_actions_outdoor');
	                        tempmsg_actions_outdoor.eq(0 % tempmsg_actions_outdoor.length).text('Aussen: ' + OutdoorTemp);
	                    }
	                }
                },
                error: function () {
                    toast('Indoor-Outdoor-Temp-Check FAILED!');
                }
            }); 
        }  

        function CheckDeviceStatus() {
            $.ajax({
                url: 'get_devices.php',
                success: function (response) {
                    var Devices = response.split('|');
                    var Index;
                        for (Index = 0; Index < Devices.length; ++Index) {
                            var Device = Devices[Index].split(':');

                            if (Index == 0) {
                                if (Device[11] == 'false') {
                                    $('#notimermsg_actions').fadeIn('slow');
                                    $('#notimermsg_devs').fadeIn('slow');
                                    $('#notimermsg_favs').fadeIn('slow');
                                    $('#notimermsg_groups').fadeIn('slow');
                                    $('#notimermsg_rooms').fadeIn('slow');
                                    $('#notimermsg_timer').fadeIn('slow');
                                }
                                else {
                                    $('#notimermsg_actions').fadeOut('slow');
                                    $('#notimermsg_devs').fadeOut('slow');
                                    $('#notimermsg_favs').fadeOut('slow');
                                    $('#notimermsg_groups').fadeOut('slow');
                                    $('#notimermsg_rooms').fadeOut('slow');
                                    $('#notimermsg_timer').fadeOut('slow');
                                }

                                if (Device[13] == 'red') {
                                    if (AlertState == "green") {
                                        AlertState = "red";
                                        PlaySound('alertSound');
                                        AlertSoundTimer = setInterval(function () { PlaySound('alertSound'); }, 3000);
                                        $('#red-alert').fadeIn('slow', function(){
                                          $('#redalertframe').attr("src", "alert.html");
                                        });
                                    }
                                } else if (Device[13] == 'green') {
                                    if (AlertState == "red") {
                                        AlertState = "green";
                                        if (AlertSoundTimer != "") window.clearInterval(AlertSoundTimer);
                                        $('#red-alert').fadeOut('slow', function() {      
                                            $('#redalertframe').attr("src", 'about:blank');
                                        });
                                    }
                                } 
                            }

                            if (Device[4] == 'ROW_COLOR') {
                                switchRowTheme(Device[3].toLowerCase(),Device[0],'g','r');
                            }
                            else if (Device[4] == 'BUTTON_COLOR') {
                                switchButtonTheme(Device[3].toLowerCase(),Device[0],'g','r','e');
                            }
                            else if (Device[4] == 'BUTTON_ICON') {
                                switchButtonIcon(Device[3].toLowerCase(),Device[0],'check','off');
                            }

                            if (Device[5] == 'milight' || Device[5] == 'milight_rgbcct') {
                                var dev_mode_text = $('.device_devs_' + Device[0] + '_energy');
                                var fav_mode_text = $('.device_favs_' + Device[0] + '_energy');
                                var BRtext = '';

                                toggle_milight_buttons(Device[3].toLowerCase(),Device[0]);

                                if (Device[3] == 'ON' && ( Device[10] == '2' || Device[5] == 'milight_rgbcct') ) {
	                                if (Device[6] != 'UNDEF') {
					                	$("#milight_"+Device[0]+"_Modus_Farbe").css("background",Device[6]).spectrum("set",Device[6]);
	                                }
                                    
                                    if (Device[9] == "Weiß" || Device[9] == "Farbe" || Device[9] == "Programm")
                                        {
                                            $("#milight_"+Device[0]+"_brightness_controls").removeClass('hide').addClass('show');
                                            if (Device[5] == 'milight_rgbcct') {
                                                //$("#milight_"+Device[0]+"_temperature").attr('value',Device[14] + '%').button().button('refresh');
                                                $("#milight_"+Device[0]+"_temperature").attr('value',Device[14] + '%');
                                                $("#milight_"+Device[0]+"_temperature_kelvin").attr('value',TemperaturePercentToKelvin(Device[14]) + 'K').button().button('refresh');
                                                $("#milight_"+Device[0]+"_saturation").attr('value',Device[15] + '%').button().button('refresh');
                                            }
                                            
                                            if (Device[9] == "Weiß") {
                                            	BRtext = ' @ ' + Device[8] + '%';
                                                if (Device[5] == 'milight_rgbcct') {
                                                    $("#milight_"+Device[0]+"_temperature_controls").removeClass('hide').addClass('show');
                                                    $("#milight_"+Device[0]+"_saturation_controls").removeClass('show').addClass('hide');
                                                    BRtext = BRtext + ' (' + TemperaturePercentToKelvin(Device[14]) + 'K)';
                                                }
	                                		    $("#milight_"+Device[0]+"_brightness").attr('value',Device[8] + '%').button().button('refresh');
                                            }
                                            if (Device[9] == "Farbe") {
                                            	BRtext = ' @ ' + Device[7] + '%';
                                                if (Device[5] == 'milight_rgbcct') {
                                                    $("#milight_"+Device[0]+"_saturation_controls").removeClass('hide').addClass('show');
                                                    $("#milight_"+Device[0]+"_temperature_controls").removeClass('show').addClass('hide');
                                                    BRtext = BRtext + ' (Sat.: ' + Device[15] + '%)';
                                                }
                                                $("#milight_"+Device[0]+"_brightness").attr('value',Device[7] + '%').button().button('refresh');
                                            }
                                            if (Device[9] == "Programm") {
                                                BRtext = ' @ ' + Device[12] + '%';
                                                if (Device[5] == 'milight_rgbcct') {
                                                    $("#milight_"+Device[0]+"_saturation_controls").removeClass('show').addClass('hide');
                                                    $("#milight_"+Device[0]+"_temperature_controls").removeClass('show').addClass('hide');
                                                }
                                                $("#milight_"+Device[0]+"_brightness").attr('value',Device[12] + '%').button().button('refresh');
                                            }
	                                   }
                                    else {
										BRtext = '';
                                    	$("#milight_"+Device[0]+"_brightness_controls").removeClass('show').addClass('hide');
                                        if (Device[5] == 'milight_rgbcct') {
                                            $("#milight_"+Device[0]+"_saturation_controls").removeClass('show').addClass('hide');
                                            $("#milight_"+Device[0]+"_temperature_controls").removeClass('show').addClass('hide');
                                        }
                            		}

                                    if (Device[9] != 'UNDEF') {
                                        dev_mode_text.eq(0 % dev_mode_text.length).text('Modus: '+ Device[9] + BRtext);
                                        fav_mode_text.eq(0 % fav_mode_text.length).text('Modus: '+ Device[9] + BRtext);
                                        switchMilightControlModeIcon(Device[9],Device[0],'check','off');
                                    }
                                    else {
                                        dev_mode_text.eq(0 % dev_mode_text.length).text('');
                                        fav_mode_text.eq(0 % fav_mode_text.length).text('');
                                        switchMilightControlModeIcon('',Device[0],'check','off');
                                    }
	                            }
                                else {
                                    dev_mode_text.eq(0 % dev_mode_text.length).text('');
                                    fav_mode_text.eq(0 % fav_mode_text.length).text('');
                                }
                            }
                        }
                },
                error: function () {
                    toast('Dynamic Device-Status-Check FAILED!');
                }
            }); 
        }  

        function GetFBdectEnergy() {
            $.ajax({
                url: 'get_fbdect_energy.php',
                success: function (response) {
                    var DevicesEnergy = response.split('|');
                    //var EnergyCosts = <?php echo $xml->global->EnergyCosts; ?>;
                    var IndexEnergy;
                        for (IndexEnergy = 0; IndexEnergy < DevicesEnergy.length; ++IndexEnergy) {
                            var DeviceEnergy = DevicesEnergy[IndexEnergy].split(':');

                            if (DeviceEnergy[1] == -1) {
                                var DevPower = "---";
                            }
                            else {
                                var DevPower = DeviceEnergy[1] / 1000;
                                DevPower = DevPower.toFixed(2);
                            }

                            if (DeviceEnergy[2] == -1) {
                                var DevEnergy = "---";
                            }
                            else {
                                var DevEnergy = DeviceEnergy[2] / 1000;
                                DevEnergy = DevEnergy.toFixed(2);
                            }

                            //var DevEnergyCosts = DeviceEnergy[2] / 1000 * EnergyCosts;
                            //DevEnergyCosts = DevEnergyCosts.toFixed(2);                            

                            var dev_energy_text = $('.device_favs_' + DeviceEnergy[0] + '_energy');
                            //dev_energy_text.eq(0 % dev_energy_text.length).text(DevPower + ' W - ' + DevEnergy + ' kWh (' + DevEnergyCosts + ' EUR)');
                            dev_energy_text.eq(0 % dev_energy_text.length).text(DevPower + ' W - ' + DevEnergy + ' kWh');
                            var dev_energy_text = $('.device_devs_' + DeviceEnergy[0] + '_energy');
                            //dev_energy_text.eq(0 % dev_energy_text.length).text(DevPower + ' W - ' + DevEnergy + ' kWh (' + DevEnergyCosts + ' EUR)');
                            dev_energy_text.eq(0 % dev_energy_text.length).text(DevPower + ' W - ' + DevEnergy + ' kWh');
                        }
                },
                error: function () {
                    toast('FBdect200 Energy-Data-Check FAILED!');
                }
            }); 
        }  

		function CheckFBnetSysAlert() {
            $.ajax({
                url: 'get_sysstate.php',
                success: function (response) {
                    var SysState = response.split('|');
                    
                    if (SysState.length > 0) {
	                    var DateNow = Date.now() / 1000;
	                    var DateOld = SysState[1];
						var MaxAge = SysState[2];
	                    
	                    if (SysState[0] == 'green' && DateNow - DateOld < MaxAge) {
	                        $('#sysalertmsg_actions').fadeOut('slow');
	                        $('#sysalertmsg_devs').fadeOut('slow');
	                        $('#sysalertmsg_favs').fadeOut('slow');
	                        $('#sysalertmsg_groups').fadeOut('slow');
	                        $('#sysalertmsg_rooms').fadeOut('slow');
	                        $('#sysalertmsg_timer').fadeOut('slow');
	                    }
	                    else {
	                        $('#sysalertmsg_actions').fadeIn('slow');
	                        $('#sysalertmsg_devs').fadeIn('slow');
	                        $('#sysalertmsg_favs').fadeIn('slow');
	                        $('#sysalertmsg_groups').fadeIn('slow');
	                        $('#sysalertmsg_rooms').fadeIn('slow');
	                        $('#sysalertmsg_timer').fadeIn('slow');
	                    }
	                }
                },
                error: function () {
                    toast('FB.NET System-Status-Check FAILED!');
                }
            }); 
		}

		function BusyAni(ToDo) {
			if (ToDo == "show") {
				$("<div class='ui-loader ui-overlay-shadow ui-body-e ui-corner-all' id='BusyLoader'><div id='busy-circle' style='margin-top: 20%' class='circle play-circle'></div><div id='busy-circle1' class='circle1 play-circle1'></div></div>")
					.css({ display: "block",
						opacity: 0.90,
						"background": "center no-repeat #000000",
						position: "fixed",
						padding: "7px",
						width: "200px",
						left: ($(window).width() - 214)/2,
						top: $(window).height()/2 - 60 })
					.appendTo( $.mobile.pageContainer );
			}
			else if (ToDo == "hide") {
				$("#BusyLoader").remove();
			}
		}

        function PlaySound(file) {
        	<?php if ($xml->gui->playSounds=="true" || $xml->gui->playSounds=="") { ?>
            	var sound = document.getElementById(file);
            	sound.play();
            <?php } ?>
        }
		
</script>