<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Google Rich Snippets", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_grs" id="gdsr_grs"<?php if ($gdsr_options["google_rich_snippets_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_grs"><?php _e("Embed Google Rich Snippets.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Data source", "gd-star-rating"); ?>:</td>
                <td align="left">
                <select style="width: 180px;" name="gdsr_grs_datasource" id="gdsr_grs_datasource">
                    <option value="standard_rating"<?php echo $gdsr_options['google_rich_snippets_datasource'] == 'standard_rating' ? ' selected="selected"' : ''; ?>><?php _e("Standard Rating", "gd-star-rating"); ?></option>
                    <option value="standard_review"<?php echo $gdsr_options['google_rich_snippets_datasource'] == 'standard_review' ? ' selected="selected"' : ''; ?>><?php _e("Standard Review", "gd-star-rating"); ?></option>
                    <option value="multis_rating"<?php echo $gdsr_options['google_rich_snippets_datasource'] == 'multis_rating' ? ' selected="selected"' : ''; ?>><?php _e("Multi Rating", "gd-star-rating"); ?></option>
                    <option value="multis_review"<?php echo $gdsr_options['google_rich_snippets_datasource'] == 'multis_review' ? ' selected="selected"' : ''; ?>><?php _e("Multi Review", "gd-star-rating"); ?></option>
                    <option value="thumbs"<?php echo $gdsr_options['google_rich_snippets_datasource'] == 'thumbs' ? ' selected="selected"' : ''; ?>><?php _e("Thumbs Rating", "gd-star-rating"); ?></option>
                </select>
                </td>
            </tr>
            <tr>
                <td width="150"><?php _e("Embed format", "gd-star-rating"); ?>:</td>
                <td align="left">
                <select style="width: 180px;" name="gdsr_grs_format" id="gdsr_grs_format">
                    <option value="microformat"<?php echo $gdsr_options['google_rich_snippets_format'] == 'microformat' ? ' selected="selected"' : ''; ?>><?php _e("Microformat", "gd-star-rating"); ?></option>
                    <!--<option value="rdf"<?php echo $gdsr_options['google_rich_snippets_format'] == 'rdf' ? ' selected="selected"' : ''; ?>><?php _e("RDF", "gd-star-rating"); ?></option>-->
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <?php _e("Thumbs ratings will be embeded as percentage ratings.", "gd-star-rating"); ?><br />
        <?php _e("If the post or page has no valid rating set, snippet code will not be embeded for that post or page.", "gd-star-rating"); ?>
    </td>
</tr>
<tr><th scope="row"><?php _e("RSS Feeds", "gd-star-rating"); ?></th>
    <td>
        <input type="checkbox" name="gdsr_integrate_rss_powered" id="gdsr_integrate_rss_powered"<?php if ($gdsr_options["integrate_rss_powered"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_integrate_rss_powered"><?php _e("Add small 80x15 badge to posts in RSS feed.", "gd-star-rating"); ?></label>
        <br />
        <input type="checkbox" name="gdsr_rss" id="gdsr_rss"<?php if ($gdsr_options["rss_active"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="gdsr_rss"><?php _e("Add ratings to posts in RSS feeds.", "gd-star-rating"); ?></label>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><?php _e("Data source", "gd-star-rating"); ?>:</td>
                <td align="left">
                <select style="width: 180px;" name="gdsr_rss_datasource" id="gdsr_rss_datasource">
                    <option value="standard"<?php echo $gdsr_options['rss_datasource'] == 'standard' ? ' selected="selected"' : ''; ?>><?php _e("Standard Rating", "gd-star-rating"); ?></option>
                    <option value="multis"<?php echo $gdsr_options['rss_datasource'] == 'multis' ? ' selected="selected"' : ''; ?>><?php _e("Multi Rating", "gd-star-rating"); ?></option>
                    <option value="thumbs"<?php echo $gdsr_options['rss_datasource'] == 'thumbs' ? ' selected="selected"' : ''; ?>><?php _e("Thumbs Rating", "gd-star-rating"); ?></option>
                </select>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
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
            <tr>
                <td width="150"><?php _e("Thumbs", "gd-star-rating"); ?>:</td>
                <td width="200" align="left">
                <select style="width: 180px;" name="gdsr_thumb_rss_style" id="gdsr_thumb_rss_style">
                <?php GDSRHelper::render_styles_select($gdsr_gfx->thumbs, $gdsr_options["thumb_rss_style"]); ?>
                </select>
                </td>
                <td width="10"></td>
                <td width="150" align="left">
                <?php GDSRHelper::render_thumbs_sizes("gdsr_thumb_rss_size", $gdsr_options["thumb_rss_size"]); ?>
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
                <td align="left"><?php gdTemplateHelper::render_templates_section("SSB", "gdsr_default_ssb_template", $gdsr_options["default_ssb_template"], 350); ?></td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>
