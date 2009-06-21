<?php

    require_once(dirname(__FILE__)."/config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;
    if ($gdsr->use_nonce) {
        require_once(ABSPATH.WPINC."/pluggable.php");
        check_ajax_referer('gdsr_ajax_r8');
    }

    $vote_id = intval($_GET["vote_id"]);
    $vote_tpl = intval($_GET["vote_tpl"]);
    $vote_size = intval($_GET["vote_size"]);
    $vote_value = $_GET["vote_value"];
    $vote_type = $_GET["vote_type"];

    if ($vote_type != "a" && $vote_type != "c" && $vote_type != "m") $result = "xss_error";
    else {
        $result = $vote_type."_error";
        if ($vote_type != "m") $vote_value = intval($vote_value);
        switch ($vote_type) {
            case 'a':
                $result = $gdsr->vote_article_ajax($vote_value, $vote_id, $vote_tpl, $vote_size);
                break;
            case 'c':
                $result = $gdsr->vote_comment_ajax($vote_value, $vote_id, $vote_tpl, $vote_size);
                break;
            case 'm':
                $vote_set = intval($_GET["vote_set"]);
                if ($vote_set > 0) $result = $gdsr->vote_multi_rating($vote_value, $vote_id, $vote_set, $vote_tpl, $vote_size);
                else $result = "m_set_error";
                break;
        }
    }

    if (isset($_GET["callback"])) {
        $callback = $_GET["callback"];
        echo $callback.'('.$result.');';
    }
    else echo $result;

?>
