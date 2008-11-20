<div class="gdsr">
<div class="wrap"><h2>GD Star Rating: <?php _e("Tools", "gd-star-rating"); ?></h2>
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Stars and Trends Graphics", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <?php _e("Rescan graphics folders for new stars and trends images.", "gd-star-rating"); ?><br />
        <input type="submit" class="inputbutton" value="<?php _e("Rescan", "gd-star-rating"); ?>" name="gdsr_preview_scan" id="gdsr_preview_scan" />
        <div class="gdsr-table-split"></div>
        Last scan was executed on: <strong><?php echo $gdsr_gfx->last_scan; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Database Cleanup", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <input type="checkbox" name="gdsr_tools_clean_invalid_log" id="gdsr_tools_clean_invalid_log" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_invalid_log"><?php _e("Remove all invalid votes from votes log.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_tools_clean_invalid_trend" id="gdsr_tools_clean_invalid_trend" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_invalid_trend"><?php _e("Remove all invalid votes from trends log.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_tools_clean_old_posts" id="gdsr_tools_clean_old_posts" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_old_posts"><?php _e("Remove data for old and deleted posts.", "gd-star-rating"); ?></label>
        <br />
        <input type="submit" class="inputbutton" value="<?php _e("Clean", "gd-star-rating"); ?>" name="gdsr_cleanup_tool" id="gdsr_cleanup_tool" />
        <div class="gdsr-table-split"></div>
        Last cleanup was executed on: <strong><?php echo $gdsr_options['database_cleanup']; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Date Based Post Locking", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="300" height="25"><?php _e("Lock voting for posts older then", "gd-star-rating"); ?>:</td>
                <td height="25"><input type="text" name="gdst_lock_date" value="" /></td>
            </tr>
        </table>
        <input type="submit" class="inputbutton" value="<?php _e("Lock", "gd-star-rating"); ?>" name="gdsr_post_lock" id="gdsr_post_lock" />
        <div class="gdsr-table-split"></div>
        Previous Lock Date: <strong><?php echo $gdsr_options['mass_lock']; ?></strong>
        </form>
    </td>
</tr>
</tbody></table>
</div>
</div>