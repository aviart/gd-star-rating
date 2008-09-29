<?php

    if ($_POST["gdsr_uninstall"] == __("UNINSTALL", "gd-star-rating")) {
        GDSRDB::uninstall_database();
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>All database table deleted.</strong></p></div> <?php
    }
    if ($_POST["gdsr_reinstall"] == __("REINSTALL", "gd-star-rating")) {
        GDSRDB::uninstall_database();
        GDSRDB::install_database();
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Database tables reinstalled.</strong></p></div> <?php
    }
    if ($_POST["gdsr_remove"] == __("REMOVE SETTINGS", "gd-star-rating")) {
        delete_option('gd-star-rating');
        delete_option('widget_gdstarrating');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>All settings are removed from WordPress installation.</strong></p></div> <?php
    }
    
?>

<div class="wrap"><h2>GD Star Rating: <?php _e("Setup", "gd-star-rating"); ?></h2>
<form method="post">
    <div id="rightnow">
        <h3 class="reallynow"><span><?php _e("Database Maintenance", "gd-star-rating"); ?>:</span><br class="clear"/></h3>
        <p class="youhave"><?php _e("Use this options to reinstall or uninstall the plugin. This will remove all existing data recorded by the plugin.", "gd-star-rating"); ?></p>
            <p class="submit" style="border:0">
                <input type="submit" value="<?php _e("REINSTALL", "gd-star-rating"); ?>" name="gdsr_reinstall" style="width: 150px;" />
                <input type="submit" value="<?php _e("UNINSTALL", "gd-star-rating"); ?>" name="gdsr_uninstall" style="width: 150px;" />
            </p><br/>
    </div>

    <div id="rightnow">
        <h3 class="reallynow"><span><?php _e("Global Maintenance", "gd-star-rating"); ?>:</span><br class="clear"/></h3>
        <p class="youhave"><?php _e("These are various maintenace options.", "gd-star-rating"); ?></p>
            <p class="submit" style="border:0">
                <input type="submit" value="<?php _e("REMOVE SETTINGS", "gd-star-rating"); ?>" name="gdsr_remove" style="width: 150px;" />
            </p><br/>
    </div>
</form>
</div>