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
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Rating Header", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_rss_header_text" id="gdsr_rss_header_text" value="<?php echo wp_specialchars($gdsr_options["rss_header_text"]); ?>" style="width: 350px" /></td>
            </tr>
            <tr>
                <td width="150"><?php _e("Rating Template", "gd-star-rating"); ?>:</td>
                <td align="left"><?php GDSRHelper::render_templates_section("SSB", "gdsr_default_ssb_template", $gdsr_options["default_ssb_template"], 350); ?></td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
