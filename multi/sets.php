<?php

global $wpdb;

$posts_per_page = $options["admin_rows"];

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&gdsr=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$url_edit = $url."&gdsr=";
$url.= "&gdsr=mulist";

$page_id = 1;

if (isset($_GET["pg"])) $page_id = $_GET["pg"];

$number_posts = GDSRDBMulti::get_multis_count();

if ($max_page * $posts_per_page != $number_posts) $max_page++;

if ($max_page > 1)
    $pager = GDSRHelper::draw_pager($max_page, $page_id, $url, "pg");

?>

<script>
function gdsrAddNewMulti() {
    window.location = "<?php echo $url_edit."munew"; ?>";
}
</script>

<div class="wrap" style="max-width: <?php echo $options["admin_width"]; ?>px">
<form id="gdsr-articles" method="post" action="">
<div class="gdsr">
<h2>GD Star Rating: <?php _e("Multi Sets", "gd-star-rating"); ?></h2>

<div class="tablenav">
    <div class="alignleft">
        <input onclick="gdsrAddNewMulti()" class="button-secondary delete" type="button" name="gdsr_filter" value="<?php _e("Add New Multi Rating Set", "gd-star-rating"); ?>" />
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
            <th scope="col" width="33"><?php _e("ID", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Name", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Description", "gd-star-rating"); ?></th>
            <th scope="col" width="55"><?php _e("Stars", "gd-star-rating"); ?></th>
            <th scope="col" width="333"><?php _e("Ratings", "gd-star-rating"); ?></th>
            <th scope="col" width="333"><?php _e("Statistics", "gd-star-rating"); ?></th>
        </tr>
    </thead>
    <tbody>

<?php

    $rows = GDSRDBMulti::get_multis(($page_id - 1) * $posts_per_page, $posts_per_page);

    $tr_class = "";
    foreach ($rows as $row) {
        echo '<tr id="multi-'.$row->multi_id.'" class="'.$tr_class.' author-self status-publish" valign="top">';
        echo '<th scope="row" class="check-column"><input name="gdsr_item[]" value="'.$row->multi_id.'" type="checkbox"></th>';
        echo '<td>'.$row->multi_id.'</td>';
        echo '<td><a href="'.$url_edit.'muedit&id='.$row->multi_id.'"><strong>'.$row->name.'</strong></a></td>';
        echo '<td>'.$row->description.'</td>';
        echo '<td><strong>'.$row->stars.'</strong></td>';
        echo '<td>';
            $elements = unserialize($row->object);
            $weights = unserialize($row->weight);
            $half = floor(count($elements) / 2);
            if ($half * 2 < count($elements)) $half++;
            echo '<table style="width: 100%;"><tr><td style="border: 0; padding: 0;">';
            for ($i = 0; $i < $half; $i++)
                echo sprintf("[%s] %s (%s)<br />", $i+1, $elements[$i], $weights[$i]);
            echo '</td><td style="border: 0; padding: 0;">';
            for ($i = $half; $i < count($elements); $i++)
                echo sprintf("[%s] %s (%s)<br />", $i+1, $elements[$i], $weights[$i]);
            echo '</td></tr></table>';
        echo '</td>';
        echo '<td>';
            echo sprintf("[ <strong>%s</strong> ] %s<br />", GDSRDBMulti::get_usage_count_posts($row->multi_id), __("Posts", "gd-star-rating"));
            echo sprintf("[ <strong>%s</strong> ] %s", GDSRDBMulti::get_usage_count_voters($row->multi_id), __("Voters", "gd-star-rating"));
        echo '</td>';
        echo '</tr>';
        
        if ($tr_class == "")
            $tr_class = "alternate ";
        else
            $tr_class = "";
    }

?>

    </tbody>
</table>
<br class="clear"/>

<div class="tablenav">
    <div class="alignleft">
        <input class="button-secondary delete" type="submit" name="gdsr_filter" value="<?php _e("Delete Selected Sets", "gd-star-rating"); ?>" />
    </div>
    <div class="tablenav-pages">
        <?php echo $pager; ?>
    </div>
</div>
<br class="clear"/>
</div>
</form>
</div>
