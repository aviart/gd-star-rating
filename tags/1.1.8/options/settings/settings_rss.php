<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Posts", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Stars", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_rss_style" id="gdsr_rss_style">
                <?php GDSRHelper::render_styles_select($gdsr_gfx->stars, $gdsr_options["rss_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_star_sizes("gdsr_rss_size", $gdsr_options["rss_size"]); ?>
                </td>
            </tr>
        </table>
        <?php if ($gdsr_options["admin_placement"] == 1) { ?>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Render", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_rss_render("gdsr_rss_render", $gdsr_options["rss_render"]); ?>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Rating text placement", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <?php GDSRHelper::render_placement("gdsr_rss_text", $gdsr_options["rss_text"]); ?>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating header", "gd-star-rating"); ?>:</td>
                <td width="200">
                    <input type="checkbox" name="gdsr_rss_header" id="gdsr_rss_header"<?php if ($gdsr_options["rss_header"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_rss_header"><?php _e("Display header text.", "gd-star-rating"); ?></label>
                </td>
                <td width="10"></td>
                <td width="150"><?php _e("Text to display", "gd-star-rating"); ?>:</td>
                <td>
                    <input type="text" name="gdsr_rss_header_text" id="gdsr_rss_header_text" value="<?php echo wp_specialchars($gdsr_options["rss_header_text"]); ?>" style="width: 170px" />
                </td>
            </tr>
        </table>
        <?php } ?>
    </td>
</tr>
</tbody></table>
