<?php

class GDSRDefaults {
    var $default_options = array(
        "version" => "1.3.3",
        "date" => "2009.05.19.",
        "status" => "Stable",
        "build" => 666,
        "external_javascript" => 0,
        "external_rating_css" => 1,
        "external_css" => 1,
        "widgets_hidempty" => 0,
        "encoding" => "UTF-8",
        "news_feed_active" => 1,
        "gfx_generator_auto" => 0,
        "gfx_prevent_leeching" => 1,
        "cache_active" => 1,
        "cache_cleanup_auto" => 0,
        "cache_cleanup_days" => 7,
        "cache_cleanup_counter" => 0,
        "cache_cleanup_last" => 0,
        "debug_active" => 0,
        "debug_inline" => 1,
        "database_upgrade" => '',
        "database_cleanup" => '',
        "database_cleanup_msg" => '',
        "mass_lock" => '',
        "ie_opacity_fix" => 1,
        "ip_filtering" => 1,
        "ip_filtering_restrictive" => 0,
        "save_user_agent" => 0,
        "save_cookies" => 1,
        "widget_articles" => 1,
        "widget_top" => 1,
        "widget_comments" => 1,
        "integrate_post_edit" => 1,
        "integrate_post_edit_mur" => 1,
        "integrate_tinymce" => 1,
        "integrate_comment_edit" => 1,
        "integrate_dashboard" => 1,
        "integrate_rss_powered" => 0,
        "moderation_active" => 1,
        "multis_active" => 1,
        "rss_active" => 0,
        "review_active" => 1,
        "timer_active" => 1,
        "comments_active" => 1,
        "comments_review_active" => 1,
        "rss_style" => 'oxygen',
        "rss_size" => 20,
        "rss_header_text" => '',
        "style" => 'oxygen',
        "style_ie6" => 'oxygen_gif',
        "size" => 30,
        "stars" => 10,
        "text" => 'bottom',
        "align" => 'none',
        "header_text" => '',
        "srb_class_block" => '',
        "srb_class_text" => '',
        "srb_class_header" => '',
        "srb_class_stars" => '',
        "cmm_class_block" => '',
        "cmm_class_text" => '',
        "cmm_class_header" => '',
        "cmm_class_stars" => '',
        "default_srb_template" => 0,
        "default_crb_template" => 0,
        "default_ssb_template" => 0,
        "default_mrb_template" => 0,
        "mur_style" => 'oxygen',
        "mur_style_ie6" => 'oxygen_gif',
        "mur_size" => 20,
        "mur_header" => 0,
        "mur_header_text" => '',
        "mur_class_block" => '',
        "mur_class_text" => '',
        "mur_class_header" => '',
        "mur_class_button" => '',
        "mur_button_text" => 'Submit',
        "mur_button_active" => 1,
        "mur_review_set" => 0,
        "cmm_style" => 'oxygen',
        "cmm_style_ie6" => 'oxygen_gif',
        "cmm_size" => 12,
        "cmm_stars" => 5,
        "cmm_text" => 'bottom',
        "cmm_align" => 'none',
        "cmm_header_text" => '',
        "cmm_review_style" => 'oxygen',
        "cmm_review_style_ie6" => 'oxygen_gif',
        "cmm_review_size" => 20,
        "cmm_review_stars" => 5,
        "cmm_aggr_style" => 'oxygen',
        "cmm_aggr_style_ie6" => 'oxygen_gif',
        "cmm_aggr_size" => 12,
        "review_style" => 'oxygen',
        "review_style_ie6" => 'oxygen_gif',
        "review_size" => 20,
        "review_stars" => 5,
        "review_header_text" => '',
        "review_class_block" => '',
        "display_comment" => 1,
        "display_comment_page" => 0,
        "display_posts" => 1,
        "display_pages" => 1,
        "display_home" => 1,
        "display_archive" => 1,
        "display_search" => 1,
        "auto_display_position" => "bottom",
        "auto_display_comment_position" => "bottom",
        "cookies" => 1,
        "logged" => 1,
        "cmm_cookies" => 1,
        "cmm_logged" => 1,
        "admin_width" => 1240,
        "admin_rows" => 20,
        "admin_advanced" => 1,
        "admin_placement" => 1,
        "admin_defaults" => 1,
        "admin_category" => 1,
        "admin_users" => 1,
        "admin_import" => 1,
        "admin_setup" => 1,
        "admin_ips" => 0,
        "author_vote" => 0,
        "cmm_author_vote" => 0,
        "allow_mixed_ip_votes" => 0,
        "cmm_allow_mixed_ip_votes" => 0,
        "mur_allow_mixed_ip_votes" => 0,
        "default_moderation_multis" => 'N',
        "default_moderation_articles" => 'N',
        "default_moderation_comments" => 'N',
        "default_voterules_multis" => 'A',
        "default_voterules_articles" => 'A',
        "default_voterules_comments" => 'A',
        "default_timer_type" => 'N',
        "default_timer_countdown_value" => 30,
        "default_timer_countdown_type" => 'D',
        "default_timer_value" => 'D30',
        "default_mur_timer_type" => 'N',
        "default_mur_timer_countdown_value" => 30,
        "default_mur_timer_countdown_type" => 'D',
        "default_mur_timer_value" => 'D30',
        "stats_trend_history" => 30,
        "stats_trend_current" => 3,
        "trend_last" => 1,
        "trend_over" => 30,
        "bayesian_minimal" => 10,
        "bayesian_mean" => 70,
        "use_nonce" => 1,
        "wait_text_article" => 'please wait...',
        "wait_loader_article" => 'flower',
        "wait_show_article" => 0,
        "wait_class_article" => '',
        "wait_text_comment" => 'please wait...',
        "wait_loader_comment" => 'flower',
        "wait_show_comment" => 0,
        "wait_class_comment" => '',
        "wait_text_multis" => 'please wait...',
        "wait_loader_multis" => 'flower',
        "wait_show_multis" => 0,
        "wait_class_multis" => ''
    );

