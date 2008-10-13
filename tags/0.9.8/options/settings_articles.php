<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_style" id="gdsr_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_size", $gdsr_options["size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_stars" id="gdsr_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_align", $gdsr_options["align"]); ?>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text Placement", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_placement("gdsr_text", $gdsr_options["text"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_header" id="gdsr_header"<?php if ($gdsr_options["header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_header_text" id="gdsr_header_text" value="<?php echo wp_specialchars($gdsr_options["header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php if ($gdsr_options["admin_advanced"] == 1) { ?>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_classblock" id="gdsr_classblock" value="<?php echo wp_specialchars($gdsr_options["class_block"]); ?>" style="width: 170px" />
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating Text CSS Class", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_classtext" id="gdsr_classtext" value="<?php echo wp_specialchars($gdsr_options["class_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Default Vote Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_rules_combo("gdsr_default_vote_articles", $gdsr_options["default_voterules_articles"]); ?>
                </td>
                <td width="10"></td>
            <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td width="150"><?php _e("Default Moderation Rule", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_moderation_combo("gdsr_default_mod_articles", $gdsr_options["default_moderation_articles"]); ?>
                </td>
            <?php } ?>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" valign="top"><?php _e("Auto Insert Rating Code", "gd-star-rating"); ?>:</td>
                <td width="200" valign="top">
                    <input type="checkbox" name="gdsr_posts" id="gdsr_posts"<?php if ($gdsr_options["display_posts"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_posts"><?php _e("For individual posts.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_pages" id="gdsr_pages"<?php if ($gdsr_options["display_pages"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_pages"><?php _e("For individual pages.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td valign="top">
                    <input type="checkbox" name="gdsr_archive" id="gdsr_archive"<?php if ($gdsr_options["display_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_archive"><?php _e("For posts displayed in Archives.", "gd-star-rating"); ?></label>
                    <br />
                    <input type="checkbox" name="gdsr_home" id="gdsr_home"<?php if ($gdsr_options["display_home"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_home"><?php _e("For posts displayed on Front Page.", "gd-star-rating"); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <input type="checkbox" name="gdsr_cookies" id="gdsr_cookies"<?php if ($gdsr_options["cookies"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_cookies"><?php _e("Save cookies to prevent duplicate voting.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_authorvote" id="gdsr_authorvote"<?php if ($gdsr_options["author_vote"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_authorvote"><?php _e("Prevent article author to vote.", "gd-star-rating"); ?> <?php _e("This is only for registered users.", "gd-star-rating"); ?></label>
    </td>
</tr>
<?php if ($gdsr_options["review_active"] == 1) { ?>
<tr><th scope="row"><?php _e("Review", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_review_style" id="gdsr_review_style">
                <?php GDSRHelper::render_styles_select($gdsr_styles, $gdsr_options["review_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_review_size", $gdsr_options["review_size"]); ?>
                </td>
                <td width="10"></td>
                <td width="100"><?php _e("Number Of Stars", "gd-star-rating"); ?>:</td>
                <td width="80" align="left">
                <select style="width: 70px;" name="gdsr_review_stars" id="gdsr_review_stars">
                <?php GDSRHelper::render_stars_select($gdsr_options["review_stars"]); ?>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Alignment", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_alignment("gdsr_review_align", $gdsr_options["review_align"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_review_header" id="gdsr_review_header"<?php if ($gdsr_options["review_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_review_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_review_header_text" id="gdsr_review_header_text" value="<?php echo wp_specialchars($gdsr_options["review_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Block CSS Class", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="text" name="gdsr_review_classblock" id="gdsr_review_classblock" value="<?php echo wp_specialchars($gdsr_options["review_class_block"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php } ?>
</tbody></table>
