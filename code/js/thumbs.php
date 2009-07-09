
jQuery(".gdsr_thumb > a").click(function() {
    var el = jQuery(this).attr("id").split("X");
    //jQuery.getJSON('<?php echo STARRATING_AJAX; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: 'r'+el[3], vote_tpl: el[4], vote_size: el[5] }, function(json) {
    //});
});
