<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_cmm_style" id="gdsr_cmm_style">
                <?php GDSRHelper::render_styles_select($gdsr_gfx->stars, $gdsr_options["cmm_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_cmm_size", $gdsr_options["cmm_size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_cmm_stars" id="gdsr_cmm_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["cmm_stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <?php if ($gdsr_options["admin_placement"] == 1) { ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_cmm_align", $gdsr_options["cmm_align"]); ?>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text Placement", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_placement("gdsr_cmm_text", $gdsr_options["cmm_text"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_cmm_header" id="gdsr_cmm_header"<?php if ($gdsr_options["cmm_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_cmm_header_text" id="gdsr_cmm_header_text" value="<?php echo wp_specialchars($gdsr_options["cmm_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
        <?php } ?>
        <?php if ($gdsr_options["admin_advanced"] == 1) { ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_cmm_classblock" id="gdsr_cmm_classblock" value="<?php echo wp_specialchars($gdsr_options["cmm_class_block"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text CSS Class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_cmm_classtext" id="gdsr_cmm_classtext" value="<?php echo wp_specialchars($gdsr_options["cmm_class_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
        <?php } ?>
        <?php if ($gdsr_options["admin_defaults"] == 1) { ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Default Vote Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_rules_combo("gdsr_default_vote_comments", $gdsr_options["default_voterules_comments"]); ?>
                </td>
                <td width="10"></td>
            <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td width="150"><?php _e("Default Moderation Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_moderation_combo("gdsr_default_mod_comments", $gdsr_options["default_moderation_comments"]); ?>
                </td>
            <?php } ?>
            </tr>
        </table>
        <?php } ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto Insert Rating Code", "gd-star-rating"); ?>:</td>
                <td valign="top" width="200">
                    <input type="checkbox" name="gdsr_dispcomment" id="gdsr_dispcomment"<?php if ($gdsr_options["display_comment"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_dispcomment"><?php _e("For comments.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Restrict", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_cmm_authorvote" id="gdsr_cmm_authorvote"<?php if ($gdsr_options["cmm_author_vote"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_authorvote"><?php _e("Prevent comment author to vote.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_cmm_logged" id="gdsr_cmm_logged"<?php if ($gdsr_options["cmm_logged"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_logged"><?php _e("Use logged data (IP) to prevent duplicate voting.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_cmm_cookies" id="gdsr_cmm_cookies"<?php if ($gdsr_options["cmm_cookies"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cmm_cookies"><?php _e("Use cookies to prevent duplicate voting.", "gd-star-rating"); ?></label>
    </td>
</tr>
</tbody></table>
