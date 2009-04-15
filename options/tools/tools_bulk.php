<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Date Based Post Locking", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" height="25"><?php _e("Lock posts older than", "gd-star-rating"); ?>:</td>
                <td height="25"><input type="text" id="gdsr_lock_date" name="gdsr_lock_date" value="" /></td>
            </tr>
        </table>
        <input type="submit" class="inputbutton" value="<?php _e("Lock", "gd-star-rating"); ?>" name="gdsr_post_lock" id="gdsr_post_lock" />
        <div class="gdsr-table-split"></div>
        <?php _e("Previous Lock Date", "gd-star-rating"); ?>: <strong><?php echo $gdsr_options['mass_lock']; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Global Voting Rules Update", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" height="25"><strong><?php _e("Posts", "gd-star-rating"); ?>:</strong></td>
                <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td style="width: 80px; height: 25px;">
                    <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 25px;" align="right">
                <?php GDSRHelper::render_moderation_combo("gdsr_article_moderation", "/", 120, "", true); ?>
                </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 25px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 25px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_article_voterules", "/", 120, "", true); ?>
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150" height="25"><strong><?php _e("Comments", "gd-star-rating"); ?>:</strong></td>
                <?php if ($gdsr_options["moderation_active"] == 1) { ?>
                <td style="width: 80px; height: 25px;">
                    <span class="paneltext"><?php _e("Moderation", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 25px;" align="right">
                <?php GDSRHelper::render_moderation_combo("gdsr_comments_moderation", "/", 120, "", true); ?>
                </td><td style="width: 10px"></td>
                <?php } ?>
                <td style="width: 80px; height: 25px;">
                    <span class="paneltext"><?php _e("Vote Rules", "gd-star-rating"); ?>:</span>
                </td>
                <td style="width: 140px; height: 25px;" align="right">
                <?php GDSRHelper::render_rules_combo("gdsr_comments_voterules", "/", 120, "", true); ?>
                </td>
            </tr>
        </table>
        <input type="submit" class="inputbutton" value="<?php _e("Set", "gd-star-rating"); ?>" name="gdsr_rules_set" id="gdsr_rules_set" />
        <div class="gdsr-table-split"></div>
        <?php _e("This will update all posts and comments with previously saved ratings.", "gd-star-rating"); ?>
        </form>
    </td>
</tr>
</tbody></table>
