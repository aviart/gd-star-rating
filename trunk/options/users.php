<?php

$posts_per_page = $options["admin_rows"];

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&gdsr=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$url.= "&gdsr=users";

$page_id = 1;
if (isset($_GET["pg"])) $page_id = $_GET["pg"];

$number_posts = GDSRDatabase::get_valid_users_count();

$max_page = floor($number_posts / $posts_per_page);
if ($max_page * $posts_per_page != $number_posts) $max_page++;

if ($max_page > 1)
    $pager = GDSRHelper::draw_pager($max_page, $page_id, $url, "pg");

$users = array();
$pre_users = GDSRDatabase::get_valid_users();
$count = -1;
$usrid = -1;
foreach ($pre_users as $user) {
    if ($user->user_id != $usrid) $count++;
    $users[$count]["id"] = $user->user_id;
    $users[$count][$user->vote_type]["votes"] = $user->votes;
    $users[$count][$user->vote_type]["voters"] = $user->voters;
    $users[$count]["name"] = $user->user_id == 0 ? "visitor" : $user->display_name;
    $users[$count]["email"] = $user->user_id == 0 ? "/" : $user->user_email;
    $users[$count]["url"] = $user->user_id == 0 ? "/" : $user->user_url;
    $usrid = $user->user_id;
}

$usr_from = ($page_id - 1) * $posts_per_page;
$usr_to = $page_id * $posts_per_page;
if ($usr_to > $number_posts) $usr_to = $number_posts;

?>

<div class="wrap" style="max-width: <?php echo $options["admin_width"]; ?>px">
<form id="gdsr-articles" method="post" action="">
<h2>GD Star Rating: <?php _e("Users", "gd-star-rating"); ?></h2>
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
            <th scope="col"><?php _e("Name", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("ID", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Email", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("URL", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Articles", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Comments", "gd-star-rating"); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
       
    $tr_class = "";
    for ($i = $usr_from; $i < $usr_to; $i++) {
        $row = $users[$i];
        $r_pst = 0;
        if ($row["article"]["voters"] > 0) $r_pst = @number_format($row["article"]["votes"] / $row["article"]["voters"], 1);
        $r_cmm = 0;
        if ($row["comment"]["voters"] > 0) $r_cmm = @number_format($row["comment"]["votes"] / $row["comment"]["voters"], 1);
        echo '<tr id="post-'.$row["id"].'" class="'.$tr_class.' author-self status-publish" valign="top">';
        echo '<th scope="row" class="check-column"><input name="gdsr_item[]" value="'.$row["id"].'" type="checkbox"></th>';
        echo '<td><strong>'.$row["name"].'</strong></td>';
        echo '<td>'.$row["id"].'</td>';
        echo '<td>'.$row["email"].'</td>';
        echo '<td>'.$row["url"].'</td>';
        echo '<td>'.__('votes').': <strong>'.$row["article"]["voters"].'</strong><br />'.__('rating').': <strong style="color: red">'.$r_pst.'</strong></td>';
        echo '<td>'.__('votes').': <strong>'.$row["comment"]["voters"].'</strong><br />'.__('rating').': <strong style="color: red">'.$r_cmm.'</strong></td>';
        echo '</tr>';
        
        if ($tr_class == "")
            $tr_class = "alternate ";
        else
            $tr_class = "";
    }

?>
    </tbody>
</table>
</form>
</div>
