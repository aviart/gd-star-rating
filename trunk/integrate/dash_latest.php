<div id="gdsr-latest-votes">
<?php

$data = GDSRDB::filter_latest_votes($o);
$first = true;

foreach ($data as $row) {
    $user = $row->user_id == 0 ? $row->ip : '<a href="">'.$row->display_name.'</a>';
    $voteon = "";
    $votevl = "";
    if ($row->vote_type == "artthumb" || $row->vote_type == "cmmthumb") {
        $votevl = __("Thumb")." <strong>".($row->vote > 0 ? "UP" : "DOWN")."</strong> ";
    } else if ($row->vote_type == "multis") {

    } else {
        $votevl = __("Vote")." <strong>".$row->vote."</strong> ";
    }

    if ($row->vote_type == "article" || $row->vote_type == "artthumb" || $row->vote_type == "multis") {
        $post = get_post($row->id);
        $voteon = '<span style="color: #2683AE">'.$post->post_title.'</span>';
    } else {
        $comment = get_comment($row->id);
        $post = get_post($comment->comment_post_ID);
        $voteon = ' '.__("comment for").' <span style="color: #2683AE">'.$post->post_title.'</span>';
    }
?>

<div class="gdsr-latest-item<?php echo $first ? " first" : ""; ?>">
    <?php echo get_avatar($row->user_email, 32); ?>
    <h5><?php echo '<span style="color: #CC0000">'.$votevl.'</span>'; _e("from"); ?> <strong><?php echo $user; ?></strong> <?php _e("on"); ?> <?php echo $voteon; ?></h5>
    <div class="clear"></div>
</div>

<?php

    $first = false;
}

?>
</div>
<div id="gdsr-latest-cmds">
</div>