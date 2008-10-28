<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating Text", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_tpl_ratingtext" id="gdsr_tpl_ratingtext" value="<?php echo wp_specialchars($gdsr_options["article_rating_text"]); ?>" style="width: 670px" /><br />
        <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%
    </td>
</tr>
<tr><th scope="row"><?php _e("Rating Text For Time Restricted Voting", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100" valign="top"><?php _e("Voting Active", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_tpl_srheader" id="gdsr_tpl_srheader" value="<?php echo wp_specialchars($gdsr_options["shortcode_starrating_header"]); ?>" style="width: 570px" /><br />
                    <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> <?php _e("all articles, custom and time restriction templates elements", "gd-star-rating"); ?></td>
            <tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100" valign="top"><?php _e("Voting Closed", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_tpl_sritem" id="gdsr_tpl_sritem" value="<?php echo wp_specialchars($gdsr_options["shortcode_starrating_item"]); ?>" style="width: 570px" /><br />
                    <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> <?php _e("all articles and customtemplates elements", "gd-star-rating"); ?></td>
            <tr>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Shortcode StarRating", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100" valign="top"><?php _e("Header", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_tpl_srheader" id="gdsr_tpl_srheader" value="<?php echo wp_specialchars($gdsr_options["shortcode_starrating_header"]); ?>" style="width: 570px" /></td>
            <tr>
            <tr>
                <td width="100" valign="top"><?php _e("Item", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_tpl_sritem" id="gdsr_tpl_sritem" value="<?php echo wp_specialchars($gdsr_options["shortcode_starrating_item"]); ?>" style="width: 570px" /><br />
                    <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> <?php _e("all articles and custom templates elements", "gd-star-rating"); ?></td>
            <tr>
            <tr>
                <td width="100" valign="top"><?php _e("Footer", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_tpl_srfooter" id="gdsr_tpl_srfooter" value="<?php echo wp_specialchars($gdsr_options["shortcode_starrating_footer"]); ?>" style="width: 570px" /></td>
            <tr>
        </table>
    </td>
</tr>
</tbody></table>
