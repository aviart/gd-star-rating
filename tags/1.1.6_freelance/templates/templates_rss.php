<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Post Rating Text", "gd-star-rating"); ?></th>
    <td>
        <input type="text" name="gdsr_tpl_rss_ratingtext" id="gdsr_tpl_rss_ratingtext" value="<?php echo wp_specialchars($gdsr_options["rss_article_rating_text"]); ?>" style="width: 670px" /><br />
        <strong><?php _e("Allowed elements", "gd-star-rating"); ?>:</strong> %RATING%, %MAX_RATING%, %VOTES%, %WORD_VOTES%, %ID%
    </td>
</tr>
</tbody></table>
