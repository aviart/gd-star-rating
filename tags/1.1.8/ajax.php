<?php

    require_once(dirname(__FILE__)."/gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;
    if ($gdsr->use_nonce) {
        require_once(ABSPATH.WPINC."/pluggable.php");
        check_ajax_referer('gdsr_ajax_r8');
    }

    $vote_id = $_GET["vote_id"];
    $vote_value = $_GET["vote_value"];
    $vote_type = $_GET["vote_type"];
    
    switch ($vote_type) {
        case 'a':
            $result = $gdsr->vote_article_ajax($vote_value, $vote_id);
            break;
        case 'c':
            $result = $gdsr->vote_comment_ajax($vote_value, $vote_id);
            break;
        case 'm':
            $vote_set = $_GET["vote_set"];
            $result = $gdsr->vote_multi_rating($vote_value, $vote_id, $vote_set);
            break;
    }
    echo $result;

?>