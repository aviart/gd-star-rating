        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><strong>%WORD_VOTES%</strong></td>
                <td width="70">: <?php _e("singlular", "gd-star-rating"); ?>:</td>
                <td width="10"></td>
                <td width="150"><input type="text" name="gdsr_word_votessingular" id="gdsr_word_votessingular" value="<?php echo wp_specialchars($gdsr_options["word_votes_singular"]); ?>" style="width: 120px" /></td>
                <td width="70"><?php _e("plural", "gd-star-rating"); ?>:</td>
                <td width="10"></td>
                <td width="150"><input type="text" name="gdsr_word_votesplural" id="gdsr_word_votesplural" value="<?php echo wp_specialchars($gdsr_options["word_votes_plural"]); ?>" style="width: 120px" /></td>
            </tr>
            <tr>
                <td width="150"></td>
                <td colspan="6"><?php _e("this can be used to replace word 'votes', and you need to set both singular and plural form of this word", "gd-star-rating"); ?></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150"><strong>%TABLE_ROW_CLASS%</strong></td>
                <td width="70">: <?php _e("even row", "gd-star-rating"); ?>:</td>
                <td width="10"></td>
                <td width="150"><input type="text" name="gdsr_tablerow_even" id="gdsr_tablerow_even" value="<?php echo wp_specialchars($gdsr_options["table_row_even"]); ?>" style="width: 120px" /></td>
                <td width="70"><?php _e("odd row", "gd-star-rating"); ?>:</td>
                <td width="10"></td>
                <td width="150"><input type="text" name="gdsr_tablerow_odd" id="gdsr_tablerow_odd" value="<?php echo wp_specialchars($gdsr_options["table_row_od"]); ?>" style="width: 120px" /></td>
            </tr>
            <tr>
                <td width="150"></td>
                <td colspan="6"><?php _e("this can be used to render table rows used in templates with different classes", "gd-star-rating"); ?></td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
