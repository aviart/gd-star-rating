<?php

/*
Plugin Name: GD Star Rating
Plugin URI: http://www.gdstarrating.com/
Description: Star Rating plugin allows you to set up rating system for pages and/or posts in your blog.
Version: 1.1.4
Author: Milan Petrovic
Author URI: http://wp.gdragon.info/
 
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

require_once(dirname(__FILE__)."/gd-star-config.php");
require_once(dirname(__FILE__)."/code/gd-star-defaults.php");
require_once(dirname(__FILE__)."/code/gd-star-functions.php");
require_once(dirname(__FILE__)."/code/gd-star-render.php");
require_once(dirname(__FILE__)."/code/gd-star-dbone.php");
require_once(dirname(__FILE__)."/code/gd-star-dbtwo.php");
require_once(dirname(__FILE__)."/code/gd-star-dbx.php");
require_once(dirname(__FILE__)."/code/gd-star-dbmulti.php");
require_once(dirname(__FILE__)."/code/gd-star-gfx.php");
require_once(dirname(__FILE__)."/code/gd-star-import.php");
require_once(dirname(__FILE__)."/code/gd-star-chart.php");
require_once(dirname(__FILE__)."/code/gd-star-generator.php");
require_once(dirname(__FILE__)."/gdragon/gd_functions.php");
require_once(dirname(__FILE__)."/gdragon/gd_debug.php");
require_once(dirname(__FILE__)."/gdragon/gd_db_install.php");

if (!class_exists('GDStarRating')) {
    /**
    * Main plugin class
    */
    class GDStarRating {
        var $is_bot = false;
        var $is_ban = false;
        var $use_nonce = true;
        var $extra_folders = false;
        var $safe_mode = false;
        var $is_cached = false;

        var $loader_article = "";
        var $loader_comment = "";
        var $loader_multis = "";
        var $wpr8_available = false;
        var $admin_plugin = false;
        var $admin_plugin_page = '';
        var $admin_page;

        var $active_wp_page;
        var $wp_version;
        var $vote_status;

        var $plugin_url;
        var $plugin_ajax;
        var $plugin_path;
        var $plugin_xtra_url;
        var $plugin_xtra_path;
        var $plugin_chart_url;
        var $plugin_chart_path;
        var $plugin_cache_path;
        var $plugin_wpr8_path;
        
        var $l; // language
        var $o; // options
        var $w; // widget options
        var $t; // database tables
        var $p; // post data
        var $x; // template
        var $e; // blank image
        var $i; // import
        var $g; // gfx

        var $wpr8;
        
        var $post_comment;

        var $shortcode_builtin_classes;
        var $shortcodes;
        var $default_shortcode_starrating;
        var $default_shortcode_starratercustom;
        var $default_shortcode_starratingmulti;
        var $default_templates;
        var $default_options;
        var $default_import;
        var $default_widget_comments;
        var $default_widget_top;
        var $default_widget;
        var $default_spiders;
        var $default_wpr8;
        
        /**
        * Constructor method
        */
        function GDStarRating() {
            $gdd = new GDSRDefaults();
            $this->shortcode_builtin_classes = $gdd->shortcode_builtin_classes;
            $this->shortcodes = $gdd->shortcodes;
            $this->default_spiders = $gdd->default_spiders;
            $this->default_wpr8 = $gdd->default_wpr8;
            $this->default_shortcode_starrating = $gdd->default_shortcode_starrating;
            $this->default_shortcode_starratercustom = $gdd->default_shortcode_starratercustom;
            $this->default_shortcode_starratingmulti = $gdd->default_shortcode_starratingmulti;
            $this->default_templates = $gdd->default_templates;
            $this->default_options = $gdd->default_options;
            $this->default_import = $gdd->default_import;
            $this->default_widget_comments = $gdd->default_widget_comments;
            $this->default_widget_top = $gdd->default_widget_top;
            $this->default_widget = $gdd->default_widget;

            $this->tabpage = "front";
            $this->log_file = STARRATING_LOG_PATH;
            $this->active_wp_page();
            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
        }
        
        // shortcodes
        /**
        * Adds new button to tinyMCE editor toolbar
        * 
        * @param mixed $buttons
        */
        function add_tinymce_button($buttons) {
            array_push($buttons, "separator", "StarRating");
            return $buttons;
        }

        /**
        * Adds plugin to tinyMCE editor
        * 
        * @param mixed $plugin_array
        */
        function add_tinymce_plugin($plugin_array) {    
            $plugin_array['StarRating'] = $this->plugin_url.'tinymce3/plugin.js';
            return $plugin_array;
        }

        /**
        * Adds shortcodes into WordPress instance
        * 
        * @param string|array $scode one or more shortcode names
        */
        function shortcode_action($scode) {
            if (is_array($scode)) {
                $sc_name = $scode["name"];
                $sc_method = $scode["method"];
            }
            else {
                $sc_name = $scode;
                $sc_method = "shortcode_".$scode;
            }
            add_shortcode(strtolower($sc_name), array(&$this, $sc_method));
            add_shortcode(strtoupper($sc_name), array(&$this, $sc_method));
        }
        
        /**
        * Code for StarRater shortcode implementation
        * 
        * @param array $atts
        */
		function shortcode_starrater($atts = array()) {
            global $post, $userdata;
            return $this->render_article($post, $userdata);
		}

        /**
        * Code for StarRaterCustom shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starratercustom($atts = array()) {
            global $post, $userdata;
            $override = shortcode_atts($this->default_shortcode_starratercustom, $atts);
            return $this->render_article($post, $userdata, $override);
        }

        /**
        * Code for StarRatingBlock shortcode implementation
        * 
        * @param array $atts
        */
        function shortcode_starratingblock($atts = array()) {
            global $post, $userdata;
            return $this->render_article($post, $userdata);
        }
        
        /**
        * Code for StarRatingMulti shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starratingmulti($atts = array()) {
            if ($this->o["multis_active"] == 1) {
                global $post, $userdata;
                $settings = shortcode_atts($this->default_shortcode_starratingmulti, $atts);
                return $this->render_multi_rating($post, $userdata, $settings);
            }
            else return '';
        }

        /**
        * Code for StarReview shortcode implementation
        * 
        * @param array $atts
        */
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
		
        /**
        * Code for StarRating shortcode implementation
        * 
        * @param array $atts
        */
        function shortcode_starrating($atts = array()) {
            $sett = shortcode_atts($this->default_shortcode_starrating, $atts);
            if ($sett["div_class"] != "") $rating = '<div class="'.$sett["div_class"].'">';
            else $rating = "<div>";
            $rating.= html_entity_decode($this->x["shortcode_starrating_header"]);
            $template = html_entity_decode($this->x["shortcode_starrating_item"]);
            
            $all_rows = $this->prepare_data($sett, $template);
            foreach ($all_rows as $row) {
                $rating.= $this->prepare_row($row, $template);
            }
            
            $rating.= html_entity_decode($this->x["shortcode_starrating_footer"]);
            $rating.= "</div>";
            return $rating;
        }
        // shortcodes
        
        // various rendering
        /**
        * Renders comment review stars
        * 
        * @param int $value initial rating value
        * @param bool $allow_vote render stars to support rendering or not to
        */
        function comment_review($value = 0, $allow_vote = true) {
            $stars = $this->o["cmm_review_stars"];
            $size = $this->o["cmm_review_size"];
            return GDSRRender::rating_stars_local($size, $stars, $allow_vote, $value * $size);
        }
        
        /**
        * Renders the rating stars for given parameters
        * 
        * @param string $style folder name of the stars set to use
        * @param int $stars number of rating stars, maximal rating value
        * @param int $size stars size 12, 20, 30, 46
        * @param bool $zero_render if set to false and $value is 0 then nothing will be rendered
        * @param decimal $value rating value to show
        * @return string rendered html
        */
        function get_rating_stars($style, $stars, $size, $zero_render, $value) {
            if ($value <= 0) {
                if ($zero_render) $value = 0;
                else return '';
            }

            $full_width = $size * $stars;
            $rate_width = $size * $value;
            
            $gfx = $this->g->find_stars($style);
            $star_path = $gfx->get_url($size);

            $render = sprintf('<div style="%s"><div style="%s"></div></div>',
                sprintf('text-align:left; background: url(%s); height: %spx; width: %spx;', $star_path, $size, $full_width),
                sprintf('background: url(%s) bottom left; height: %spx; width: %spx;', $star_path, $size, $rate_width)
            );
            return $render;
        }

        /**
         * Renders comment review stars for selected comment
         *
         * @param int $comment_id id of the comment you want displayed
         * @param bool $zero_render if set to false and $value is 0 then nothing will be rendered
         * @param bool $use_default rendering is using default rendering settings
         * @param string $style folder name of the stars set to use
         * @param int $size stars size 12, 20, 30, 46
         * @return string rendered stars for comment review
         */
        function display_comment_review($comment_id, $zero_render = true, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = $this->o["cmm_review_style"];
                $size = $this->o["cmm_review_size"];
            }
            $stars = $this->o["cmm_review_stars"];
            $review = GDSRDatabase::get_comment_review($comment_id);
            
            return $this->get_rating_stars($style, $stars, $size, $zero_render, $review);
        }

        /**
         * Renders post review stars for selected post
         *
         * @param int $post_id id for the post you want review displayed
         * @param bool $zero_render if set to false and $value is 0 then nothing will be rendered
         * @param bool $use_default rendering is using default rendering settings
         * @param string $style folder name of the stars set to use
         * @param int $size stars size 12, 20, 30, 46
         * @return string rendered stars for article review
         */
        function display_article_review($post_id, $zero_render = true, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = $this->o["review_style"];
                $size = $this->o["review_size"];
            }
            $stars = $this->o["review_stars"];
            $review = GDSRDatabase::get_review($post_id);
            
            return $this->get_rating_stars($style, $stars, $size, $zero_render, $review);
        }

        /**
         * Renders post rating stars for selected post
         *
         * @param int $post_id id for the post you want rating displayed
         * @param bool $zero_render if set to false and $value is 0 then nothing will be rendered
         * @param bool $use_default rendering is using default rendering settings
         * @param string $style folder name of the stars set to use
         * @param int $size stars size 12, 20, 30, 46
         * @return string rendered stars for article rating
         */
        function display_article_rating($post_id, $zero_render = true, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = $this->o["style"];
                $size = $this->o["size"];
            }
            $stars = $this->o["stars"];
            list($votes, $score) = $this->get_article_rating($post_id);
            if ($votes > 0) $rating = $score / $votes;
            else $rating = 0;
            $rating = @number_format($rating, 1);
            
            return $this->get_rating_stars($style, $stars, $size, $zero_render, $rating);
        }
        // various rendering

        // edit boxes
        function editbox_comment() {
            if ($this->wp_version < 27)
                include($this->plugin_path.'integrate/editcomment26.php');
            else {
                if ($this->admin_page != "edit-comments.php") return;
                include($this->plugin_path.'integrate/editcomment27.php');
            }
        }

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
                    $cmm_vote_rules = $post_data->rules_comments;
                    $cmm_moderation_rules = $post_data->moderate_comments;
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

            if ($this->wp_version < 27) {
                $box_width = "100%";
                include($this->plugin_path.'integrate/edit26.php');
            }
            else {
                $box_width = "260";
                include($this->plugin_path.'integrate/edit.php');
            }
        }
        // edit boxes

        // install
        /**
         * WordPress action for adding administration menu items
         */
        function admin_menu() {
            if ($this->wp_version < 27)
                add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"));
            else {
                add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"), plugins_url('gd-star-rating/gfx/menu.png'));
                if ($this->o["integrate_post_edit"] == 1) {
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_post'), "post", "side", "high");
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_post'), "page", "side", "high");
                }
                if ($this->o["integrate_comment_edit"] == 1) {
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_comment'), "comments", "side", "high");
                }
            }

            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Front Page", "gd-star-rating"), __("Front Page", "gd-star-rating"), 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Articles", "gd-star-rating"), __("Articles", "gd-star-rating"), 10, "gd-star-rating-stats", array(&$this,"star_menu_stats"));
            if ($this->o["admin_category"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Categories", "gd-star-rating"), __("Categories", "gd-star-rating"), 10, "gd-star-rating-cats", array(&$this,"star_menu_cats"));
            if ($this->o["admin_users"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Users", "gd-star-rating"), __("Users", "gd-star-rating"), 10, "gd-star-rating-users", array(&$this,"star_menu_users"));
            if ($this->o["multis_active"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("Multi Sets", "gd-star-rating"), __("Multi Sets", "gd-star-rating"), 10, "gd-star-rating-multi-sets", array(&$this,"star_multi_sets"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Settings", "gd-star-rating"), __("Settings", "gd-star-rating"), 10, "gd-star-rating-settings-page", array(&$this,"star_menu_settings"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Tools", "gd-star-rating"), __("Tools", "gd-star-rating"), 10, "gd-star-rating-tools", array(&$this,"star_menu_tools"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Templates", "gd-star-rating"), __("Templates", "gd-star-rating"), 10, "gd-star-rating-templates", array(&$this,"star_menu_templates"));
            if ($this->o["admin_ips"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("IP's", "gd-star-rating"), __("IP's", "gd-star-rating"), 10, "gd-star-rating-ips", array(&$this,"star_menu_ips"));
            if ($this->o["admin_import"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Import", "gd-star-rating"), __("Import", "gd-star-rating"), 10, "gd-star-rating-import", array(&$this,"star_menu_import"));
            if ($this->o["admin_export"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Export", "gd-star-rating"), __("Export", "gd-star-rating"), 10, "gd-star-rating-export", array(&$this,"star_menu_export"));
            $this->custom_actions('admin_menu');
            if ($this->o["admin_setup"] == 1) add_submenu_page(__FILE__, 'GD Star Rating: '.__("Setup", "gd-star-rating"), __("Setup", "gd-star-rating"), 10, "gd-star-rating-setup", array(&$this,"star_menu_setup"));
        }                                                                
        
        /**
         * WordPress action for adding administration header contents
         */
        function admin_head() {
            global $parent_file;
            $this->admin_page = $parent_file;
            $tabs_extras = "";
            
            if ($this->admin_plugin_page == "ips" && $_GET["gdsr"] == "iplist") $tabs_extras = ", selected: 1";
            if ($this->admin_plugin) {
                wp_admin_css('css/dashboard');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_main.css" type="text/css" media="screen" />');
                if ($this->wp_version >= 27) echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_wp27.css" type="text/css" media="screen" />');
                echo '<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui.js"></script>';
                echo '<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-tabs.js"></script>';
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.tabs.css" type="text/css" media="screen" />');
            }
            if ($this->admin_plugin || $this->admin_page == "edit.php" || $this->admin_page == "post-new.php" || $this->admin_page == "themes.php") {
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.core.css" type="text/css" media="screen" />');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.theme.css" type="text/css" media="screen" />');
                $datepicker_date = date("Y, n, j");
                echo '<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-datepicker.js"></script>';
                if(!empty($this->l)) {
                    $jsFile = $this->plugin_path.'js/i18n/jquery-ui-datepicker-'.$this->l.'.js';
                    if (@file_exists($jsFile) && is_readable($jsFile)) echo '<script type="text/javascript" src="'.$this->plugin_url.'js/i18n/jquery-ui-datepicker-'.$this->l.'.js"></script>';
                }
            }
            echo('<script type="text/javascript">jQuery(document).ready(function() {');
            if ($this->admin_page == "edit-comments.php") include ($this->plugin_path."code/gd-star-jsx.php");
            if ($this->admin_plugin) echo('jQuery("#gdsr_tabs > ul").tabs({fx: {height: "toggle"}'.$tabs_extras.' });');
            if ($this->admin_plugin || $this->admin_page == "edit.php" || $this->admin_page == "post-new.php" || $this->admin_page == "themes.php") echo('jQuery("#gdsr_timer_date_value").datepicker({duration: "fast", minDate: new Date('.$datepicker_date.'), dateFormat: "yy-mm-dd"});');
            if ($this->admin_plugin_page == "tools") echo('jQuery("#gdsr_lock_date").datepicker({duration: "fast", dateFormat: "yy-mm-dd"});');
            if ($this->admin_plugin_page == "settings-page") include(STARRATING_PATH."code/gd-star-jsa.php");
            echo('});</script>');

            if ($this->admin_page == "themes.php") echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_widgets.css" type="text/css" media="screen" />');

            if ($this->admin_page == "edit-comments.php" || $this->admin_page == "comment.php") {
                $gfx_r = $this->g->find_stars($this->o["cmm_review_style"]);
                $comment_review = urlencode($this->o["cmm_review_style"]."|".$this->o["cmm_review_size"]."|".$this->o["cmm_review_stars"]."|".$gfx_r->type."|".$gfx_r->primary);
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_comment.css.php?stars='.$comment_review.'" type="text/css" media="screen" />');
            }

            $this->custom_actions('admin_head');

            if ($this->admin_plugin && $this->wp_version < 26)
                echo('<link rel="stylesheet" href="'.get_option('home').'/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" />');

            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_post.css" type="text/css" media="screen" />');
        }

        /**
         * Adding WordPress action and filter
         */
        function actions_filters() {
            add_action('init', array(&$this, 'init'));
            add_action('wp_head', array(&$this, 'wp_head'));
            if ($this->o["ajax"] != 1) 
                add_action('get_header', array(&$this, 'redirect'));
            add_action('widgets_init', array(&$this, 'widget_init'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('admin_head', array(&$this, 'admin_head'));
            if ($this->o["integrate_post_edit"] == 1) {
                if ($this->wp_version < 27) {
                    add_action('submitpost_box', array(&$this, 'editbox_post'));
                    add_action('submitpage_box', array(&$this, 'editbox_post'));
                }
                add_action('save_post', array(&$this, 'saveedit_post'));
            }
            if ($this->o["integrate_dashboard"] == 1) {
                add_action('wp_dashboard_setup', array(&$this, 'add_dashboard_widget'));
                if (!function_exists('wp_add_dashboard_widget'))
                    add_filter('wp_dashboard_widgets', array(&$this, 'add_dashboard_widget_filter'));
            }
            add_filter('comment_text', array(&$this, 'display_comment'));
            add_filter('the_content', array(&$this, 'display_article'));
            if ($this->o["comments_review_active"] == 1) {
                add_filter('preprocess_comment', array(&$this, 'comment_read_post'));
                add_filter('comment_post', array(&$this, 'comment_save_review'));
                if ($this->o["integrate_comment_edit"] == 1) {
                    if ($this->wp_version < 27) 
                        add_action('submitcomment_box', array(&$this, 'editbox_comment'));
                    
                    add_filter('comment_save_pre', array(&$this, 'comment_edit_review'));
                }
            }
            if ($this->o["integrate_tinymce"] == 1) {
                add_filter("mce_external_plugins", array(&$this, 'add_tinymce_plugin'), 5);
                add_filter('mce_buttons', array(&$this, 'add_tinymce_button'), 5);
            }

            if ($this->o["integrate_rss_powered"] == 1 || $this->o["rss_active"] == 1) {
                add_filter('the_excerpt_rss', array(&$this, 'rss_filter'));
                add_filter('the_content_rss', array(&$this, 'rss_filter'));
            }

            foreach ($this->shortcodes as $code) {
                $this->shortcode_action($code);
            }
        }

        function rss_filter($content) {
            if ($this->o["rss_active"] == 1) $content.= "<br />".$this->render_article_rss();
            if ($this->o["integrate_rss_powered"] == 1) $content.= "<br />".$this->powered_by();
            return $content."<br />";
        }

        function powered_by() {
            return '<a target="_blank" href="http://www.gdstarrating.com/"><img src="'.STARRATING_URL.'gfx/powered.png" border="0" width="80" height="15" /></a>';
        }
        
        function add_dashboard_widget() {
            if (!function_exists('wp_add_dashboard_widget'))
                wp_register_sidebar_widget("dashboard_gdstarrating", "GD Star Rating", array(&$this, 'display_dashboard_widget'), array('all_link' => get_bloginfo('wpurl').'/wp-admin/admin.php?page=gd-star-rating/gd-star-rating.php', 'width' => 'half', 'height' => 'single'));
            else
                wp_add_dashboard_widget("dashboard_gdstarrating", "GD Star Rating", array(&$this, 'display_dashboard_widget'));
        }
        
        function add_dashboard_widget_filter($widgets) {
            global $wp_registered_widgets;

            if (!isset($wp_registered_widgets["dashboard_gdstarrating"])) return $widgets;

            array_splice($widgets, 2, 0, "dashboard_gdstarrating");
            return $widgets;
        }

        function display_dashboard_widget($sidebar_args) {
            if (!function_exists('wp_add_dashboard_widget')) {
                extract($sidebar_args, EXTR_SKIP);
                echo $before_widget.$before_title.$widget_name.$after_title;
            }
            include($this->plugin_path.'integrate/dashboard.php');
            if (!function_exists('wp_add_dashboard_widget')) {
                echo $after_widget;
            }
        }

        function comment_read_post($comment) {
			if (isset($_POST["gdsr_cmm_review"]))
				$this->post_comment["review"] = $_POST["gdsr_cmm_review"];
			else
				$this->post_comment["review"] = -1;
            $this->post_comment["post_id"] = $_POST["comment_post_ID"];
            return $comment;
        }
        
        function comment_save_review($comment_id) {
            $comment_data = GDSRDatabase::get_comment_data($comment_id);
            if (count($comment_data) == 0)
                GDSRDatabase::add_empty_comment($comment_id, $this->post_comment["post_id"], $this->post_comment["review"]);
            else
                GDSRDatabase::save_comment_review($comment_id, $this->post_comment["review"]);
        }

        function comment_edit_review($comment_content) {
            if ($_POST['gdsr_comment_edit'] == "edit") {
                $post_id = $_POST["comment_post_ID"];
                $comment_id = $_POST["comment_ID"];
				if (isset($_POST["gdsr_cmm_review"]))
					$value = $_POST["gdsr_cmm_review"];
				else
					$value = -1;
                $comment_data = GDSRDatabase::get_comment_data($comment_id);
                if (count($comment_data) == 0)
                    GDSRDatabase::add_empty_comment($comment_id, $post_id, $value);
                else
                    GDSRDatabase::save_comment_review($comment_id, $value);
            }
            return $comment_content;
        }
        
        function saveedit_post($post_id) {
            if ($_POST['gdsr_post_edit'] == "edit") {
                $old = GDSRDatabase::check_post($post_id);

                $review = $_POST['gdsr_review'];
                if ($_POST['gdsr_review_decimal'] != "-1")
                    $review.= ".".$_POST['gdsr_review_decimal'];
                GDSRDatabase::save_review($post_id, $review, $old);
                $old = true;

                GDSRDatabase::save_article_rules($post_id, $_POST['gdsr_vote_articles'], $_POST['gdsr_mod_articles']);
                if ($this->o["comments_active"] == 1)
                    GDSRDatabase::save_comment_rules($post_id, $_POST['gdsr_cmm_vote_articles'], $_POST['gdsr_cmm_mod_articles']);
                $timer = $_POST['gdsr_timer_type'];
                GDSRDatabase::save_timer_rules(
                    $post_id,
                    $timer,
                    GDSRHelper::timer_value($timer,
                        $_POST['gdsr_timer_date_value'],
                        $_POST['gdsr_timer_countdown_value'],
                        $_POST['gdsr_timer_countdown_type'])
                );
            }
        }

        /**
         * Main installation method of the plugin
         */
        function install_plugin() {
            $this->o = get_option('gd-star-rating');
            $this->x = get_option('gd-star-rating-templates');
            $this->i = get_option('gd-star-rating-import');
            $this->g = get_option('gd-star-rating-gfx');
            $this->wpr8 = get_option('gd-star-rating-wpr8');

            if ($this->o["build"] < $this->default_options["build"]) {
                gdDBInstall::delete_tables(STARRATING_PATH);
                gdDBInstall::create_tables(STARRATING_PATH);
                gdDBInstall::upgrade_tables(STARRATING_PATH);
                gdDBInstall::alter_tables(STARRATING_PATH);
                $this->o["database_upgrade"] = date("r");
            }
            
            if (!is_array($this->o)) {
                update_option('gd-star-rating', $this->default_options);
                $this->o = get_option('gd-star-rating');
                gdDBInstall::create_tables(STARRATING_PATH);
            }
            else {
                $this->o = gdFunctions::upgrade_settings($this->o, $this->default_options);

                $this->o["version"] = $this->default_options["version"];
                $this->o["date"] = $this->default_options["date"];
                $this->o["status"] = $this->default_options["status"];
                $this->o["build"] = $this->default_options["build"];
                
                update_option('gd-star-rating', $this->o);
            }

            if (!is_array($this->x)) {
                update_option('gd-star-rating-templates', $this->default_templates);
                $this->x = get_option('gd-star-rating-templates');
            }
            else {
                $this->x = gdFunctions::upgrade_settings($this->x, $this->default_templates);
                update_option('gd-star-rating-templates', $this->x);
            }

            if (!is_array($this->i)) {
                update_option('gd-star-rating-import', $this->default_import);
                $this->i = get_option('gd-star-rating-import');
            }
            else {
                $this->i = gdFunctions::upgrade_settings($this->i, $this->default_import);
                update_option('gd-star-rating-import', $this->i);
            }
            
            if (!is_object($this->g)) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }

            if (!is_object($this->wpr8)) {
                update_option('gd-star-rating-wpr8', $this->default_wpr8);
                $this->wpr8 = get_option('gd-star-rating-wpr8');
            }
            else {
                $this->wpr8 = gdFunctions::upgrade_settings($this->wpr8, $this->default_wpr8);
                update_option('gd-star-rating-wpr8', $this->wpr8);
            }

            $this->use_nonce = $this->o["use_nonce"] == 1;
            define("STARRATING_VERSION", $this->o["version"].'_'.$this->o["build"]);
            define("STARRATING_DEBUG_ACTIVE", $this->o["debug_active"]);
            $this->t = GDSRDB::get_database_tables();
        }

        /**
         * Scans main and additional graphics folders for stars and trends sets.
         *
         * @return GDgfxLib scanned graphics object
         */
        function gfx_scan() {
            $data = new GDgfxLib();
            
            $stars_folders = gdFunctions::get_folders($this->plugin_path."stars/");
            foreach ($stars_folders as $f) {
                $gfx = new GDgfxStar($f);
                if ($gfx->imported)
                    $data->stars[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."stars/")) {
                $stars_folders = gdFunctions::get_folders($this->plugin_xtra_path."stars/");
                foreach ($stars_folders as $f) {
                    $gfx = new GDgfxStar($f, false);
                    if ($gfx->imported)
                        $data->stars[] = $gfx;
                }
            }
            $trend_folders = gdFunctions::get_folders($this->plugin_path."trends/");
            foreach ($trend_folders as $f) {
                $gfx = new GDgfxTrend($f);
                if ($gfx->imported)
                    $data->trend[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."trends/")) {
                $trend_folders = gdFunctions::get_folders($this->plugin_xtra_path."trends/");
                foreach ($trend_folders as $f) {
                    $gfx = new GDgfxTrend($f, false);
                    if ($gfx->imported)
                        $data->trend[] = $gfx;
                }
            }
            return $data;
        }

        /**
         * Gest the name of active plugin page
         */
        function active_wp_page() {
            if (stripos($_GET["page"], "gd-star-rating") === false)
                $this->active_wp_page = false;
            else
                $this->active_wp_page = true;
        }

        /**
         * Calculates all needed paths and sets them as constants.
         *
         * @global string $wp_version wordpress version
         */
        function plugin_path_url() {
            global $wp_version;
            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
            if ($this->wp_version < 26) {
                $this->plugin_url = get_option('siteurl').'/'.PLUGINDIR.'/gd-star-rating/';
                $this->plugin_ajax = get_option('siteurl').'/'.PLUGINDIR.'/gd-star-rating/ajax.php';
                $this->plugin_xtra_url = get_option('siteurl').'/wp-content/gd-star-rating/';
                $this->plugin_xtra_path = ABSPATH.'/wp-content/gd-star-rating/';
                $this->plugin_cache_path = $this->plugin_xtra_path."cache/";
            }
            else {
                $this->plugin_url = WP_PLUGIN_URL.'/gd-star-rating/';
                $this->plugin_ajax = get_option('siteurl').'/wp-content/plugins/gd-star-rating/ajax.php';
                $this->plugin_xtra_url = WP_CONTENT_URL.'/gd-star-rating/';
                $this->plugin_xtra_path = WP_CONTENT_DIR.'/gd-star-rating/';
                $this->plugin_cache_path = $this->plugin_xtra_path."cache/";
            }
            $this->plugin_path = dirname(__FILE__)."/";
            $this->e = $this->plugin_url."gfx/blank.gif";

            $this->plugin_wpr8_path = $this->plugin_path."wpr8/";
            $this->plugin_chart_path = $this->plugin_path."charts/";
            $this->plugin_chart_url = $this->plugin_url."charts/";

            if (is_dir($this->plugin_wpr8_path))
                $this->wpr8_available = true;

            define('STARRATING_URL', $this->plugin_url);
            define('STARRATING_AJAX', $this->plugin_ajax);
            define('STARRATING_PATH', $this->plugin_path);
            define('STARRATING_XTRA_URL', $this->plugin_xtra_url);
            define('STARRATING_XTRA_PATH', $this->plugin_xtra_path);
            define('STARRATING_CACHE_PATH', $this->plugin_cache_path);
            
            define('STARRATING_CHART_URL', $this->plugin_chart_url);
            define('STARRATING_CHART_PATH', $this->plugin_chart_path);
            define('STARRATING_CHART_CACHE_URL', $this->plugin_xtra_url."charts/");
            define('STARRATING_CHART_CACHE_PATH', $this->plugin_xtra_path."charts/");
        }

        /**
         * Executes attached hook actions methods for plugin internal actions.
         * - init: executed after init method
         *
         * @param <type> $action name of the plugin action
         */
        function custom_actions($action) {
            do_action('gdsr_'.$action);
        }

        /**
         * Main init method executed as wordpress action 'init'.
         */
        function init() {
            define('STARRATING_ENCODING', $this->o["encoding"]);

            if (isset($_GET["page"])) {
                if (substr($_GET["page"], 0, 14) == "gd-star-rating") {
                    $this->admin_plugin = true;
                    $this->admin_plugin_page = substr($_GET["page"], 15);
                }
            }

            if (!is_admin()) {
                $this->is_bot = GDSRHelper::detect_bot($_SERVER['HTTP_USER_AGENT']);
                $this->is_ban = GDSRHelper::detect_ban();
                $this->render_wait_article();
                if ($this->o["comments_active"] == 1) $this->render_wait_comment();
                if ($this->o["multis_active"] == 1) $this->render_wait_multis();
            }

            if ($this->admin_plugin_page == "settings-page") {
                $gdsr_options = $this->o;
                include ($this->plugin_path."code/gd-star-settings.php");
                $this->o = $gdsr_options;
            }
            
            if ($_POST["gdsr_full_uninstall"] == __("FULL UNINSTALL", "gd-star-rating")) {
                delete_option('gd-star-rating');
                delete_option('widget_gdstarrating');
                delete_option('gd-star-rating-templates');
                delete_option('gd-star-rating-import');
                delete_option('gd-star-rating-gfx');

                gdDBInstall::drop_tables(STARRATING_PATH);
                GDSRHelper::deactivate_plugin();
                update_option('recently_activated', array("gd-star-rating/gd-star-rating.php" => time()) + (array)get_option('recently_activated'));
                wp_redirect('index.php');
            }

            wp_enqueue_script('jquery');
            if ($this->admin_plugin) {
                if ($this->wp_version >= 26) add_thickbox();
                else wp_enqueue_script("thickbox");
                $this->safe_mode = gdFunctions::php_in_safe_mode();
                if (!$this->safe_mode)
                    $this->extra_folders = GDSRHelper::create_folders($this->wp_version);
            }
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
            $this->is_cached = $this->o["cache_active"];
            $this->custom_actions('init');
        }

        /**
         * Gets rating posts data for the post in the loop, used only when wordpress is rendering page or single post.
         *
         * @global object $post post from the loop
         */
        function init_post() {
            global $post;
            
            $this->p = GDSRDatabase::get_post_data($post->ID);
            if (count($this->p) == 0) {
                GDSRDatabase::add_default_vote($post->ID, $post->post_type == "page" ? "1" : "0");
                $this->p = GDSRDatabase::get_post_data($post->ID);
            }
        }

        /**
         * Simple function that uses WordPress redirect function
         */
        function redirect() {
            if ($this->vote_status) {
                $url = $_SERVER["HTTP_REFERER"];
                wp_redirect($url);
            }
        }

        /**
         * WordPress action for adding blog header contents
         */
        function wp_head() {
            if (is_feed()) return;
            $include_cmm_review = false;
            $include_mur_rating = false;

            $gfx_a = $this->g->find_stars($this->o["style"]);
            $css_string = "a".$this->o["style"]."|".$this->o["size"]."|".$this->o["stars"]."|".$gfx_a->type."|".$gfx_a->primary;
            if ($this->o["multis_active"] == 1) {
                $gfx_m = $this->g->find_stars($this->o["mur_style"]);
                $css_string.= "#m".$this->o["mur_style"]."|".$this->o["mur_size"]."|20|".$gfx_a->type."|".$gfx_a->primary;
                $include_mur_rating = true;
            }
            if (is_single() || is_page()) {
                if ($this->o["comments_active"] == 1) {
                    $gfx_c = $this->g->find_stars($this->o["cmm_style"]);
                    $css_string.= "#c".$this->o["cmm_style"]."|".$this->o["cmm_size"]."|".$this->o["cmm_stars"]."|".$gfx_c->type."|".$gfx_c->primary;
                }

                if ($this->o["comments_review_active"] == 1) {
                    $css_string.= "#r".$this->o["cmm_review_style"]."|".$this->o["cmm_review_size"]."|".$this->o["cmm_review_stars"]."|".$gfx_r->type."|".$gfx_r->primary;
                    $gfx_r = $this->g->find_stars($this->o["cmm_review_style"]);
                    $include_cmm_review = true;
                }
            }
            $css_string = urlencode($css_string);
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/gdstarating.css.php?s='.urlencode($css_string).'" type="text/css" media="screen" />');
            echo("\r\n");
            if ($this->o["external_css"] == 1 && file_exists($this->plugin_xtra_path."css/rating.css")) {
                echo('<link rel="stylesheet" href="'.$this->plugin_xtra_url.'css/rating.css" type="text/css" media="screen" />');
                echo("\r\n");
            }
            if ($this->o["external_javascript"] == 1)
                echo('<script type="text/javascript" src="'.$this->plugin_url.'script.js.php"></script>');
            else {
                echo('<script type="text/javascript">');
                if ($this->use_nonce) $nonce = wp_create_nonce('gdsr_ajax_r8');
                else $nonce = "";
                $ajax_active = $this->o["ajax"];
                $button_active = $this->o["mur_button_active"] == 1;
                echo('//<[!CDATA[');
                include ($this->plugin_path."code/gd-star-js.php");
                echo('// ]]>');
                echo('</script>');
            }
            echo("\r\n");

            $this->custom_actions('wp_head');
            
            if ($this->o["ie_png_fix"] == 1) GDSRHelper::ie_png_fix();
            if ($this->o["ie_opacity_fix"] == 1) GDSRHelper::ie_opacity_fix();
        }
        // install

        // vote
        function vote_article($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            if ($user == '') $user = 0;
            
            $allow_vote = $this->check_cookie($id);

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip, $this->o["logged"] != 1);
            
            if ($allow_vote) {
                GDSRDatabase::save_vote($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id);
                if (!$this->o["ajax"]) $this->vote_status = true;
            }
        }

        function vote_multi_rating($votes, $post_id, $set_id) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            $user = intval($userdata->ID);

wp_gdsr_dump("VOTE_MUR", $post_id."/".$set_id.": ".$votes." [".$user."]");

            $values = explode("X", $votes);
            $allow_vote = true;
            foreach ($values as $v) {
                if ($v > $this->o["stars"]) {
                    $allow_vote = false;
                    break;
                }
            }
            
            if ($allow_vote) $allow_vote = $this->check_cookie($post_id."#".$set_id, "multis");
            
            if ($allow_vote) $allow_vote = GDSRDBMulti::check_vote($post_id, $user, $set_id, 'multis', $ip, $this->o["logged"] != 1);

            $data = GDSRDatabase::get_post_data($post_id);

            if ($allow_vote) {
                GDSRDBMulti::save_vote($post_id, $set_id, $user, $ip, $ua, $values, $data);
                $this->save_cookie($post_id."#".$set_id, "multis");
                $msg = '%STATUS_OK_VOTED%';
            }
            else $msg = '%STATUS_ERROR_VOTED%';

            $set = gd_get_multi_set($set_id);
            $multi_data = GDSRDBMulti::get_values_join($post_id, $set_id);
            $votes_js = array();
            $weight_norm = array_sum($set->weight);
            $total_votes = 0;
            $weighted = 0;
            $i = 0;
            foreach ($multi_data as $md) {
                $votes = 0;
                $score = 0;

                if ($data->rules_articles == "A" || $data->rules_articles == "N") {
                    $votes = $md->user_voters + $md->visitor_voters;
                    $score = $md->user_votes + $md->visitor_votes;
                }
                else if ($data->rules_articles == "V") {
                    $votes = $md->visitor_voters;
                    $score = $md->visitor_votes;
                }
                else {
                    $votes = $md->user_voters;
                    $score = $md->user_votes;
                }
                if ($votes > 0) $rating = $score / $votes;
                else $rating = 0;
                if ($rating > $set->stars) $rating = $set->stars;
                $rating = @number_format($rating, 1);
                $votes_js[] = $rating * $this->o["mur_size"];
                $weighted += ( $rating * $set->weight[$i] ) / $weight_norm;
                $total_votes += $votes;
                $i++;
            }
            $rating = @number_format($weighted, 1);
            $total_votes = @number_format($total_votes / $i, 0);
            if ($total_votes == 1) $tense = $this->x["word_votes_singular"];
            else $tense = $this->x["word_votes_plural"];

            $tpl = $this->x["multis_rating_text"];
            $rt = html_entity_decode($tpl);
            $rt = str_replace('%RATING%', $rating, $rt);
            $rt = str_replace('%MAX_RATING%', $set->stars, $rt);
            $rt = str_replace('%VOTES%', $total_votes, $rt);
            $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
            $rt = str_replace('%ID%', $post_id, $rt);

            return "{ status: 'ok', values: ".json_encode($votes_js).", rater: '".$rt."' }";
        }

        function vote_article_ajax($votes, $id) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            $user = intval($userdata->ID);

wp_gdsr_dump("VOTE", $id.": ".$votes." [".$user."]");

            $allow_vote = intval($votes) <= $this->o["stars"];
            
            if ($allow_vote) $allow_vote = $this->check_cookie($id);
            
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip, $this->o["logged"] != 1);

            if ($allow_vote) {
                GDSRDatabase::save_vote($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id);
                $msg = '%STATUS_OK_VOTED%';
            }
            else $msg = '%STATUS_ERROR_VOTED%';

            $data = GDSRDatabase::get_post_data($id);

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

            if ($votes == 1) $tense = $this->x["word_votes_singular"];
            else $tense = $this->x["word_votes_plural"];

            if ($votes > 0) $rating2 = $score / $votes;
            else $rating2 = 0;
            $rating1 = @number_format($rating2, 1);
            $rating_width = $rating2 * $unit_width;

            $tpl = $this->x["article_rating_text"];
            $rt = html_entity_decode($tpl);
            $rt = str_replace('%RATING%', $rating1, $rt);
            $rt = str_replace('%MAX_RATING%', $unit_count, $rt);
            $rt = str_replace('%VOTES%', $votes, $rt);
            $rt = str_replace('%WORD_VOTES%', __($tense), $rt);

            return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
        }

        function vote_comment($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            if ($user == '') $user = 0;
            
            $allow_vote = $this->check_cookie($id, 'comment');

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip, $this->o["cmm_logged"] != 1);
                
            if ($allow_vote) {
                GDSRDatabase::save_vote_comment($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id, 'comment');
                $this->vote_status = true;
            }
        }

        function vote_comment_ajax($votes, $id) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            $user = intval($userdata->ID);

wp_gdsr_dump("VOTE_CMM", $id.": ".$votes." [".$user."]");

            $allow_vote = intval($votes) <= $this->o["cmm_stars"];

            if ($allow_vote) $allow_vote = $this->check_cookie($id, 'comment');

            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip, $this->o["cmm_logged"] != 1);
            
            if ($allow_vote) {
                GDSRDatabase::save_vote_comment($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id, 'comment');
                $data = GDSRDatabase::get_comment_data($id);
                $post_data = GDSRDatabase::get_post_data($data->post_id);

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

                if ($votes == 1) $tense = $this->x["word_votes_singular"];
                else $tense = $this->x["word_votes_plural"];

                if ($votes > 0) $rating2 = $score / $votes;
                else $rating2 = 0;
                $rating1 = @number_format($rating2, 1);
                $rating_width = $rating2 * $unit_width;
                
                $tpl = $this->x["cmm_rating_text"];
                $rt = html_entity_decode($tpl);
                $rt = str_replace('%CMM_RATING%', $rating1, $rt);
                $rt = str_replace('%MAX_CMM_RATING%', $unit_count, $rt);
                $rt = str_replace('%CMM_VOTES%', $votes, $rt);
                $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
                
                return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
            }
            else {
                return "{ status: 'error' }";
            }
        }
        // vote
        
        // calculations
        /**
        * Calculates Bayesian Estimate Mean value for given number of votes and rating
        * 
        * @param int $v number of votes
        * @param decimal $R rating value
        * @return decimal Bayesian rating value
        */
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

            if (count($all_rows) > 0) {
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

                    if ($row->voters > 1) $row->tense = $this->x["word_votes_plural"];
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
            $template = str_replace('%TITLE%', __($row->title), $template);
            $template = str_replace('%PERMALINK%', $row->permalink, $template);
            $template = str_replace('%ID%', $row->post_id, $template);
            $template = str_replace('%COUNT%', $row->counter, $template);
            $template = str_replace('%WORD_VOTES%', __($row->tense), $template);
            $template = str_replace('%BAYES_RATING%', $row->bayesian, $template);
            $template = str_replace('%BAYES_STARS%', $row->bayesian_stars, $template);
            $template = str_replace('%STARS%', $row->rating_stars, $template);
            $template = str_replace('%REVIEW_STARS%', $row->review_stars, $template);
            $template = str_replace('%RATE_TREND%', $row->item_trend_rating, $template);
            $template = str_replace('%VOTE_TREND%', $row->item_trend_voting, $template);
            $template = str_replace('%TABLE_ROW_CLASS%', $row->table_row_class, $template);
            return $template;
        }
        
        function prepare_blog_rating($widget) {
            global $wpdb;

            $sql = GDSRX::get_totals($widget);
            $data = $wpdb->get_row($sql);

            $data->max_rating = $this->o["stars"];
            if ($data->votes == null) {
                $data->votes = 0;
                $data->voters = 0;
            }
            if ($data->votes > 0) {
                $data->rating = @number_format($data->votes / $data->voters, 1);
                $data->bayes_rating = $this->bayesian_estimate($data->voters, $data->rating);
                $data->percentage = floor((100 / $data->max_rating) * $data->rating);
            }
            
            return $data;
        }

        function get_ratings_post($post_id) {
            if ($this->p && $this->p->post_id == $post_id) $post_data = $this->p;
            else $post_data = GDSRDatabase::get_post_data($post_id);
            if (count($post_data) == 0) return null;
            return new GDSRArticleRating($post_data);
        }

        function get_ratings_comment($comment_id) {
            $comment_data = GDSRDatabase::get_comment_data($comment_id);
            if (count($comment_data) == 0) return null;
            return new GDSRCommentRating($comment_data);
        }
        // calculations

        // menues
        function star_multi_sets() {
            $options = $this->o;
            $wpv = $this->wp_version;
            $gdsr_page = $_GET["gdsr"];

            $editor = true;
            if ($_POST['gdsr_action'] == 'save') {
                $editor = false;
                $eset = new GDMultiSingle(false);
                $eset->multi_id = $_POST["gdsr_ms_id"];
                $eset->name = stripslashes(htmlentities($_POST["gdsr_ms_name"], ENT_QUOTES, STARRATING_ENCODING));
                $eset->description = stripslashes(htmlentities($_POST["gdsr_ms_description"], ENT_QUOTES, STARRATING_ENCODING));
                $eset->stars = $_POST["gdsr_ms_stars"];
                $elms = $_POST["gdsr_ms_element"];
                $elwe = $_POST["gdsr_ms_weight"];
                $i = 0;
                foreach ($elms as $el) {
                    if (($el != "" && $eset->id == 0) || $eset->id > 0) {
                        $eset->object[] = stripslashes(htmlentities($el, ENT_QUOTES, STARRATING_ENCODING));
                        $ew = $elwe[$i];
                        if (!is_numeric($ew)) $ew = 1;
                        $eset->weight[] = $ew;
                        $i++;
                    }
                }
                if ($eset->name != "") {
                    if ($eset->id == 0) GDSRDBMulti::add_multi_set($eset);
                    else GDSRDBMulti::edit_multi_set($eset);
                }
            }
            if (($gdsr_page == "munew" || $gdsr_page == "muedit") && $editor) include($this->plugin_path.'multi/editor.php');
            else {
                switch ($gdsr_page) {
                    case "mulist":
                    default:
                        include($this->plugin_path.'multi/sets.php');
                        break;
                    case "murpost":
                        include($this->plugin_path.'multi/results_post.php');
                        break;
                    case "murset":
                        include($this->plugin_path.'multi/results_set.php');
                        break;
                }
            }
        }

        function star_multi_results() {
            $options = $this->o;
            include($this->plugin_path.'multi/results.php');
        }
        
        function star_menu_front() {
            $options = $this->o;
            $wpv = $this->wp_version;
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
            $gdsr_wpr8 = $this->wpr8_available;
            $extra_folders = $this->extra_folders;
            $safe_mode = $this->safe_mode;
            
            $wpr8 = $this->wpr8;
            
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
            $options = $this->o;
            include($this->plugin_path.'templates/templates.php');
        }

        function star_menu_setup() {
            include($this->plugin_path.'options/setup.php');
        }

        function star_menu_ips() {
            $options = $this->o;
            include($this->plugin_path.'options/ips.php');
        }
        
        function star_menu_tools() {
            $msg = "";
            if (isset($_POST['gdsr_cache_clean'])) {
                GDSRHelper::clean_cache(substr(STARRATING_CACHE_PATH, 0, strlen(STARRATING_CACHE_PATH) - 1));
                $this->o["cache_cleanup_last"] = date("r");
                update_option('gd-star-rating', $this->o);
            }
            if (isset($_POST['gdsr_preview_scan'])) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }
            if (isset($_POST['gdsr_upgrade_tool'])) {
                gdDBInstall::delete_tables(STARRATING_PATH);
                gdDBInstall::create_tables(STARRATING_PATH);
                gdDBInstall::upgrade_tables(STARRATING_PATH);
                gdDBInstall::alter_tables(STARRATING_PATH);
                $this->o["database_upgrade"] = date("r");
                update_option('gd-star-rating', $this->o);
            }
            if (isset($_POST['gdsr_cleanup_tool'])) {
                if (isset($_POST['gdsr_tools_clean_invalid_log'])) {
                    $count = GDSRDBTools::clean_invalid_log_articles();
                    if ($count > 0) $msg.= $count." articles records from log table removed. ";
                    $count = GDSRDBTools::clean_invalid_log_comments();
                    if ($count > 0) $msg.= $count." comments records from log table removed. ";
                }
                if (isset($_POST['gdsr_tools_clean_invalid_trend'])) {
                    $count = GDSRDBTools::clean_invalid_trend_articles();
                    if ($count > 0) $msg.= $count." articles records from trends log table removed. ";
                    $count = GDSRDBTools::clean_invalid_trend_comments();
                    if ($count > 0) $msg.= $count." comments records from trends log table removed. ";
                }
                if (isset($_POST['gdsr_tools_clean_old_posts'])) {
                    $count = GDSRDBTools::clean_dead_articles();
                    if ($count > 0) $msg.= $count." dead articles records from articles table. ";
                    $count = GDSRDBTools::clean_dead_comments();
                    if ($count > 0) $msg.= $count." dead comments records from comments table. ";
                }
                $this->o["database_cleanup"] = date("r");
                $this->o["database_cleanup_msg"] = $msg;
                update_option('gd-star-rating', $this->o);
            }
            if (isset($_POST['gdsr_post_lock'])) {
                $lock_date = $_POST['gdsr_lock_date'];
                GDSRDatabase::lock_post_massive($lock_date);
                $this->o["mass_lock"] = $lock_date;
                update_option('gd-star-rating', $this->o);
            }
            if (isset($_POST['gdsr_rules_set'])) {
                GDSRDatabase::update_settings_full($_POST["gdsr_article_moderation"], $_POST["gdsr_article_voterules"], $_POST["gdsr_comments_moderation"], $_POST["gdsr_comments_voterules"]);
            }

            $gdsr_options = $this->o;
            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_gfx = $this->g;

            include($this->plugin_path.'options/tools.php');
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
            $wpv = $this->wp_version;
            $gdsr_page = $_GET["gdsr"];
            $use_nonce = $this->use_nonce;
            
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
                case "voters":
                    include($this->plugin_path.'options/voters.php');
                    break;
            }
        }
        
        function star_menu_users(){
            $options = $this->o;
            if ($_GET["gdsr"] == "userslog")
                include($this->plugin_path.'options/users_log.php');
            else
                include($this->plugin_path.'options/users.php');
        }

        function star_menu_cats(){
            $options = $this->o;
            include($this->plugin_path.'options/categories.php');
        }
        // menues

        // widgets
        function widget_init() {
            if ($this->o["widget_articles"] == 1) $this->widget_articles_init();
            if ($this->o["widget_top"] == 1) $this->widget_top_init();
            if ($this->o["widget_comments"] == 1) $this->widget_comments_init();
        }

        function widget_comments_init() {
            if (!$options = get_option('widget_gdstarrating_comments'))
                $options = array();

            $widget_ops = array('classname' => 'widget_gdstarrating_comments', 'description' => 'GD Comments Rating');
            $control_ops = array('width' => $this->wp_old ? 580 : 440, 'height' => 420, 'id_base' => 'gdstarcmm');
            $name = 'GD Comments Rating';

            $registered = false;
            foreach (array_keys($options) as $o) {
                if (!isset($options[$o]['title']))
                    continue;

                $id = "gdstarcmm-$o";
                $registered = true;
                wp_register_sidebar_widget($id, $name, array(&$this, 'widget_comments_display'), $widget_ops, array( 'number' => $o ) );
                wp_register_widget_control($id, $name, array(&$this, 'widget_comments_control'), $control_ops, array( 'number' => $o ) );
            }
            if (!$registered) {
                wp_register_sidebar_widget('gdstarcmm-1', $name, array(&$this, 'widget_comments_display'), $widget_ops, array( 'number' => -1 ) );
                wp_register_widget_control('gdstarcmm-1', $name, array(&$this, 'widget_comments_control'), $control_ops, array( 'number' => -1 ) );
            }
        }

        function widget_comments_control($widget_args = 1) {
            global $wp_registered_widgets;
            static $updated = false;

            if ( is_numeric($widget_args) )
                $widget_args = array('number' => $widget_args);

            $widget_args = wp_parse_args($widget_args, array('number' => -1));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating_comments');
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
                    if ('widget_gdstarrating_comments' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                        $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                        if (!in_array("gdstarcmm-$widget_number", $_POST['widget-id']))
                            unset($options_all[$widget_number]);
                    }
                }
                foreach ((array)$_POST['gdstart'] as $widget_number => $posted) {
                    if (!isset($posted['title']) && isset($options_all[$widget_number]))
                        continue;
                    $options = array();

                    $options['title'] = strip_tags(stripslashes($posted['title']));

                    $options_all[$widget_number] = $options;
                }
                update_option('widget_gdstarrating_comments', $options_all);
                $updated = true;
            }

            if (-1 == $number) {
                $wpnm = '%i%';
                $wpno = $this->default_widget_comments;
            }
            else {
                $wpnm = $number;
                $wpno = $options_all[$number];
            }

            $wpfn = 'gdstart['.$wpnm.']';

            include($this->plugin_path."widgets/widget_comments.php");
        }

        function widget_comments_display($args, $widget_args = 1) {
            extract($args);
            global $wpdb, $userdata;

            if (is_numeric($widget_args))
                $widget_args = array('number' => $widget_args);
            $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
            extract($widget_args, EXTR_SKIP);
            $options_all = get_option('widget_gdstarrating_comments');
            if (!isset($options_all[$number]))
                return;
            $this->w = $options_all[$number];

            echo $before_widget.$before_title.$this->w['title'].$after_title;
            echo $this->render_comments_widget($this->w);
            echo $after_widget;
        }

        function render_comments_widget($widget) {

        }

        function widget_top_init() {
            if (!$options = get_option('widget_gdstarrating_top'))
                $options = array();
                
            $widget_ops = array('classname' => 'widget_gdstarrating_top', 'description' => 'GD Blog Rating');
            $control_ops = array('width' => $this->wp_old ? 580 : 440, 'height' => 420, 'id_base' => 'gdstartop');
            $name = 'GD Blog Rating';
            
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
                    $options['select'] = $posted['select'];
                    $options['show'] = $posted['show'];

                    $options['div_template'] = $posted['div_template'];
                    $options['div_filter'] = $posted['div_filter'];
                    $options['div_elements'] = $posted['div_elements'];

                    $options['template'] = stripslashes(htmlentities($posted['template'], ENT_QUOTES, STARRATING_ENCODING));
                    
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
            
            include($this->plugin_path."widgets/widget_top.php");
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
            echo $this->render_top_widget($this->w);
            echo $after_widget;
        }
        
        function render_top_widget($widget) {
            $data = $this->prepare_blog_rating($widget);

            if ($data->voters == 1) $tense = $this->x["word_votes_singular"];
            else $tense = $this->x["word_votes_plural"];
            $template = html_entity_decode($widget["template"]);
            $rt = str_replace('%PERCENTAGE%', $data->percentage, $template);
            $rt = str_replace('%RATING%', $data->rating, $rt);
            $rt = str_replace('%MAX_RATING%', $data->max_rating, $rt);
            $rt = str_replace('%VOTES%', $data->voters, $rt);
            $rt = str_replace('%COUNT%', $data->count, $rt);
            $rt = str_replace('%BAYES_RATING%', $data->bayes_rating, $rt);
            $rt = str_replace('%WORD_VOTES%', __($tense), $rt);
            return $rt;
        }
        
        function get_blog_rating($select = "postpage", $show = "total") {
            $widget["select"] = $select;
            $widget["show"] = $show;
            return $this->prepare_blog_rating($widget);
        }

        function widget_articles_init() {
            if (!$options = get_option('widget_gdstarrating'))
                $options = array();
                
            $widget_ops = array('classname' => 'widget_gdstarrating', 'description' => 'GD Star Rating');
            $control_ops = array('width' => $this->wp_old ? 580 : 440, 'height' => 420, 'id_base' => 'gdstarrmulti');
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
                    $options['tpl_header'] = stripslashes(htmlentities($posted['tpl_header'], ENT_QUOTES, STARRATING_ENCODING));
                    $options['tpl_item'] = stripslashes(htmlentities($posted['tpl_item'], ENT_QUOTES, STARRATING_ENCODING));
                    $options['tpl_footer'] = stripslashes(htmlentities($posted['tpl_footer'], ENT_QUOTES, STARRATING_ENCODING));
                    $options['tpl_title_length'] = $posted['title_max'];
                    
                    $options['rows'] = $posted['rows'];
                    $options['min_votes'] = $posted['min_votes'];
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
            
            include($this->plugin_path."widgets/widget_rating.php");
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
            echo $this->render_articles_widget($this->w);
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

            return html_entity_decode($widget["tpl_footer"]);
        }
        // widgets

        // ccookies
        /**
        * Check the cookie for the given id and type to see if the visitor is already voted for it
        * 
        * @param int $id post or comment id depending on $type
        * @param string $type article or comment
        * @return bool true if cookie exists for $id and $type, false if is not
        */
        function check_cookie($id, $type = "article") {
            if (
                ($type == "article" && $this->o["cookies"]) ||
                ($type == "multis" && $this->o["cookies"] == 1) ||
                ($type == "comment" && $this->o["cmm_cookies"])
                ) {
                if (isset($_COOKIE["wp_gdsr_".$type])) {
                    $cookie = $_COOKIE["wp_gdsr_".$type];
                    $cookie = substr($cookie, 7, strlen($cookie) - 7);
                    $cookie_ids = explode('|', $cookie);
                    if (in_array($id, $cookie_ids))
                        return false;
                }
            }
            return true;
        }

        /**
        * Saves the vote in the cookie for the given id and type
        * 
        * @param int $id post or comment id depending on $type
        * @param string $type article or comment
        */
        function save_cookie($id, $type = "article") {
            if (
                ($type == "article" && $this->o["cookies"] == 1) ||
                ($type == "multis" && $this->o["cookies"] == 1) ||
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

        // rendering
        function render_wait_article() {
            $cls = "loader ".$this->o["wait_loader_article"]." ";
            if ($this->o["wait_show_article"] == 1)
                $cls.= "width ";
            $cls.= $this->o["wait_class_article"];
            $div = '<div class="'.$cls.'" style="height: '.$this->o["size"].'px">';
            if ($this->o["wait_show_article"] == 0) {
                $padding = "";
                if ($this->o["size"] > 20) $padding = ' style="padding-top: '.(($this->o["size"] / 2) - 10).'px"';
                $div.= '<div class="loaderinner"'.$padding.'>'.__($this->o["wait_text_article"]).'</div>';
            }
            $div.= '</div>';
            $this->loader_article = $div;
        }

        function render_wait_multis() {
            $cls = "loader ".$this->o["wait_loader_multis"]." ";
            if ($this->o["wait_show_multis"] == 1)
                $cls.= "width ";
            $cls.= $this->o["wait_class_multis"];
            $div = '<div class="'.$cls.'" style="height: '.$this->o["mur_size"].'px">';
            if ($this->o["wait_show_multis"] == 0) {
                $padding = "";
                if ($this->o["size"] > 20) $padding = ' style="padding-top: '.(($this->o["mur_size"] / 2) - 10).'px"';
                $div.= '<div class="loaderinner"'.$padding.'>'.__($this->o["wait_text_multis"]).'</div>';
            }
            $div.= '</div>';
            $this->loader_multis = $div;
        }

        function render_wait_comment() {
            $cls = "loader ".$this->o["wait_loader_comment"]." ";
            if ($this->o["wait_show_comment"] == 1)
                $cls.= "width ";
            $cls.= $this->o["wait_class_comment"];
            $div = '<div class="'.$cls.'" style="height: '.$this->o["cmm_size"].'px">';
            if ($this->o["wait_show_comment"] == 0) {
                $padding = "";
                if ($this->o["cmm_size"] > 20) $padding = ' style="padding-top: '.(($this->o["cmm_size"] / 2) - 10).'px"';
                $div.= '<div class="loaderinner"'.$padding.'>'.__($this->o["wait_text_comment"]).'</div>';
            }
            $div.= '</div>';
            $this->loader_comment = $div;
        }

        function display_comment($content) {
            global $post, $comment, $userdata;
            
            if ($comment->comment_type == "pingback" && $this->o["display_trackback"] == 0)
                return $content;

            if ((is_single() && !is_admin() && $this->o["display_comment"] == 1) ||
                (is_page() && !is_admin() && $this->o["display_comment_page"] == 1))
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

        function get_article_rating($post_id, $is_page = '') {
            $post_data = GDSRDatabase::get_post_data($post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($post_id, $is_page);
                $post_data = GDSRDatabase::get_post_data($post_id);
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
            $out[] = $votes;
            $out[] = $score;

            return $out;
        }

        function render_rating_text_article($post, $cls = "") {
            $rd_post_id = intval($post->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            list($votes, $score) = $this->get_article_rating($rd_post_id, $rd_is_page);
            
            echo GDSRRender::rating_text($rd_post_id, "a", $votes, $score, $this->o["stars"], "article", $cls == "" ? $this->o["class_text"] : $cls);
        }

        function render_comment($post, $comment, $user) {
            if ($this->o["comments_active"] != 1) return "";
            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = true;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            $rd_unit_width = $this->o["cmm_size"];
            $rd_unit_count = $this->o["cmm_stars"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_comment_id = intval($comment->comment_ID);

            if ($this->p)
                $post_data = $this->p;
            else if (is_single() || is_page()) {
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

            if ($allow_vote) {
                if ($this->o["cmm_author_vote"] == 1 && $rd_user_id == $comment->user_id && $rd_user_id > 0) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }
            
            if ($allow_vote) {
                if (
                    ($post_data->rules_comments == "") ||
                    ($post_data->rules_comments == "A") || 
                    ($post_data->rules_comments == "U" && $rd_user_id > 0) || 
                    ($post_data->rules_comments == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$post_data->rules_comments;
                }
            }

            if ($allow_vote) {
                $allow_vote = GDSRDatabase::check_vote($rd_comment_id, $rd_user_id, 'comment', $_SERVER["REMOTE_ADDR"], $this->o["cmm_logged"] != 1);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_comment_id, "comment");
                if (!$allow_vote) $dbg_allow = "C";
            }

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

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $comment->user_id ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";
            return GDSRRender::rating_block($rd_comment_id, "ratecmm", "c", $votes, $score, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "comment", $this->o["cmm_align"], $this->o["cmm_text"], $this->o["cmm_header"], $this->o["cmm_header_text"], $this->o["cmm_class_block"], $this->o["cmm_class_text"], $this->o["ajax"], $debug, $this->loader_comment);
        }

        function render_article_rss() {
            global $post;
            $rd_post_id = intval($post->ID);
            $post_data = GDSRDatabase::get_post_data($rd_post_id);
            
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

            $rating_block = GDSRRender::rss_rating_block($rd_post_id, $votes, $score, $this->o["rss_style"], $this->o["rss_size"], $this->o["stars"], $this->o["rss_render"], $this->o["rss_text"], $this->o["rss_header"], $this->o["rss_header_text"]);
            return $rating_block;
        }

        function render_article($post, $user, $override = array()) {
            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = true;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }
            
            if (is_single() || (is_page() && $this->o["display_comment_page"] == 1))
                $this->init_post();
            
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
            if ($post_data->rules_articles == "H") return "";
            
            if ($allow_vote) {
                if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            if ($allow_vote) {
                if (
                    ($post_data->rules_articles == "") ||
                    ($post_data->rules_articles == "A") ||
                    ($post_data->rules_articles == "U" && $rd_user_id > 0) || 
                    ($post_data->rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$post_data->rules_articles;
                }
            }
            
            $remaining = 0;
            if ($allow_vote && ($post_data->expiry_type == 'D' || $post_data->expiry_type == 'T')) {
                switch($post_data->expiry_type) {
                    case "D":
                        $remaining = GDSRHelper::expiration_date($post_data->expiry_value);
                        $deadline = $post_data->expiry_value;
                        break;
                    case "T":
                        $remaining = GDSRHelper::expiration_countdown($post->post_date, $post_data->expiry_value);
                        $deadline = GDSRHelper::calculate_deadline($remaining);
                        break; 
                }
                if ($remaining < 1) {
                    GDSRDatabase::lock_post($rd_post_id);
                    $allow_vote = false;
                    $dbg_allow = "T";
                }
            }

            if ($allow_vote) {
                $allow_vote = GDSRDatabase::check_vote($rd_post_id, $rd_user_id, 'article', $_SERVER["REMOTE_ADDR"], $this->o["logged"] != 1);
                if (!$allow_vote) $dbg_allow = "D";
            }
                
            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_post_id);
                if (!$allow_vote) $dbg_allow = "C";
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

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $post->post_author ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";
            $rating_block = GDSRRender::rating_block($rd_post_id, "ratepost", "a", $votes, $score, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "article", $this->o["align"], $this->o["text"], $this->o["header"], $this->o["header_text"], $this->o["class_block"], $this->o["class_text"], $this->o["ajax"], $debug, $this->loader_article, $post_data->expiry_type, $remaining, $deadline);
            return $rating_block;
        }

        function render_multi_rating($post, $user, $settings, $override = array()) {
            if ($this->is_bot) return "";
            if (is_feed()) return "";

            $set = gd_get_multi_set($settings["id"]);
            if ($set == null) return "";
            
            $dbg_allow = "F";
            $allow_vote = true;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            if ($settings["read_only"] == 1) {
                $dbg_allow = "RO";
                $allow_vote = false;
            }

            if (is_single() || (is_page() && $this->o["display_comment_page"] == 1))
                $this->init_post();

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
            if ($post_data->rules_articles == "H") return "";

            if ($allow_vote) {
                if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            if ($allow_vote) {
                if (
                    ($post_data->rules_articles == "") ||
                    ($post_data->rules_articles == "A") ||
                    ($post_data->rules_articles == "U" && $rd_user_id > 0) ||
                    ($post_data->rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$post_data->rules_articles;
                }
            }

            if ($allow_vote && ($post_data->expiry_type == 'D' || $post_data->expiry_type == 'T')) {
                switch($post_data->expiry_type) {
                    case "D":
                        $remaining = GDSRHelper::expiration_date($post_data->expiry_value);
                        $deadline = $post_data->expiry_value;
                        break;
                    case "T":
                        $remaining = GDSRHelper::expiration_countdown($post->post_date, $post_data->expiry_value);
                        $deadline = GDSRHelper::calculate_deadline($remaining);
                        break;
                }
                if ($remaining < 1) {
                    GDSRDatabase::lock_post($rd_post_id);
                    $allow_vote = false;
                    $dbg_allow = "T";
                }
            }

            if ($allow_vote) {
                $allow_vote = GDSRDBMulti::check_vote($rd_post_id, $rd_user_id, $set->multi_id, 'multis', $_SERVER["REMOTE_ADDR"], $this->o["logged"] != 1);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_post_id."#".$set->multi_id, "multis");
                if (!$allow_vote) $dbg_allow = "C";
            }

            $multi_record_id = GDSRDBMulti::get_vote($rd_post_id, $set->multi_id, count($set->object));
            $multi_data = GDSRDBMulti::get_values($multi_record_id);

            wp_gdsr_dump("RAW", $multi_data);

            $votes = array();
            foreach ($multi_data as $md) {
                $single_vote["votes"] = 0;
                $single_vote["score"] = 0;

                if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                    $single_vote["votes"] = $md->user_voters + $md->visitor_voters;
                    $single_vote["score"] = $md->user_votes + $md->visitor_votes;
                }
                else if ($post_data->rules_articles == "V") {
                    $single_vote["votes"] = $md->visitor_voters;
                    $single_vote["score"] = $md->visitor_votes;
                }
                else {
                    $single_vote["votes"] = $md->user_voters;
                    $single_vote["score"] = $md->user_votes;
                }
                if ($single_vote["votes"] > 0) $rating = $single_vote["score"] / $single_vote["votes"];
                else $rating = 0;
                if ($rating > $set->stars) $rating = $set->stars;
                $single_vote["rating"] = @number_format($rating, 1);

                $votes[] = $single_vote;
            }

            wp_gdsr_dump("CALC", $votes);

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $post->post_author ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";
            return GDSRRender::multi_rating_block($this->loader_article, $allow_vote, $votes, $debug, $rd_post_id, $set, $this->o["mur_size"], $this->o["mur_header"], $this->o["mur_header_text"], $this->o["mur_class_block"], $this->o["mur_class_text"], $this->o["mur_class_table"], $this->o["mur_class_button"], $post_data->expiry_type, $remaining, $deadline, $this->o["mur_button_active"] == 1, $this->o["mur_button_text"]);
        }
        // rendering
    }

    $gd_debug = new gdDebug(STARRATING_LOG_PATH);
    $gdsr = new GDStarRating();

    include(STARRATING_PATH."gd-star-custom.php");
}
