<?php

    require_once("./gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;

    if ($gdsr->use_nonce) $nonce = wp_create_nonce('gdsr_ajax_r8');
    else $nonce = "";
    $ajax_active = $gdsr->o["ajax"];

    $include_mur_rating = $gdsr->o["multis_active"] == 1;
    $include_cmm_review = $gdsr->o["comments_review_active"] == 1;

    header('Content-Type: text/javascript');

    include ($gdsr->plugin_path."code/gd-star-js.php");

?>
