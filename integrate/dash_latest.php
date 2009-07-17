<div id="gdsr-latest-votes">
<?php

$data = GDSRDB::filter_latest_votes($o);
$first = true;

foreach ($data as $row) {
    $user = $row->user_id == 0 ? __("visitor", "gd-star-rating") : $row->display_name;
    $voteon = $votevl = $loguser = $pocmlog = $postlog = "";

    if ($row->vote_type == "artthumb" || $row->vote_type == "cmmthumb") {
        $votevl = __("Thumb", "gd-star-rating")." <strong>".($row->vote > 0 ? "UP" : "DOWN")."</strong> ";
    } else if ($row->vote_type == "multis") {
        $voteval = intval($row->vote) / 10;
        if ($row->vote == 0) {
            $set = wp_gdget_multi_set($row->multi_id);
            $voteval = GDSRDBMulti::get_multi_rating_from_single_object($set, unserialize($row->object));
        }
        $votevl = __("Multi Vote", "gd-star-rating")." <strong>".$voteval."</strong> ";
    } else {
        $votevl = __("Vote", "gd-star-rating")." <strong>".$row->vote."</strong> ";
    }

    if ($row->vote_type == "article" || $row->vote_type == "artthumb" || $row->vote_type == "multis") {
        $post = get_post($row->id);
        $voteon = '<a href="'.get_permalink($post->ID).'"><span style="color: #2683AE">'.$post->post_title.'</span></a>';
        $pocmlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $row->id, $row->vote_type);
        $pocmlog = sprintf('<a href="%s">%s</a>', $pocmlog, __("post", "gd-star-rating"));
    } else {
        $comment = get_comment($row->id);
        $post = get_post($comment->comment_post_ID);
        $voteon = ' <a href="'.get_comment_link($comment).'">'.__("comment", "gd-star-rating").'</a> '.__("for", "gd-star-rating").' <a href="'.get_permalink($post->ID).'"><span style="color: #2683AE">'.$post->post_title.'</span></a>';
        $pocmlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $row->id, $row->vote_type);
        $pocmlog = sprintf('<a href="%s">%s</a>', $pocmlog, __("comment", "gd-star-rating"));
        $pctype = $row->vote_type == "comment" ? "article" : "artthumb";
        $postlog = sprintf("admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=%s&vg=total", $comment->comment_post_ID, $pctype);
        $postlog = sprintf('<a href="%s">%s</a>', $postlog, __("post", "gd-star-rating"));
    }

    if ($row->user_id == 0) {
        $loguser = sprintf("admin.php?page=gd-star-rating-users&gdsr=userslog&ui=0&vt=%s&un=Visitor", $row->vote_type);
        $loguser = sprintf('<a href="%s">%s</a>', $loguser, __("visitors", "gd-star-rating"));
    } else {
        $loguser = sprintf("admin.php?page=gd-star-rating-users&gdsr=userslog&ui=%s&vt=%s&un=%s", $row->user_id, $row->vote_type, $row->display_name);
        $loguser = sprintf('<a href="%s">%s</a>', $loguser, __("user", "gd-star-rating"));
    }

?>

<div class="gdsr-latest-item<?php echo $first ? " first" : ""; ?><?php echo $row->user_id > 0 ? " user" : ""; ?>">
    <?php echo get_avatar($row->user_email, 32); ?>
    <h5><?php echo '<span style="color: #CC0000">'.$votevl.'</span>'; _e("from", "gd-star-rating"); ?> <strong style="color: <?php echo $row->user_id == 0 ? "blue" : "green"; ?>"><?php echo $user; ?></strong> <?php _e("on", "gd-star-rating"); ?> <?php echo $voteon; ?></h5>
    <p class="datx"><?php echo $row->voted; ?></p>
    <p class="linx"><strong><?php _e("ip", "gd-star-rating"); ?>:</strong> <span style="color: blue"><?php echo $row->ip; ?></span> | <strong><?php _e("log", "gd-star-rating"); ?>:</strong> <?php echo $loguser; ?>, <?php echo $pocmlog; ?><?php if ($postlog != "") echo ", "; echo $postlog; ?></p>
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