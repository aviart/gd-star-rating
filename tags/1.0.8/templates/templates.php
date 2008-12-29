<?php 

    if ($_POST['gdsr_action'] == 'save') :
        $gdsr_options["article_rating_text"] = stripslashes(htmlentities($_POST['gdsr_tpl_ratingtext'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["cmm_rating_text"] = stripslashes(htmlentities($_POST['gdsr_tpl_cmm_ratingtext'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["word_votes_singular"] = stripslashes(htmlentities($_POST['gdsr_word_votessingular'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["word_votes_plural"] = stripslashes(htmlentities($_POST['gdsr_word_votesplural'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["table_row_even"] = stripslashes(htmlentities($_POST['gdsr_tablerow_even'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["table_row_odd"] = stripslashes(htmlentities($_POST['gdsr_tablerow_odd'], ENT_QUOTES, 'UTF-8'));

        $gdsr_options["time_restricted_active"] = stripslashes(htmlentities($_POST['gdsr_time_active'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["time_restricted_closed"] = stripslashes(htmlentities($_POST['gdsr_time_closed'], ENT_QUOTES, 'UTF-8'));

        $gdsr_options["shortcode_starrating_header"] = stripslashes(htmlentities($_POST['gdsr_tpl_srheader'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["shortcode_starrating_item"] = stripslashes(htmlentities($_POST['gdsr_tpl_sritem'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["shortcode_starrating_footer"] = stripslashes(htmlentities($_POST['gdsr_tpl_srfooter'], ENT_QUOTES, 'UTF-8'));
        
        update_option("gd-star-rating-templates", $gdsr_options);

?>

<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings saved.</strong></p></div>

<?php endif; ?>

<form method="post">
<input type="hidden" id="gdsr_action" name="gdsr_action" value="save">
<div class="wrap"><h2>GD Star Rating: <?php _e("Templates", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Template Elements", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Articles (Posts And Pages)", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Comments", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>

<div id="fragment-1">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Custom Elements", "gd-star-rating"); ?></th>
    <td>
        <?php include(STARRATING_PATH."templates/templates_elements_custom.php"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Articles", "gd-star-rating"); ?></th>
    <td>
        <?php include(STARRATING_PATH."templates/templates_elements_articles.php"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Comments", "gd-star-rating"); ?></th>
    <td>
        <?php include(STARRATING_PATH."templates/templates_elements_comments.php"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("Time Restriction", "gd-star-rating"); ?></th>
    <td>
        <?php include(STARRATING_PATH."templates/templates_elements_timer.php"); ?>
    </td>
</tr>
</tbody></table>
</div>

<div id="fragment-2">
    <?php include(STARRATING_PATH."templates/templates_articles.php"); ?>
</div>

<div id="fragment-3">
    <?php include(STARRATING_PATH."templates/templates_comments.php"); ?>
</div>

</div>
<p class="submit"><input type="submit" value="<?php _e("Save Settings", "gd-star-rating"); ?>" name="gdsr_saving"/></p>
</div>
</div>
</form>
