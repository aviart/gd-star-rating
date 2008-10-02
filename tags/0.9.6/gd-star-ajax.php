<?php

    require_once("./gd-star-config.php");
    $wpconfig = get_wpconfig();
    require($wpconfig);

    global $gdsr;
    
    $vote_id = $_GET["vote_id"];
    $vote_value = $_GET["vote_value"];
    $vote_user = $_GET["vote_user"];
    $vote_type = $_GET["vote_type"];
    
    switch ($vote_type) {
        case 'a':
            $result = $gdsr->vote_article_ajax($vote_value, $vote_id, $vote_user);
            break;
        case 'c':
            $result = $gdsr->vote_comment_ajax($vote_value, $vote_id, $vote_user);
            break;
    }
    echo $result;
    
?>