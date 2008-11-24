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
        $ids = "(".join(", ", $gdsr_items).")";
        $delact = $_POST["gdsr_delete_voters"];
        if ($delact == "L") GDSRDatabase::delete_voters_log($ids);
        if ($delact = "D") GDSRDatabase::delete_voters_full($ids, $vote_type);
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
        if ($n->user == "1") $number_posts_visitors = $n->count;
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
<form id="gdsr-comments" method="post" action="">
<p><strong><?php _e("Vote log for post", "gd-star-rating"); ?>: 
    <?php echo sprintf('<a href="./post.php?action=edit&post=%s">%s</a> <a href="%s" target="_blank">[view]</a>', $post_id, GDSRDB::get_post_title($post_id), get_permalink($post_id)); ?>
</strong></p>
<ul class="subsubsub">
    <li><a<?php echo $select == "total" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>&vg=total">All Votes (<?php echo $number_posts_all; ?>)</a> |</li>
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

    $sql = GDSRDatabase::get_visitors($post_id, $vote_type, $filter_date, $select, ($page_id - 1) * $posts_per_page, $posts_per_page);
    $rows = $wpdb->get_results($sql, OBJECT);
    
?>

<table class="widefat">
    <thead>
        <tr>
            <th class="check-column" scope="col"><input type="checkbox" onclick="checkAll(document.getElementById('gdsr-articles'));"/></th>
            <th scope="col" nowrap="nowrap"><?php _e("ID", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Name", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Vote Date", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("IP", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("User Agent", "gd-star-rating"); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
       
    $tr_class = "";
    foreach ($rows as $row) {
        if ($row->user_id == 0) $tr_class.= " visitor";
        if ($row->user_id == 1) $tr_class.= " admin";

        echo '<tr id="post-'.$row->record_id.'" class="'.$tr_class.' author-self status-publish" valign="top">';
        echo '<th scope="row" class="check-column"><input name="gdsr_item[]" value="'.$row->record_id.'" type="checkbox"></th>';
        echo '<td><strong>'.$row->user_id.'</strong></td>';
        echo '<td><strong>';
        echo $row->user_id == 0 ? "Visitor" : $row->user_nicename;
        echo '</strong></td>';
        echo '<td>'.$row->vote.'</td>';
        echo '<td>'.$row->voted.'</td>';
        echo '<td>'.$row->ip.'</td>';
        echo '<td>'.$row->user_agent.'</td>';

        echo '</tr>';
        if ($tr_class == "")
            $tr_class = "alternate ";
        else
            $tr_class = "";
    }
    
?>
    </tbody>
</table>
<div class="tablenav" style="height: 3em">
    <div class="alignleft">
        <div class="panel">
            <table width="100%"><tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("With selected", "gd-star-rating"); ?>:</strong></span>
                </td>
                <td style="height: 29px;">
                    <span class="paneltext"><?php _e("Delete", "gd-star-rating"); ?>:</span>
                    <select id="gdsr_delete_voters" name="gdsr_delete_voters" style="margin-top: -4px; width: 150px;">
                        <option value="">/</option>
                        <option value="D"><?php _e("Full Delete", "gd-star-rating"); ?></option>
                        <option value="L"><?php _e("From Log Only", "gd-star-rating"); ?></option>
                    </select>
                </td>
                <td align="right" style="width: 80px; height: 29px;">
                    <input class="button-secondary delete" type="submit" name="gdsr_update" value="<?php _e("Update", "gd-star-rating"); ?>" style="margin-top: -4px;" />
                </td>
            </tr></table>
        </div>
    </div>
<br class="clear"/>
</div>
</form>
</div>