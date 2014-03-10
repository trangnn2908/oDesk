(function($) {
    $(function() {

        function alert_message(action, message, disable) {
            if (action === 'show') {
                var disable_class = disable === true ? ' disabled' : '';
                $('body').append('<div id="loading" class="alert">' + message + '</div>', '<div class="modal-backdrop' + disable_class + '">');
            } else {
                $('#loading, .modal-backdrop').remove();
            }
        }

        $('body').on('click', '.modal-backdrop', function() {
            if ($(this).hasClass('disabled')) {
                // do nothing;
            } else {
                alert_message('hide');
            }
        });

        $(".track-select").click(function() {
            $(this).addClass('error').siblings('tr').removeClass('error');
            $("#track-id-selected").val($(this).attr('id'));
        });

        $("#start-race-button").click(function(e) {
            e.preventDefault();
            var $track_id = $("#track-id-selected").val();

            if ($track_id === undefined || $track_id === '') {
                alert_message("show", "You must be choose one and only one track to start race");
                return;
            } else {
                window.location = "control.php?track_id=" + $track_id;
            }
        });

        function send_message(action, color, local, text) {
            var $track_id = $("#track-id").val();

            alert_message("show", "Sending message...", true);

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
                        alert_message("show", "Error: " + data.msg, true);
                    } else {
                        var service_data = data.data;
                        if (service_data !== null && service_data !== undefined) {
                            if (service_data.globalMessage !== undefined) {
                                if (service_data.globalMessage.color !== undefined) {
                                    $(".btn-flag-" + service_data.globalMessage.color).addClass('active').siblings("[class^='btn-flag-']").removeClass('active');
                                }
                            }

                            if (service_data.localFlags !== undefined) {
                                $(".number-group a").removeClass('active');
                                $.each(service_data.localFlags, function(index, flag) {
                                    $("#flag-number-" + flag.number).addClass('active');
                                });
                            }

                            if (service_data.history !== undefined) {
                                var history_tbody = "";
                                $.each(service_data.history, function(index, history_entry) {
                                    var time = new Date(history_entry.time);
                                    var time_show = time.toLocaleDateString() + " " + time.toLocaleTimeString();
                                    history_tbody += "<tr><td>" + history_entry.action + "</td><td>";
                                    history_tbody += history_entry.message + "</td><td>";
                                    history_tbody += time_show + "</td></tr>";
                                });

                                $("#history-table tbody").html(history_tbody);
                            }
                        }

                    }
                    setTimeout(function() {
                        alert_message('hide')
                    }, 500);
                }
            });
        }

        $("[class^='btn-flag-']").click(function(e) {
            e.preventDefault();
            var $color = $(this).attr('class').replace("btn-flag-", "");
            send_message('sendglobalflag', $color, '', '');

        });

        $(".number-group a").click(function(e) {
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