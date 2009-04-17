<?php

class GDSRDefaults {
    var $shortcode_builtin_classes = array(
        array("name" => "Standard", "class" => "starrating"),
        array("name" => "Simple", "class" => "starsimple")
    );

    var $shortcodes = array(
        "starrating",
        "starreview",
        "starrater",
        "starratingblock",
        "starratercustom",
        "starratingmulti",
        "starreviewmulti",
        "starcomments"
    );

    var $default_spiders = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler");
    
    var $default_wpr8 = array(
        "web_key" => ""
    );

    var $default_options = array(
        "version" => "1.1.8",
        "date" => "2009.03.26.",
        "status" => "Stable",
        "build" => 518,
        "external_javascript" => 0,
        "external_rating_css" => 1,
        "external_css" => 1,
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
        "rss_text" => 'bottom',
        "rss_render" => 'both',
        "rss_header" => 0,
        "rss_header_text" => '',
        "style" => 'oxygen',
        "style_ie6" => 'oxygen_gif',
        "size" => 30,
        "stars" => 10,
        "text" => 'bottom',
        "align" => 'none',
        "header" => 0,
        "header_text" => '',
        "class_block" => '',
        "class_text" => '',
        "mur_style" => 'oxygen',
        "mur_style_ie6" => 'oxygen_gif',
        "mur_size" => 20,
        "mur_header" => 0,
        "mur_header_text" => '',
        "mur_class_block" => '',
        "mur_class_text" => '',
        "mur_class_table" => '',
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
        "cmm_header" => 0,
        "cmm_header_text" => '',
        "cmm_class_block" => '',
        "cmm_class_text" => '',
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
        "review_align" => 'none',
        "review_header" => 0,
        "review_header_text" => '',
        "review_class_block" => '',
        "review_class_text" => '',
        "display_comment" => 1,
        "display_comment_page" => 0,
        "display_posts" => 1,
        "display_pages" => 1,
        "display_home" => 1,
        "display_archive" => 1,
        "display_search" => 1,
        "cookies" => 1,
        "logged" => 1,
        "cmm_cookies" => 1,
        "cmm_logged" => 1,
        "admin_width" => 1240,
        "admin_rows" => 20,
        "admin_advanced" => 0,
        "admin_placement" => 0,
        "admin_defaults" => 0,
        "admin_category" => 0,
        "admin_users" => 0,
        "admin_import" => 1,
        "admin_export" => 1,
        "admin_setup" => 1,
        "admin_ips" => 0,
        "author_vote" => 0,
        "cmm_author_vote" => 0,
        "allow_mixed_ip_votes" => 0,
        "cmm_allow_mixed_ip_votes" => 0,
        "mur_allow_mixed_ip_votes" => 0,
        "default_moderation_articles" => 'N',
        "default_moderation_comments" => 'N',
        "default_voterules_articles" => 'A',
        "default_voterules_comments" => 'A',
        "default_timer_type" => 'N',
        "default_timer_countdown_value" => 30,
        "default_timer_countdown_type" => 'D',
        "default_timer_value" => 'D30',
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

    var $default_import = array(
        "post_star_rating" => 0,
        "wp_post_ratings" => 0,
        "star_rating_for_reviews" => 0,
    );

    var $default_shortcode_starcomments = array(
        "post" => 0,
        "show" => "total"
    );

    var $default_widget_comments = array(
        "title" => "Blog Rating",
        "display" => "all",
        "rows" => 5,
        "min_votes" => 3,
        "column" => "rating",
        "order" => "desc",
        "hide_empty" => 1
    );

    var $default_widget_top = array(
        "title" => "Blog Rating",
        "display" => "all",
        "template" => "&lt;p class=&quot;trw-title&quot;&gt;Average blog rating:&lt;/p&gt;&lt;p class=&quot;trw-rating&quot;&gt;%RATING%&lt;/p&gt;&lt;p class=&quot;trw-footer&quot;&gt;&lt;strong&gt;%VOTES%&lt;/strong&gt; %WORD_VOTES% cast for &lt;strong&gt;%COUNT%&lt;/strong&gt; posts&lt;/p&gt;",
        "div_template" => '0',
        "div_filter" => '0',
        "div_elements" => '0',
        "select" => "postpage",
        "show" => "total"
    );

    var $default_widget = array(
        "title" => "Rating",
        "display" => "all",
        "rows" => 10,
        "min_votes" => 5,
        "select" => "postpage",
        "column" => "rating",
        "order" => "desc",
        "category" => 0,
        "show" => "total",
        "hide_empty" => 1,
        "hide_noreview" => 0,
        "tpl_header" => '&lt;ul&gt;',
        "tpl_item" => '&lt;li&gt;&lt;strong&gt;%RATING%&lt;/strong&gt; : &lt;a href=&quot;%PERMALINK%&quot;&gt;%TITLE%&lt;/a&gt; (%VOTES% %WORD_VOTES%)&lt;/li&gt;',
        "tpl_footer" => '&lt;/ul&gt;',
        "tpl_title_length" => 0,
        "tpl_encoded" => 1,
        "publish_date" => 'lastd',
        "publish_days" => 30,
        "publish_month" => '200808',
        "publish_range_from" => "YYYYMMDD",
        "publish_range_to" => "YYYYMMDD",
        "div_template" => '0',
        "div_filter" => '0',
        "div_trend" => '0',
        "div_elements" => '0',
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
        "bayesian_calculation" => '0'
    );

    var $default_shortcode_starratercustom = array(
        'allow_voting' => 0,
        'rating_header' => 0,
        'rating_text' => 0
    );

    var $default_shortcode_starratingmulti = array(
        'id' => 1,
        'read_only' => 0
    );

    var $default_shortcode_starreviewmulti = array(
        'id' => 1
    );

    var $default_templates = array(
        "word_votes_singular" => "vote",
        "word_votes_plural" => "votes",
        "table_row_even" => "even",
        "table_row_odd" => "odd",
        "rss_article_rating_text" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
        "multis_rating_text" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
        "article_rating_text" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
        "cmm_rating_text" => "Rating: %CMM_RATING%/&lt;strong&gt;%MAX_CMM_RATING%&lt;/strong&gt; (%CMM_VOTES% %WORD_VOTES% cast)",
        "shortcode_starrating_header" => "&lt;table&gt;&lt;thead&gt;&lt;td class=&quot;title&quot;&gt;Title&lt;/td&gt;&lt;td class=&quot;votes&quot;&gt;Votes&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;Rating&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;Review&lt;/td&gt;&lt;/thead&gt;&lt;tbody&gt;",
        "shortcode_starrating_item" => "&lt;tr class=&quot;%TABLE_ROW_CLASS%&quot;&gt;&lt;td class=&quot;title&quot;&gt;%RATE_TREND%&lt;a href=&quot;%PERMALINK%&quot;&gt;%TITLE%&lt;/a&gt;&lt;/td&gt;&lt;td class=&quot;votes&quot;&gt;%VOTES%&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;%RATING%&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;%REVIEW%&lt;/td&gt;&lt;/tr&gt;",
        "shortcode_starrating_footer" => "&lt;/tbody&gt;&lt;/table&gt;",
        "multis_time_restricted_active" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)<br />%TR_MONTHS% months, %TR_DAYS% days, %TR_HOURS% hours, %TR_MINUTES% minutes remaining.",
        "multis_time_restricted_closed" => "&lt;strong&gt;Voting Closed.&lt;/strong&gt; Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
        "time_restricted_active" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)<br />%TR_MONTHS% months, %TR_DAYS% days, %TR_HOURS% hours, %TR_MINUTES% minutes remaining.",
        "time_restricted_closed" => "&lt;strong&gt;Voting Closed.&lt;/strong&gt; Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)"
    );

    var $default_shortcode_starrating = array(
        'rows' => 10,
        "min_votes" => 5,
        'select' => 'postpage',
        'column' => 'rating',
        'order' => 'desc',
        'category' => 0,
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
        'rating_stars' => 'oxygen',
        'rating_size' => 20,
        'review_stars' => 'oxygen',
        'review_size' => 20
    );

    function GDSRDefaults() { }
}

?>