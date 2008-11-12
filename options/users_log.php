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

$url.= "&ui=".$user_id."&vt=".$vote_type."&un=".$user_name;

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
            <th scope="col"><?php _e("Post", "gd-star-rating"); ?></th>
            <?php if ($vote_type == "comment") { ?>
            <th scope="col"><?php _e("Comment Author", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Comment Excerpt", "gd-star-rating"); ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>

<?php

    $log = GDSRDatabase::get_user_log($user_id, $vote_type, ($page_id - 1) * $posts_per_page, $posts_per_page);
    $ips = array();
    $idx = array();
    foreach ($log as $l) {
        if (!in_array($l->ip, $ips)) {
            $ips[] = $l->ip;
            $idx[] = 1;
        }
        else $idx[count($idx) - 1]++;
    }
    
    $counter = 0;
    $tr_class = "";
    for ($i = 0; $i < count($idx); $i++) {
        for ($j = 0; $j < $idx[$i]; $j++) {
            echo '<tr id="post-'.$log[$counter]->record_id.'" class="'.$tr_class.' author-self status-publish" valign="top">';
            if ($j == 0) {
                echo '<th rowspan='.$idx[$i].' scope="row" class="check-column"><input name="gdsr_item[]" value="'.$log[$counter]->record_id.'" type="checkbox"></th>';
                echo '<td rowspan='.$idx[$i].'><strong>'.$log[$counter]->ip.'</strong></td>';
            }
            echo '<td nowrap="nowrap">'.$log[$counter]->voted.'</td>';
            echo '<td><strong>'.$log[$counter]->vote.'</strong></td>';
            echo '<td nowrap="nowrap">';
            if ($log[$counter]->id != $log[$counter]->control_id)
                echo '<strong style="color: red">INVALID VOTE</strong>';
            else
                echo '<strong>['.$log[$counter]->post_id.']</strong> '.$log[$counter]->post_title;
            echo '</td>';
            if ($vote_type == "comment") {
                echo '<td>'.$log[$counter]->author.'</td>';
                echo '<td>'.$log[$counter]->comment_content.'</td>';
            }
            echo '</tr>';
            $counter++;
        }
        if ($tr_class == "")
            $tr_class = "alternate ";
        else
            $tr_class = "";
    }

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
