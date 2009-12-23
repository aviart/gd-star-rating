<?php

    define('STARRATING_AJAX', true);

    require_once(dirname(__FILE__)."/config.php");
    $wpload = get_gdsr_wpload_path();
    require($wpload);
    global $gdsr;

    $nonce = $gdsr->use_nonce ? wp_create_nonce('gdsr_ajax_r8') : "";
    $include_mur_rating = $gdsr->o["multis_active"] == 1;
    $include_cmm_review = $gdsr->o["comments_review_active"] == 1;
    $button_active = $gdsr->o["mur_button_active"] == 1;

    @ob_start ("ob_gzhandler");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: text/javascript; charset: UTF-8");
    header("Cache-Control: must-revalidate");

    include(STARRATING_PATH."code/js/main.php");
    include(STARRATING_PATH."code/js/comments.php");

    if ($gdsr->o["cmm_integration_replay_hide_review"]) { ?>

jQuery(document).ready(function() {
    jQuery(".comment-reply-link").click(function() { hideshowCmmInt(); });
});

<?php }

?>