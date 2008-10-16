<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Administration Settings", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Maximum screen width", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <select name="gdsr_admin_width" id="gdsr_admin_width" style="width: 180px; text-align: center">
                        <option value="980"<?php echo $gdsr_options["admin_width"] == '980' ? ' selected="selected"' : ''; ?>>1024 px</option>
                        <option value="1240"<?php echo $gdsr_options["admin_width"] == '1240' ? ' selected="selected"' : ''; ?>>1280 px</option>
                        <option value="1400"<?php echo $gdsr_options["admin_width"] == '1400' ? ' selected="selected"' : ''; ?>>1440 px</option>
                        <option value="1640"<?php echo $gdsr_options["admin_width"] == '1640' ? ' selected="selected"' : ''; ?>>1680 px</option>
                        <option value="1880"<?php echo $gdsr_options["admin_width"] == '1880' ? ' selected="selected"' : ''; ?>>1920 px</option>
                    </select>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rows for display", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <select name="gdsr_admin_rows" id="gdsr_admin_rows" style="width: 180px; text-align: center">
                        <option value="5"<?php echo $gdsr_options["admin_rows"] == '5' ? ' selected="selected"' : ''; ?>>5</option>
                        <option value="10"<?php echo $gdsr_options["admin_rows"] == '10' ? ' selected="selected"' : ''; ?>>10</option>
                        <option value="20"<?php echo $gdsr_options["admin_rows"] == '20' ? ' selected="selected"' : ''; ?>>20</option>
                        <option value="50"<?php echo $gdsr_options["admin_rows"] == '50' ? ' selected="selected"' : ''; ?>>50</option>
                        <option value="100"<?php echo $gdsr_options["admin_rows"] == '100' ? ' selected="selected"' : ''; ?>>100</option>
                    </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_admin_advanced" id="gdsr_admin_advanced"<?php if ($gdsr_options["admin_advanced"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_admin_advanced"><?php _e("Display Advanced Settings.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Plugin Features", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_ajax" id="gdsr_ajax"<?php if ($gdsr_options["ajax"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_ajax"><?php _e("AJAX enabled rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_modactive" id="gdsr_modactive"<?php if ($gdsr_options["moderation_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_modactive"><?php _e("Moderation options and handling.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_reviewactive" id="gdsr_reviewactive"<?php if ($gdsr_options["review_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_modactive"><?php _e("Review Rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_commentsactive" id="gdsr_commentsactive"<?php if ($gdsr_options["comments_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Comments Rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_iepngfix" id="gdsr_iepngfix"<?php if ($gdsr_options["ie_png_fix"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Use IE6 PNG Transparency Fix.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Widgets", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_widget_articles" id="gdsr_widget_articles"<?php if ($gdsr_options["widget_articles"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_ajax"><?php _e("GD Star Rating: Post/Page rating widget.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Stars and Trends Graphics", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_preview_scan" id="gdsr_preview_scan" /><label style="margin-left: 5px;" for="gdsr_preview_scan"><?php _e("Rescan graphics folders for new stars and trends images.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        Last scan was executed on: <strong><?php echo $gdsr_gfx->last_scan; ?></strong>
    </td>
</tr>
</tbody></table>
