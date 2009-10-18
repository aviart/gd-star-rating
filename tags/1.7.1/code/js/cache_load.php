<?php if ($gdsr->o["cached_loading"] == 1) { ?>

jQuery(document).ready(function() {
    var ela = "";
    var elc = "";
    jQuery(".gdsrcacheloader").each(function(i) {
        el = jQuery(this).attr("id").substring(6);
        if (el.substring(0, 1) == "a") ela+= el + ":";
        else elc+= el + ":";
    });

    if (ela.length > 0) {
        ela = ela.substring(0, ela.length - 1);
        jQuery.getJSON('<?php echo STARRATING_AJAX_URL; ?>', {<?php echo $nonce; ?>vote_type: 'cache', vote_domain: 'a', votes: ela}, function(json) {
            for (var i = 0; i < json.items.length; i++) {
                var item = json.items[i];
                jQuery(jquery_escape_id(item.id)).replaceWith(item.html);
            }

            if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
            if (jQuery.browser.msie) jQuery(".gdthumb > a").attr("href", "javascript:gdsrEmpty()");
            if (jQuery.browser.msie) jQuery(".gdsr_multisbutton_as > a").attr("href", "javascript:gdsrEmpty()");
            if (jQuery.browser.msie) jQuery(".gdsr_multis_as > a").attr("href", "javascript:gdsrEmpty()");

            jQuery(".gdsr_rating_as > a").unbind("click");
            jQuery(".gdsr_rating_as > a").click(function() { gdsr_rating_standard(this); });
            jQuery(".gdthumb > a").unbind("click");
            jQuery(".gdthumb > a").click(function() { gdsr_rating_thumb(this); });

            jQuery(".gdsr_multisbutton_as > a").unbind("click");
            jQuery(".gdsr_multisbutton_as > a").click(function() { gdsr_rating_multi_button(this); });
            jQuery(".gdsr_multis_as > a").unbind("click");
            jQuery(".gdsr_multis_as > a").click(function() { gdsr_rating_multi_stars(this); });
        });
    }

    if (elc.length > 0) {
        elc = elc.substring(0, elc.length - 1);
        jQuery.getJSON('<?php echo STARRATING_AJAX_URL; ?>', {<?php echo $nonce; ?>vote_type: 'cache', vote_domain: 'c', votes: elc}, function(json) {
            for (var i = 0; i < json.items.length; i++) {
                var item = json.items[i];
                jQuery(jquery_escape_id(item.id)).replaceWith(item.html);
            }

            if (jQuery.browser.msie) jQuery(".gdsr_rating_as > a").attr("href", "javascript:gdsrEmpty()");
            if (jQuery.browser.msie) jQuery(".gdthumb > a").attr("href", "javascript:gdsrEmpty()");

            jQuery(".gdsr_rating_as > a").unbind("click");
            jQuery(".gdsr_rating_as > a").click(function() { gdsr_rating_standard(this); });
            jQuery(".gdthumb > a").unbind("click");
            jQuery(".gdthumb > a").click(function() { gdsr_rating_thumb(this); });
        });
    }
});

<?php } ?>