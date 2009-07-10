
jQuery(".gdthumb > a").click(function() {
    var el = jQuery(this).attr("id").split("X");
    jQuery.getJSON('<?php echo STARRATING_AJAX; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: 'r'+el[3], vote_tpl: el[4], vote_size: el[5] }, function(json) {
        if (json.status == 'ok') {
            jQuery("#gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "up").remove();
            jQuery("#gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "dw").remove();
            jQuery("#gdsr_thumb_text_" + el[1] + el[3]).addClass("voted");
            jQuery("#gdsr_thumb_text_" + el[1] + el[3]).html(json.rater);
        }
    });
});
