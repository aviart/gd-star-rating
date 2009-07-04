<?php 

    if ($_POST['gdsr_action'] == 'save') {
        $gdsr_oldstars = $gdsr_options["stars"];
        $gdsr_newstars = $_POST['gdsr_stars'];
        $gdsr_cmm_oldstars = $gdsr_options["cmm_stars"];
        $gdsr_cmm_newstars = $_POST['gdsr_cmm_stars'];
        $gdsr_review_oldstars = $gdsr_options["review_stars"];
        $gdsr_review_newstars = $_POST['gdsr_review_stars'];
        $gdsr_cmm_review_oldstars = $gdsr_options["cmm_review_stars"];
        $gdsr_cmm_review_newstars = $_POST['gdsr_cmm_review_stars'];
        
        if ($gdsr_options["stars"] != $gdsr_newstars) $recalculate_articles = true;
        else $recalculate_articles = false;
        if ($gdsr_options["cmm_stars"] != $gdsr_cmm_newstars) $recalculate_comment = true;
        else $recalculate_comment = false;
        if ($gdsr_options["review_stars"] != $gdsr_review_newstars) $recalculate_reviews = true;
        else $recalculate_reviews = false;
        if ($gdsr_options["cmm_review_stars"] != $gdsr_cmm_review_newstars) $recalculate_cmm_reviews = true;
        else $recalculate_cmm_reviews = false;
        
        $gdsr_options["stars"] = $gdsr_newstars;
        $gdsr_options["cmm_stars"] = $gdsr_cmm_newstars;
        $gdsr_options["review_stars"] = $gdsr_review_newstars;
        $gdsr_options["cmm_review_stars"] = $gdsr_cmm_review_newstars;
        
        update_option("gd-star-rating", $gdsr_options);

?>

<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Settings saved.", "gd-star-rating"); ?></strong></p></div>

<?php } ?>

<script type="text/javascript">
function gdsrStyleSelection(preview) {
    var gdsrImages = { <?php GDSRHelper::render_gfx_js($gdsr_gfx->stars); ?> };
    var gdsrTrends = { <?php GDSRHelper::render_gfx_js($gdsr_gfx->trend); ?> };
    var gdsrImagesExt = { <?php GDSRHelper::render_ext_gfx_js($gdsr_gfx->stars); ?> };
    var gdsrTrendsExt = { <?php GDSRHelper::render_ext_gfx_js($gdsr_gfx->trend); ?> };
    var gdsrAuthors = { <?php GDSRHelper::render_authors_gfx_js($gdsr_gfx->stars); ?> };

    var gdsrBase = "#gdsr_preview";
    var gdsrStyle = "";
    var gdsrSize = "";
    var gdsrImage = "";

    if (preview == "trends") {
        gdsrBase = gdsrBase + "_trends";
        gdsrStyle = jQuery("#gdsr_style_preview_trends").val();
        gdsrImage = gdsrTrends[gdsrStyle] + "trend." + gdsrTrendsExt[gdsrStyle];
    }
    else {
        gdsrStyle = jQuery("#gdsr_style_preview").val();
        gdsrSize = jQuery("#gdsr_size_preview").val();
        gdsrImage = gdsrImages[gdsrStyle] + "stars" + gdsrSize + "." + gdsrImagesExt[gdsrStyle];
        jQuery("#gdsrauthorname").html(gdsrAuthors[gdsrStyle]["name"]);
        jQuery("#gdsrauthoremail").html(gdsrAuthors[gdsrStyle]["email"]);
        jQuery("#gdsrauthorurl").html(gdsrAuthors[gdsrStyle]["url"]);
    }

    jQuery(gdsrBase+"_black").attr("src", gdsrImage);
    jQuery(gdsrBase+"_red").attr("src", gdsrImage);
    jQuery(gdsrBase+"_green").attr("src", gdsrImage);
    jQuery(gdsrBase+"_white").attr("src", gdsrImage);
    jQuery(gdsrBase+"_blue").attr("src", gdsrImage);
    jQuery(gdsrBase+"_yellow").attr("src", gdsrImage);
    jQuery(gdsrBase+"_gray").attr("src", gdsrImage);
    jQuery(gdsrBase+"_picture").attr("src", gdsrImage);
}
</script>

<div class="gdsr">
<form method="post">
<input type="hidden" id="gdsr_preview_stars" name="gdsr_preview_stars" value="<?php echo $gdsr_options["preview_active"]; ?>" />
<input type="hidden" id="gdsr_preview_trends" name="gdsr_preview_trends" value="<?php echo $gdsr_options["preview_trends_active"]; ?>" />
<input type="hidden" id="gdsr_action" name="gdsr_action" value="save" />
<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Settings", "gd-star-rating"); ?></h2>

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-11"><span><?php _e("Features", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-1"><span><?php _e("Administration", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-9"><span><?php _e("Advanced", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-6"><span><?php _e("Integration", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Posts And Pages", "gd-star-rating"); ?></span></a></li>
    <?php if ($gdsr_options["comments_active"] == 1) { ?><li><a href="#fragment-3"><span><?php _e("Comments", "gd-star-rating"); ?></span></a></li><?php } ?>
    <?php if ($gdsr_options["multis_active"] == 1) { ?><li><a href="#fragment-8"><span><?php _e("Multis", "gd-star-rating"); ?></span></a></li><?php } ?>
    <li><a href="#fragment-4"><span><?php _e("Statistics", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-5"><span><?php _e("Graphics", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-11">
<?php include STARRATING_PATH."options/settings/settings_features.php"; ?>
</div>
<div id="fragment-1">
<?php include STARRATING_PATH."options/settings/settings_administration.php"; ?>
</div>
<div id="fragment-9">
<?php include STARRATING_PATH."options/settings/settings_advanced.php"; ?>
<?php include STARRATING_PATH."options/settings/settings_bots.php"; ?>
</div>
<div id="fragment-6">
<?php include STARRATING_PATH."options/settings/settings_integration.php"; ?>
</div>
<div id="fragment-2">
<?php include STARRATING_PATH."options/settings/settings_articles.php"; ?>
</div>
<?php if ($gdsr_options["comments_active"] == 1) { ?><div id="fragment-3">
<?php include STARRATING_PATH."options/settings/settings_comments.php"; ?>
</div><?php } ?>
<?php if ($gdsr_options["multis_active"] == 1) { ?><div id="fragment-8">
<?php include STARRATING_PATH."options/settings/settings_multis.php"; ?>
</div><?php } ?>
<div id="fragment-4">
<?php include STARRATING_PATH."options/settings/settings_statistics.php"; ?>
</div>
<div id="fragment-5">
<?php include STARRATING_PATH."options/settings/settings_gfx.php"; ?>
<?php include STARRATING_PATH."options/settings/settings_preview.php"; ?>
</div>
</div>

<div style="margin-top: 10px"><input type="submit" class="inputbutton" value="<?php _e("Save Settings", "gd-star-rating"); ?>" name="gdsr_saving"/></div>
</div>
</form>
</div>

<script type="text/javascript">
    gdsrStyleSelection('stars');
    gdsrStyleSelection('trends');
</script>