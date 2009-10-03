<?php if ($nonce != "") $nonce = sprintf("_ajax_nonce: '%s', ", $nonce); ?>

<?php include(STARRATING_PATH.'code/js/functions.php'); ?>
<?php include(STARRATING_PATH."code/js/cache_load.php"); ?>

jQuery(document).ready(function() {
    if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_rating_as > a").click(function() { gdsr_rating_standard(this); });

    if (jQuery.browser.msie) jQuery(".gdthumb > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdthumb > a").click(function() { gdsr_rating_thumb(this); });

    <?php include(STARRATING_PATH.'code/js/integration.php'); ?>
    <?php if ($include_mur_rating) {
        include(STARRATING_PATH.'code/js/multi_rt.php');
        include(STARRATING_PATH.'code/js/multi_in.php');
    } ?>
});
