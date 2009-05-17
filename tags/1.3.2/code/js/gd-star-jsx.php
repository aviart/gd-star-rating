    if (jQuery.browser.msie) jQuery(".gdsr_review_as > a").attr("href", "javascript:gdsrEmpty()");
    jQuery(".gdsr_integration > a").click(function() {
        var el = jQuery(this).attr("id").split("X");
        var pid = "#" + jQuery(this).parent().attr("id");
        var new_width = el[1] * el[2];
        jQuery(pid + "_stars_rated").css("width", new_width + "px");
        jQuery(pid + "_value").val(el[1]);
    });
