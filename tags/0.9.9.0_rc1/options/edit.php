    <table width="100%"><tr>
    <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Review", "gd-star-rating"); ?>:</label></td>
    <td align="right" style="height: 25px;" valign="baseline">
    <select style="width: 50px; text-align: right;" name="gdsr_review" id="gdsr_review">
        <option value="-1">/</option>
        <?php GDSRHelper::render_stars_select_full($rating, $gdsr_options["review_stars"], 0); ?>
    </select><span style="vertical-align: bottom;">.</span>
    <select id="gdsr_review_decimal" name="gdsr_review_decimal" style="width: 50px; text-align: right;">
        <option value="-1">/</option>
        <?php GDSRHelper::render_stars_select_full($rating_decimal, 9, 0); ?>
    </select>
    </td></tr></table>
