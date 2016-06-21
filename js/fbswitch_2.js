<script type="text/javascript">

        var EVENT_HUE = 0;
        var EVENT_LUM = 1;
        var milight_colorswipeMS = 100;
        var lastTimestamps = [0, 0];
        var lastEventTimers = [0, 0];

        function milight_colorswipe(dataToSend, evtId) {
          var timestamp = $.now();
          if (lastEventTimers[evtId] != 0) {
            window.clearTimeout(lastEventTimers[evtId]);
          }
          if ((timestamp-lastTimestamps[evtId]) > milight_colorswipeMS) {
            lastTimestamps[evtId] = timestamp;
            sendMlAjax(dataToSend);
          } else {
            lastEventTimers[evtId] = window.setTimeout(sendMlAjax, milight_colorswipeMS, dataToSend);
          }
        }

        function sendMlAjax(dataToSend) {
        $.ajax({
            url: "milight_ajax.php",
            data: dataToSend,
            success: function(response) {
                if (response != "#OK#") {
                    toast(response);
                   } 
                },
            error: function(response) {
                toast(response);
            }
        });
        }


        function send_connair(action, type, id) {
            BusyAni('show');
			
			var data={ 'action': action, 'type': type, 'id': id };
            //toast( 'action:'+ action+ '  type:'+ type+ '  id:'+ id);
            $.ajax({
                type:'POST', 
                url: '<?php echo $_SERVER['PHP_SELF']; ?>', 
                data: data,
                async: true,
                success: function(response) {
					CheckDeviceStatus();
                    <?php if ($AutoRefreshDeviceData == "false" && $ShowFBdect200EnergyData == "true") { ?>
						if (FBdectTimer != "") window.clearTimeout(FBdectTimer);
						FBdectTimer = setTimeout(function () { GetFBdectEnergy()}, 10000);
					<?php } ?>
					BusyAni('hide');
					toast(response);
                },
                error: function(response) {
                    BusyAni('hide');
					toast(response);
                }
            });
        }

        function send_milight(id, command, value) {
			BusyAni('show');
            
			var ToDo = "sendmilight";
            var data={ 'todo': ToDo, 'id': id, 'command': command, 'value': value };
            //toast( 'todo:' + ToDo + ',id:' + id + ',command:' + command + ',value:' + value);
            $.ajax({
                type:'POST', 
                url: '<?php echo $_SERVER['PHP_SELF']; ?>', 
                data: data,
                async: true,
                success: function(response) {
					CheckDeviceStatus();
					BusyAni('hide');
					if (response.indexOf('#OK#') < 0) toast(response);
                },
                error: function(response) {
					BusyAni('hide');
                    toast('Konnte MiLight-Kommando nicht senden!');
                }
            });
        }
        
        function reboot_connair() {
            $.ajax({
                type:'POST', 
                url: 'edit_config.php',
                data: 'action=rebootconnair',
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        toast("Gateway wird rebootet");
                    } else {
                        toast('Reboot fehlgeschlagen: ' + response);
                    }
                },
                error: function(jqXHR, exception) {
                    toast('Uncaught Error: ' + jqXHR.responseText);
                }
            });
        }

        function refreshPage()
        {
         location.reload();
        }

        function updateTheme(newTheme) {
            var rmbtnClasses = '';
            var rmhfClasses = '';
            var rmbdClassess = '';
            var arr = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"  ];

            $.each(arr,function(index, value){
                rmbtnClasses = rmbtnClasses + " ui-btn-up-"+value + " ui-btn-hover-"+value;
                rmhfClasses = rmhfClasses + " ui-bar-"+value;
                rmbdClassess = rmbdClassess + " ui-body-"+value;
            });

             $.mobile.activePage.find('.ui-btn').not('.ui-li-divider').removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
             $.mobile.activePage.find('.ui-header, .ui-footer').removeClass(rmhfClasses).addClass('ui-bar-' + newTheme).attr('data-theme', newTheme);
             $.mobile.activePage.removeClass(rmbdClassess).addClass('ui-body-' + newTheme).attr('data-theme', newTheme);
             $.mobile.activePage.find('.ui-li-divider').each(function(index, obj) {
                $(this).removeClass(rmhfClasses).addClass('ui-bar-' + newTheme).attr('data-theme',newTheme);
             });
        }

        function switchRowTheme(action, id, onColor, offColor) {
            var rmbtnClasses = '';
            var arr = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"  ];
            $.each(arr,function(index, value){
                rmbtnClasses = rmbtnClasses + " ui-btn-up-"+value + " ui-btn-hover-"+value;
            });
            if(action == "on") {
                newTheme = onColor;
            } else if(action == "off") {
                newTheme = offColor;
            }         
            $("[id=deviceRow"+id+"]").each(function() {
                $(this).removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
            });
        }

        function switchButtonTheme(action, id, onColor, offColor, curColor) {
            var rmbtnClasses = '';
            var arr = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"  ];
            $.each(arr,function(index, value){
                rmbtnClasses = rmbtnClasses + " ui-btn-up-"+value + " ui-btn-hover-"+value;
            });
            if(action == "on") {
                newTheme = curColor;
                $("[id=btnOn"+id+"]").each(function() {
                    $(this).button().attr('data-theme', newTheme).parent('.ui-btn').removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
                });
                newTheme = offColor;
                $("[id=btnOff"+id+"]").each(function() {
                    $(this).button().attr('data-theme', newTheme).parent('.ui-btn').removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
                });
            } else if(action == "off") {
                newTheme = onColor;
                $("[id=btnOn"+id+"]").each(function() {
                    $(this).button().attr('data-theme', newTheme).parent('.ui-btn').removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
                });
                newTheme = curColor;
                $("[id=btnOff"+id+"]").each(function() {
                    $(this).button().attr('data-theme', newTheme).parent('.ui-btn').removeClass(rmbtnClasses).addClass('ui-btn-up-' + newTheme).attr('data-theme', newTheme);
                });
            }
        }

        function switchButtonIcon(action, id, onIcon, offIcon) {
            if(action == "on") {
                $("[id=btnOn"+id+"]").each(function() {
                    $(this).button().buttonMarkup({ icon: onIcon });
                });
            } else if(action == "off") {
                $("[id=btnOn"+id+"]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
            }
        }
        
        function showEditButtons() {
            var editButton = $('#editButton');
            if(editButton.hasClass("l3x-active")) {
                editButton.removeClass("ui-btn-active");
                editButton.removeClass("l3x-active");
                
                $.mobile.activePage.find(".box-btn-edit").each(function() {
                    $(this).removeClass('show').addClass('hide');
                });
                $.mobile.activePage.find(".box-btn-switch").each(function() {
                    $(this).removeClass('hide').addClass('show');
                });
            } else {
                editButton.addClass("ui-btn-active");
                editButton.addClass("l3x-active"); 
                
                $.mobile.activePage.find(".box-btn-switch").each(function() {
                    $(this).removeClass('show').addClass('hide');
                });
                $.mobile.activePage.find(".box-btn-edit").each(function() {
                    $(this).removeClass('hide').addClass('show');
                });
            }
        }

        function toggle_milight_buttons(mode, id) {
            if (mode == "on") {
                $("#milight_"+id+"_offtext").removeClass('show').addClass('hide');
                $("#milight_"+id+"_buttons").removeClass('hide').addClass('show');
            }
            else if (mode == "off") {
                $("#milight_"+id+"_offtext").removeClass('hide').addClass('show');
                $("#milight_"+id+"_buttons").removeClass('show').addClass('hide');
            }
        }

        function switchMilightControlModeIcon(mode, id, onIcon, offIcon) {
            if(mode == "Farbe") {
                $("[id=milight_"+id+"_Modus_Farbe]").each(function() {
                    $(this).button().buttonMarkup({ icon: onIcon });
                });
                $("[id=milight_"+id+"_Modus_Weiss]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Nacht]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Programm]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
            } else if(mode == "Weiß") {
                $("[id=milight_"+id+"_Modus_Farbe]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Weiss]").each(function() {
                    $(this).button().buttonMarkup({ icon: onIcon });
                });
                $("[id=milight_"+id+"_Modus_Nacht]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Programm]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
            } else if(mode == "Nacht") {
                $("[id=milight_"+id+"_Modus_Farbe]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Weiss]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Nacht]").each(function() {
                    $(this).button().buttonMarkup({ icon: onIcon });
                });
                $("[id=milight_"+id+"_Modus_Programm]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
            } else if(mode == "Programm") {
                $("[id=milight_"+id+"_Modus_Farbe]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Weiss]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Nacht]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Programm]").each(function() {
                    $(this).button().buttonMarkup({ icon: onIcon });
                });
            } else {
                $("[id=milight_"+id+"_Modus_Farbe]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Weiss]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Nacht]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
                $("[id=milight_"+id+"_Modus_Programm]").each(function() {
                    $(this).button().buttonMarkup({ icon: offIcon });
                });
            }
        }
        
        function edit_device(id) {
            $.ajax({
                url: "index.php#newdevice",
                type: "POST",
                data: "action=edit&id="+id,
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
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }
        function delete_device(id) {
            $.ajax({
                url: "edit_device.php",
                type: "POST",
                data: "action=delete&id="+id,
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#devices';                        
                        toast('Gelöscht');
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }
        function delete_person(id) {
            $.ajax({
                url: "edit_person.php",
                type: "POST",
                data: "action=delete&id="+id,
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#configurations';
                        toast('Gelöscht');
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }
        function edit_group(id) {
            $.ajax({
                url: "index.php#newgroup",
                type: "POST",
                data: "action=edit&id="+id,
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
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }
        function delete_group(id) {
            $.ajax({
                url: "edit_group.php",
                type: "POST",
                data: "action=delete&id="+id,
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#groups';
                        toast('Gelöscht');
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }
        function edit_timer(id, action) {
            if(action=="EIN") {
                $.ajax({
                    url: "edit_timer.php",
                    type: "POST",
                    data: "action=on&id="+id,
                    async: true,
                success: function(response) {
                        if(response.trim()=="ok") {
                            setTimeout(function(){refreshPage();}, 1500);
                            location.href = 'index.php#timers';
                            toast('Timer aktiviert');
                        } else {
                            toast('response:'+response);
                        }
                }
                });
            }
            if(action=="AUS") {
                $.ajax({
                url: "edit_timer.php",
                type: "POST",
                data: "action=off&id="+id,
                    async: true,
                success: function(response) {
                if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#timers';
                        toast('Timer deaktiviert');
                        } else {
                            toast('response:'+response);
                        }
                }
                });
            }
        }
        function delete_timer(id) {
            $.ajax({
                url: "edit_timer.php",
                type: "POST",
                data: "action=delete&id="+id,
                async: true,
                success: function(response) {
                    if(response.trim()=="ok") {
                        setTimeout(function(){refreshPage();}, 1500);
                        location.href = 'index.php#timers';
                        toast('Gelöscht');
                    } else {
                        toast('response:'+response);
                    }
                }
            });
        }

        function newcountdown() {
            var countdown = $('#countdown');
            if(countdown.hasClass("l3x-active")) {
                countdown.removeClass("newcountdown");
                countdown.removeClass("l3x-active");
                
                $.mobile.activePage.find(".newcountdown").each(function() {
                    $(this).removeClass('show').addClass('hide');
                });
            } else {
                countdown.addClass("newcountdown");
                countdown.addClass("l3x-active"); 
                
                $.mobile.activePage.find(".newcountdown").each(function() {
                    $(this).removeClass('hide').addClass('show');
                });
            }
        }
        
        yj = {};

        yj.newcountdown = function(){
            $('#newcountdown').submit(function(evt){
                evt.preventDefault();

                var minutes = $('select[name=minutes]');
                var device = $('select[name=device]');
                var action = $('select[name=action]');

                $messagetrue = '<div class="alert alert-success">Countdown erfolgreich gesetzt!</div><br>';
                $messagefalse = '<div class="alert alert-error">Zeit wählen!</div><br>';

                var data =
                'minutes=' + minutes.val() + 
                '&device=' + device.val() +
                '&action=' + action.val();

                $.ajax({
                    url: "createcountdown.php?mode=submit",
                    type: "POST",
                    data: data,

                    success: function(data){
                        if(data == '1'){
                            $('#formmessage').html($messagetrue).fadeIn('slow');
                            setTimeout("$('#formmessage').html($messagetrue).fadeOut('slow')", 3000);
                        }else{
                            $('#formmessage').html($messagefalse).fadeIn('slow');
                        }
                    }
                    
                });
            })
        };

        jQuery(function($){
            yj.newcountdown();
        });
    
</script>