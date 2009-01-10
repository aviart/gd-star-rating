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

<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings saved.</strong></p></div>

<?php } ?>

<div class="gdsr">
<form method="post">
<input type="hidden" id="gdsr_action" name="gdsr_action" value="save" />
<div class="wrap"><h2>GD Star Rating: <?php _e("Settings", "gd-star-rating"); ?></h2>

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("General", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-9"><span><?php _e("Debug", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-7"><span><?php _e("Cache", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-6"><span><?php _e("Integration", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Posts And Pages", "gd-star-rating"); ?></span></a></li>
    <?php if ($gdsr_options["comments_active"] == 1) { ?><li><a href="#fragment-3"><span><?php _e("Comments", "gd-star-rating"); ?></span></a></li><?php } ?>
    <li><a href="#fragment-8"><span><?php _e("Multis", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-4"><span><?php _e("Statistics", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-5"><span><?php _e("Preview", "gd-star-rating"); ?></span></a></li>
    <!-- <li><a href="#fragment-8"><span><?php _e("BOT List", "gd-star-rating"); ?></span></a></li> -->
</ul>
<div style="clear: both"></div>
<div id="fragment-1">
<?php include STARRATING_PATH."options/settings_general.php"; ?>
</div>
<div id="fragment-9">
<?php include STARRATING_PATH."options/settings_debug.php"; ?>
</div>
<div id="fragment-7">
<?php include STARRATING_PATH."options/settings_cache.php"; ?>
</div>
<div id="fragment-6">
<?php include STARRATING_PATH."options/settings_integration.php"; ?>
</div>
<div id="fragment-2">
<?php include STARRATING_PATH."options/settings_articles.php"; ?>
</div>
<?php if ($gdsr_options["comments_active"] == 1) { ?><div id="fragment-3">
<?php include STARRATING_PATH."options/settings_comments.php"; ?>
</div><?php } ?>
<div id="fragment-8">
<?php include STARRATING_PATH."options/settings_multis.php"; ?>
</div>
<div id="fragment-4">
<?php include STARRATING_PATH."options/settings_statistics.php"; ?>
</div>
<div id="fragment-5">
<?php include STARRATING_PATH."options/settings_preview.php"; ?>
</div>
<!-- <div id="fragment-8">
<?php include STARRATING_PATH."options/settings_bots.php"; ?>
</div> -->
</div>

<p class="submit"><input type="submit" class="inputbutton" value="<?php _e("Save Settings", "gd-star-rating"); ?>" name="gdsr_saving"/></p>
</div>
</form>
</div>

<script>
    gdsrStyleSelection('stars');
    gdsrStyleSelection('trends');
</script>