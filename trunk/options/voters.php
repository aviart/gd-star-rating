<?php

global $wpdb, $gdsr;

$options = $gdsr->o;
$posts_per_page = $options["admin_rows"];

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&gdsr=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$url.= "&gdsr=voters";

$select = "";
$post_id = -1;
$page_id = 1;
$filter_date = "";
$vote_type = "article";

if (isset($_GET["pid"])) $post_id = $_GET["pid"];
if (isset($_GET["vg"])) $select = $_GET["vg"];
if (isset($_GET["vt"])) $vote_type = $_GET["vt"];
if (isset($_GET["pg"])) $page_id = $_GET["pg"];
if (isset($_GET["date"])) $filter_date = $_GET["date"];

if ($_POST["gdsr_filter"] == __("Filter", "gd-star-rating")) {
    $filter_date = $_POST["gdsr_dates"];
}

if ($_POST["gdsr_update"] == __("Update", "gd-star-rating")) {
    $gdsr_items = $_POST["gdsr_item"];
    if (count($gdsr_items) > 0) {
    }
}

$url.= "&pid=".$post_id;
if ($filter_date != '' || $filter_date != '0') $url.= "&date=".$filter_date;
if ($select != '') $url.= "&vg=".$select;

$sql_count = GDSRDatabase::get_voters_count($post_id, $filter_date);
$np = $wpdb->get_results($sql_count);
$number_posts_users = 0;
$number_posts_visitors = 0;
if (count($np) > 0) {
    foreach ($np as $n) {
        if ($n->user == "0") $number_posts_visitors = $n->count;
        else $number_posts_users = $n->count;
    }
}
$number_posts_all = $number_posts_users + $number_posts_visitors;
if ($select == "users") $number_posts = $number_posts_users;
else if ($select == "visitors") $number_posts = $number_posts_visitors;
else $number_posts = $number_posts_all;

$max_page = floor($number_posts / $posts_per_page);
if ($max_page * $posts_per_page != $number_posts) $max_page++;

if ($max_page > 1)
    $pager = GDSRHelper::draw_pager($max_page, $page_id, $url, "pg");

?>

<script>
function checkAll(form) {
    for (i = 0, n = form.elements.length; i < n; i++) {
        if(form.elements[i].type == "checkbox" && !(form.elements[i].getAttribute('onclick', 2))) {
            if(form.elements[i].checked == true)
                form.elements[i].checked = false;
            else
                form.elements[i].checked = true;
        }
    }
}
</script>

<div class="wrap"><h2>GD Star Rating: <?php _e("Voters Log", "gd-star-rating"); ?></h2>
<ul class="subsubsub">
    <li><a<?php echo $select == "total" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>">All Votes (<?php echo $number_posts_all; ?>)</a> |</li>
    <li><a<?php echo $select == "users" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>&vg=users">Users (<?php echo $number_posts_users; ?>)</a> |</li>
    <li><a<?php echo $select == "visitors" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>&vg=visitors">Visitors (<?php echo $number_posts_visitors; ?>)</a></li>
</ul>
<?php
    if ($select != '') $url.= "&vg=".$select;
?>
<div class="tablenav">
    <div class="alignleft">
<?php GDSRDatabase::get_combo_months($filter_date); ?>
        <input class="button-secondary delete" type="submit" name="gdsr_filter" value="<?php _e("Filter", "gd-star-rating"); ?>" />
    </div>
    <div class="tablenav-pages">
        <?php echo $pager; ?>
    </div>
</div>
<br class="clear"/>
<?php

    $sql = GDSRDatabase::get_visitors($post_id, $select, ($page_id - 1) * $posts_per_page, $posts_per_page, $filter_date);
    $rows = $wpdb->get_results($sql, OBJECT);
    
?>

<table class="widefat">
    <thead>
        <tr>
            <th class="check-column" scope="col"><input type="checkbox" onclick="checkAll(document.getElementById('gdsr-articles'));"/></th>
            <th scope="col"><?php _e("User / Visitor", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("User ID", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote Date", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("IP", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("User Agent", "gd-star-rating"); ?></th>
        </tr>
    </thead>
</table>

</div>