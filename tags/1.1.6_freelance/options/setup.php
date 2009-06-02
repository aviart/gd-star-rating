<?php

    if ($_POST["gdsr_reinstall"] == __("Reinstall", "gd-star-rating")) {
        gdDBInstall::drop_tables(STARRATING_PATH);
        gdDBInstall::create_tables(STARRATING_PATH);
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Database tables reinstalled.</strong></p></div> <?php
    }
    if ($_POST["gdsr_remove_settings"] == __("Remove Settings", "gd-star-rating")) {
        delete_option('gd-star-rating');
        delete_option('widget_gdstarrating');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings are removed from WordPress installation.</strong></p></div> <?php
    }
    if ($_POST["gdsr_remove_templates"] == __("Remove Templates", "gd-star-rating")) {
        delete_option('gd-star-rating-templates');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Plugin Templates are removed from WordPress installation.</strong></p></div> <?php
    }
    if ($_POST["gdsr_reset_imports"] == __("Reset Imports", "gd-star-rating")) {
        delete_option('gd-star-rating-import');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Import Information is reseted.</strong></p></div> <?php
    }
    
?>
<script>
    function areYouSure() {
        return confirm("Are you sure?");
    }
</script>

<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Setup", "gd-star-rating"); ?></h2>
<form method="post" onsubmit="return areYouSure()">
<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Database Maintenance", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Global Maintenance", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Full Uninstall", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-1">
<?php include STARRATING_PATH."options/setup/setup_database.php"; ?>
</div>
<div id="fragment-2">
<?php include STARRATING_PATH."options/setup/setup_global.php"; ?>
</div>
<div id="fragment-3">
<?php include STARRATING_PATH."options/setup/setup_uninstall.php"; ?>
</div>
</div>
</form>
</div>