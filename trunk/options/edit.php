    <script>
    function gdsrTimerChange() {
        var timer = jQuery("#gdsr_default_timer_type").val();
        jQuery("#gdsr_timer_date").css("display", "none");
        jQuery("#gdsr_timer_countdown").css("display", "none");
        if (timer == "D") jQuery("#gdsr_timer_date").css("display", "block");
        if (timer == "T") jQuery("#gdsr_timer_countdown").css("display", "block");
    }
    </script>
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
    <div class="gdsr-table-split-edit"></div>
    <table width="100%"><tr>
    <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Vote Rule", "gd-star-rating"); ?>:</label></td>
    <td align="right" style="height: 25px;" valign="baseline">
    <?php GDSRHelper::render_rules_combo("gdsr_default_vote_articles", $vote_rules, 110); ?>
    </td>
    <?php if ($gdsr_options["moderation_active"] == 1) { ?>
    </tr><tr>
    <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Moderate", "gd-star-rating"); ?>:</label></td>
    <td align="right" style="height: 25px;" valign="baseline">
    <?php GDSRHelper::render_moderation_combo("gdsr_default_mod_articles", $moderation_rules, 110); ?>
    </td>
    <?php } ?>
    </tr></table>
    <?php if ($gdsr_options["timer_active"] == 1) { ?>
    <div class="gdsr-table-split-edit"></div>
    <table width="100%"><tr>
    <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Restriction", "gd-star-rating"); ?>:</label></td>
    <td align="right" style="height: 25px;" valign="baseline">
    <?php GDSRHelper::render_timer_combo("gdsr_default_timer_type", $timer_restrictions, 110, '', false, 'gdsrTimerChange()'); ?>
    </td>
    </tr></table>
    <div id="gdsr_timer_date" style="display: <?php echo $timer_restrictions == "D" ? "block" : "none" ?>">
        <table width="100%"><tr>
        <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Date", "gd-star-rating"); ?>:</label></td>
        <td align="right" style="height: 25px;" valign="baseline">
            <input type="text" value="" id="gdsr_timer_date_value" name="gdsr_timer_date_value" style="width: 100px; padding: 2px;" />
        </td>
        </tr></table>
    </div>
    <div id="gdsr_timer_countdown" style="display: <?php echo $timer_restrictions == "T" ? "block" : "none" ?>">
        <table width="100%"><tr>
        <td style="height: 25px;"><label style="font-size: 12px;" for="gdsr_review"><?php _e("Countdown", "gd-star-rating"); ?>:</label></td>
        <td align="right" style="height: 25px;" valign="baseline">
            <input type="text" value="" id="gdsr_timer_countdown_value" name="gdsr_timer_countdown_value" style="width: 35px; text-align: right; padding: 2px;" />
            <?php GDSRHelper::render_countdown_combo("gdsr_timer_countdown_type", $gdsr_options["default_timer_countdown_type"], 60); ?>
        </td>
        </tr></table>
    </div>
    <?php } ?>