function gdsrEmpty() { }

jQuery(document).ready(function() {
    if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_rating_as > a").click(function() {
            var el = jQuery(this).attr("id").split("X");
            gdsrWait(el[5], el[6]);
            jQuery.getJSON('<?php echo STARRATING_URL; ?>gd-star-ajax.php', {vote_id: el[1], vote_value: el[2], vote_user: el[3], vote_type: el[4] }, function(json) {
                gdsrWait(el[6], el[5]);
                jQuery("#gdr_stars_" + el[1]).html("");
                jQuery("#gdr_vote_" + el[1]).css("width", json.value);
                jQuery("#gdr_text_" + el[1]).addClass("voted");
                jQuery("#gdr_text_" + el[1]).html(json.rater);
            });
    });
});