<?php

define('STARRATING_AJAX', true);
$types = array("a", "c", "m", "ra", "rc");

require_once(dirname(__FILE__)."/config.php");
$wpload = get_gdsr_wpload_path();
require($wpload);
global $gdsr;

if ($gdsr->use_nonce) {
    require_once(ABSPATH.WPINC."/pluggable.php");
    check_ajax_referer('gdsr_ajax_r8');
}

$vote_type = $_GET["vote_type"];

$vote_id = isset($_GET["vote_id"]) ? intval($_GET["vote_id"]) : 0;
$vote_tpl = isset($_GET["vote_tpl"]) ? intval($_GET["vote_tpl"]) : 0;
$vote_size = isset($_GET["vote_size"]) ? intval($_GET["vote_size"]) : 0;
$vote_value = isset($_GET["vote_value"]) ? $_GET["vote_value"] : "";

if ($vote_type == "cache") {
    $result = "xss_error";
    if (isset($_GET["vote_domain"]) && isset( $_GET["votes"])) {
        $votes = explode(":", $_GET["votes"]);
        switch ($_GET["vote_domain"]) {
            case 'a':
                $result = $gdsr->cached_posts($votes);
                break;
            case 'c':
                $result = $gdsr->cached_comments($votes);
                break;
        }
    }
} else if (!(in_array($vote_type, $types))) {
    $result = "xss_error";
} else {
    $result = $vote_type."_error";
    if ($vote_type == "a" || $vote_type == "c") $vote_value = intval($vote_value);
    switch ($vote_type) {
        case 'ra':
            $result = $gdsr->v->vote_thumbs_article($vote_value, $vote_id, $vote_tpl, $vote_size);
            break;
        case 'rc':
            $result = $gdsr->v->vote_thumbs_comment($vote_value, $vote_id, $vote_tpl, $vote_size);
            break;
        case 'a':
            $result = $gdsr->v->vote_article($vote_value, $vote_id, $vote_tpl, $vote_size);
            break;
        case 'c':
            $result = $gdsr->v->vote_comment($vote_value, $vote_id, $vote_tpl, $vote_size);
            break;
        case 'm':
            $vote_set = intval($_GET["vote_set"]);
            if ($vote_set > 0) $result = $gdsr->v->vote_multis($vote_value, $vote_id, $vote_set, $vote_tpl, $vote_size);
            else $result = "m_set_error";
            break;
    }
}

if (isset($_GET["callback"])) {
    $callback = $_GET["callback"];
    echo $callback.'('.$result.');';
} else echo $result;

?>