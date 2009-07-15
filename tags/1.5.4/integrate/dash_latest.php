<div id="gdsr-latest-votes">
<?php

$data = GDSRDB::filter_latest_votes($o);
$first = true;

foreach ($data as $row) {
    $user = $row->user_id == 0 ? $row->ip : $row->display_name;
    $voteon = $votevl = $loguser = $pocmlog = $postlog = "";

    if ($row->vote_type == "artthumb" || $row->vote_type == "cmmthumb") {
        $votevl = __("Thumb", "gd-star-rating")." <strong>".($row->vote > 0 ? "UP" : "DOWN")."</strong> ";
    } else if ($row->vote_type == "multis") {
        $set = gd_get_multi_set($row->multi_id);
        $weighted = $i = 0;
        $weight_norm = array_sum($set->weight);
        $multi_data = unserialize($row->object);
        foreach ($multi_data as $md) {
            $weighted += ( intval($md) * $set->weight[$i] ) / $weight_norm;
            $i++;
        }
        $votevl = __("Multi Vote", "gd-star-rating")." <strong>".number_format($weighted, 1)."</strong> ";
    } else {
        $votevl = __("Vote", "gd-star-rating")." <strong>".$row->vote."</strong> ";
    }

    if ($row->vote_type == "article" || $row->vote_type == "artthumb" || $row->vote_type == "multis") {
        $post = get_post($row->id);
        $voteon = '<span style="color: #2683AE">'.$post->post_title.'</span>';
        $pocmlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $row->id, $row->vote_type);
        $pocmlog = sprintf('<a href="%s">%s</a>', $pocmlog, __("post log", "gd-star-rating"));
    } else {
        $comment = get_comment($row->id);
        $post = get_post($comment->comment_post_ID);
        $voteon = ' '.__("comment for", "gd-star-rating").' <span style="color: #2683AE">'.$post->post_title.'</span>';
        $pocmlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $row->id, $row->vote_type);
        $pocmlog = sprintf('<a href="%s">%s</a>', $pocmlog, __("comment log", "gd-star-rating"));
        $pctype = $row->vote_type == "comment" ? "article" : "artthumb";
        $postlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $comment->comment_post_ID, $pctype);
        $postlog = sprintf('<a href="%s">%s</a>', $postlog, __("post log", "gd-star-rating"));
    }

    if ($row->user_id == 0) {
        $loguser = sprintf("admin.php?page=gd-star-rating-users&gdsr=userslog&ui=0&vt=%s&un=Visitor", $row->vote_type);
        $loguser = sprintf('<a href="%s">%s</a>', $loguser, __("visitors log", "gd-star-rating"));
    } else {
        $loguser = sprintf("admin.php?page=gd-star-rating-users&gdsr=userslog&ui=%s&vt=%s&un=%s", $row->user_id, $row->vote_type, $row->display_name);
        $loguser = sprintf('<a href="%s">%s</a>', $loguser, __("user log", "gd-star-rating"));
    }

?>

<div class="gdsr-latest-item<?php echo $first ? " first" : ""; ?><?php echo $row->user_id > 0 ? " user" : ""; ?>">
    <?php echo get_avatar($row->user_email, 32); ?>
    <h5><?php echo '<span style="color: #CC0000">'.$votevl.'</span>'; _e("from", "gd-star-rating"); ?> <strong><?php echo $user; ?></strong> <?php _e("on", "gd-star-rating"); ?> <?php echo $voteon; ?></h5>
    <p class="datx"><?php echo $row->voted; ?></p>
    <p class="linx"><?php echo $loguser; ?> | <?php echo $pocmlog; ?> <?php if ($postlog != "") echo " | "; echo $postlog; ?></p>
    <div class="clear"></div>
</div>

<?php

    $first = false;
}

?>
</div>
<div id="gdsr-latest-cmds">
    <a class="button" href="admin.php?page=gd-star-rating-stats"><?php _e("Articles Log", "gd-star-rating"); ?></a>
    <a class="button" href="admin.php?page=gd-star-rating-users"><?php _e("Users Log", "gd-star-rating"); ?></a>
</div>