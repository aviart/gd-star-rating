<table class="form-table"><tbody>
<tr><th scope="row"><?php _e("Trend Calculations", "gd-star-rating"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="360"><?php _e("Calculate for last number of days", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_trend_last" id="gdsr_trend_last" value="<?php echo $gdsr_options["trend_last"]; ?>" style="width: 70px; text-align: right;" /></td>
            </tr>
            <tr>
                <td width="360"><?php _e("Calculate over how many days before", "gd-star-rating"); ?>:</td>
                <td><input type="text" name="gdsr_trend_over" id="gdsr_trend_over" value="<?php echo $gdsr_options["trend_over"]; ?>" style="width: 70px; text-align: right;" /> [0 to include comlete history]</td>
            </tr>
        </table>
    </td>
</tr>
<tr><th scope="row"><?php _e("Bayesian Estimate Mean", "gd-star-rating"); ?></th>
    <td>
    </td>
</tr>
</tbody></table>
