<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating Text", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_tpl_mur_ratingtext" id="gdsr_tpl_mur_ratingtext" value="<?php echo wp_specialchars($gdsr_options["multis_rating_text"]); ?>" style="width: 670px" /><br />
        <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%, %ID%
    </td>
</tr>
<tr><th scope="row"><?php _e("Rating Text For Time Restricted Voting", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100" valign="top"><?php _e("Voting Active", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_mur_time_active" id="gdsr_mur_time_active" value="<?php echo wp_specialchars($gdsr_options["multis_time_restricted_active"]); ?>" style="width: 570px" /><br />
                    <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%, %ID% <?php _e("and time restriction elements", "gd-star-rating"); ?></td>
            <tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100" valign="top"><?php _e("Voting Closed", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_mur_time_closed" id="gdsr_mur_time_closed" value="<?php echo wp_specialchars($gdsr_options["multis_time_restricted_closed"]); ?>" style="width: 570px" /><br />
                    <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%, %ID%</td>
            <tr>
        </table>
    </td>
</tr>
</tbody></table>
