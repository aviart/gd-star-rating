<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Posts &amp; Pages", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto insert rating code", "gd-star-rating"); ?>:</td>
                <td width="200" valign="top">
                    <input type="checkbox" name="gdsr_thumb_posts" id="gdsr_thumb_posts"<?php if ($gdsr_options["thumb_display_posts"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_posts"><?php _e("For individual posts.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_thumb_pages" id="gdsr_thumb_pages"<?php if ($gdsr_options["thumb_display_pages"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_pages"><?php _e("For individual pages.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td valign="top">
                    <input type="checkbox" name="gdsr_thumb_archive" id="gdsr_thumb_archive"<?php if ($gdsr_options["thumb_display_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_archive"><?php _e("For posts displayed in Archives.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_thumb_home" id="gdsr_thumb_home"<?php if ($gdsr_options["thumb_display_home"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_home"><?php _e("For posts displayed on Front Page.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_thumb_search" id="gdsr_thumb_search"<?php if ($gdsr_options["thumb_display_search"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_search"><?php _e("For posts displayed on Search results.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Auto insert location", "gd-star-rating"); ?>:</td>
                <td width="200" valign="top"><?php GDSRHelper::render_insert_position("gdsr_thumb_auto_display_position", $gdsr_options["thumb_auto_display_position"]); ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Comments", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto insert rating code", "gd-star-rating"); ?>:</td>
                <td valign="top" width="200">
                    <input type="checkbox" name="gdsr_thumb_dispcomment" id="gdsrthumb__dispcomment"<?php if ($gdsr_options["thumb_display_comment"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_thumb_dispcomment"><?php _e("For comments for posts.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td valign="top">
                    <input type="checkbox" name="gdsr_thumb_dispcomment_pages" id="gdsr_thumb_dispcomment_pages"<?php if ($gdsr_options["thumb_display_comment_page"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_thumb_dispcomment_pages"><?php _e("For comments for pages.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Auto insert location", "gd-star-rating"); ?>:</td>
                <td width="200" valign="top"><?php GDSRHelper::render_insert_position("gdsr_thumb_auto_display_position", $gdsr_options["thumb_auto_display_position"]); ?></td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
