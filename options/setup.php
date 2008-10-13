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
    if ($_POST["gdsr_remove_settings"] == __("REMOVE SETTINGS", "gd-star-rating")) {
        delete_option('gd-star-rating');
        delete_option('widget_gdstarrating');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings are removed from WordPress installation.</strong></p></div> <?php
    }
    if ($_POST["gdsr_remove_templates"] == __("REMOVE TEMPLATES", "gd-star-rating")) {
        delete_option('gd-star-rating-templates');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Plugin Templates are removed from WordPress installation.</strong></p></div> <?php
    }
    if ($_POST["gdsr_reset_imports"] == __("RESET IMPORTS", "gd-star-rating")) {
        delete_option('gd-star-rating-import');
        ?> <div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Import Information is reseted.</strong></p></div> <?php
    }
    
?>

<div class="wrap"><h2>GD Star Rating: <?php _e("Setup", "gd-star-rating"); ?></h2>
<form method="post">
    <div id="rightnow">
        <h3 class="reallynow"><span><?php _e("Database Maintenance", "gd-star-rating"); ?>:</span><br class="clear"/></h3>
        <p class="youhave"><?php _e("Use this options to reinstall or uninstall the plugin. This will remove all existing data recorded by the plugin.", "gd-star-rating"); ?></p>
        <table>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("REINSTALL", "gd-star-rating"); ?>" name="gdsr_reinstall" style="width: 150px;" /></p></td>
                <td><?php _e("All database tables will be deleted, and installed again.", "gd-star-rating"); ?></td>
            </tr>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("UNINSTALL", "gd-star-rating"); ?>" name="gdsr_uninstall" style="width: 150px;" /></p></td>
                <td><?php _e("All database tables will be deleted.", "gd-star-rating"); ?></td>
            </tr>
        </table>
    </div>

    <div id="rightnow">
        <h3 class="reallynow"><span><?php _e("Global Maintenance", "gd-star-rating"); ?>:</span><br class="clear"/></h3>
        <p class="youhave"><?php _e("These are various maintenace options.", "gd-star-rating"); ?></p>
        <table>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("REMOVE SETTINGS", "gd-star-rating"); ?>" name="gdsr_remove_settings" style="width: 150px;" /></p></td>
                <td><?php _e("This will remove all plugin settings and all the saved widgets.", "gd-star-rating"); ?></td>
            </tr>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("REMOVE TEMPLATES", "gd-star-rating"); ?>" name="gdsr_remove_templates" style="width: 150px;" /></p></td>
                <td><?php _e("This will remove all saved templates for the plugin. Templates will be reseted to their default values.", "gd-star-rating"); ?></td>
            </tr>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("RESET IMPORTS", "gd-star-rating"); ?>" name="gdsr_reset_imports" style="width: 150px;" /></p></td>
                <td><?php _e("This will reset all import flags for import data modules and will allow you to import again allready imported data.", "gd-star-rating"); ?></td>
            </tr>
        </table>
    </div>

    <div id="rightnow">
        <h3 class="reallynow"><span><?php _e("Full Uninstall", "gd-star-rating"); ?>:</span><br class="clear"/></h3>
        <p class="youhave"><?php _e("This will remove all database tables created by plugin, all settings, templates and import settings.", "gd-star-rating"); ?></p>
        <table>
            <tr>
                <td><p class="submit" style="border: 0; margin: 0;"><input type="submit" value="<?php _e("FULL UNISTALL", "gd-star-rating"); ?>" name="gdsr_reset_imports" style="width: 150px;" /></p></td>
                <td><?php _e("After removall, plugin will be disabled.", "gd-star-rating"); ?></td>
            </tr>
        </table>
    </div>

</form>
</div>