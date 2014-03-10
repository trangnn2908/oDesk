(function($) {
    $(function() {
        $(".flag-select").click(function() {
            var $type = $(this).attr('data-select-type'),
                    $value = $(this).attr('data-select-value');

            $("#flag-" + $type).val($value);

            $(".flag-select[data-select-type='" + $type + "']").removeClass('disabled')

            $(this).addClass('disabled');
        });

        $(".track-select").click(function() {
            $(this).parent().children().removeClass('error');
            $(this).addClass('error');
            $("#" + $(this).attr('id') + " .track-id-radio").attr('checked', true);
        });

        $("#start-race-button").click(function(e) {
            e.preventDefault();
            var $track_id = $("input[name=track-id]:checked").val();

            if ($track_id === undefined) {
                alert("You must be choose one and only one track to start race");
                return;
            } else {
                window.location = "control.php?track_id=" + $track_id;
            }
        });

        function send_message(action, color, local, text) {
            var $track_id = $("#track-id").val();

            $("#myModal").modal();
            $.ajax({
                url: "sendmessage.php",
                data: {
                    track_id: $track_id,
                    action: action,
                    color: color,
                    number: local,
                    text: text
                },
                success: function(data) {
                    $("#myModal").modal('hide');
                    if (data.error !== 0) {
                        alert("Error: " + data.msg);
                    } else {
                        var service_data = data.data;
//                        var current_state = 0;
                        if (service_data !== null && service_data !== undefined) {
                            if (service_data.globalMessage !== undefined) {
                                if (service_data.globalMessage.color !== undefined) {
                                    $(".btn-flag-" + service_data.globalMessage.color).addClass('active').siblings("[class^='btn-flag-']").removeClass('active');
                                }
                            }
                            
                            if(service_data.localFlags !== undefined) {
                                $(".number-group a").removeClass('active');
                                $.each(service_data.localFlags, function(index, flag) {
                                    $("#flag-number-" + flag.number).addClass('active');
                                });
//                                if (service_data.localFlags[service_data.localFlags.length - 1].number !== undefined) {
//                                    $("#flag-number-" + service_data.localFlags[service_data.localFlags.length - 1].number).addClass('active').siblings('a').removeClass('active');
//                                    if (service_data.localFlags[service_data.localFlags.length - 1].number != local) {
//                                        current_state = service_data.localFlags[service_data.localFlags.length - 1].number;
//                                    } else {
//                                        if (service_data.localFlags[service_data.localFlags.length - 2] !== undefined) {
//                                            current_state = service_data.localFlags[service_data.localFlags.length - 2].number;
//                                        }
//                                    }
//                                }
                            }
                        }

//                        var current_state_html = "yellow " + current_state;

                        var date = new Date();
                        var sent_time = date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

                        var html = "<tr><td>" + action + "</td><td>" + color + " " + local + " " + text + "</td><td>" + sent_time + "</td></tr>"
                        $("#history-table tbody").prepend(html);
                    }
                }
            });
        }

        $("[class^='btn-flag-']").click(function() {
            var $color = $(this).attr('class').replace("btn-flag-", "");
            var data = send_message('sendglobalflag', $color, '', '');
        });

        $(".number-group a").click(function() {
            var $local = $(this).attr('id').replace("flag-number-", "");
            var data = send_message('sendlocalflag', 'yellow', $local, '');
        });

        $("#send-message").click(function(e) {
            e.preventDefault();
            var $text = $("#message").val();
            var data = send_message('sendtext', '', '', $text);
        });
    });
}(jQuery));