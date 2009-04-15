<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating Text", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_tpl_cmm_ratingtext" id="gdsr_tpl_cmm_ratingtext" value="<?php echo wp_specialchars($gdsr_options["cmm_rating_text"]); ?>" style="width: 670px" /><br />
        <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %CMM_RATING%, %MAX_CMM_RATING%, %CMM_VOTES%, %WORD_VOTES%
    </td>
</tr>
</tbody></table>
