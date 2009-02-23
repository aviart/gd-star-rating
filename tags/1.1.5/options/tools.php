<div class="wrap"><h2 class="gdptlogopage">GD Star Rating: <?php _e("Tools", "gd-star-rating"); ?></h2>
<div class="gdsr">

<div id="gdsr_tabs" class="gdsrtabs">
<ul>
    <li><a href="#fragment-1"><span><?php _e("Graphics", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-2"><span><?php _e("Database", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-3"><span><?php _e("Bulk", "gd-star-rating"); ?></span></a></li>
    <li><a href="#fragment-4"><span><?php _e("Cache", "gd-star-rating"); ?></span></a></li>
</ul>
<div style="clear: both"></div>
<div id="fragment-1">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Stars and Trends Graphics", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <?php _e("Rescan graphics folders for new stars and trends images.", "gd-star-rating"); ?><br />
        <input type="submit" class="inputbutton" value="<?php _e("Rescan", "gd-star-rating"); ?>" name="gdsr_preview_scan" id="gdsr_preview_scan" />
        <div class="gdsr-table-split"></div>
        <?php _e("Last scan was executed on.", "gd-star-rating"); ?>: <strong><?php echo $gdsr_gfx->last_scan; ?></strong>
        </form>
    </td>
</tr>
</tbody></table>
</div>
<div id="fragment-2">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Upgrade", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <?php _e("Try to upgrade plugin tables. This is performed automatically after each plugin upgrade, but you can use this tool also.", "gd-star-rating"); ?><br />
        <input type="submit" class="inputbutton" value="<?php _e("Upgrade", "gd-star-rating"); ?>" name="gdsr_upgrade_tool" id="gdsr_upgrade_tool" />
        <div class="gdsr-table-split"></div>
        <?php _e("Last cleanup was executed on", "gd-star-rating"); ?>: <strong><?php echo $gdsr_options['database_upgrade']; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Cleanup", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <input type="checkbox" name="gdsr_tools_clean_invalid_log" id="gdsr_tools_clean_invalid_log" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_invalid_log"><?php _e("Remove all invalid votes from votes log.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_tools_clean_invalid_trend" id="gdsr_tools_clean_invalid_trend" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_invalid_trend"><?php _e("Remove all invalid votes from trends log.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_tools_clean_old_posts" id="gdsr_tools_clean_old_posts" checked="checked" /><label style="margin-left: 5px;" for="gdsr_tools_clean_old_posts"><?php _e("Remove data for old and deleted posts and comments.", "gd-star-rating"); ?></label>
        <br />
        <input type="submit" class="inputbutton" value="<?php _e("Clean", "gd-star-rating"); ?>" name="gdsr_cleanup_tool" id="gdsr_cleanup_tool" />
        <div class="gdsr-table-split"></div>
        <?php _e("Last cleanup was executed on", "gd-star-rating"); ?>: <strong><?php echo $gdsr_options['database_cleanup']; ?></strong><br />
        <?php _e("Cleanup summary", "gd-star-rating"); ?>: <strong><?php echo $gdsr_options['database_cleanup_msg']; ?></strong>
        </form>
    </td>
</tr>
</tbody></table>
</div>
<div id="fragment-3">
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
</div>
<div id="fragment-4">
<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Cleanup", "gd-star-rating"); ?></th>
    <td>
        <form method="post">
        <?php _e("Delete all cached files from folder", "gd-star-rating"); ?> <strong>'/wp-content/gd-star-rating/cache/'</strong>:<br />
        <input type="submit" class="inputbutton" value="<?php _e("Clean", "gd-star-rating"); ?>" name="gdsr_cache_clean" id="gdsr_cache_clean" />
        <div class="gdsr-table-split"></div>
        <?php _e("Last cleanup was executed on", "gd-star-rating"); ?>: <strong><?php echo $options->cache_cleanup_last; ?></strong>
        </form>
    </td>
</tr>
<tr><th scope="row"><?php _e("Status", "gd-star-rating"); ?></th>
    <td>
        <?php _e("Total files cached", "gd-star-rating"); ?>: <strong><?php echo gdFunctions::get_folder_files_count( substr(STARRATING_CACHE_PATH, 0, strlen(STARRATING_CACHE_PATH) - 1) ); ?> files</strong>
        <br />
        <?php _e("Total size of cached files", "gd-star-rating"); ?>: <strong><?php echo gdFunctions::get_folder_size( substr(STARRATING_CACHE_PATH, 0, strlen(STARRATING_CACHE_PATH) - 1) ); ?> bytes</strong>
    </td>
</tr>
</tbody></table>
</div>
</div>

</div>
</div>