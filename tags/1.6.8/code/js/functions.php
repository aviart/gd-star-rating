function gdsrWait(rater, loader) {
    jQuery("#"+rater).css("display", "none");
    jQuery("#"+loader).css("display", "block");
}

function jquery_escape_id(myid) {
    return '#'+myid.replace(/:/g,"\\:").replace(/\./g,"\\.");
}

function gdsrEmpty() { }

function gdsr_rating_standard(elm) {
    var el = jQuery(elm).attr("id").split("X");
    gdsrWait(el[5], el[6]);
    jQuery.getJSON('<?php echo STARRATING_AJAX_URL; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: el[4], vote_tpl: el[7], vote_size: el[8] }, function(json) {
        gdsrWait(el[6], el[5]);
        if (json.status == 'ok') {
            jQuery("#gdr_stars_" + el[4] + el[1]).html("");
            jQuery("#gdr_vote_" + el[4] + el[1]).css("width", json.value);
            jQuery("#gdr_text_" + el[4] + el[1]).addClass("voted");
            jQuery("#gdr_text_" + el[4] + el[1]).html(json.rater);
        }
    });
}

function gdsr_rating_thumb(elm) {
    var cls = jQuery(elm).attr('class');
    var el = jQuery(elm).attr("id").split("X");
    if (el[6] == 'Y') gdsrWait("gdsr_thumb_" + el[1] + "_" + el[3] + "_" + el[2], "gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "loader_" + el[2]);
    jQuery("#gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "up a").replaceWith('<div class="' + cls + '"></div>');
    jQuery("#gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "dw a").replaceWith('<div class="' + cls + '"></div>');
    jQuery.getJSON('<?php echo STARRATING_AJAX_URL; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: 'r'+el[3], vote_tpl: el[4], vote_size: el[5] }, function(json) {
        if (el[6] == 'Y') gdsrWait("gdsr_thumb_" + el[1] + "_" + el[3] + "_" + "loader_" + el[2], "gdsr_thumb_" + el[1] + "_" + el[3] + "_" + el[2]);
        if (json.status == 'ok') {
            jQuery("#gdsr_thumb_text_" + el[1] + "_" + el[3]).addClass("voted");
            jQuery("#gdsr_thumb_text_" + el[1] + "_" + el[3]).html(json.rater);
        }
    });
}

<?php if ($include_mur_rating) { ?>

function multi_rating_vote(block) {
    var el = block.split("_");
    var post_id = el[0];
    var set_id = el[1];
    var tpl_id = el[2];
    var size = el[3]
    var values = jQuery("#gdsr_multi_" + post_id + "_" + set_id).val();
    gdsrWait("gdsr_mur_text_" + post_id + "_" + set_id, "gdsr_mur_loader_" + post_id + "_" + set_id);
    jQuery.getJSON('<?php echo STARRATING_AJAX_URL; ?>', {<?php echo $nonce; ?>vote_id: post_id, vote_set: set_id, vote_value: values, vote_tpl: tpl_id, vote_type: 'm', vote_size: size }, function(json) {
        jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .gdsr_multis_as").remove();
        jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .gdcurrent").remove();
        jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " input").remove();
        jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .ratingbutton").remove();
        var height = jQuery("#gdsr_mur_avgstars_" + post_id + "_" + set_id + " div").css("height");
        if (height > 0)
            jQuery("#gdsr_mur_avgstars_" + post_id + "_" + set_id + " div").css("width", json.average * height.substring(0, 2));
        for (var i = 0; i < json.values.length; i++)
            jQuery("#gdsr_mur_stars_rated_" + post_id + "_" + set_id + "_" + i).css("width", json.values[i]);
        jQuery("#gdsr_mur_text_" + post_id + "_" + set_id).html(json.rater).addClass("voted");
        gdsrWait("gdsr_mur_loader_" + post_id + "_" + set_id, "gdsr_mur_text_" + post_id + "_" + set_id);
    });
}

<?php } ?>
