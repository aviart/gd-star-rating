<?php

$posts_per_page = $options["admin_rows"];

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&gdsr=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$url.= "&gdsr=userslog";

$page_id = 1;
$user_id = 0;
$vote_type = "article";
$user_name = "Visitor";
if (isset($_GET["pg"])) $page_id = $_GET["pg"];
if (isset($_GET["ui"])) $user_id = $_GET["ui"];
if (isset($_GET["vt"])) $vote_type = $_GET["vt"];
if (isset($_GET["un"])) $user_name = urldecode($_GET["un"]);

$number_posts = GDSRDatabase::get_count_user_log($user_id, $vote_type);

$max_page = floor($number_posts / $posts_per_page);
if ($max_page * $posts_per_page != $number_posts) $max_page++;

if ($max_page > 1)
    $pager = GDSRHelper::draw_pager($max_page, $page_id, $url, "pg");

?>

<div class="wrap" style="max-width: <?php echo $options["admin_width"]; ?>px">
<form id="gdsr-articles" method="post" action="">
<h2>GD Star Rating: <?php _e("User Vote Log", "gd-star-rating"); ?></h2>
<p><strong><?php _e("Votes log for user", "gd-star-rating"); ?>: 
    <?php echo sprintf('<a href="./user-edit.php?user_id=%s">%s</a>', $user_id, $user_name); ?>
</strong></p>
<div class="tablenav">
    <div class="alignleft">
    </div>
    <div class="tablenav-pages">
        <?php echo $pager; ?>
    </div>
</div>
<br class="clear"/>
<table class="widefat">
    <thead>
        <tr>
            <th class="check-column" scope="col"><input type="checkbox" onclick="checkAll(document.getElementById('gdsr-articles'));"/></th>
            <th scope="col"><?php _e("IP", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote Date", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote", "gd-star-rating"); ?></th>
        </tr>
    </thead>
    <tbody>

<?php

    $log = GDSRDatabase::get_user_log($user_id, $vote_type, ($page_id - 1) * $posts_per_page, $posts_per_page);

?>

    </tbody>
</table>
<div class="tablenav">
    <div class="alignleft">
    </div>
    <div class="tablenav-pages">
    </div>
</div>
<br class="clear"/>
</form>
</div>