    var $stars_sizes = array("12" => 1, "16" => 1, "20" => 1, "24" => 1, "30" => 1, "46" => 1);

    var $shortcodes = array(
        "starrating",
        "starreview",
        "starrater",
        "starratingblock",
        "starratingmulti",
        "starreviewmulti",
        "starcomments"
    );

    var $default_spiders = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler");

    var $default_wpr8 = array(
        "web_key" => ""
    );

    var $default_import = array(
        "post_star_rating" => 0,
        "wp_post_ratings" => 0,
        "star_rating_for_reviews" => 0,
    );

    var $default_widget_comments = array(
        "title" => "Comments Rating",
        "display" => "all",
        "template_id" => 0,
        "rows" => 5,
        "min_votes" => 3,
        "select" => "post",
        "column" => "rating",
        "order" => "desc",
        "hide_empty" => 1,
        "div_filter" => 0,
        "div_image" => 0,
        "div_stars" => 0,
        "text_max" => 50,
        "rating_stars" => "oxygen",
        "rating_size" => 12,
        "last_voted_days" => 0,
        "avatar" => 40
    );

    var $default_widget_top = array(
        "title" => "Blog Rating",
        "display" => "all",
        "template_id" => 0,
        "div_template" => '0',
        "div_filter" => '0',
        "div_elements" => '0',
        "select" => "postpage",
        "show" => "total"
    );

    var $default_widget = array(
        "title" => "Rating",
        "source" => "standard",
        "source_set" => 0,
        "template_id" => 0,
        "display" => "all",
        "display_categories" => "",
        "rows" => 10,
        "min_votes" => 5,
        "select" => "postpage",
        "column" => "rating",
        "order" => "desc",
        "category" => 0,
        "category_toponly" => 1,
        "categories" => "",
        "show" => "total",
        "hide_empty" => 1,
        "hide_noreview" => 0,
        "tpl_title_length" => 0,
        "tpl_encoded" => 1,
        "publish_date" => 'alldt',
        "publish_days" => 30,
        "publish_month" => '200808',
        "publish_range_from" => "YYYYMMDD",
        "publish_range_to" => "YYYYMMDD",
        "div_template" => '0',
        "div_filter" => '0',
        "div_trend" => '0',
        "div_elements" => '0',
        "div_image" => '0',
        "div_stars" => '0',
        "grouping" => 'post',
        "trends_rating" => 'txt',
        "trends_rating_rise" => '+',
        "trends_rating_same" => '=',
        "trends_rating_fall" => '-',
        "trends_rating_set" => 'famfamfam',
        "trends_voting" => 'txt',
        "trends_voting_rise" => '+',
        "trends_voting_same" => '=',
        "trends_voting_fall" => '-',
        "trends_voting_set" => 'famfamfam',
        "bayesian_calculation" => '0',
        "image_from" => 'none',
        "image_custom" => 'Image',
        "rating_stars" => "oxygen",
        "rating_size" => 20,
        "review_stars" => 'oxygen',
        "review_size" => 20,
        "last_voted_days" => 0
    );

    var $default_shortcode_starcomments = array(
        "post" => 0,
        "show" => "total",
        "tpl" => 0
    );

    var $default_shortcode_starreview = array(
        "post" => 0,
        "tpl" => 0
    );

    var $default_shortcode_starrater = array(
        "tpl" => 0,
        "read_only" => 0
    );

    var $default_shortcode_starratingmulti = array(
        'id' => 1,
        'tpl' => 0,
        'read_only' => 0,
        "average_stars" => "oxygen",
        "average_size" => 30
    );

    var $default_shortcode_starreviewmulti = array(
        'id' => 1,
        'tpl' => 0,
        "element_stars" => "oxygen",
        "element_size" => 20,
        "average_stars" => "oxygen",
        "average_size" => 30
    );

    var $default_shortcode_starrating = array(
        'template_id' => 0,
        'source' => 'standard',
        'source_set' => 0,
        'rows' => 10,
        'min_votes' => 5,
        'select' => 'postpage',
        'column' => 'rating',
        'order' => 'desc',
        'category' => 0,
        'category_toponly' => 1,
        'categories' => "",
        'show' => 'total',
        'hide_empty' => 1,
        'hide_noreview' => 0,
        'bayesian_calculation' => '0',
        'publish_date' => 'lastd',
        'publish_days' => 0,
        'publish_month' => '200808',
        'publish_range_from' => 'YYYYMMDD',
        'publish_range_to' => 'YYYYMMDD',
        'div_class' => '',
        'grouping' => 'post',
        'trends_rating' => 'txt',
        'trends_rating_rise' => '+',
        'trends_rating_same' => '=',
        'trends_rating_fall' => '-',
        'trends_rating_set' => 'famfamfam',
        'trends_voting' => 'txt',
        'trends_voting_rise' => '+',
        'trends_voting_same' => '=',
        'trends_voting_fall' => '-',
        'trends_voting_set' => 'famfamfam',
        'image_from' => 'none',
        'image_custom' => 'Image',
        'rating_stars' => 'oxygen',
        'rating_size' => 20,
        'review_stars' => 'oxygen',
        'review_size' => 20,
        'last_voted_days' => 0
    );

    function GDSRDefaults() { }
}

?>
