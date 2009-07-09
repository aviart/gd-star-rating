<?php if ($_POST['gdsr_action'] == 'save') { ?>
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
<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Graphics", "gd-star-rating"); ?></h2>

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Stars", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Thumbs", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Preview", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-1">
<?php include STARRATING_PATH."options/gfx/gfx_stars.php"; ?>
</div>
<div id="fragment-2">
<?php include STARRATING_PATH."options/gfx/gfx_thumbs.php"; ?>
</div>
<div id="fragment-3">
<?php include STARRATING_PATH."options/gfx/gfx_preview.php"; ?>
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
