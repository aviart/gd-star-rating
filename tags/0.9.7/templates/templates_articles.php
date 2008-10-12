<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Rating Text", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_tpl_ratingtext" id="gdsr_tpl_ratingtext" value="<?php echo wp_specialchars($gdsr_options["article_rating_text"]); ?>" style="width: 670px" /><br />
        <strong>Allowed elements:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%
    </td>
</tr>
</tbody></table>
