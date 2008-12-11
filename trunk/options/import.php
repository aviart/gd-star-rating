<?php

    if ($_POST["gdsr_import_psr"] == __("Import Data", "gd-star-rating")) {
        GDSRImport::import_psr();
        $imports["post_star_rating"] = $imports["post_star_rating"] + 1;
        update_option('gd-star-rating-import', $imports);
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Data import completed.</strong></p></div> <?php
    }

    if ($_POST["gdsr_import_wpr"] == __("Import Data", "gd-star-rating")) {
        GDSRImport::import_wpr();
        $imports["wp_post_ratings"] = $imports["wp_post_ratings"] + 1;
        update_option('gd-star-rating-import', $imports);
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Data import completed.</div> <?php
    }

    if ($_POST["gdsr_import_srfr"] == __("Import Data", "gd-star-rating")) {
        GDSRImport::import_srfr($_POST["gdsr_srfr_max"], $_POST["gdsr_srfr_meta"], $_POST["gdsr_srfr_try"], isset($_POST["gdsr_srfr_over"]) ? 1 : 0);
        $imports["star_rating_for_reviews"] = $imports["star_rating_for_reviews"] + 1;
        update_option('gd-star-rating-import', $imports);
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Data import completed.</div> <?php
    }

?>

<div class="wrap"><h2>GD Star Rating: <?php _e("Import Data", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-3"><span>Star Rating For Reviews</span></a></li>
    <li><a href="#fragment-1"><span>Post Star Rating</span></a></li>
    <li><a href="#fragment-2"><span>WP Post Ratings</span></a></li>
    <!--<li><a href="#fragment-4"><span>Vote The Post</span></a></li>-->
</ul>
<div style="clear: both"></div>

<div id="fragment-1">
<form method="post">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Plugin URL", "gd-star-rating"); ?>:</th>
    <td>
        <a href="http://wordpress.org/extend/plugins/post-star-rating/">http://wordpress.org/extend/plugins/post-star-rating/</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Author URL", "gd-star-rating"); ?>:</th>
    <td>
        O. Doutor
    </td>
</tr>
<tr><th scope="row"><?php _e("Status", "gd-star-rating"); ?>:</th>
    <td>
        <?php $import_available = GDSRImport::import_psr_check($imports["post_star_rating"]); ?>
    </td>
</tr>
</tbody></table>
<?php if ($import_available) { ?>
<p class="submit"><input type="submit" value="<?php _e("Import Data", "gd-star-rating"); ?>" name="gdsr_import_psr"/></p>
<?php } ?>
</form>
</div>

<div id="fragment-2">
<form method="post">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Plugin URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://wordpress.org/extend/plugins/wp-postratings/">http://wordpress.org/extend/plugins/wp-postratings/</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Author URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://lesterchan.net/">Lester Chan</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Status", "gd-star-rating"); ?>:</th>
    <td>
        <?php $import_available = GDSRImport::import_wpr_check($imports["wp_post_ratings"]); ?>
    </td>
</tr>
</tbody></table>
<?php if ($import_available) { ?>
<p class="submit"><input type="submit" value="<?php _e("Import Data", "gd-star-rating"); ?>" name="gdsr_import_wpr"/></p>
<?php } ?>
</form>
</div>

<div id="fragment-3">
<form method="post">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Plugin URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://www.channel-ai.com/blog/plugins/star-rating/">http://www.channel-ai.com/blog/plugins/star-rating/</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Author URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://www.channel-ai.com/">eyn</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Instructions", "gd-star-rating"); ?>:</th>
    <td>
        
    </td>
</tr>
<tr><th scope="row"><?php _e("Import Settings", "gd-star-rating"); ?>:</th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Maximum review value", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_srfr_max" id="gdsr_srfr_max" value="5" style="width: 170px" /> [$sr_defaultstar]
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Meta Key", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_srfr_meta" id="gdsr_srfr_meta" value="rating" style="width: 170px" /> [$sr_metakey]
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Try Importing from", "gd-star-rating"); ?>:</td>
                <td>
                    <select name="gdsr_srfr_try" id="gdsr_srfr_try" style="width: 180px">
                        <option value="M"><?php _e("Posts meta table", "gd-star-rating"); ?></option>
                        <option value="P"><?php _e("Posts contents only", "gd-star-rating"); ?></option>
                        <option value="B"><?php _e("Both contents and meta", "gd-star-rating"); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_srfr_over" id="gdsr_srfr_over" checked="checked" /><label style="margin-left: 5px;" for="gdsr_srfr_over"><?php _e("Overwrite already existing reviews in GD Star Rating tables.", "gd-star-rating"); ?></label>
</td>
<tr><th scope="row"><?php _e("Status", "gd-star-rating"); ?>:</th>
    <td>
        <?php $import_available = GDSRImport::import_srfr_check($imports["star_rating_for_reviews"]); ?>
    </td>
</tr>
</tbody></table>
<?php if ($import_available) { ?>
<p class="submit"><input type="submit" value="<?php _e("Import Data", "gd-star-rating"); ?>" name="gdsr_import_srfr"/></p>
<?php } ?>
</form>
</div>

<!--<div id="fragment-4">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Plugin URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://www.1800blogger.com/word-press-voting-plugin/">http://www.1800blogger.com/word-press-voting-plugin/</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Author URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://crowdfavorite.com/">Alex King</a>
    </td>
</tr>
</tbody></table>
<p>Work in progress...</p>
</div>-->

<br /><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>
<?php _e("To avoid rating data import problems, please don't use GD Star Rating in the same time as the plugins above, to avoid potential import problems and false data. I can't guarantee that data will be transfered without problems.", "gd-star-rating"); ?>
</strong></p></div>

</div>
</div>