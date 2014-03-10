(function($) {
    $(function() {
        $(".flag-select").click(function() {
            var $type = $(this).attr('data-select-type'),
                    $value = $(this).attr('data-select-value');
            
            $("#flag-" + $type).val($value);
            
            $(".flag-select[data-select-type='"+ $type +"']").removeClass('disabled')
            
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
            
            if($track_id === undefined) {
                alert("You must be choose one and only one track to start race");
                return ;
            } else {
                window.location = "send.php?track_id=" + $track_id;
            }
        });
    });
}(jQuery));