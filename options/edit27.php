<?php

if ($post_id == 0) {
    $rating_decimal = -1;
    $rating = -1;
}
else {
    $rating = GDSRDatabase::get_review($post_id);
    if ($rating != -1) {
        $rating = explode(".", strval($rating));
        $rating_decimal = intval($rating[1]);
        $rating = intval($rating[0]);
    }
}

?>

<div id="gd-star-rating" class="postbox">
<a class="togbox">+</a>
<h3><span class="hndle">GD Star Rating</span></h3>
<div class="inside" style="padding-top: 5px;">
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
</div>
</div>