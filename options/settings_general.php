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
        <input type="checkbox" name="gdsr_admin_advanced" id="gdsr_admin_advanced"<?php if ($gdsr_options["admin_advanced"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_admin_advanced"><?php _e("Display Rating Custom CSS Settings.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_admin_placement" id="gdsr_admin_placement"<?php if ($gdsr_options["admin_placement"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_admin_placement"><?php _e("Display Rating Placement Settings.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_admin_defaults" id="gdsr_admin_defaults"<?php if ($gdsr_options["admin_defaults"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_admin_defaults"><?php _e("Display Rating Defaults Settings.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Plugin Features", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_ajax" id="gdsr_ajax"<?php if ($gdsr_options["ajax"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_ajax"><?php _e("AJAX enabled rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_timer" id="gdsr_timer"<?php if ($gdsr_options["timer_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_timer"><?php _e("Time restriction for rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_modactive" id="gdsr_modactive"<?php if ($gdsr_options["moderation_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_modactive"><?php _e("Moderation options and handling.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_reviewactive" id="gdsr_reviewactive"<?php if ($gdsr_options["review_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_reviewactive"><?php _e("Post And Page Review Rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_commentsactive" id="gdsr_commentsactive"<?php if ($gdsr_options["comments_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Comments Rating.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_cmmreviewactive" id="gdsr_cmmreviewactive"<?php if ($gdsr_options["comments_review_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmmreviewactive"><?php _e("Comments Review Rating.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_iepngfix" id="gdsr_iepngfix"<?php if ($gdsr_options["ie_png_fix"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_commentsactive"><?php _e("Use IE6 PNG Transparency Fix.", "gd-star-rating"); ?></label>
    </td>
</tr>
<tr><th scope="row"><?php _e("Rating Log", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_save_user_agent" id="gdsr_save_user_agent"<?php if ($gdsr_options["save_user_agent"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_save_user_agent"><?php _e("Log user agent (browser) information.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_save_cookies" id="gdsr_save_cookies"<?php if ($gdsr_options["save_cookies"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_save_cookies"><?php _e("Save cookies with ratings.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
