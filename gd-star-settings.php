<?php 

    if ($_POST['gdsr_action'] == 'save') {
        $gdsr_options["admin_width"] = $_POST['gdsr_admin_width'];
        $gdsr_options["admin_rows"] = $_POST['gdsr_admin_rows'];
        $gdsr_options["admin_advanced"] = isset($_POST['gdsr_admin_advanced']) ? 1 : 0;
        $gdsr_options["admin_placement"] = isset($_POST['gdsr_admin_placement']) ? 1 : 0;
        $gdsr_options["admin_defaults"] = isset($_POST['gdsr_admin_defaults']) ? 1 : 0;
        $gdsr_options["admin_category"] = isset($_POST['gdsr_admin_category']) ? 1 : 0;
        $gdsr_options["admin_users"] = isset($_POST['gdsr_admin_users']) ? 1 : 0;
        $gdsr_options["admin_import"] = isset($_POST['gdsr_admin_import']) ? 1 : 0;
        $gdsr_options["admin_export"] = isset($_POST['gdsr_admin_export']) ? 1 : 0;
        $gdsr_options["admin_setup"] = isset($_POST['gdsr_admin_setup']) ? 1 : 0;
        $gdsr_options["admin_ips"] = isset($_POST['gdsr_admin_ips']) ? 1 : 0;

        $gdsr_options["debug_active"] = isset($_POST['gdsr_debug_active']) ? 1 : 0;
        $gdsr_options["ajax"] = isset($_POST['gdsr_ajax']) ? 1 : 0;
        $gdsr_options["use_nonce"] = isset($_POST['gdsr_use_nonce']) ? 1 : 0;
        $gdsr_options["ip_filtering"] = isset($_POST['gdsr_ip_filtering']) ? 1 : 0;
        $gdsr_options["ip_filtering_restrictive"] = isset($_POST['gdsr_ip_filtering_restrictive']) ? 1 : 0;
        $gdsr_options["widget_articles"] = isset($_POST['gdsr_widget_articles']) ? 1 : 0;
        $gdsr_options["display_pages"] = isset($_POST['gdsr_pages']) ? 1 : 0;
        $gdsr_options["display_posts"] = isset($_POST['gdsr_posts']) ? 1 : 0;
        $gdsr_options["display_archive"] = isset($_POST['gdsr_archive']) ? 1 : 0;
        $gdsr_options["display_home"] = isset($_POST['gdsr_home']) ? 1 : 0;
        $gdsr_options["display_search"] = isset($_POST['gdsr_search']) ? 1 : 0;
        $gdsr_options["display_comment"] = isset($_POST['gdsr_dispcomment']) ? 1 : 0;
        $gdsr_options["moderation_active"] = isset($_POST['gdsr_modactive']) ? 1 : 0;
        $gdsr_options["multis_active"] = isset($_POST['gdsr_multis']) ? 1 : 0;
        $gdsr_options["timer_active"] = isset($_POST['gdsr_timer']) ? 1 : 0;
        $gdsr_options["save_user_agent"] = isset($_POST['gdsr_save_user_agent']) ? 1 : 0;
        $gdsr_options["save_cookies"] = isset($_POST['gdsr_save_cookies']) ? 1 : 0;
        $gdsr_options["ie_png_fix"] = isset($_POST['gdsr_iepngfix']) ? 1 : 0;
        
        $gdsr_options["preview_active"] = $_POST['gdsr_preview_stars'];
        $gdsr_options["preview_trends_active"] = $_POST['gdsr_preview_trends'];

        $gdsr_options["integrate_post_edit"] = isset($_POST['gdsr_integrate_post_edit']) ? 1 : 0;
        $gdsr_options["integrate_tinymce"] = isset($_POST['gdsr_integrate_tinymce']) ? 1 : 0;
        $gdsr_options["integrate_comment_edit"] = isset($_POST['gdsr_integrate_comment_edit']) ? 1 : 0;

        $gdsr_options["trend_last"] = $_POST['gdsr_trend_last'];
        $gdsr_options["trend_over"] = $_POST['gdsr_trend_over'];
        $gdsr_options["bayesian_minimal"] = $_POST['gdsr_bayesian_minimal'];
        $gdsr_options["bayesian_mean"] = $_POST['gdsr_bayesian_mean'];

        $gdsr_options["default_timer_type"] = $_POST['gdsr_default_timer_type'];
        $gdsr_options["default_timer_countdown_value"] = $_POST['default_timer_countdown_value'];
        $gdsr_options["default_timer_countdown_type"] = $_POST['default_timer_countdown_type'];
        $gdsr_options["default_timer_value"] = $_POST['default_timer_countdown_type'].$_POST['default_timer_countdown_value'];
        
        $gdsr_options["review_active"] = isset($_POST['gdsr_reviewactive']) ? 1 : 0;
        $gdsr_options["comments_active"] = isset($_POST['gdsr_commentsactive']) ? 1 : 0;
        $gdsr_options["comments_review_active"] = isset($_POST['gdsr_cmmreviewactive']) ? 1 : 0;
        $gdsr_options["hide_empty_rating"] = isset($_POST['gdsr_haderating']) ? 1 : 0;
        $gdsr_options["cookies"] = isset($_POST['gdsr_cookies']) ? 1 : 0;
        $gdsr_options["cmm_cookies"] = isset($_POST['gdsr_cmm_cookies']) ? 1 : 0;
        $gdsr_options["author_vote"] = isset($_POST['gdsr_authorvote']) ? 1 : 0;
        $gdsr_options["cmm_author_vote"] = isset($_POST['gdsr_cmm_authorvote']) ? 1 : 0;
        $gdsr_options["logged"] = isset($_POST['gdsr_logged']) ? 1 : 0;
        $gdsr_options["cmm_logged"] = isset($_POST['gdsr_cmm_logged']) ? 1 : 0;
        
        $gdsr_options["style"] = $_POST['gdsr_style'];
        $gdsr_options["size"] = $_POST['gdsr_size'];
        $gdsr_options["text"] = $_POST['gdsr_text'];
        $gdsr_options["align"] = $_POST['gdsr_align'];
        $gdsr_options["header"] = isset($_POST['gdsr_header']) ? 1 : 0;
        $gdsr_options["header_text"] = stripslashes(htmlentities($_POST['gdsr_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["class_block"] = $_POST['gdsr_classblock'];
        $gdsr_options["class_text"] = $_POST['gdsr_classtext'];
        
        $gdsr_options["cmm_style"] = $_POST['gdsr_cmm_style'];
        $gdsr_options["cmm_size"] = $_POST['gdsr_cmm_size'];
        $gdsr_options["cmm_text"] = $_POST['gdsr_cmm_text'];
        $gdsr_options["cmm_align"] = $_POST['gdsr_cmm_align'];
        $gdsr_options["cmm_header"] = isset($_POST['gdsr_cmm_header']) ? 1 : 0;
        $gdsr_options["cmm_header_text"] = stripslashes(htmlentities($_POST['gdsr_cmm_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["cmm_class_block"] = $_POST['gdsr_cmm_classblock'];
        $gdsr_options["cmm_class_text"] = $_POST['gdsr_cmm_classtext'];
        
        $gdsr_options["review_style"] = $_POST['gdsr_review_style'];
        $gdsr_options["review_size"] = $_POST['gdsr_review_size'];
        $gdsr_options["review_text"] = $_POST['gdsr_review_text'];
        $gdsr_options["review_align"] = $_POST['gdsr_review_align'];
        $gdsr_options["review_header"] = isset($_POST['gdsr_review_header']) ? 1 : 0;

        $gdsr_options["review_header_text"] = stripslashes(htmlentities($_POST['gdsr_review_header_text'], ENT_QUOTES, 'UTF-8'));
        $gdsr_options["review_class_block"] = $_POST['gdsr_review_classblock'];
                           
        $gdsr_options["cmm_review_style"] = $_POST['gdsr_cmm_review_style'];
        $gdsr_options["cmm_review_size"] = $_POST['gdsr_cmm_review_size'];
                           
        $gdsr_options["default_voterules_articles"] = $_POST['gdsr_default_vote_articles'];
        $gdsr_options["default_voterules_comments"] = $_POST['gdsr_default_vote_comments'];
        $gdsr_options["default_moderation_articles"] = $_POST['gdsr_default_mod_articles'];
        $gdsr_options["default_moderation_comments"] = $_POST['gdsr_default_mod_comments'];
        
        update_option("gd-star-rating", $gdsr_options);
    }
    
?>
