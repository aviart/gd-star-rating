var gdsrCanceled = false;
function hideshowCmmInt() {
    var value = jQuery("#comment_parent").val();
    if (value == 0) {
        jQuery("#gdsr-cmm-integration-block-review").removeClass("cmminthide");
        jQuery("#gdsr-cmm-integration-block-standard").removeClass("cmminthide");
        jQuery("#gdsr-cmm-integration-block-multis").removeClass("cmminthide");
    } else {
        jQuery("#gdsr-cmm-integration-block-review").addClass("cmminthide");
        jQuery("#gdsr-cmm-integration-block-standard").addClass("cmminthide");
        jQuery("#gdsr-cmm-integration-block-multis").addClass("cmminthide");
    }
    if (!gdsrCanceled) {
        jQuery("#cancel-comment-reply-link").click(function() {
            hideshowCmmInt();
        });
        gdsrCanceled = true;
    } else {
        jQuery("#cancel-comment-reply-link").unbind("click");
        gdsrCanceled = false;
    }
}

jQuery(document).ready(function() {
    jQuery(".comment-reply-link").click(function() {
        hideshowCmmInt();
    });
});

function is_cmm_rated_multis() {
    var value = value_cmm_rated_multis();
    var rated = true;
    for (var i = 0; i < value.length; i++) {
        if (value[i] == 0) rated = false;
    }
    return rated;
}

function is_cmm_rated_standard() {
    return value_cmm_rated_standard() > 0;
}

function is_cmm_rated_review() {
    return value_cmm_rated_review() > 0;
}

function value_cmm_rated_multis() {
    var value = jQuery(".gdsr-mur-cls-rt").val();
    return value.split("X");
}

function value_cmm_rated_standard() {
    return jQuery(".gdsr-int-cls-rt").val();
}

function value_cmm_rated_review() {
    return jQuery(".gdsr-cmm-cls-rt").val();
}

