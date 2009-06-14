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
