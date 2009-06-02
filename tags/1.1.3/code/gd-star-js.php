<?php

if ($use_nonce) $nonce = sprintf("_ajax_nonce: '%s', ", wp_create_nonce('gdsr_ajax_r8'));
else $nonce = "";

?>

function gdsrWait(rater, loader) {
    jQuery("#"+rater).css("display", "none");
    jQuery("#"+loader).css("display", "block");
}

function gdsrEmpty() { }

jQuery(document).ready(function() {
    <?php if ($this->o["ajax"] == 1) : ?>
    if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_rating_as > a").click(function() {
        var el = jQuery(this).attr("id").split("X");
        gdsrWait(el[5], el[6]);
        jQuery.getJSON('<?php echo STARRATING_AJAX; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: el[4] }, function(json) {
            gdsrWait(el[6], el[5]);
            if (json.status == 'ok') {
                jQuery("#gdr_stars_" + el[1]).html("");
                jQuery("#gdr_vote_" + el[1]).css("width", json.value);
                jQuery("#gdr_text_" + el[1]).addClass("voted");
                jQuery("#gdr_text_" + el[1]).html(json.rater);
            }
        });
    });
    <?php endif; ?>
    <?php if ($include_cmm_review) include(STARRATING_PATH.'code/gd-star-jsx.php'); ?>
    <?php if ($include_mur_rating) include(STARRATING_PATH.'code/gd-star-jsm.php'); ?>
});