<?php if (!(is_dir(STARRATING_CACHE_PATH) && is_writable(STARRATING_CACHE_PATH))) { ?>
    <?php if (!$extra_folders) { ?>
        <div id="gdnotice" class="gderror">
            <?php if ($safe_mode) { ?><p><?php _e("PHP is working in the safe mode. Plugin can't create folders for caching. You need to do it manually if you want to use cache.", "gd-star-rating"); ?></p><?php } ?>
            <p><?php _e("For cache to work, plugin must be able to access cache folder. Plugin has tried to create folders needed and failed. Until you resolved this issue cache feature can't be used.", "gd-star-rating"); ?></p>
            <p><?php _e("Either make <strong>wp-content</strong> folder writeable or create <strong>gd-star-rating</strong> folder in <strong>wp-content</strong> and make it writeable. Use 0755 chmod setting.", "gd-star-rating"); ?></p>
        </div>
    <?php } ?>
<?php } ?>
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Activation", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_cache_active" id="gdsr_cache_active"<?php if ($gdsr_options["cache_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cache_active"><?php _e("Cache enabled.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Cleanup", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="200"><?php _e("Auto cleanup cache", "gd-star-rating"); ?>:</td>
                <td><input type="checkbox" name="gdsr_cache_cleanup_auto" id="gdsr_cache_cleanup_auto"<?php if ($gdsr_options["cache_cleanup_auto"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cache_cleanup_auto"><?php _e("Enabled.", "gd-star-rating"); ?></label></td>
            </tr>
            <tr>
                <td width="200"><?php _e("Cleanup cache every", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_cache_cleanup_days" id="gdsr_cache_cleanup_days" value="<?php echo $gdsr_options["cache_cleanup_days"]; ?>" style="width: 70px; text-align: right;" /> [days]</td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
