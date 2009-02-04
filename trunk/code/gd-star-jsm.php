    if (jQuery.browser.msie) jQuery(".gdsr_multisbutton_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_multisbutton_as > a").click(function() {
        if (jQuery(this).hasClass("active")) {
            var el = jQuery(this).parent().attr("id").substring(12).split("_");
            var post_id = el[0];
            var set_id = el[1];
            var values = jQuery("#gdsr_multi_" + post_id + "_" + set_id).val();
            gdsrWait("gdsr_mur_text_" + post_id + "_" + set_id, "gdsr_mur_loader_" + post_id + "_" + set_id);
            jQuery.getJSON('<?php echo STARRATING_AJAX; ?>', {<?php echo $nonce; ?>vote_id: post_id, vote_set: set_id, vote_value: values, vote_type: 'm' }, function(json) {
                jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .gdsr_multis_as").remove();
                jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .gdcurrent").remove();
                jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " input").remove();
                jQuery("#gdsr_mur_block_" + post_id + "_" + set_id + " .ratingbutton").remove();
                for (var i = 0; i < json.values.length; i++)
                    jQuery("#gdsr_mur_stars_rated_" + post_id + "_" + set_id + "_" + i).css("width", json.values[i]);
                jQuery("#gdsr_mur_text_" + post_id + "_" + set_id + " .ratingtextmulti").html(json.rater).addClass("voted");
                gdsrWait("gdsr_mur_loader_" + post_id + "_" + set_id, "gdsr_mur_text_" + post_id + "_" + set_id);
            });
        }
    });

    if (jQuery.browser.msie) jQuery(".gdsr_multis_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_multis_as > a").click(function() {
        var el = jQuery(this).attr("id").split("X");
        var vote = el[4];
        var size = el[5];
        var new_width = vote * size;
        var current_id = '#gdsr_mur_stars_current_' + el[1] + '_' + el[2] + '_' + el[3];
        var input_id = '#gdsr_multi_' + el[1] + '_' + el[2];
        jQuery(current_id).css("width", new_width + "px");
        var rating_values = jQuery(input_id).val().split("X");
        rating_values[el[3]] = vote;
        var active = true;
        for (var i = 0; i < rating_values.length; i++) {
            if (rating_values[i] == 0) {
                active = false;
                break;
            }
        }
        jQuery(input_id).val(rating_values.join("X"));
        var button_id = '#gdsr_button_' + el[1] + '_' + el[2];
        if (active) {
            jQuery(button_id).removeClass('gdinactive');
            jQuery(button_id).addClass('gdactive');
            jQuery(button_id + " a").addClass('active');
        }
        else {
            jQuery(button_id).removeClass('gdactive');
            jQuery(button_id).addClass('gdinactive');
            jQuery(button_id + " a").removeClass('active');
        }
    });
