<?php if ($nonce != "") $nonce = sprintf("_ajax_nonce: '%s', ", $nonce); ?>

function gdsrWait(rater, loader) {
    jQuery("#"+rater).css("display", "none");
    jQuery("#"+loader).css("display", "block");
}

function gdsrEmpty() { }

<?php if ($include_mur_rating) include(STARRATING_PATH.'code/js/multi_fn.php'); ?>
jQuery(document).ready(function() {
    if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_rating_as > a").click(function() {
        var el = jQuery(this).attr("id").split("X");
        gdsrWait(el[5], el[6]);
        jQuery.getJSON('<?php echo STARRATING_AJAX; ?>', {<?php echo $nonce; ?>vote_id: el[1], vote_value: el[2], vote_type: el[4], vote_tpl: el[7], vote_size: el[8] }, function(json) {
            gdsrWait(el[6], el[5]);
            if (json.status == 'ok') {
                jQuery("#gdr_stars_" + el[4] + el[1]).html("");
                jQuery("#gdr_vote_" + el[4] + el[1]).css("width", json.value);
                jQuery("#gdr_text_" + el[4] + el[1]).addClass("voted");
                jQuery("#gdr_text_" + el[4] + el[1]).html(json.rater);
            }
        });
    });
    <?php include(STARRATING_PATH.'code/js/integration.php'); ?>
    <?php include(STARRATING_PATH.'code/js/thumbs.php'); ?>
    <?php if ($include_mur_rating) {
        include(STARRATING_PATH.'code/js/multi_rt.php');
        include(STARRATING_PATH.'code/js/multi_in.php');
    } ?>
});
