<?php

/*
Plugin Name: GD Star Rating
Plugin URI: http://wp.gdragon.info/plugin/gd-star-rating/
Description: Star Rating plugin allows you to set up rating system for pages and/or posts in your blog.
Version: 1.0.0
Author: Milan Petrovic
Author URI: http://wp.gdragon.info/
 
== Info ==

* change log is available in readme.txt
* roadmap list is available in readme.txt
  
== Copyright ==

Copyright 2008 Milan Petrovic (email : milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

require_once(dirname(__FILE__)."/code/gd-star-functions.php");
require_once(dirname(__FILE__)."/code/gd-star-render.php");
require_once(dirname(__FILE__)."/code/gd-star-dbone.php");
require_once(dirname(__FILE__)."/code/gd-star-dbtwo.php");
require_once(dirname(__FILE__)."/code/gd-star-dbx.php");
require_once(dirname(__FILE__)."/code/gd-star-gfx.php");
require_once(dirname(__FILE__)."/code/gd-star-import.php");

if (!class_exists('GDStarRating')) {
    class GDStarRating {
        var $log_file = "c:/gd_star_rating_log.txt";
        
        var $charting = false;
        var $admin_plugin = false;
        var $admin_plugin_page = '';

        var $active_wp_page;
        var $wp_version;
        var $vote_status;

        var $plugin_url;
        var $plugin_path;
        var $plugin_xtra_url;
        var $plugin_xtra_path;
        var $plugin_chart_url;
        var $plugin_chart_path;
        
        var $l;
        var $o;
        var $w;
        var $t;
        var $p;
        var $x;
        var $e;
        var $i;
        var $g;

        var $shortcode_builtin_classes = array(
            array("name" => "Standard", "class" => "starrating"),
            array("name" => "Simple", "class" => "starsimple")
        );

        var $shortcodes = array(
            "starrating",
			"starreview",
			"starrater",
            "starratingblock"
        );
        
        var $default_templates = array(
            "word_votes_singular" => "vote",
            "word_votes_plural" => "votes",
            "table_row_even" => "even",
            "table_row_odd" => "odd",
            "article_rating_text" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
            "cmm_rating_text" => "Rating: %CMM_RATING%/&lt;strong&gt;%MAX_CMM_RATING%&lt;/strong&gt; (%CMM_VOTES% %WORD_VOTES% cast)",
            "shortcode_starrating_header" => "&lt;table&gt;&lt;thead&gt;&lt;td class=&quot;title&quot;&gt;Title&lt;/td&gt;&lt;td class=&quot;votes&quot;&gt;Votes&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;Rating&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;Review&lt;/td&gt;&lt;/thead&gt;&lt;tbody&gt;",
            "shortcode_starrating_item" => "&lt;tr class=&quot;%TABLE_ROW_CLASS%&quot;&gt;&lt;td class=&quot;title&quot;&gt;%RATE_TREND%&lt;a href=&quot;%PERMALINK%&quot;&gt;%TITLE%&lt;/a&gt;&lt;/td&gt;&lt;td class=&quot;votes&quot;&gt;%VOTES%&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;%RATING%&lt;/td&gt;&lt;td class=&quot;rating&quot;&gt;%REVIEW%&lt;/td&gt;&lt;/tr&gt;",
            "shortcode_starrating_footer" => "&lt;/tbody&gt;&lt;/g phptable&gt;",
            "time_restricted_active" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)<br />%TR_DAYS% days, %TR_HOURS% hours, %TR_MINUTES% minutes remaining.",
            "time_restricted_closed" => "&lt;strong&gt;Voting Closed.&lt;/strong&gt; Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)"
        );
        
        var $default_options = array(
            "version" => "1.0.0",
            "date" => "2008.10.31.",
            "status" => "Stable",
            "ie_png_fix" => 1,
            "ajax" => 1,
            "widget_articles" => 1,
            "widget_top" => 1,
            "preview_active" => 1,
            "preview_trends_active" => 1,
            "moderation_active" => 1,
            "review_active" => 1,
            "timer_active" => 1,
            "comments_active" => 1,
            "style" => 'oxygen',
            "size" => 30,
            "stars" => 10,
            "text" => 'bottom',
            "align" => 'none',
            "header" => 0,
            "header_text" => '',
            "class_block" => '',
            "class_text" => '',
            "cmm_style" => 'oxygen',
            "cmm_size" => 12,
            "cmm_stars" => 5,
            "cmm_text" => 'bottom',
            "cmm_align" => 'none',
            "cmm_header" => 0,
            "cmm_header_text" => '',
            "cmm_class_block" => '',
            "cmm_class_text" => '',
            "review_style" => 'oxygen',
            "review_size" => 20,
            "review_stars" => 5,
            "review_align" => 'none',
            "review_header" => 0,
            "review_header_text" => '',
            "review_class_block" => '',
            "review_class_text" => '',
            "display_comment" => 1,
            "display_posts" => 1,
            "display_pages" => 1,
            "display_home" => 1,
            "display_archive" => 1,
            "display_search" => 1,
            "cookies" => 1,
            "cmm_cookies" => 1,
            "admin_width" => 1200,
            "admin_rows" => 20,
            "admin_advanced" => 0,
            "author_vote" => 1,
            "cmm_author_vote" => 1,
            "default_moderation_articles" => 'N',
            "default_moderation_comments" => 'N',
            "default_voterules_articles" => 'A',
            "default_voterules_comments" => 'A',
            "default_timer_type" => 'N',
            "default_timer_countdown_value" => 30,
            "default_timer_countdown_type" => 'D',
            "stats_trend_history" => 30,
            "stats_trend_current" => 3,
            "trend_last" => 1,
            "trend_over" => 30,
            "bayesian_minimal" => 10,
            "bayesian_mean" => 70
        );
        
        var $default_import = array(
            "post_star_rating" => 0,
            "wp_post_ratings" => 0
        );
        
        var $default_widget_top = array(
            "title" => "Top Rating",
            "display" => "all"
        );
            
        var $default_widget = array(
            "title" => "Rating",
            "display" => "all",
            "rows" => 10,
            "select" => "postpage",
            "column" => "rating",
            "order" => "desc",
            "category" => 0,
            "show" => "total",
            "hide_empty" => 1,
            "hide_noreview" => 0,
            "tpl_header" => '&lt;ul&gt;',
            "tpl_item" => '&lt;li&gt;&lt;strong&gt;%RATING%&lt;/strong&gt; : &lt;a href=&quot;%PERMALINK%&quot;&gt;%TITLE%&lt;/a&gt; (%VOTES% votes)&lt;/li&gt;',
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
        
        var $default_shortcode_starrating = array(
            'rows' => 10, 
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
        
        function GDStarRating() {
            $this->tabpage = "front";
            $this->active_wp_page();
            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
        }
        
        // shortcode
        function add_tinymce_button($buttons) {
            array_push($buttons, "separator", "StarRating");
            return $buttons;
        }

        function add_tinymce_plugin($plugin_array) {    
            $plugin_array['StarRating'] = $this->plugin_url.'tinymce3/plugin.js';
            return $plugin_array;
        }

        function shortcode_action($scode) {
            if (is_array($scode)) {
                $sc_name = $scode["name"];
                $sc_method = $scode["method"];
            }
            else {
                $sc_name = $scode;
                $sc_method = "shortcode_".$scode;
            }
            add_shortcode($sc_name, array(&$this, $sc_method));
        }
        
		function shortcode_starrater($atts = array()) {
            global $post, $userdata;
            return $this->render_article($post, $userdata);
		}

        function shortcode_starratingblock($atts = array()) {
            global $post, $userdata;
            return $this->render_article($post, $userdata);
        }
        
		function shortcode_starreview($atts = array()) {
            global $post;
            $rating = GDSRDatabase::get_review($post->ID);
            if ($rating > -1) {
                $full_width = $this->o['review_size'] * $this->o["review_stars"];
                $rate_width = $this->o['review_size'] * $rating;

                if ($this->o['review_class_block'] != '')
                    $css_class = ' class="'.$this->o['review_class_block'].'"';
                else
                    $css_class = '';

                if ($this->o['review_align'] != 'none')
                    $rater_align = ' align="'.$this->o['review_align'].'"';
                else
                    $rater_align = '';

                if ($this->o['review_header'] == 1)
                    $rater_header = '<div class="ratingreviewheader">'.$this->o['review_header_text'].'</div>';
                else
                    $rater_header = '';
                
                $gfx = $this->g->find_stars($this->o['review_style']);
                $star_path = $gfx->get_url($this->o['review_size']);

                $review = sprintf('<div%s%s>%s<div style="%s"><div style="%s"></div></div></div>',
                    $css_class, $rater_align, $rater_header,
                    sprintf('text-align:left; background: url(%s); height: %spx; width: %spx;', $star_path, $this->o['review_size'], $full_width),
                    sprintf('background: url(%s) bottom left; height: %spx; width: %spx;', $star_path, $this->o['review_size'], $rate_width)
                );
                return $review;
            }
            else
                return '';
		}
		
        function shortcode_starrating($atts) {
            $sett = shortcode_atts($this->default_shortcode_starrating, $atts);
            if ($sett["div_class"] != "") $rating.= '<div class="'.$sett["div_class"].'">';
            else $rating.= "<div>";
            $rating.= html_entity_decode($this->x["shortcode_starrating_header"]);
            $template = html_entity_decode($this->x["shortcode_starrating_item"]);
            
            $all_rows = $this->prepare_data($sett, $template);
            foreach ($all_rows as $row) {
                $rating.= $this->prepare_row($row, $template);
            }
            
            $rating.= html_entity_decode($this->x["shortcode_starrating_footer"])."</div>";
            return $rating;
        }
        // shortcode
        
        // install
        function admin_menu() {
            add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Front Page", "gd-star-rating"), __("Front Page", "gd-star-rating"), 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Articles", "gd-star-rating"), __("Articles", "gd-star-rating"), 10, "gd-star-rating-stats", array(&$this,"star_menu_stats"));
            if ($this->charting) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Charts", "gd-star-rating"), __("Charts", "gd-star-rating"), 10, "gd-star-rating-charts", array(&$this,"star_menu_charts"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Settings", "gd-star-rating"), __("Settings", "gd-star-rating"), 10, "gd-star-rating-settings-page", array(&$this,"star_menu_settings"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Templates", "gd-star-rating"), __("Templates", "gd-star-rating"), 10, "gd-star-rating-templates", array(&$this,"star_menu_templates"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Import", "gd-star-rating"), __("Import", "gd-star-rating"), 10, "gd-star-rating-import", array(&$this,"star_menu_import"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Export", "gd-star-rating"), __("Export", "gd-star-rating"), 10, "gd-star-rating-export", array(&$this,"star_menu_export"));
            // add_submenu_page(__FILE__, 'GD Star Rating: '.__("Batch", "gd-star-rating"), __("Batch", "gd-star-rating"), 10, "gd-star-rating-batch", array(&$this,"star_menu_batch"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Setup", "gd-star-rating"), __("Setup", "gd-star-rating"), 10, "gd-star-rating-setup", array(&$this,"star_menu_setup"));
        }                                                                
        
        function admin_head() {
            if (isset($_GET["page"])) {
                if (substr($_GET["page"], 0, 14) == "gd-star-rating") {
                    $this->admin_plugin = true;
                    $this->admin_plugin_page = substr($_GET["page"], 14);
                }
            }
            
            if ($this->admin_plugin) {
                wp_print_scripts('jquery-ui-tabs');
                wp_admin_css('css/dashboard');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin.css" type="text/css" media="screen" />');
            }
            
            if ($this->admin_plugin_page == "charts" && $this->charting && $this->admin_plugin) {
                echo '<script type="text/javascript" src="'.$this->plugin_url.'ofc2/js/swfobject.js"></script>';
            }
                                    
            $datepicker_date = date("Y, n, j");
            echo '<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-datepicker.js"></script>';
            if(!empty($this->l)) {
                $jsFile = $this->plugin_path.'js/i18n/jquery-ui-datepicker-'.$this->l.'.js';
                if (@file_exists($jsFile) && is_readable($jsFile)) echo '<script type="text/javascript" src="'.$this->plugin_url.'js/i18n/jquery-ui-datepicker-'.$this->l.'.js"></script>';
            }
            echo('<script type="text/javascript">jQuery(document).ready(function() {');
            if ($this->admin_plugin) echo('jQuery("#gdsr_tabs > ul").tabs();');
            echo('jQuery("#gdsr_timer_date_value").datepicker({duration: "fast", minDate: new Date('.$datepicker_date.'), dateFormat: "yy-mm-dd"});');
            echo('});</script>');

            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery.css" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin_post.css" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/widgets.css" type="text/css" media="screen" />');
        }

        function actions_filters() {
            add_action('init', array(&$this, 'init'));
            add_action('wp_head', array(&$this, 'wp_head'));
            if (!$this->o["ajax"]) 
                add_action('get_header', array(&$this, 'redirect'));
            add_action('widgets_init', array(&$this, 'widget_init'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('admin_head', array(&$this, 'admin_head'));
            if ($this->o["review_active"] == 1) {
                add_action('submitpost_box', array(&$this, 'editbox_post'));
                add_action('submitpage_box', array(&$this, 'editbox_post'));
                add_action('save_post', array(&$this, 'saveedit_post'));
            }
            add_filter('comment_text', array(&$this, 'display_comment'));
            add_filter('the_content', array(&$this, 'display_article'));
            add_filter("mce_external_plugins", array(&$this, 'add_tinymce_plugin'), 5);
            add_filter('mce_buttons', array(&$this, 'add_tinymce_button'), 5);

            foreach ($this->shortcodes as $code) {
                $this->shortcode_action($code);
            }
        }

        function saveedit_post($post_id) {
            if ($_POST['gdsr_post_edit'] == "edit") {
                $old = GDSRDatabase::check_post($post_id);
                    
                if ($_POST['gdsr_review'] != "-1") {
                    $review = $_POST['gdsr_review'];
                    if ($_POST['gdsr_review_decimal'] != "-1")
                        $review.= ".".$_POST['gdsr_review_decimal'];
                    GDSRDatabase::save_review($post_id, $review);
                    $old = true;
                }
                
                GDSRDatabase::save_article_rules($post_id, $_POST['gdsr_vote_articles'], $_POST['gdsr_mod_articles']);
                $timer = $_POST['gdsr_timer_type'];
                if ($timer != 'N') {
                    GDSRDatabase::save_timer_rules($post_id, $timer, $this->timer_value($timer, $_POST['gdsr_timer_date_value'], $_POST['gdsr_timer_countdown_value'], $_POST['gdsr_timer_countdown_type']));
                }
            }
        }
        
        function timer_value($t_type, $t_date = '', $t_count_value = 0, $t_count_type = 'D') {
            $value = '';
            switch ($t_type) {
                case 'D':
                    $value = $t_date;
                    break;
                case 'T':
                    $value = $t_count_type.$t_count_value;
                    break;
            }
            return $value;
        }

        function upgrade_settings($old, $new) {
            foreach ($new as $key => $value) {
                if (!isset($old[$key])) $old[$key] = $value;
            }
            
            $unset = Array();
            foreach ($old as $key => $value) {
                if (!isset($new[$key])) $unset[] = $key;
            }
            
            foreach ($unset as $key) {
                unset($old[$key]);
            }
            
            return $old;
        }

        function install_plugin() {
            $this->o = get_option('gd-star-rating');
            $this->x = get_option('gd-star-rating-templates');
            $this->i = get_option('gd-star-rating-import');
            $this->g = get_option('gd-star-rating-gfx');

            if (intval(str_replace(".", "", $this->o["version"]) < 99))
                GDSRDB::upgrade_database();
            
            if (!is_array($this->o)) {
                update_option('gd-star-rating', $this->default_options);
                GDSRDB::install_database();
            }
            else {
                $this->o = $this->upgrade_settings($this->o, $this->default_options);

                $this->o["version"] = $this->default_options["version"];
                $this->o["date"] = $this->default_options["date"];
                $this->o["status"] = $this->default_options["status"];
                
                update_option('gd-star-rating', $this->o);
            }

            if (!is_array($this->x))
                update_option('gd-star-rating-templates', $this->default_templates);
            else {
                $this->x = $this->upgrade_settings($this->x, $this->default_templates);
                update_option('gd-star-rating-templates', $this->x);
            }

            if (!is_array($this->i))
                update_option('gd-star-rating-import', $this->default_import);
            else {
                $this->i = $this->upgrade_settings($this->i, $this->default_import);
                update_option('gd-star-rating-import', $this->i);
            }
            
            if (!is_object($this->g)) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }

            $this->t = GDSRDB::get_database_tables();
        }

        function gfx_scan() {
            $data = new GDgfxLib();
            
            $stars_folders = $this->scan_folder($this->plugin_path."stars/");
            foreach ($stars_folders as $f) {
                $gfx = new GDgfxStar($f);
                if ($gfx->imported)
                    $data->stars[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."stars/")) {
                $stars_folders = $this->scan_folder($this->plugin_xtra_path."stars/");
                foreach ($stars_folders as $f) {
                    $gfx = new GDgfxStar($f, false);
                    if ($gfx->imported)
                        $data->stars[] = $gfx;
                }
            }
            $trend_folders = $this->scan_folder($this->plugin_path."trends/");
            foreach ($trend_folders as $f) {
                $gfx = new GDgfxTrend($f);
                if ($gfx->imported)
                    $data->trend[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."trends/")) {
                $trend_folders = $this->scan_folder($this->plugin_xtra_path."trends/");
                foreach ($trend_folders as $f) {
                    $gfx = new GDgfxTrend($f, false);
                    if ($gfx->imported)
                        $data->trend[] = $gfx;
                }
            }
            return $data;
        }
        
        function scan_folder($path) {
            $folders = gd_scandir($path);
            $import = array();
            foreach ($folders as $folder) {
                if (substr($folder, 0, 1) != ".") {
                    if (is_dir($path.$folder."/"))
                        $import[] = $folder;
                }
            }
            return $import;
        }
        
        function active_wp_page() {
            if (stripos($_GET["page"], "gd-star-rating") === false)
                $this->active_wp_page = false;
            else
                $this->active_wp_page = true;
        }

        function plugin_path_url() {
            global $wp_version;
            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
            if ($this->wp_version < 26) {
                $this->plugin_url = get_option('home').'/'.PLUGINDIR.'/gd-star-rating/';
                $this->plugin_xtra_url = get_option('home').'/wp-content/gd-star-rating/';
                $this->plugin_xtra_path = ABSPATH.'/wp-content/gd-star-rating/';
            }
            else {
                $this->plugin_url = WP_PLUGIN_URL.'/gd-star-rating/';
                $this->plugin_xtra_url = WP_CONTENT_URL.'/gd-star-rating/';
                $this->plugin_xtra_path = WP_CONTENT_DIR.'/gd-star-rating/';
            }
            $this->plugin_path = dirname(__FILE__)."/";
            $this->e = $this->plugin_url."gfx/blank.gif";
            
            $this->plugin_chart_path = $this->plugin_path."ofc2/";
            $this->plugin_chart_url = $this->plugin_url."ofc2/";
            
            if (is_dir($this->plugin_chart_path))
                $this->charting = true;
            
            define('STARRATING_URL', $this->plugin_url);
            define('STARRATING_PATH', $this->plugin_path);
            define('STARRATING_XTRA_URL', $this->plugin_xtra_url);
            define('STARRATING_XTRA_PATH', $this->plugin_xtra_path);
            define('STARRATING_CHART_URL', $this->plugin_chart_url);
            define('STARRATING_CHART_PATH', $this->plugin_chart_path);
        }

        function init() {
            wp_enqueue_script('jquery');
            $this->l = get_locale();
            if(!empty($this->l)) {
                $moFile = dirname(__FILE__)."/languages/gd-star-rating-".$this->l.".mo";
                if (@file_exists($moFile) && is_readable($moFile)) load_textdomain('gd-star-rating', $moFile);
            }
            
            if (!$this->o["ajax"]) {
                $votes = $_REQUEST['gdsrvotes'];
                $id = $_REQUEST['gdsrid'];
                $type = $_REQUEST['gdsrtype'];
                $user = $_REQUEST['gdsruser'];
                $this->vote_status = false;

                if ($votes != '' && $id != '' && $type != '') {
                    switch ($type) {
                        case "a": 
                            $this->vote_article($votes, $id, $user);
                            break;
                        case "c":
                            $this->vote_comment($votes, $id, $user);
                            break;
                    }
                }
            }
        }

        function init_post() {
            global $post;
            
            $this->p = GDSRDatabase::get_post_data($post->ID);
            if (count($this->p) == 0) {
                GDSRDatabase::add_default_vote($post->ID, $post->post_type == "page" ? "1" : "0");
                $this->p = GDSRDatabase::get_post_data($post->ID);
            }
        }

        function redirect() {
            if ($this->vote_status) {
                $url = $_SERVER["HTTP_REFERER"];
                wp_redirect($url);
            }
        }

        function wp_head() {
            if (is_feed()) return;
            
            $gfx_a = $this->g->find_stars($this->o["style"]);
            $gfx_c = $this->g->find_stars($this->o["cmm_style"]);
            
            $article = urlencode($this->o["style"]."|".$this->o["size"]."|".$this->o["stars"]."|".$gfx_a->type."|".$gfx_a->primary);
            $comment = urlencode($this->o["cmm_style"]."|".$this->o["cmm_size"]."|".$this->o["cmm_stars"]."|".$gfx_c->type."|".$gfx_c->primary);

            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/stars_article.css.php?stars='.$article.'" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/stars_comment.css.php?stars='.$comment.'" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/rating.css" type="text/css" media="screen" />');
            echo('<script type="text/javascript">');
            echo('function gdsrWait(rater, loader) { jQuery("#"+rater).css("display", "none"); jQuery("#"+loader).css("display", "block"); }');
            if ($this->o["ajax"] == 1) include (dirname(__FILE__)."/code/gd-star-js.php");
            echo('</script>');
            
            if ($this->o["ie_png_fix"] == 1) $this->ie_png_fix();
        }

        function ie_png_fix() {
            echo('<!--[if lte IE 6]>');
            echo('<style type="text/css">');
            echo('.ratertbl, .outer, .inner, .starsbar a:hover { behavior: url('.$this->plugin_url.'iepngfix/iepngfix.php) }');
            echo('</style>');
            echo('<script type="text/javascript" src="'.$this->plugin_url.'iepngfix/iepngfix_tilebg.js"></script>');
            echo('<![endif]-->');
        }
        // install

        // vote
        function vote_article($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if ($user == '') $user = 0;
            
            $allow_vote = $this->check_cookie($id);

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip);
            
            if ($allow_vote) {
                GDSRDatabase::save_vote($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id);
                if (!$this->o["ajax"]) $this->vote_status = true;
            }
        }

        function vote_article_ajax($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if ($user == '') $user = 0;

            $allow_vote = intval($votes) <= $this->o["stars"];
            
            if ($allow_vote) $allow_vote = $this->check_cookie($id);
            
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip);

            if ($allow_vote) {
                GDSRDatabase::save_vote($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id);
                $data = GDSRDatabase::get_post_data($id);

                if ($votes == 1) $tense = $this->x["word_votes_singular"];
                else $tense = $this->x["word_votes_plural"];
                $unit_width = $this->o["size"];
                $unit_count = $this->o["stars"];

                $votes = 0;
                $score = 0;
                
                if ($data->rules_articles == "A" || $data->rules_articles == "N") {
                    $votes = $data->user_voters + $data->visitor_voters;
                    $score = $data->user_votes + $data->visitor_votes;
                }
                else if ($data->rules_articles == "V") {
                    $votes = $data->visitor_voters;
                    $score = $data->visitor_votes;
                }
                else {
                    $votes = $data->user_voters;
                    $score = $data->user_votes;
                }
                
                if ($votes > 0) $rating2 = $score / $votes;
                else $rating2 = 0;
                $rating1 = @number_format($rating2, 1);
                $rating_width = $rating2 * $unit_width;
                
                $tpl = $this->x["article_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
                $rt = str_replace('%VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                
                return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
            }
            else {
                return "{ status: 'error' }";
            }
        }

        function vote_comment($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if ($user == '') $user = 0;
            
            $allow_vote = $this->check_cookie($id, 'comment');

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip);
                
            if ($allow_vote) {
                GDSRDatabase::save_vote_comment($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id, 'comment');
                $this->vote_status = true;
            }
        }

        function vote_comment_ajax($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if ($user == '') $user = 0;

            $allow_vote = intval($votes) <= $this->o["cmm_stars"];

            if ($allow_vote) $allow_vote = $this->check_cookie($id, 'comment');

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip);
            
            if ($allow_vote) {
                GDSRDatabase::save_vote_comment($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id, 'comment');
                $data = GDSRDatabase::get_comment_data($id);
                $post_data = GDSRDatabase::get_post_data($data->post_id);

                if ($votes == 1) $tense = $this->o["word_votes_singular"];
                else $tense = $this->o["word_votes_plural"];

                $unit_width = $this->o["cmm_size"];
                $unit_count = $this->o["cmm_stars"];
                
                $votes = 0;
                $score = 0;
                
                if ($post_data->rules_comments == "A" || $post_data->rules_comments == "N") {
                    $votes = $data->user_voters + $data->visitor_voters;
                    $score = $data->user_votes + $data->visitor_votes;
                }
                else if ($post_data->rules_comments == "V") {
                    $votes = $data->visitor_voters;
                    $score = $data->visitor_votes;
                }
                else {
                    $votes = $data->user_voters;
                    $score = $data->user_votes;
                }

                if ($votes > 0) $rating2 = $score / $votes;
                else $rating2 = 0;
                $rating1 = @number_format($rating2, 1);
                $rating_width = $rating2 * $unit_width;
                
                $tpl = $this->x["cmm_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', $tense, $rt);
                
                return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
            }
            else {
                return "{ status: 'error' }";
            }
        }
        // vote
        
        // calculation
        function bayesian_estimate($v, $R) {
            $m = $this->o["bayesian_minimal"];
            $C = ($this->o["bayesian_mean"] / 100) * $this->o["stars"];
            
            $WR = ($v / ($v + $m)) * $R + ($m / ($v + $m)) * $C;
            return @number_format($WR, 1);
        }

        function prepare_data($widget, $template) {
            global $wpdb;
            
            $bayesian_calculated = !(strpos($template, "%BAYES_RATING%") === false);
            $t_rate = !(strpos($template, "%RATE_TREND%") === false);
            $t_vote = !(strpos($template, "%VOTE_TREND%") === false);
            
            if ($widget["column"] == "bayes" && !$bayesian_calculated) $widget["column"] == "rating";
            $sql = GDSRX::get_widget($widget, $this->o["bayesian_minimal"]);
            $all_rows = $wpdb->get_results($sql);

            if ($t_rate || $t_vote) {
                $idx = array();
                foreach ($all_rows as $row) {
                    switch ($widget["grouping"]) {
                        case "post":
                            $id = $row->post_id;
                            break;
                        case "category":
                            $id = $row->term_id;
                            break;
                        case "user":
                            $id = $row->id;
                            break;
                    }
                    $idx[] = $id;
                }
                $trends = GDSRX::get_trend_calculation(join(", ", $idx), $widget["grouping"], $widget['show'], $this->o["trend_last"], $this->o["trend_over"]);
                $trends_calculated = true;
            }
            else {
                $trends = array();
                $trends_calculated = false;
            }

            $new_rows = array();
            foreach ($all_rows as $row) {
                if ($widget['show'] == "total") {
                    $row->votes = $row->user_votes + $row->visitor_votes;
                    $row->voters = $row->user_voters + $row->visitor_voters;
                }                
                if ($widget['show'] == "visitors") {
                    $row->votes = $row->visitor_votes;
                    $row->voters = $row->visitor_voters;
                }                
                if ($widget['show'] == "users") {
                    $row->votes = $row->user_votes ;
                    $row->voters = $row->user_voters;
                }

                if ($row->voters == 0) $row->rating = 0;
                else $row->rating = @number_format($row->votes / $row->voters, 1);

                if ($bayesian_calculated)
                    $row->bayesian = $this->bayesian_estimate($row->voters, $row->rating);
                else
                    $row->bayesian = -1;
                $new_rows[] = $row;
            }

            if ($widget["column"] == "bayes" && $bayesian_calculated)
                usort($new_rows, "gd_sort_bayesian_".$widget["order"]);

            $tr_class = $this->x["table_row_even"];
            if ($trends_calculated) {
                $set_rating = $this->g->find_trend($widget["trends_rating_set"]);
                $set_voting = $this->g->find_trend($widget["trends_voting_set"]);
            }
            
            $all_rows = array();
            foreach ($new_rows as $row) {
                $row->table_row_class = $tr_class;
                if (strlen($row->title) > $widget["tpl_title_length"] - 3 && $widget["tpl_title_length"] > 0)
                    $row->title = substr($row->title, 0, $widget["tpl_title_length"] - 3)." ...";

                if ($trends_calculated) {
                    $empty = $this->e;

                    switch ($widget["grouping"]) {
                        case "post":
                            $id = $row->post_id;
                            break;
                        case "category":
                            $id = $row->term_id;
                            break;
                        case "user":
                            $id = $row->id;
                            break;
                    }
                    $t = $trends[$id];
                    switch ($widget["trends_rating"]) {
                        case "img":
                            $rate_url = $set_rating->get_url();
                            switch ($t->trend_rating) {
                                case -1:
                                    $image_loc = "bottom";
                                    break;
                                case 0:
                                    $image_loc = "center";
                                    break;
                                case 1:
                                    $image_loc = "top";
                                    break;
                            }
                            $image_bg = sprintf('background: url(%s) %s no-repeat; height: %spx; width: %spx;', $rate_url, $image_loc, $set_rating->size, $set_rating->size);
                            $row->item_trend_rating = sprintf('<img class="trend" src="%s" style="%s" width="%s" height="%s"></img>', $this->e, $image_bg, $set_rating->size, $set_rating->size);
                            break;
                        case "txt":
                            switch ($t->trend_rating) {
                                case -1:
                                    $row->item_trend_rating = $widget["trends_rating_fall"];
                                    break;
                                case 0:
                                    $row->item_trend_rating = $widget["trends_rating_same"];
                                    break;
                                case 1:
                                    $row->item_trend_rating = $widget["trends_rating_rise"];
                                    break;
                            }
                            break;
                    }
                    switch ($widget["trends_voting"]) {
                        case "img":
                            $vote_url = $set_voting->get_url();
                            switch ($t->trend_voting) {
                                case -1:
                                    $image_loc = "bottom";
                                    break;
                                case 0:
                                    $image_loc = "center";
                                    break;
                                case 1:
                                    $image_loc = "top";
                                    break;
                            }
                            $image_bg = sprintf('background: url(%s) %s no-repeat; height: %spx; width: %spx;', $vote_url, $image_loc, $set_voting->size, $set_voting->size);
                            $row->item_trend_voting = sprintf('<img class="trend" src="%s" style="%s" width="%s" height="%s"></img>', $this->e, $image_bg, $set_voting->size, $set_voting->size);
                            break;
                        case "txt":
                            switch ($t->trend_voting) {
                                case -1:
                                    $row->item_trend_voting = $widget["trends_voting_fall"];
                                    break;
                                case 0:
                                    $row->item_trend_voting = $widget["trends_voting_same"];
                                    break;
                                case 1:
                                    $row->item_trend_voting = $widget["trends_voting_rise"];
                                    break;
                            }
                            break;
                    }
                }

                switch ($widget["grouping"]) {
                    case "post":
                        $row->permalink = get_permalink($row->post_id);
                        break;
                    case "category":
                        $row->permalink = get_category_link($row->term_id);
                        break;
                    case "user":
                        $row->permalink = get_bloginfo('url')."/index.php?author=".$row->id;
                        break;
                }

                if ($voters > 1) $row->tense = $this->x["word_votes_plural"];
                else $row->tense = $this->x["word_votes_singular"];

                if (!(strpos($template, "%STARS%") === false)) $row->rating_stars = $this->prepare_stars($widget['rating_size'], $this->o["stars"], $widget['rating_stars'], $row->rating);
                if (!(strpos($template, "%BAYES_STARS%") === false) && $row->bayesian > -1) $row->bayesian_stars = $this->prepare_stars($widget['rating_size'], $this->o["stars"], $widget['rating_stars'], $row->bayesian);
                if (!(strpos($template, "%REVIEW_STARS%") === false) && $row->review > -1) $row->review_stars = $this->prepare_stars($widget['review_size'], $this->o["review_stars"], $widget['review_stars'], $row->review);
                
                if ($tr_class == $this->x["table_row_even"])
                    $tr_class = $this->x["table_row_odd"];
                else
                    $tr_class = $this->x["table_row_even"];
                
                $all_rows[] = $row;
            }

            return $all_rows;
        }
        
        function prepare_stars($rating_size, $stars, $rating_stars, $rating) {
            $full_width = $rating_size * $stars;
            $rate_width = floor($rating_size * $rating);
            $gfx = $this->g->find_stars($rating_stars);
            
            $star_path = $gfx->get_url($rating_size);
            return sprintf('<div style="%s" class="ratertbl"><div style="%s" class="ratertbl"></div></div>',
                        sprintf('text-align:left; background: url(%s); height: %spx; width: %spx;', $star_path, $rating_size, $full_width),
                        sprintf('background: url(%s) bottom left; height: %spx; width: %spx;', $star_path, $rating_size, $rate_width)
                   );
        }
        
        function prepare_row($row, $template) {
            $template = str_replace('%RATING%', $row->rating, $template);
            $template = str_replace('%MAX_RATING%', $this->o["stars"], $template);
            $template = str_replace('%VOTES%', $row->voters, $template);
            $template = str_replace('%REVIEW%', $row->review, $template);
            $template = str_replace('%MAX_REVIEW%', $this->o["review_stars"], $template);
            $template = str_replace('%TITLE%', $row->title, $template);
            $template = str_replace('%PERMALINK%', $row->permalink, $template);
            $template = str_replace('%ID%', $row->post_id, $template);
            $template = str_replace('%COUNT%', $row->counter, $template);
            $template = str_replace('%WORD_VOTES%', $row->tense, $template);
            $template = str_replace('%BAYES_RATING%', $row->bayesian, $template);
            $template = str_replace('%BAYES_STARS%', $row->bayesian_stars, $template);
            $template = str_replace('%STARS%', $row->rating_stars, $template);
            $template = str_replace('%REVIEW_STARS%', $row->review_stars, $template);
            $template = str_replace('%RATE_TREND%', $row->item_trend_rating, $template);
            $template = str_replace('%VOTE_TREND%', $row->item_trend_voting, $template);
            $template = str_replace('%TABLE_ROW_CLASS%', $row->table_row_class, $template);
            return $template;
        }
        
        function expiration_countdown($post_date, $value) {
            $period = substr($value, 0, 1);
            $value = substr($value, 1);
            $pdate = strtotime($post_date);
            switch ($period) {
                case 'H':
                    $expiry = mktime(date("H", $pdate) + $value, date("i", $pdate), date("s", $pdate), date("m", $pdate), date("d", $pdate), date("Y", $pdate));
                    break;
                case 'D':
                    $expiry = mktime(date("H", $pdate), date("i", $pdate), date("s", $pdate), date("m", $pdate), date("d", $pdate) + $value, date("Y", $pdate));
                    break;
                case 'M':
                    $expiry = mktime(date("H", $pdate), date("i", $pdate), date("s", $pdate), date("m", $pdate) + $value, date("d", $pdate), date("Y", $pdate));
                    break;
            }
            return $expiry - mktime();
        }

        function expiration_date($value) {
            return strtotime($value) - mktime();
        }
        // calculation

        // log
        function dump($msg, $object, $mode = "a+") {
            $obj = print_r($object, true);
            $f = fopen($this->log_file, $mode);
            fwrite ($f, sprintf("[%s] : %s\r\n", current_time('mysql'), $msg));
            fwrite ($f, "$obj");
            fwrite ($f, "\r\n");
            fclose($f);
        }
        // log

        // menu
        function editbox_post() {
            global $post;
            $gdsr_options = $this->o;
            $post_id = $post->ID;
            $default = false;
            
            $countdown_value = $gdsr_options["default_timer_countdown_value"];
            $countdown_type = $gdsr_options["default_timer_countdown_type"];
            if ($post_id == 0)
                $default = true;
            else {
                $post_data = GDSRDatabase::get_post_edit($post_id);
                if (count($post_data) > 0) {
                    $rating = explode(".", strval($post_data->review));
                    $rating_decimal = intval($rating[1]);
                    $rating = intval($rating[0]);
                    $vote_rules = $post_data->rules_articles;
                    $moderation_rules = $post_data->moderate_articles;
                    $timer_restrictions = $post_data->expiry_type;
                    if ($timer_restrictions == "T") {
                        $countdown_type = substr($post_data->expiry_value, 0, 1);
                        $countdown_value = substr($post_data->expiry_value, 1);
                    }
                    else if ($timer_restrictions == "D") {
                        $timer_date_value = $post_data->expiry_value;
                    }
                }
                else
                    $default = true;
            }

            if ($default) {
                $rating_decimal = -1;
                $rating = -1;
                $vote_rules = $gdsr_options["default_voterules_articles"];
                $moderation_rules = $gdsr_options["default_moderation_articles"];
                $timer_restrictions = $gdsr_options["default_timer_type"];
            }
            
            if ($this->wp_version < 27)
                include($this->plugin_path.'options/edit26.php');
            else
                include($this->plugin_path.'options/edit27.php');
        }                  

        function star_menu_front() {
            $options = $this->o;
            include($this->plugin_path.'options/front.php');
        }

        function star_menu_settings() {
            if (isset($_POST['gdsr_preview_scan'])) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }
            
            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_options = $this->o;
            $gdsr_root_url = $this->plugin_url;
            $gdsr_gfx = $this->g;
            
            include($this->plugin_path.'options/settings.php');

            if ($recalculate_articles)
                GDSRDB::recalculate_articles($gdsr_oldstars, $gdsr_newstars);

            if ($recalculate_comment)
                GDSRDB::recalculate_comments($gdsr_cmm_oldstars, $gdsr_cmm_newstars);

            if ($recalculate_reviews)
                GDSRDB::recalculate_reviews($gdsr_review_oldstars, $gdsr_review_newstars);

            if ($recalculate_cmm_reviews)
                GDSRDB::recalculate_comments_reviews($gdsr_cmm_review_oldstars, $gdsr_cmm_review_newstars);
        }

        function star_menu_templates() {
            $gdsr_options = $this->x;
            include($this->plugin_path.'templates/templates.php');
        }

        function star_menu_setup() {
            include($this->plugin_path.'options/setup.php');
        }

        function star_menu_charts() {
            include($this->plugin_path.'options/charts.php');
        }

        function star_menu_batch() {
            $options = $this->o;
            include($this->plugin_path.'options/batch.php');
        }

        function star_menu_import() {
            $options = $this->o;
            $imports = $this->i;
            include($this->plugin_path.'options/import.php');
        }
        
        function star_menu_export() {
            $options = $this->o;
            include($this->plugin_path.'options/export.php');
        }

        function star_menu_stats() {
            $options = $this->o;
            $gdsr_page = $_GET["gdsr"];
            
            switch ($gdsr_page) {
                case "articles":
                default:
                    include($this->plugin_path.'options/articles.php');
                    break;
                case "moderation":
                    include($this->plugin_path.'options/moderation.php');
                    break;
                case "comments":
                    include($this->plugin_path.'options/comments.php');
                    break;
                case "log":
                    include($this->plugin_path.'options/voters.php');
                    break;
            }
        }
        // menu

        // widgets
        function widget_init() {
            if ($this->o["widget_articles"] == 1) $this->widget_articles_init();
            if ($this->o["widget_top"] == 1) $this->widget_top_init();
        }

        function widget_top_init() {
            if (!$options = get_option('widget_gdstarrating_top'))
                $options = array();
                
            $widget_ops = array('classname' => 'widget_gdstarrating_top', 'description' => 'GD Star Rating Top');
            $control_ops = array('width' => $this->wp_old ? 580 : 420, 'height' => 420, 'id_base' => 'gdstartop');
            $name = 'GD Star Rating Top';
            
            $registered = false;
            foreach (array_keys($options) as $o) {
                if (!isset($options[$o]['title']))
                    continue;
                    
                $id = "gdstartop-$o";
                $registered = true;
                wp_register_sidebar_widget($id, $name, array(&$this, 'widget_top_display'), $widget_ops, array( 'number' => $o ) );
                wp_register_widget_control($id, $name, array(&$this, 'widget_top_control'), $control_ops, array( 'number' => $o ) );
            }
            if (!$registered) {
                wp_register_sidebar_widget('gdstartop-1', $name, array(&$this, 'widget_top_display'), $widget_ops, array( 'number' => -1 ) );
                wp_register_widget_control('gdstartop-1', $name, array(&$this, 'widget_top_control'), $control_ops, array( 'number' => -1 ) );
            }
        }

        function widget_top_control($widget_args = 1) {
            global $wp_registered_widgets;
            static $updated = false;

            if ( is_numeric($widget_args) )
                $widget_args = array('number' => $widget_args);
                
            $widget_args = wp_parse_args($widget_args, array('number' => -1));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating_top');
            if (!is_array($options_all))
                $options_all = array();

            if (!$updated && !empty($_POST['sidebar'])) {
                $sidebar = (string)$_POST['sidebar'];

                $sidebars_widgets = wp_get_sidebars_widgets();
                if (isset($sidebars_widgets[$sidebar]))
                    $this_sidebar =& $sidebars_widgets[$sidebar];
                else
                    $this_sidebar = array();

                foreach ($this_sidebar as $_widget_id) {
                    if ('widget_gdstarrating_top' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                        $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                        if (!in_array("gdstartop-$widget_number", $_POST['widget-id']))
                            unset($options_all[$widget_number]);
                    }
                }
                foreach ((array)$_POST['gdstart'] as $widget_number => $posted) {
                    if (!isset($posted['title']) && isset($options_all[$widget_number]))
                        continue;
                    $options = array();
                    
                    $options['title'] = strip_tags(stripslashes($posted['title']));
                    $options['display'] = $posted['display'];
                    
                    $options_all[$widget_number] = $options;
                }
                update_option('widget_gdstarrating_top', $options_all);
                $updated = true;
            }
            
            if (-1 == $number) {
                $wpnm = '%i%';
                $wpno = $this->default_widget_top;
            }
            else {
                $wpnm = $number;
                $wpno = $options_all[$number];
            }
            
            $wpfn = 'gdstart['.$wpnm.']';
            
            include("widgets/widget_top.php");
        }

        function widget_top_display($args, $widget_args = 1) {
            extract($args);
            global $wpdb, $userdata;

            if (is_numeric($widget_args))
                $widget_args = array('number' => $widget_args);
            $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating_top');
            if (!isset($options_all[$number]))
                return;
            $this->w = $options_all[$number];
            
            if ($this->w["display"] == "hide" || ($this->w["display"] == "users" && $userdata->ID == 0) || ($this->w["display"] == "visitors" && $userdata->ID > 0)) return;
           
            echo $before_widget.$before_title.$this->w['title'].$after_title;
            $this->render_top_widget($this->w);
            echo $after_widget;
        }
        
        function render_top_widget($widget) {
        }

        function widget_articles_init() {
            if (!$options = get_option('widget_gdstarrating'))
                $options = array();
                
            $widget_ops = array('classname' => 'widget_gdstarrating', 'description' => 'GD Star Rating');
            $control_ops = array('width' => $this->wp_old ? 580 : 420, 'height' => 420, 'id_base' => 'gdstarrmulti');
            $name = 'GD Star Rating';
            
            $registered = false;
            foreach (array_keys($options) as $o) {
                if (!isset($options[$o]['title']))
                    continue;
                    
                $id = "gdstarrmulti-$o";
                $registered = true;
                wp_register_sidebar_widget($id, $name, array(&$this, 'widget_articles_display'), $widget_ops, array( 'number' => $o ) );
                wp_register_widget_control($id, $name, array(&$this, 'widget_articles_control'), $control_ops, array( 'number' => $o ) );
            }
            if (!$registered) {
                wp_register_sidebar_widget('gdstarrmulti-1', $name, array(&$this, 'widget_articles_display'), $widget_ops, array( 'number' => -1 ) );
                wp_register_widget_control('gdstarrmulti-1', $name, array(&$this, 'widget_articles_control'), $control_ops, array( 'number' => -1 ) );
            }
        }

        function widget_articles_control($widget_args = 1) {
            global $wp_registered_widgets;
            static $updated = false;

            if ( is_numeric($widget_args) )
                $widget_args = array('number' => $widget_args);
                
            $widget_args = wp_parse_args($widget_args, array('number' => -1));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating');
            if (!is_array($options_all))
                $options_all = array();

            if (!$updated && !empty($_POST['sidebar'])) {
                $sidebar = (string)$_POST['sidebar'];

                $sidebars_widgets = wp_get_sidebars_widgets();
                if (isset($sidebars_widgets[$sidebar]))
                    $this_sidebar =& $sidebars_widgets[$sidebar];
                else
                    $this_sidebar = array();

                foreach ($this_sidebar as $_widget_id) {
                    if ('widget_gdstarrating' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                        $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                        if (!in_array("gdstarrmulti-$widget_number", $_POST['widget-id']))
                            unset($options_all[$widget_number]);
                    }
                }
                foreach ((array)$_POST['gdstarr'] as $widget_number => $posted) {
                    if (!isset($posted['title']) && isset($options_all[$widget_number]))
                        continue;
                    $options = array();
                    
                    $options['title'] = strip_tags(stripslashes($posted['title']));
                    $options['tpl_header'] = stripslashes(htmlentities($posted['tpl_header'], ENT_QUOTES, 'UTF-8'));
                    $options['tpl_item'] = stripslashes(htmlentities($posted['tpl_item'], ENT_QUOTES, 'UTF-8'));
                    $options['tpl_footer'] = stripslashes(htmlentities($posted['tpl_footer'], ENT_QUOTES, 'UTF-8'));
                    $options['tpl_title_length'] = $posted['title_max'];
                    
                    $options['rows'] = $posted['rows'];
                    $options['select'] = $posted['select'];
                    $options['grouping'] = $posted['grouping'];
                    $options['column'] = $posted['column'];
                    $options['order'] = $posted['order'];
                    $options['category'] = $posted['category'];
                    $options['show'] = $posted['show'];
                    $options['display'] = $posted['display'];
                    
                    $options['publish_date'] = $posted['publish_date'];
                    $options['publish_month'] = $posted['publish_month'];
                    $options['publish_days'] = $posted['publish_days'];
                    $options['publish_range_from'] = $posted['publish_range_from'];
                    $options['publish_range_to'] = $posted['publish_range_to'];
                    
                    $options['div_template'] = $posted['div_template'];
                    $options['div_filter'] = $posted['div_filter'];
                    $options['div_trend'] = $posted['div_trend'];
                    $options['div_elements'] = $posted['div_elements'];
                    
                    $options['trends_rating'] = $posted['trends_rating'];
                    $options['trends_rating_set'] = $posted['trends_rating_set'];
                    $options['trends_rating_rise'] = strip_tags(stripslashes($posted['trends_rating_rise']));
                    $options['trends_rating_same'] = strip_tags(stripslashes($posted['trends_rating_same']));
                    $options['trends_rating_fall'] = strip_tags(stripslashes($posted['trends_rating_fall']));
                    $options['trends_voting'] = $posted['trends_voting'];
                    $options['trends_voting_set'] = $posted['trends_voting_set'];
                    $options['trends_voting_rise'] = strip_tags(stripslashes($posted['trends_voting_rise']));
                    $options['trends_voting_same'] = strip_tags(stripslashes($posted['trends_voting_same']));
                    $options['trends_voting_fall'] = strip_tags(stripslashes($posted['trends_voting_fall']));
                    
                    $options['hide_empty'] = isset($posted['hidempty']) ? 1 : 0;
                    $options['hide_noreview'] = isset($posted['hidenoreview']) ? 1 : 0;
                    $options['bayesian_calculation'] = isset($posted['bayesian_calculation']) ? 1 : 0;
                    
                    $options_all[$widget_number] = $options;
                }
                update_option('widget_gdstarrating', $options_all);
                $updated = true;
            }
            
            if (-1 == $number) {
                $wpnm = '%i%';
                $wpno = $this->default_widget;
            }
            else {
                $wpnm = $number;
                $wpno = $options_all[$number];
            }
            
            $wpfn = 'gdstarr['.$wpnm.']';
            $wptr = $this->g->trend;
            
            include("widgets/widget.php");
        }

        function widget_articles_display($args, $widget_args = 1) {
            extract($args);
            global $wpdb, $userdata;

            if (is_numeric($widget_args))
                $widget_args = array('number' => $widget_args);
            $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating');
            if (!isset($options_all[$number]))
                return;
            $this->w = $options_all[$number];
            
            if ($this->w["display"] == "hide" || ($this->w["display"] == "users" && $userdata->ID == 0) || ($this->w["display"] == "visitors" && $userdata->ID > 0)) return;
           
            echo $before_widget.$before_title.$this->w['title'].$after_title;
            $this->render_articles_widget($this->w);
            echo $after_widget;
        }

        function render_articles_widget($widget) {
            global $wpdb;
            echo html_entity_decode($widget["tpl_header"]);
            $template = html_entity_decode($widget["tpl_item"]);
            
            $all_rows = $this->prepare_data($widget, $template);
            
            foreach ($all_rows as $row) {
                echo $this->prepare_row($row, $template);
            }

            echo html_entity_decode($widget["tpl_footer"]);
        }
        // widgets

        // ccookies
        function check_cookie($post_id, $type = "article") {
            if (isset($_COOKIE["wp_gdsr"])) {
                $cookie = $_COOKIE["wp_gdsr"];
                $cookie = substr($cookie, 6, strlen($cookie) - 6);
                $cookie_ids = explode('|', $cookie);
                if (in_array($post_id, $cookie_ids))
                    return false;
            }
            if (isset($_COOKIE["wp_gdsr_".$type])) {
                $cookie = $_COOKIE["wp_gdsr_".$type];
                $cookie = substr($cookie, 6, strlen($cookie) - 6);
                $cookie_ids = explode('|', $cookie);
                if (in_array($post_id, $cookie_ids))
                    return false;
            }
            return true;
        }

        function save_cookie($id, $type = "article") {
            if (
                ($type == "article" && $this->o["cookies"] == 1) || 
                ($type == "comment" && $this->o["cmm_cookies"] == 1)
                ) {
                if (isset($_COOKIE["wp_gdsr_".$type])) {
                    $cookie = $_COOKIE["wp_gdsr_".$type];
                    $cookie = substr($cookie, 6, strlen($cookie) - 6);
                }
                else $cookie = '';
                $cookie.= "|".$id;
                setcookie("wp_gdsr_".$type, "voted_".$cookie, time() + 3600 * 24 * 365, '/');
            }
        }
        // ccookies

        // render
        function display_comment($content) {
            global $post, $comment, $userdata;
            
            if ($comment->comment_type == "pingback" && $this->o["display_trackback"] == 0)
                return $content;

            if (is_single() && !is_admin())
                $content .= $this->render_comment($post, $comment, $userdata);
            return $content;
        }

        function display_article($content) {
            global $post, $userdata;
            
            if (is_admin())
                return $content;
                
            if (!is_feed()) {
                if (is_single() || is_page())
                    GDSRDatabase::add_new_view($post->ID);
                
                if ((is_single() && $this->o["display_posts"] == 1) || 
                    (is_page() && $this->o["display_pages"] == 1) ||
                    (is_home() && $this->o["display_home"] == 1) ||
                    (is_archive() && $this->o["display_archive"] == 1) ||
                    (is_search() && $this->o["display_search"] == 1)
                ) {
                    $content .= $this->render_article($post, $userdata);
                }
            }
            
            return $content;
        }

        function render_rating_text_article($post, $cls = "") {
            $rd_post_id = intval($post->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";
            $post_data = GDSRDatabase::get_post_data($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = GDSRDatabase::get_post_data($rd_post_id);
            }

            $votes = 0;
            $score = 0;
            
            if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                $votes = $post_data->user_voters + $post_data->visitor_voters;
                $score = $post_data->user_votes + $post_data->visitor_votes;
            }
            else if ($post_data->rules_articles == "V") {
                $votes = $post_data->visitor_voters;
                $score = $post_data->visitor_votes;
            }
            else {
                $votes = $post_data->user_voters;
                $score = $post_data->user_votes;
            }
            
            echo GDSRRender::rating_text($rd_post_id, "a", $votes, $score, $this->o["stars"], "article", $cls == "" ? $this->o["class_text"] : $cls);
        }
        
        function render_comment($post, $comment, $user) {
            if ($this->o["comments_active"] != 1) 
                return "";

            $rd_unit_width = $this->o["cmm_size"];
            $rd_unit_count = $this->o["cmm_stars"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_comment_id = intval($comment->comment_ID);

            if ($this->p)
                $post_data = $this->p;
            else if (is_single()) {
                $this->init_post();
                $post_data = $this->p;
            }
            else {
                $post_data = GDSRDatabase::get_post_data($rd_post_id);
                if (count($post_data) == 0) {
                    GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                    $post_data = GDSRDatabase::get_post_data($rd_post_id);
                }
            }
            if ($post_data->rules_comments == "H") 
                return "";

            $comment_data = GDSRDatabase::get_comment_data($rd_comment_id);
            if (count($comment_data) == 0) {
                GDSRDatabase::add_empty_comment($rd_comment_id, $rd_post_id);
                $comment_data = GDSRDatabase::get_comment_data($rd_comment_id);
            }

            if ($this->o["cmm_author_vote"] == 1 && $rd_user_id == $comment->user_id)
                $allow_vote = false;
            else
                $allow_vote = true;
            
            if ($allow_vote)
                $allow_vote = !GDSRHelper::detect_bot($_SERVER['HTTP_USER_AGENT']);

            if ($allow_vote) {
                if (
                    ($post_data->rules_comments == "A") || 
                    ($post_data->rules_comments == "U" && $rd_user_id > 0) || 
                    ($post_data->rules_comments == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else
                    $allow_vote = false;
            }

            if ($allow_vote) 
                $allow_vote = GDSRDatabase::check_vote($rd_comment_id, $rd_user_id, 'comment', $_SERVER["REMOTE_ADDR"]);

            if ($allow_vote)
                $allow_vote = $this->check_cookie($rd_post_id, "comment");

            $votes = 0;
            $score = 0;
            
            if ($post_data->rules_comments == "A" || $post_data->rules_comments == "N") {
                $votes = $comment_data->user_voters + $comment_data->visitor_voters;
                $score = $comment_data->user_votes + $comment_data->visitor_votes;
            }
            else if ($post_data->rules_comments == "V") {
                $votes = $comment_data->visitor_voters;
                $score = $comment_data->visitor_votes;
            }
            else {
                $votes = $comment_data->user_voters;
                $score = $comment_data->user_votes;
            }

            return GDSRRender::rating_block($rd_comment_id, "ratecmm", "c", $votes, $score, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "comment", $this->o["cmm_align"], $this->o["cmm_text"], $this->o["cmm_header"], $this->o["cmm_header_text"], $this->o["cmm_class_block"], $this->o["cmm_class_text"], $this->o["ajax"]);
        }

        function render_article($post, $user) {
            global $wpdb;
            if (is_single()) $this->init_post();
            
            $rd_unit_width = $this->o["size"];
            $rd_unit_count = $this->o["stars"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            if ($this->p)
                $post_data = $this->p;
            else {
                $post_data = GDSRDatabase::get_post_data($rd_post_id);
                if (count($post_data) == 0) {
                    GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                    $post_data = GDSRDatabase::get_post_data($rd_post_id);
                }
            }
            if ($post_data->rules_articles == "H") 
                return "";
            
            if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author)
                $allow_vote = false;
            else
                $allow_vote = true;
            
            if ($allow_vote)
                $allow_vote = !GDSRHelper::detect_bot($_SERVER['HTTP_USER_AGENT']);

            if ($allow_vote) {
                if (
                    ($post_data->rules_articles == "A") || 
                    ($post_data->rules_articles == "U" && $rd_user_id > 0) || 
                    ($post_data->rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else
                    $allow_vote = false;
            }
            
            if ($allow_vote && $post_data->expiry_type != 'N') {
                switch($post_data->expiry_type) {
                    case "D":
                        $remaining = $this->expiration_date($post_data->expiry_value);
                        break;
                    case "T":
                        $remaining = $this->expiration_countdown($post->post_date, $post_data->expiry_value);
                        break; 
                }
            }

            if ($allow_vote) 
                $allow_vote = GDSRDatabase::check_vote($rd_post_id, $rd_user_id, 'article', $_SERVER["REMOTE_ADDR"]);

            if ($allow_vote)
                $allow_vote = $this->check_cookie($rd_post_id);
            
            $votes = 0;
            $score = 0;
            
            if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                $votes = $post_data->user_voters + $post_data->visitor_voters;
                $score = $post_data->user_votes + $post_data->visitor_votes;
            }
            else if ($post_data->rules_articles == "V") {
                $votes = $post_data->visitor_voters;
                $score = $post_data->visitor_votes;
            }
            else {
                $votes = $post_data->user_voters;
                $score = $post_data->user_votes;
            }
            
            $rating_block = GDSRRender::rating_block($rd_post_id, "ratepost", "a", $votes, $score, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "article", $this->o["align"], $this->o["text"], $this->o["header"], $this->o["header_text"], $this->o["class_block"], $this->o["class_text"], $this->o["ajax"]);
            return $rating_block;
        }
        // render
    }

    $gdsr = new GDStarRating();

    function wp_gdsr_render_widget($widget = array()) {
        global $gdsr;
        $gdsr->render_articles_widget($widget);
    }
    
    function wp_gdsr_render_article() {
        global $post, $userdata, $gdsr;
        echo $gdsr->render_article($post, $userdata);
    }
    
    function wp_gdsr_rating_text_article($cls = "") {
        global $post, $gdsr;
        echo $gdsr->render_rating_text_article($post, $cls);
    }

    function wp_gdsr_render_comment() {
        global $comment, $userdata, $gdsr, $post;
        echo $gdsr->render_comment($post, $comment, $userdata);
    }
    
	function wp_gdsr_render_review() {
        global $gdsr;
        echo $gdsr->shortcode_starreview();
	}
    
    function wp_gdsr_user_votes($user_id) {
        global $gdsr;
    }
    
    function wp_gdsr_all_users_votes() {
        global $gdsr;
    }
}
