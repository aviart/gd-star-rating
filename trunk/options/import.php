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

?>

<div class="wrap"><h2>GD Star Rating: <?php _e("Import Data", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span>Post Star Rating</span></a></li>
    <li><a href="#fragment-2"><span>WP Post Ratings</span></a></li>
    <!--<li><a href="#fragment-3"><span>Vote The Post </span></a></li>-->
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

<!--<div id="fragment-3">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Plugin URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://www.1800blogger.com/word-press-voting-plugin/">http://www.1800blogger.com/word-press-voting-plugin/</a>
    </td>
</tr>
<tr><th scope="row"><?php _e("Author URL", "gd-star-rating"); ?>:</th>
    <td>
        <a target="_blank" href="http://crowdfavorite.com/">Crowd Favorite</a>
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