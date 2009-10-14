<?php

global $wpdb;

$posts_per_page = $options["admin_rows"];

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&gdsr=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$url.= "&amp;gdsr=articles";

$select = $search = "";
$page_id = 1;
$filter_date = "";
$filter_cats = "";
if (isset($_GET["select"])) $select = $_GET["select"];
if (isset($_GET["pg"])) $page_id = $_GET["pg"];
if (isset($_GET["date"])) $filter_date = $_GET["date"];
if (isset($_GET["cat"])) $filter_cats = $_GET["cat"];
if (isset($_GET["s"])) $search = $_GET["s"];

if (isset($_POST["gdsr_filter"]) && $_POST["gdsr_filter"] == __("Filter", "gd-star-rating")) {
    $filter_date = $_POST["gdsr_dates"];
    $filter_cats = $_POST["gdsr_categories"];
    $page_id = 1;
}

if (isset($_POST["gdsr_search"]) && $_POST["gdsr_search"] == __("Search Posts", "gd-star-rating")) {
    $search = apply_filters('get_search_query', stripslashes($_POST["s"]));
    $page_id = 1;
}

if (isset($_POST["gdsr_update"]) && $_POST["gdsr_update"] == __("Update", "gd-star-rating")) {
    $gdsr_items = $_POST["gdsr_item"];
    if (count($gdsr_items) > 0) {
        $ids = "(".join(", ", $gdsr_items).")";
        if ($_POST["gdsr_delete_articles"] != '') 
            GDSRDatabase::delete_votes($ids, $_POST["gdsr_delete_articles"], $gdsr_items);
        if ($_POST["gdsr_delete_comments"] != '') 
            GDSRDatabase::delete_votes($ids, $_POST["gdsr_delete_comments"], $gdsr_items);
        if ($_POST["gdsr_review_rating"] != "") {
            $review = $_POST["gdsr_review_rating"];
            if ($_POST["gdsr_review_rating_decimal"] != "" && $_POST["gdsr_review_rating"] < $options["review_stars"])
                $review.= ".".$_POST["gdsr_review_rating_decimal"];
            GDSRDatabase::update_reviews($ids, $review, $gdsr_items);
        }
        GDSRDatabase::update_settings($ids, $_POST["gdsr_article_moderation"], $_POST["gdsr_article_voterules"], $_POST["gdsr_comments_moderation"], $_POST["gdsr_comments_voterules"], $gdsr_items);
        if ($_POST["gdsr_timer_type"] != "") {
            GDSRDatabase::update_restrictions($ids, $_POST["gdsr_timer_type"], GDSRHelper::timer_value($_POST["gdsr_timer_type"], $_POST['gdsr_timer_date_value'], $_POST['gdsr_timer_countdown_value'], $_POST['gdsr_timer_countdown_type']));
        }
    }
}

if ($filter_cats != '' || $filter_cats != '0') $url.= "&amp;cat=".$filter_cats;
if ($filter_date != '' || $filter_date != '0') $url.= "&amp;date=".$filter_date;
if ($search != '') $url.= "&amp;s=".$search;
if ($select != '') $url.= "&amp;select=".$select;

$sql_count = GDSRDatabase::get_stats_count($filter_date, $filter_cats, $search);
$np = $wpdb->get_results($sql_count);
$number_posts_page = 0;
$number_posts_post = 0;
if (count($np) > 0) {
    foreach ($np as $n) {
        if ($n->post_type == "page") $number_posts_page = $n->count;
        else $number_posts_post = $n->count;
    }
}
$number_posts_all = $number_posts_post + $number_posts_page;
if ($select == "post") $number_posts = $number_posts_post;
else if ($select == "page") $number_posts = $number_posts_page;
else $number_posts = $number_posts_all;

$max_page = floor($number_posts / $posts_per_page);
if ($max_page * $posts_per_page != $number_posts) $max_page++;

$pager = $max_page > 1 ? gdFunctionsGDSR::draw_pager($max_page, $page_id, $url, "pg") : "";

?>

<div class="wrap">
<form id="gdsr-articles" method="post" action="">
<h2 class="gdptlogopage">GD Star Rating: <?php _e("Articles", "gd-star-rating"); ?></h2>
<ul class="subsubsub">
    <li><a<?php echo $select == "" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>">All Articles (<?php echo $number_posts_all; ?>)</a> |</li>
    <li><a<?php echo $select == "post" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>&amp;select=post">Posts (<?php echo $number_posts_post; ?>)</a> |</li>
    <li><a<?php echo $select == "page" ? ' class="current"' : ''; ?> href="<?php echo $url; ?>&amp;select=page">Pages (<?php echo $number_posts_page; ?>)</a></li>
