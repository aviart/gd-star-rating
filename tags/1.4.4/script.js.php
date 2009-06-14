<?php

    require_once("./config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;

    if ($gdsr->use_nonce) $nonce = wp_create_nonce('gdsr_ajax_r8');
    else $nonce = "";

    $include_mur_rating = $gdsr->o["multis_active"] == 1;
    $include_cmm_review = $gdsr->o["comments_review_active"] == 1;
    $button_active = $gdsr->o["mur_button_active"] == 1;

    header("Cache-Control: no-cache, must-revalidate");
    header('Content-Type: text/javascript');

    include ($gdsr->plugin_path."code/js/main.php");

    if ($gdsr->o["cmm_integration_replay_hide_review"] == 1)
        include ($gdsr->plugin_path."code/js/comments.php");

?>
