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
            $('body').append('<div id="loading" class="alert">Sending message ....</div>', '<div class="modal-backdrop">');
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
                    if (data.error !== 0) {
                        alert("Error: " + data.msg);
                    } else {
                        var service_data = data.data;
                        if (service_data !== null && service_data !== undefined) {
                            if (service_data.globalMessage !== undefined) {
                                if (service_data.globalMessage.color !== undefined) {
                                    $(".btn-flag-" + service_data.globalMessage.color).addClass('active').siblings("[class^='btn-flag-']").removeClass('active');
                                }
                            }

                            if (service_data.localFlags !== undefined) {
                                $(".number-group button").removeClass('active');
                                $.each(service_data.localFlags, function(index, flag) {
                                    $("#flag-number-" + flag.number).addClass('active');
                                });
                            }
                        }
                        var date = new Date();
                        var sent_time = date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                        var html = "<tr><td>" + action + "</td><td>" + color + " " + local + " " + text + "</td><td>" + sent_time + "</td></tr>"
                        $("#history-table tbody").prepend(html);
                    }
                    setTimeout(function(){$('#loading, .modal-backdrop').remove()}, 500);
                }
            });
        }

        $("[class^='btn-flag-']").click(function(e) {
            e.preventDefault();
            var $color = $(this).attr('class').replace("btn-flag-", "");
            send_message('sendglobalflag', $color, '', '');

        });

        $(".number-group button").click(function(e) {
            e.preventDefault();
            var $local = $(this).attr('id').replace("flag-number-", "");
            send_message('sendlocalflag', 'yellow', $local, '');
        });

        $("#send-message").click(function(e) {
            e.preventDefault();
            var $text = $("#message").val();
            send_message('sendtext', '', '', $text);
        });
    });
}(jQuery));