</ul>
<?php
    if ($select != '') $url.= "&select=".$select;
?>
<p id="post-search">
    <label class="hidden" for="post-search-input"><?php _e("Search Posts", "gd-star-rating"); ?>:</label>
    <input class="search-input" id="post-search-input" type="text" value="<?php echo $search; ?>" name="s"/>
    <input class="button" type="submit" value="<?php _e("Search Posts", "gd-star-rating"); ?>" name="gdsr_search" />
</p>
<div class="tablenav">
    <div class="alignleft">
<?php GDSRDatabase::get_combo_months($filter_date); ?>
<?php GDSRDatabase::get_combo_categories($filter_cats); ?>
        <input class="button-secondary delete" type="submit" name="gdsr_filter" value="<?php _e("Filter", "gd-star-rating"); ?>" />
    </div>
    <div class="tablenav-pages">
        <?php echo $pager; ?>
    </div>
</div>
<br class="clear"/>
<?php

    $sql = GDSRDatabase::get_stats($select, ($page_id - 1) * $posts_per_page, $posts_per_page, $filter_date, $filter_cats, $search);
    $rows = $wpdb->get_results($sql, OBJECT);
    
?>
<table class="widefat">
    <thead>
        <tr>
            <th class="check-column" scope="col"><input type="checkbox" onclick="checkAll(document.getElementById('gdsr-articles'));"/></th>
            <th scope="col"><?php _e("ID", "gd-star-rating"); ?></th>
            <th scope="col" style="width: 48px; padding: 1px;"> </th>
            <th scope="col"><?php _e("Title &amp; Categories", "gd-star-rating"); ?></th>
            <th scope="col" style="padding-left: 34px;"><?php _e("Vote Rules", "gd-star-rating"); ?></th>
            <?php if ($options["timer_active"] == 1) { ?>
                <th scope="col"><?php _e("Time", "gd-star-rating"); ?></th>
            <?php } ?>
            <?php if ($options["moderation_active"] == 1) { ?>
                <th scope="col"><?php _e("Moderation", "gd-star-rating"); ?></th>
            <?php } ?>
            <th scope="col"><?php _e("Ratings", "gd-star-rating"); ?></th>
            <th scope="col"><?php _e("Total", "gd-star-rating"); ?></th>
            <?php if ($options["comments_integration_articles_active"] == 1) { ?>
                <th scope="col"><?php _e("Comment Integration", "gd-star-rating"); ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>

<?php

    $tr_class = "";
    $multi_sets = GDSRDBMulti::get_multis_tinymce();
    $multis = array();
    foreach ($multi_sets as $ms) {
        $multis[$ms->folder] = $ms->name;
    }
    foreach ($rows as $row) {
        $row = GDSRDB::convert_row($row, $multis);
        $moderate_articles = $moderate_comments = "";
        if ($options["moderation_active"] == 1) {
            $moderate_articles = GDSRDatabase::get_moderation_count($row->pid);
            $moderate_comments = GDSRDatabase::get_moderation_count_joined($row->pid);

            if ($moderate_articles == 0) $moderate_articles = "[ 0 ] ";
            else $moderate_articles = sprintf('[<a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=moderation&amp;pid=%s&amp;vt=article"> <strong style="color: red;">%s</strong> </a>] ', $row->pid, $moderate_articles);

            if ($moderate_comments == 0) $moderate_comments = "[ 0 ] ";
            else $moderate_comments = sprintf('[<a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=moderation&amp;pid=%s&amp;vt=post"> <strong style="color: red;">%s</strong> </a>] ', $row->pid, $moderate_comments);
        }

        $timer_info = "";
        if ($options["timer_active"] == 1) {
            if ($row->expiry_type == "D") {
                $timer_info = '<strong><span style="color: red">'.__("date limit", "gd-star-rating").'</span></strong><br />';
                $timer_info.= $row->expiry_value;
            } else if ($row->expiry_type == "T") {
                $timer_info = '<strong><span style="color: red">'.__("countdown", "gd-star-rating").'</span></strong><br />';
                $timer_info.= substr($row->expiry_value, 1)." ";
                switch (substr($row->expiry_value, 0, 1)) {
                    case "H":
                        $timer_info.= __("Hours", "gd-star-rating");
                        break;
                    case "D":
                        $timer_info.= __("Days", "gd-star-rating");
                        break;
                    case "M":
                        $timer_info.= __("Months", "gd-star-rating");
                        break;
                }
            } else $timer_info = __("no limit", "gd-star-rating").'<br /><br />';
        }

        if ($row->rating_total > $options["stars"] ||
            $row->rating_visitors > $options["stars"] ||
            $row->rating_users > $options["stars"]) $tr_class.=" invalidarticle";

        echo '<tr id="post-'.$row->pid.'" class="'.$tr_class.' author-self status-publish" valign="top">';
        echo '<th scope="row" class="check-column"><input name="gdsr_item[]" value="'.$row->pid.'" type="checkbox"/></th>';
        echo '<td nowrap="nowrap">'.$row->pid.'</td>';
        echo '<td class="gdrinner">';
            echo '<a title="'.__("Comments").'" href="./admin.php?page=gd-star-rating-stats&amp;gdsr=comments&amp;postid='.$row->pid.'"><img alt="'.__("Comments").'" src="'.STARRATING_URL.'gfx/comments.png" border="0" /></a>';
            echo '<a title="'.__("Chart").'" onclick="generateUrl('.$row->pid.')" href="#TB_inline?height=520&amp;width=950&amp;inlineId=gdsrchart" class="thickbox"><img alt="'.__("Chart").'" src="'.STARRATING_URL.'gfx/chart.png" border="0" /></a><br />';
            echo '<a title="'.__("Edit").'" href="'.get_edit_post_link($row->pid).'" target="_blank"><img alt="'.__("Edit").'" src="'.STARRATING_URL.'gfx/edit.png" border="0" /></a>';
            echo '<a title="'.__("View").'" href="'.get_permalink($row->pid).'" target="_blank"><img alt="'.__("View").'" src="'.STARRATING_URL.'gfx/view.png" border="0" /></a>';
        echo '</td>';
            echo '<td><div class="gdsr-td-title">'.$row->title.'</div><div class="gdsr-td-condensed">';
            if ($row->post_type == "post") echo '<span style="color: #c00">'.__("Post").'</span>: '.GDSRDatabase::get_categories($row->pid);
            else echo '<span style="color: #c00">'.__("Page").'</span>';
            echo ' | <span style="color: #c00">'.__("Views", "gd-star-rating").'</span>: '.$row->views.'</div>';
        echo '</td>';
        echo '<td nowrap="nowrap" class="gdsr-td-condensed">';
            echo '<div class="gdsr-art-stars">';
            echo $row->rules_articles.'<br />'.$row->rules_comments;
            echo '</div>';
        echo '<div class="gdsr-art-split"></div>';
            echo '<div class="gdsr-art-thumbs">';
            echo $row->rules_articles.'<br />'.$row->rules_comments;
            echo '</div>';
        echo '</td>';
        if ($options["timer_active"] == 1) {
            echo '<td nowrap="nowrap" class="gdsr-td-condensed">';
            echo $timer_info;
            echo '<div class="gdsr-art-split"></div>';
            echo $timer_info;
            echo '</td>';
        }
        if ($options["moderation_active"] == 1) {
            echo '<td nowrap="nowrap" class="gdsr-td-condensed">';
            echo $moderate_articles.$row->moderate_articles.'<br />'.$moderate_comments.$row->moderate_comments;
            echo '<div class="gdsr-art-split"></div>';
            echo $moderate_articles.$row->moderate_articles.'<br />'.$moderate_comments.$row->moderate_comments;
            echo '</td>';
        }
        echo '<td nowrap="nowrap" class="gdsr-td-condensed">';
            echo $row->thumbs;
            echo '<div class="gdsr-art-split"></div>';
            echo $row->votes;
        echo '</td>';
        echo '<td nowrap="nowrap" class="gdsr-td-condensed">'.$row->total.'</td>';
        if ($options["comments_integration_articles_active"] == 1) {
            echo '<td nowrap="nowrap" class="gdsr-td-condensed">';
                echo '<div class="gdsr-art-stars">';
                echo $row->cmm_integration_std;
                echo '</div>';
            echo '<div class="gdsr-art-split"></div>';
                echo '<div class="gdsr-art-multis">';
                echo $row->cmm_integration_mur;
                echo '</div>';
            echo '</td>';
        }
        echo '</tr>';

        if ($tr_class == "") $tr_class = "alternate ";
        else $tr_class = "";
    }

?>

    </tbody>
</table>
<div class="tablenav" style="height: 8em">
    <div class="alignleft">
        <div class="panel">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("Articles", "gd-star-rating"); ?>:</strong><br/>(<?php _e("Stars", "gd-star-rating"); ?>)</span>
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Delete", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_delete_articles" name="gdsr_delete_articles" style="width: 120px;">
                        <option value="">/</option>
                        <option value="AV"><?php _e("Visitors", "gd-star-rating"); ?></option>
                        <option value="AU"><?php _e("Users", "gd-star-rating"); ?></option>
                        <option value="AA"><?php _e("All", "gd-star-rating"); ?></option>
                    </select>
                </td><td style="width: 10px"></td>
                <?php if ($options["moderation_active"] == 1) { ?>
                    <td style="width: 80px; height: 29px;">
                        <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                    </td>
                    <td style="width: 140px; height: 29px;" align="right">
                    <?php GDSRHelper::render_moderation_combo("gdsr_article_moderation", "/", 120, "", true); ?>
                    </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_article_voterules", "/", 120, "", true); ?>
                </td>
            </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;"></td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Restriction", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_timer_combo("gdsr_timer_type", 'N', 120, '', true, 'gdsrTimerChange(\'\')'); ?>
                </td><td style="width: 10px"></td>
                <td style="width: 80px; height: 29px;">
                    <div id="gdsr_timer_countdown_text" style="display: none"><span class="paneltext"><?php _e("Countdown", "gd-star-rating"); ?>:</span></div>
                    <div id="gdsr_timer_date_text" style="display: none"><span class="paneltext"><?php _e("Date", "gd-star-rating"); ?>:</span></div>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <div id="gdsr_timer_countdown" style="display: none"><input class="regular-text" type="text" value="<?php echo ''; ?>" id="gdsr_timer_countdown_value" name="gdsr_timer_countdown_value" style="width: 35px; text-align: right; padding: 2px;" />
                    <?php GDSRHelper::render_countdown_combo("gdsr_timer_countdown_type", 'H', 70); ?></div>
                    <div id="gdsr_timer_date" style="display: none"><input class="regular-text" type="text" value="<?php echo ''; ?>" id="gdsr_timer_date_value" name="gdsr_timer_date_value" style="width: 110px; padding: 2px;" /></div>
                </td>
                <?php if ($options["review_active"] == 1) { ?>
                <td style="width: 10px"></td><td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Review", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <select id="gdsr_review_rating" name="gdsr_review_rating" style="width: 50px; text-align: right;">
                    <option value="">/</option>
                    <?php GDSRHelper::render_stars_select_full(-1, $options["review_stars"], 0); ?>
                </select>.
                <select id="gdsr_review_rating_decimal" name="gdsr_review_rating_decimal" style="width: 50px; text-align: right;">
                    <option value="">/</option>
                    <?php GDSRHelper::render_stars_select_full(-1, 9, 0); ?>
                </select>
                </td><td style="width: 10px"></td>
                <?php } ?>
            </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("Articles", "gd-star-rating"); ?>:</strong><br/>(<?php _e("Thumbs", "gd-star-rating"); ?>)</span>
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Delete", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_delete_articles_recc" name="gdsr_delete_articles_recc" style="width: 120px;">
                        <option value="">/</option>
                        <option value="AV"><?php _e("Visitors", "gd-star-rating"); ?></option>
                        <option value="AU"><?php _e("Users", "gd-star-rating"); ?></option>
                        <option value="AA"><?php _e("All", "gd-star-rating"); ?></option>
                    </select>
                </td><td style="width: 10px"></td>
                <?php if ($options["moderation_active"] == 1) { ?>
                    <td style="width: 80px; height: 29px;">
                        <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                    </td>
                    <td style="width: 140px; height: 29px;" align="right">
                    <?php GDSRHelper::render_moderation_combo("gdsr_article_moderation_recc", "/", 120, "", true); ?>
                    </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_article_voterules_recc", "/", 120, "", true); ?>
                </td>
            </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Restriction", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_timer_combo("gdsr_timer_type_recc", 'N', 120, '', true, 'gdsrTimerChange(\'_recc\')'); ?>
                </td><td style="width: 10px"></td>
                <td style="width: 80px; height: 29px;">
                    <div id="gdsr_timer_countdown_text_recc" style="display: none"><span class="paneltext"><?php _e("Countdown", "gd-star-rating"); ?>:</span></div>
                    <div id="gdsr_timer_date_text_recc" style="display: none"><span class="paneltext"><?php _e("Date", "gd-star-rating"); ?>:</span></div>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <div id="gdsr_timer_countdown_recc" style="display: none"><input class="regular-text" type="text" value="<?php echo ''; ?>" id="gdsr_timer_countdown_value_recc" name="gdsr_timer_countdown_value_recc" style="width: 35px; text-align: right; padding: 2px;" />
                    <?php GDSRHelper::render_countdown_combo("gdsr_timer_countdown_type_recc", 'H', 70); ?></div>
                    <div id="gdsr_timer_date_recc" style="display: none"><input class="regular-text" type="text" value="<?php echo ''; ?>" id="gdsr_timer_date_value_recc" name="gdsr_timer_date_value_recc" style="width: 110px; padding: 2px;" /></div>
                </td>
            </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("Comment Integration", "gd-star-rating"); ?>:</strong></span>
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Multis", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_integration_active_mur" name="gdsr_integration_active_mur" style="width: 120px">
                        <option value="">/</option>
                        <option value="A"><?php _e("Normal activity", "gd-star-rating"); ?></option>
                        <option value="N"><?php _e("Force hidden", "gd-star-rating"); ?></option>
                        <option value="I"><?php _e("Inherit from Category", "gd-star-rating"); ?></option>
                    </select>
                </td><td style="width: 10px"></td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Standard", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_integration_active_std" name="gdsr_integration_active_std" style="width: 120px">
                        <option value="">/</option>
                        <option value="A"><?php _e("Normal activity", "gd-star-rating"); ?></option>
                        <option value="N"><?php _e("Force hidden", "gd-star-rating"); ?></option>
                        <option value="I"><?php _e("Inherit from Category", "gd-star-rating"); ?></option>
                    </select>
                </td>
                <td colspan="3"></td>
            </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;"></td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Multi Set", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 230px; height: 29px;" align="right">
                    <select id="gdsr_integration_mur" name="gdsr_integration_mur" style="width: 210px">
                        <option value="">/</option>
                        <option value="0"><?php _e("Inherit from Category", "gd-star-rating"); ?></option>
                        <option value="">------------------------</option>
                        <?php GDSRHelper::render_styles_select(GDSRDBMulti::get_multis_tinymce(), 0); ?>
                    </select>
                </td>
            </tr>
            </table>
            <?php if ($options["comments_active"] == 1) { ?>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("Comments", "gd-star-rating"); ?>:</strong><br/>(<?php _e("Stars", "gd-star-rating"); ?>)</span>
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Delete", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_delete_articles" name="gdsr_delete_comments" style="margin-top: -4px; width: 120px;">
                        <option value="">/</option>
                        <option value="CV"><?php _e("Visitors", "gd-star-rating"); ?></option>
                        <option value="CU"><?php _e("Users", "gd-star-rating"); ?></option>
                        <option value="CA"><?php _e("All", "gd-star-rating"); ?></option>
                    </select>
                </td>
            </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;"></td>
                <?php if ($options["moderation_active"] == 1) { ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_moderation_combo("gdsr_comments_moderation", "/", 120, "", true); ?>
                </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_comments_voterules", "/", 120, "", true); ?>
                </td>
            </tr>
            </table>
            <div class="gdsr-table-split"></div>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;">
                    <span class="paneltext"><strong><?php _e("Comments", "gd-star-rating"); ?>:</strong><br/>(<?php _e("Thumbs", "gd-star-rating"); ?>)</span>
                </td>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Delete", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                    <select id="gdsr_delete_articles" name="gdsr_delete_comments_recc" style="margin-top: -4px; width: 120px;">
                        <option value="">/</option>
                        <option value="CV"><?php _e("Visitors", "gd-star-rating"); ?></option>
                        <option value="CU"><?php _e("Users", "gd-star-rating"); ?></option>
                        <option value="CA"><?php _e("All", "gd-star-rating"); ?></option>
                    </select>
                </td>
            </tr>
            </table>
            <table cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 120px; height: 29px;"></td>
                <?php if ($options["moderation_active"] == 1) { ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_moderation_combo("gdsr_comments_moderation_recc", "/", 120, "", true); ?>
                </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 29px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 29px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_comments_voterules_recc", "/", 120, "", true); ?>
                </td>
            </tr>
            </table>
            <?php } ?>
        </td>
        <td style="width: 10px;"></td>
        <td class="gdsr-vertical-line">
            <input class="inputbutton" type="submit" name="gdsr_update" value="<?php _e("Update", "gd-star-rating"); ?>" />
        </td>
        </tr>
        </table>
        </div>
    </div>
<br class="clear"/>
</div>
<br class="clear"/>
</form>
</div>

<div id="gdsrchart" style="display: none">
    <?php include(STARRATING_PATH.'options/articles_chart.php'); ?>
</div>