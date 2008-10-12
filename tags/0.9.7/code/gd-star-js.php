function gdsrEmpty() { }

jQuery(document).ready(function() {
    if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_rating_as > a").click(function() {
            var el = jQuery(this).attr("id").split("|");
            gdsrWait(el[4], el[5]);
            jQuery.getJSON('<?php echo STARRATING_URL; ?>gd-star-ajax.php', {vote_id: el[0], vote_value: el[1], vote_user: el[2], vote_type: el[3] }, function(json) {
                gdsrWait(el[5], el[4]);
                jQuery("#gdr_stars_" + el[0]).html("");
                jQuery("#gdr_vote_" + el[0]).css("width", json.value);
                jQuery("#gdr_text_" + el[0]).addClass("voted");
                jQuery("#gdr_text_" + el[0]).html(json.rater);
            });
    });
});