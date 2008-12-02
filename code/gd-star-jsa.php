jQuery(".jqloaderarticle").change(function() {
    var loadr = jQuery("#gdsr_wait_loader_article").val();
    var texts = jQuery("#gdsr_wait_text_article").val();
    var usetx = jQuery("#gdsr_wait_show_article").is(':checked');
    var class = jQuery("#gdsr_wait_class_article").val();
    if (usetx) {
        texts = '';
        loadr = loadr + ' width';
    }
    jQuery("#gdsrwaitpreviewarticle").removeClass();
    jQuery("#gdsrwaitpreviewarticle").addClass("wait-preview-holder-article loader "+loadr+" "+class);
    jQuery("#gdsrwaitpreviewarticle").html(texts);
});

jQuery(".jqloadercomment").change(function() {
    var loadr = jQuery("#gdsr_wait_loader_comment").val();
    var texts = jQuery("#gdsr_wait_text_comment").val();
    var usetx = jQuery("#gdsr_wait_show_comment").is(':checked');
    var class = jQuery("#gdsr_wait_class_comment").val();

    jQuery("#gdsrwaitpreviewcomment").removeClass();
    jQuery("#gdsrwaitpreviewcomment").addClass("wait-preview-holder-article loader "+loadr+" "+class);
});
