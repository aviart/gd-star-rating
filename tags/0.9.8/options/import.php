<?php

    if ($_POST["gdsr_import_psr"] == __("Import Data", "gd-star-rating")) {
        GDSRImport::import_psr();
        $imports["post_star_rating"] = $imports["post_star_rating"] + 1;
        update_option('gd-star-rating-import', $imports);
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Data import completed.</strong></p></div> <?php
    }

?>

<div class="wrap"><h2>GD Star Rating: <?php _e("Import Data", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span>Post Star Rating</span></a></li>
    <li><a href="#fragment-2"><span>WP Post Ratings</span></a></li>
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
        <?php
            if ($imports["post_star_rating"] == 0)
                _e("Data not imported.", "gd-star-rating");
            else
                _e("Data imported.", "gd-star-rating");
        ?>
    </td>
</tr>
</tbody></table>
<?php if ($imports["post_star_rating"] == 0) { ?>
<p class="submit"><input type="submit" value="<?php _e("Import Data", "gd-star-rating"); ?>" name="gdsr_import_psr"/></p>
<?php } ?>
</form>
</div>

<div id="fragment-2">
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
</tbody></table>
<p>Work in progress...</p>
</div>

</div>
</div>