<?php

/*
Plugin Name: GD Star Rating
Plugin URI: http://www.gdstarrating.com/
Description: GD Star Rating plugin allows you to set up advanced rating and review system for posts, pages and comments in your blog using single, multi and thumbs ratings.
Version: 1.6.0
Author: Milan Petrovic
Author URI: http://www.dev4press.com/

== Copyright ==

Copyright 2008-2009 Milan Petrovic (email: milan@gdragon.info)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/code/defaults.php");
require_once(dirname(__FILE__)."/code/results_classes.php");
require_once(dirname(__FILE__)."/code/standard_render.php");
require_once(dirname(__FILE__)."/code/helpers.php");
require_once(dirname(__FILE__)."/code/db/main.php");
require_once(dirname(__FILE__)."/code/db/operations.php");
require_once(dirname(__FILE__)."/code/db/widgetizer.php");
require_once(dirname(__FILE__)."/code/db/multi.php");
require_once(dirname(__FILE__)."/code/gfx/charting.php");
require_once(dirname(__FILE__)."/code/gfx/gfx_lib.php");
require_once(dirname(__FILE__)."/code/gfx/generator.php");
require_once(dirname(__FILE__)."/code/query.php");
require_once(dirname(__FILE__)."/code/cache.php");
require_once(dirname(__FILE__)."/gdt2/classes.php");
require_once(dirname(__FILE__)."/code/t2/render.php");
require_once(dirname(__FILE__)."/code/widgets.php");
require_once(dirname(__FILE__)."/code/widgets_wp28.php");
require_once(dirname(__FILE__)."/gdragon/gd_functions.php");
require_once(dirname(__FILE__)."/gdragon/gd_debug.php");
require_once(dirname(__FILE__)."/gdragon/gd_db_install.php");
require_once(dirname(__FILE__)."/gdragon/gd_wordpress.php");

if (!class_exists('GDStarRating')) {
    /**
    * Main plugin class
    */
    class GDStarRating {
        var $is_bot = false;
        var $is_ban = false;
        var $is_ie6 = false;
        var $is_cached = false;
        var $is_update = false;

        var $is_cached_integration_std = false;
        var $is_cached_integration_mur = false;

        var $use_nonce = true;
        var $extra_folders = false;
        var $safe_mode = false;
        var $widget_post_id;
        var $cats_data_posts = array();
        var $cats_data_cats = array();

        var $loader_article_thumb = "";
        var $loader_comment_thumb = "";
        var $loader_article = "";
        var $loader_comment = "";
        var $loader_multis = "";
        var $wpr8_available = false;
        var $admin_plugin = false;
        var $admin_plugin_page = '';
        var $admin_page;
        var $widgets;

        var $active_wp_page;
        var $wp_version;
        var $vote_status;
        var $rendering_sets = null;
        var $override_readonly_standard = false;
        var $override_readonly_multis = false;

        var $plugin_url;
        var $plugin_ajax;
        var $plugin_path;
        var $plugin_xtra_url;
        var $plugin_xtra_path;
        var $plugin_chart_url;
        var $plugin_chart_path;
        var $plugin_cache_path;
        var $plugin_wpr8_path;
        var $post_comment;
        var $wpr8;

        var $l; // language
        var $o; // options
        var $w; // widget options
        var $t; // database tables
        var $p; // post data
        var $e; // blank image
        var $i; // import
        var $g; // gfx
        var $q; // query class instance
        var $c; // cached post ids
        var $qc; // query class instance for comments
        var $ginc;
        var $bots;

        var $shortcodes;
        var $stars_sizes;
        var $thumb_sizes;
        var $default_shortcode_starrating;
        var $default_shortcode_starratingmulti;
        var $default_shortcode_starreviewmulti;
        var $default_shortcode_starcomments;
        var $default_shortcode_starrater;
        var $default_shortcode_starthumbsblock;
        var $default_shortcode_starreview;
        var $default_options;
        var $default_import;
        var $default_widget_comments;
        var $default_widget_top;
        var $default_widget;
        var $default_spider_bots;
        var $default_wpr8;

        /**
        * Constructor method
        */
        function GDStarRating() {
            $gdd = new GDSRDefaults();
            $this->shortcodes = $gdd->shortcodes;
            $this->stars_sizes = $gdd->stars_sizes;
            $this->thumb_sizes = $gdd->thumb_sizes;
            $this->default_spider_bots = $gdd->default_spider_bots;
            $this->default_wpr8 = $gdd->default_wpr8;
            $this->default_shortcode_starrating = $gdd->default_shortcode_starrating;
            $this->default_shortcode_starratingmulti = $gdd->default_shortcode_starratingmulti;
            $this->default_shortcode_starreviewmulti = $gdd->default_shortcode_starreviewmulti;
            $this->default_shortcode_starcomments = $gdd->default_shortcode_starcomments;
            $this->default_shortcode_starrater = $gdd->default_shortcode_starrater;
            $this->default_shortcode_starthumbsblock = $gdd->default_shortcode_starthumbsblock;
            $this->default_shortcode_starreview = $gdd->default_shortcode_starreview;
            $this->default_options = $gdd->default_options;
            $this->default_import = $gdd->default_import;
            $this->default_widget_comments = $gdd->default_widget_comments;
            $this->default_widget_top = $gdd->default_widget_top;
            $this->default_widget = $gdd->default_widget;
            define('STARRATING_INSTALLED', $this->default_options["version"]." ".$this->default_options["status"]);

            $this->c = array();
            $this->q = new GDSRQuery();
            // $this->qc = new GDSRQueryComments();

            $this->tabpage = "front";
            $this->log_file = STARRATING_LOG_PATH;

            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();

            if ($this->o["ajax_jsonp"] == 1) $this->plugin_ajax.= "?callback=?";
            define('STARRATING_AJAX', $this->plugin_ajax);
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
            $sc_name = $scode;
            $sc_method = "shortcode_".$scode;
            if (is_array($scode)) {
                $sc_name = $scode["name"];
                $sc_method = $scode["method"];
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
            return $this->shortcode_starratingblock($atts);
	}

        /**
        * Code for StarThumbsBlock shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starthumbsblock($atts = array()) {
            global $post, $userdata;
            $override = shortcode_atts($this->default_shortcode_starthumbsblock, $atts);
            $user_id = $userdata->ID;
            $this->cache_posts($user_id);
            return $this->render_thumb_article($post, $userdata, $override);
        }

        /**
        * Code for StarRatingBlock shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starratingblock($atts = array()) {
            global $post, $userdata;
            $override = shortcode_atts($this->default_shortcode_starrater, $atts);
            $user_id = $userdata->ID;
            $this->cache_posts($user_id);
            return $this->render_article($post, $userdata, $override);
        }

        /**
        * Code for StarRating shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starrating($atts = array()) {
            $sett = shortcode_atts($this->default_shortcode_starrating, $atts);
            return GDSRRenderT2::render_srr($sett);
        }

        /**
        * Code for StarComments shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starcomments($atts = array()) {
            $rating = "";
            $sett = shortcode_atts($this->default_shortcode_starcomments, $atts);
            if ($sett["post"] == 0) {
                global $post;
                $sett["post"] = $post->ID;
                $sett["comments"] = $post->comment_count;
            } else {
                $post = get_post($sett["post"]);
                $sett["comments"] = $post->comment_count;
            }
            if ($post->ID > 0) {
                $rows = GDSRDatabase::get_comments_aggregation($sett["post"], $sett["show"]);
                $totel_comments = count($rows);
                $total_voters = 0;
                $total_votes = 0;
                $calc_rating = 0;
                foreach ($rows as $row) {
                    switch ($sett["show"]) {
                        default:
                        case "total":
                            $total_voters += $row->user_voters + $row->visitor_voters;
                            $total_votes += $row->user_votes + $row->visitor_votes;
                            break;
                        case "users":
                            $total_voters += $row->user_voters;
                            $total_votes += $row->user_votes;
                            break;
                        case "visitors":
                            $total_voters += $row->visitor_voters;
                            $total_votes += $row->visitor_votes;
                            break;
                    }
                }
                if ($total_voters > 0) $calc_rating = $total_votes / $total_voters;
                $calc_rating = number_format($calc_rating, 1);
                $rating = GDSRRenderT2::render_car($sett["tpl"], $total_voters, $calc_rating, $sett["comments"], ($this->is_ie6 ? $this->o["cmm_aggr_style_ie6"] : $this->o["cmm_aggr_style"]), $this->o['cmm_aggr_size'], $this->o["cmm_stars"]);
            }
            return $rating;
        }

        /**
        * Code for StarReview shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starreview($atts = array()) {
            $sett = shortcode_atts($this->default_shortcode_starreview, $atts);
            if ($sett["post"] == 0) {
                global $post;
                $sett["post"] = $post->ID;
            }

            $rating = GDSRDatabase::get_review($sett["post"]);
            if ($rating < 0) $rating = 0;
            return GDSRRenderT2::render_rsb($sett["tpl"], $rating, ($this->is_ie6 ? $this->o["review_style_ie6"] : $this->o["review_style"]), $this->o['review_size'], $this->o["review_stars"], $this->o["review_header_text"], $this->o["review_class_block"]);
	}

        /**
        * Code for StarReviewMulti shortcode implementation
        *
        * @param array $atts
        */
        function shortcode_starreviewmulti($atts = array()) {
            $settings = shortcode_atts($this->default_shortcode_starreviewmulti, $atts);
            $post_id = $settings["post_id"];
            if ($post_id == 0) {
                global $post;
                $post_id = $post->ID;
            }
            if ($settings["id"] == 0) $multi_id = $this->o["mur_review_set"];
            else $multi_id = $settings["id"];
            $set = gd_get_multi_set($multi_id);
            if ($multi_id > 0 && $post_id > 0) {
                $vote_id = GDSRDBMulti::get_vote($post_id, $multi_id, count($set->object));
                $multi_data = GDSRDBMulti::get_values($vote_id, 'rvw');
                $votes = array();
                foreach ($multi_data as $md) {
                    $single_vote = array();
                    $single_vote["votes"] = 1;
                    $single_vote["score"] = $md->user_votes;
                    $single_vote["rating"] = $md->user_votes;
                    $votes[] = $single_vote;
                }
                $avg_rating = GDSRDBMulti::get_multi_review_average($vote_id);
                return GDSRRenderT2::render_rmb($settings["tpl"], $votes, $post_id, $set, $avg_rating, $settings["element_stars"], $settings["element_size"], $settings["average_stars"], $settings["average_size"]);
            }
            else return '';
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
        // shortcodes

        // various rendering
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
        function display_comment_review($comment_id, $use_default = true, $style = "oxygen", $size = 20) {
            $review = wp_gdget_comment_review($comment_id);
            if ($review < 1) return "";
                else {if ($use_default) {
                    $style = ($this->is_ie6 ? $this->o["cmm_review_style_ie6"] : $this->o["cmm_review_style"]);
                    $size = $this->o["cmm_review_size"];
                }
                $stars = $this->o["cmm_review_stars"];
                return GDSRRender::render_static_stars($style, $size, $stars, $review);
            }
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
        function display_article_review($post_id, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = ($this->is_ie6 ? $this->o["review_style_ie6"] : $this->o["review_style"]);
                $size = $this->o["review_size"];
            }
            $stars = $this->o["review_stars"];
            $review = GDSRDatabase::get_review($post_id);
            if ($review < 0) $review = 0;

            return GDSRRender::render_static_stars($style, $size, $stars, $review);
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
        function display_multis_review($multi_id, $post_id, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = ($this->is_ie6 ? $this->o["review_style_ie6"] : $this->o["review_style"]);
                $size = $this->o["review_size"];
            }
            $set = gd_get_multi_set($multi_id);
            $stars = $set->stars;
            $review = GDSRDBMulti::get_review_avg($multi_id, $post_id);
            if ($review < 0) $review = 0;

            return GDSRRender::render_static_stars($style, $size, $stars, $review);
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
        function display_article_rating($post_id, $use_default = true, $style = "oxygen", $size = 20) {
            if ($use_default) {
                $style = ($this->is_ie6 ? $this->o["style_ie6"] : $this->o["style"]);
                $size = $this->o["size"];
            }
            $stars = $this->o["stars"];
            $rating = $this->get_article_rating_simple($post_id);

            return GDSRRender::render_static_stars($style, $size, $stars, $rating);
        }

        /**
         * Renders single rating stars image with average rating for the multi rating post results from rating or review.
         *
         * @param int $post_id id of the post rating will be attributed to
         * @param bool $review if set to true average of review will be rendered
         * @param array $settings override settings for rendering the block
         */
        function get_multi_average_rendered($post_id, $settings = array()) {
            if ($settings["id"] == "") $multi_id = $this->o["mur_review_set"];
            else $multi_id = $settings["id"];
            if ($multi_id > 0 && $post_id > 0) {
                $set = gd_get_multi_set($multi_id);
                $data = GDSRDBMulti::get_averages($post_id, $multi_id);
                if ($set != null) {
                    if ($settings["render"] == "review") {
                        $review = GDSRRender::render_static_stars(($this->is_ie6 ? $this->o["mur_style_ie6"] : $this->o["mur_style"]), $this->o['mur_size'], $set->stars, $data->average_review);
                        return $review;
                    } else {
                        switch ($settings["show"]) {
                            case "visitors":
                                $rating = $data->average_rating_visitors;
                                break;
                            case "users":
                                $rating = $data->average_rating_users;
                                break;
                            case "total":
                                $sum = $data->average_rating_users * $data->total_votes_users + $data->average_rating_visitors * $data->total_votes_visitors;
                                $votes = $data->total_votes_users + $data->total_votes_visitors;
                                $rating = number_format($votes == 0 ? 0 : $sum / $votes, 1);
                                break;
                        }
                        $rating = GDSRRender::render_static_stars(($this->is_ie6 ? $this->o["mur_style_ie6"] : $this->o["mur_style"]), $this->o['mur_size'], $set->stars, $rating);
                        return $rating;
                    }
                }
            }
            $max = is_null($set) ? 10 : $set->stars;
            $rating = GDSRRender::render_static_stars(($this->is_ie6 ? $this->o["mur_style_ie6"] : $this->o["mur_style"]), $this->o['mur_size'], $max, 0);
            return $rating;
        }

        /**
         * Renders multi rating review editor block.
         *
         * @param int $post_id id of the post to render review editor for
         * @param bool $admin wheter the rendering is for admin edit post page or not
         * @return string rendered result
         */
        function blog_multi_review_editor($post_id, $settings = array(), $admin = true) {
            if ($settings["id"] == "") $multi_id = $this->o["mur_review_set"];
            else $multi_id = $settings["id"];
            $set = gd_get_multi_set($multi_id);
            if (is_null($set)) {
                $set = gd_get_multi_set();
                $multi_id = $set->multi_id;
            }
            if ($multi_id > 0 && $post_id > 0) {
                $vote_id = GDSRDBMulti::get_vote($post_id, $multi_id, count($set->object));
                $multi_data = GDSRDBMulti::get_values($vote_id, 'rvw');
                if (count($multi_data) == 0) {
                    GDSRDBMulti::add_empty_review_values($vote_id, count($set->object));
                    $multi_data = GDSRDBMulti::get_values($vote_id, 'rvw');
                }
            } else $multi_data = array();

            $votes = array();
            foreach ($multi_data as $md) {
                $single_vote = array();
                $single_vote["votes"] = 1;
                $single_vote["score"] = $md->user_votes;
                $single_vote["rating"] = $md->user_votes;
                $votes[] = $single_vote;
            }
            if ($admin) include($this->plugin_path.'integrate/edit_multi.php');
            else return GDSRRenderT2::render_mre("oxygen", intval($settings["tpl"]), true, $votes, $post_id, $set, 20);
        }
        // various rendering

        // edit boxes
        /**
         * Insert edit box for a comment on edit comment panel.
         */
        function editbox_comment() {
            if ($this->wp_version < 27)
                include($this->plugin_path.'integrate/editcomment26.php');
            else {
                if ($this->admin_page != "edit-comments.php") return;
                include($this->plugin_path.'integrate/editcomment27.php');
            }
        }

        /**
         * Insert box multi review on post edit panel.
         */
        function editbox_post_mur() {
            global $post;
            $post_id = $post->ID;
            $this->blog_multi_review_editor($post_id);
        }

        /**
         * Insert plugin box on post edit panel.
         */
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
            } else {
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
            if ($this->wp_version < 27) {
                add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"));
                if ($this->o["integrate_post_edit_mur"] == 1) {
                    add_meta_box("gdsr-meta-box", "GD Star Rating: ".__("Multi Ratings Review", "gd-star-rating"), array(&$this, 'editbox_post_mur'), "post", "advanced", "high");
                    add_meta_box("gdsr-meta-box", "GD Star Rating: ".__("Multi Ratings Review", "gd-star-rating"), array(&$this, 'editbox_post_mur'), "page", "advanced", "high");
                }
            } else {
                add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"), plugins_url('gd-star-rating/gfx/menu.png'));
                if ($this->o["integrate_post_edit"] == 1) {
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_post'), "post", "side", "high");
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_post'), "page", "side", "high");
                }
                if ($this->o["integrate_post_edit_mur"] == 1) {
                    add_meta_box("gdsr-meta-box-mur", "GD Star Rating: ".__("Multi Ratings Review", "gd-star-rating"), array(&$this, 'editbox_post_mur'), "post", "advanced", "high");
                    add_meta_box("gdsr-meta-box-mur", "GD Star Rating: ".__("Multi Ratings Review", "gd-star-rating"), array(&$this, 'editbox_post_mur'), "page", "advanced", "high");
                }
                if ($this->o["integrate_comment_edit"] == 1) {
                    add_meta_box("gdsr-meta-box", "GD Star Rating", array(&$this, 'editbox_comment'), "comments", "side", "high");
                }
            }

            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Front Page", "gd-star-rating"), __("Front Page", "gd-star-rating"), 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Builder", "gd-star-rating"), __("Builder", "gd-star-rating"), 10, "gd-star-rating-builder", array(&$this,"star_menu_builder"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Articles", "gd-star-rating"), __("Articles", "gd-star-rating"), 10, "gd-star-rating-stats", array(&$this,"star_menu_stats"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Categories", "gd-star-rating"), __("Categories", "gd-star-rating"), 10, "gd-star-rating-cats", array(&$this,"star_menu_cats"));
            if ($this->o["admin_users"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("Users", "gd-star-rating"), __("Users", "gd-star-rating"), 10, "gd-star-rating-users", array(&$this,"star_menu_users"));
            if ($this->o["multis_active"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("Multi Sets", "gd-star-rating"), __("Multi Sets", "gd-star-rating"), 10, "gd-star-rating-multi-sets", array(&$this,"star_multi_sets"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Settings", "gd-star-rating"), __("Settings", "gd-star-rating"), 10, "gd-star-rating-settings-page", array(&$this,"star_menu_settings"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Graphics", "gd-star-rating"), __("Graphics", "gd-star-rating"), 10, "gd-star-rating-gfx-page", array(&$this,"star_menu_gfx"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Tools", "gd-star-rating"), __("Tools", "gd-star-rating"), 10, "gd-star-rating-tools", array(&$this,"star_menu_tools"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("T2 Templates", "gd-star-rating"), __("T2 Templates", "gd-star-rating"), 10, "gd-star-rating-t2", array(&$this,"star_menu_t2"));
            if ($this->o["admin_ips"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("IP's", "gd-star-rating"), __("IP's", "gd-star-rating"), 10, "gd-star-rating-ips", array(&$this,"star_menu_ips"));
            if ($this->o["admin_import"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("Import", "gd-star-rating"), __("Import", "gd-star-rating"), 10, "gd-star-rating-import", array(&$this,"star_menu_import"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Export", "gd-star-rating"), __("Export", "gd-star-rating"), 10, "gd-star-rating-export", array(&$this,"star_menu_export"));
            $this->custom_actions('admin_menu');
            if ($this->o["admin_setup"] == 1)
                add_submenu_page(__FILE__, 'GD Star Rating: '.__("Setup", "gd-star-rating"), __("Setup", "gd-star-rating"), 10, "gd-star-rating-setup", array(&$this,"star_menu_setup"));
        }

        /**
         * WordPress action for adding administration header contents
         */
        function admin_head() {
            global $parent_file;
            $this->admin_page = $parent_file;
            $tabs_extras = "";
            $datepicker_date = "";

            if ($this->admin_plugin_page == "ips" && $_GET["gdsr"] == "iplist") $tabs_extras = ", selected: 1";
            if ($this->admin_plugin) {
                wp_admin_css('css/dashboard');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_main.css" type="text/css" media="screen" />');
                if ($this->wp_version >= 27 && $this->wp_version < 28) echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_wp27.css" type="text/css" media="screen" />');
                if ($this->wp_version >= 28) echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_wp28.css" type="text/css" media="screen" />');
                if ($this->wp_version < 27) echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_wp26.css" type="text/css" media="screen" />');
                if ($this->wp_version < 28) {
                    echo('<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui.js"></script>');
                    echo('<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-tabs.js"></script>');
                    echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.tabs.css" type="text/css" media="screen" />');
                } else {
                    echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.17.css" type="text/css" media="screen" />');
                }
                if ($this->admin_plugin_page == "t2" ||
                    $this->admin_plugin_page == "multi-sets") {
                    include(STARRATING_PATH."code/js/corrections.php");
                }
            }
            if ($this->admin_plugin || $this->admin_page == "edit-pages.php" || $this->admin_page == "edit.php" || $this->admin_page == "post-new.php" || $this->admin_page == "themes.php") {
                $datepicker_date = date("Y, n, j");
                if ($this->wp_version < 28) {
                    echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.core.css" type="text/css" media="screen" />');
                    echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/jquery/ui.theme.css" type="text/css" media="screen" />');
                    echo('<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-datepicker.js"></script>');
                } else {
                    echo('<script type="text/javascript" src="'.$this->plugin_url.'js/jquery-ui-datepicker-17.js"></script>');
                }
                if(!empty($this->l)) {
                    $jsFile = $this->plugin_path.'js/i18n/jquery-ui-datepicker-'.$this->l.'.js';
                    if (@file_exists($jsFile) && is_readable($jsFile)) echo '<script type="text/javascript" src="'.$this->plugin_url.'js/i18n'.($this->wp_version < 28 ? '' : '-17').'/jquery-ui-datepicker-'.$this->l.'.js"></script>';
                }
            }
            if ($this->admin_page == "edit-pages.php" || $this->admin_page == "edit.php" || $this->admin_page == "post-new.php") {
                echo('<script type="text/javascript" src="'.$this->plugin_url.'code/js/editors.php"></script>');
            }
            echo("\r\n");
            echo('<script type="text/javascript">jQuery(document).ready(function() {');
                echo("\r\n");
                if ($this->admin_page == "edit-comments.php") include ($this->plugin_path."code/js/integration.php");
                if ($this->admin_plugin) echo('jQuery("#gdsr_tabs'.($this->wp_version < 28 ? ' > ul' : '').'").tabs({fx: {height: "toggle"}'.$tabs_extras.' });');
                if ($this->admin_plugin || $this->admin_page == "edit.php" || $this->admin_page == "post-new.php" || $this->admin_page == "themes.php") echo('jQuery("#gdsr_timer_date_value").datepicker({duration: "fast", minDate: new Date('.$datepicker_date.'), dateFormat: "yy-mm-dd"});');
                if ($this->admin_plugin_page == "tools") echo('jQuery("#gdsr_lock_date").datepicker({duration: "fast", dateFormat: "yy-mm-dd"});');
                if ($this->admin_plugin_page == "settings-page") include(STARRATING_PATH."code/js/loaders.php");
                if ($this->admin_page == "edit.php" && $this->o["integrate_post_edit_mur"] == 1) {
                    echo("\r\n");
                    include(STARRATING_PATH."code/js/multi_in.php");
                }
            echo('});');
            if ($this->admin_page == "post-new.php" || $this->admin_page == "edit-pages.php" ||$this->admin_page == "edit.php") {
                $edit_std = $this->o["integrate_post_edit_mur"] == 1;
                $edit_mur = $this->o["integrate_post_edit"] == 1;
                echo("\r\n");
                include(STARRATING_PATH."code/js/editors.php");
            }
            echo('</script>');
            if (($this->admin_page == "edit-pages.php" || $this->admin_page == "edit.php") && $this->o["integrate_post_edit_mur"] == 1) {
                $this->include_rating_css_admin();
            }
            if ($this->admin_page == "widgets.php" || $this->admin_page == "themes.php") {
                if ($this->wp_version < 28) echo('<script type="text/javascript" src="'.$this->plugin_url.'js/rating-widgets.js"></script>');
                else echo('<script type="text/javascript" src="'.$this->plugin_url.'js/rating-widgets-28.js"></script>');
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_widgets.css" type="text/css" media="screen" />');
            }

            if ($this->admin_page == "edit-comments.php" || $this->admin_page == "comment.php") {
                $gfx_r = $this->g->find_stars($this->o["cmm_review_style"]);
                $comment_review = urlencode($this->o["cmm_review_style"]."|".$this->o["cmm_review_size"]."|".$this->o["cmm_review_stars"]."|".$gfx_r->type."|".$gfx_r->primary);
                echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_comment.css.php?stars='.$comment_review.'" type="text/css" media="screen" />');
            }

            $this->custom_actions('admin_head');

            if ($this->admin_plugin && $this->wp_version < 26)
                echo('<link rel="stylesheet" href="'.get_option('home').'/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" />');

            if ($this->admin_plugin_page == "builder")
                echo('<script type="text/javascript" src="'.$this->plugin_url.'tinymce3/tinymce.js"></script>');

            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin/admin_post.css" type="text/css" media="screen" />');
        }

        /**
         * WordPress action to get post ID's from active loop
         *
         * @param WP_Query $wpq query object
         * @return WP_Query query object
         */
        function loop_start($wp_query) {
            if (!is_admin()) {
                if ($this->wp_version < 28) global $wp_query;
                if (is_array($wp_query->posts)) {
                    foreach ($wp_query->posts as $p) {
                        if (!isset($this->c[$p->ID])) $this->c[$p->ID] = 0;
                    }
                }
            }
            if ($this->wp_version >= 28) return $wp_query;
        }

        /**
         * WordPress action to get and cache comments rating data for a post
         *
         * @param array $comments post comments
         * @param int $post_id post id
         * @return array post comments
         */
        function comments_array($comments, $post_id) {
            if (count($comments) > 0 && !is_admin()) {
                if ((is_single() && ($this->o["display_comment"] == 1 || $this->o["thumb_display_comment"] == 1)) ||
                    (is_page() && ($this->o["display_comment_page"] == 1 || $this->o["thumb_display_comment_page"] == 1)) ||
                    $this->o["override_thumb_display_comment"] == 1 || $this->o["override_display_comment"] == 1 ||
                    $this->qc->is_active) {
                        $this->cache_comments($post_id);
                }
            }
            return $comments;
        }

        /**
         * In progress...
         */
        function set_comment_reorder($params = array()) {
            // $this->qc->set($params);
        }

        /**
         * Adding WordPress action and filter
         */
        function actions_filters() {
            add_action('init', array(&$this, 'init'));
            add_action('wp_head', array(&$this, 'wp_head'));
            add_action('widgets_init', array(&$this, 'widgets_init'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('admin_head', array(&$this, 'admin_head'));
            add_action('loop_start', array(&$this, 'loop_start'));
            add_filter('comments_array', array(&$this, 'comments_array'), 10, 2);

            add_filter('query_vars', array($this->q, 'query_vars'));
            add_action('pre_get_posts', array($this->q, 'pre_get_posts'));

            if ($this->o["integrate_post_edit"] == 1) {
                if ($this->wp_version < 27) {
                    add_action('submitpost_box', array(&$this, 'editbox_post'));
                    add_action('submitpage_box', array(&$this, 'editbox_post'));
                }
            }
            if ($this->o["integrate_post_edit_mur"] == 1 || $this->o["integrate_post_edit"] == 1)
                add_action('save_post', array(&$this, 'saveedit_post'));
            if ($this->o["integrate_dashboard"] == 1) {
                add_action('wp_dashboard_setup', array(&$this, 'add_dashboard_widget'));
                if (!function_exists('wp_add_dashboard_widget'))
                    add_filter('wp_dashboard_widgets', array(&$this, 'add_dashboard_widget_filter'));
            }
            add_filter('comment_text', array(&$this, 'display_comment'));
            add_filter('the_content', array(&$this, 'display_article'));
            add_filter('preprocess_comment', array(&$this, 'comment_read_post'));
            add_filter('comment_post', array(&$this, 'comment_save'));
            if ($this->o["comments_review_active"] == 1) {
                if ($this->o["integrate_comment_edit"] == 1) {
                    if ($this->wp_version < 27) add_action('submitcomment_box', array(&$this, 'editbox_comment'));
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
                add_filter('the_content', array(&$this, 'rss_filter'));
            }

            add_action('delete_comment', array(&$this, 'comment_delete'));
            add_action('delete_post', array(&$this, 'post_delete'));
            add_filter('plugin_action_links', array(&$this, 'plugin_links'), 10, 2 );
            add_action('after_plugin_row', array(&$this,'plugin_check_version'), 10, 2);

            foreach ($this->shortcodes as $code) $this->shortcode_action($code);
        }

        /**
         * WordPress widgets init action
         */
        function widgets_init() {
            if ($this->wp_version < 28) {
                $this->widgets = new gdsrWidgets($this->g, $this->default_widget_comments, $this->default_widget_top, $this->default_widget, $this->wp_version < 27);
                if ($this->o["widget_articles"] == 1) $this->widgets->widget_articles_init();
                if ($this->o["widget_top"] == 1) $this->widgets->widget_top_init();
                if ($this->o["widget_comments"] == 1) $this->widgets->widget_comments_init();
            } else {
                if ($this->o["widget_articles"] == 1) register_widget("gdsrWidgetRating");
                if ($this->o["widget_top"] == 1) register_widget("gdsrWidgetTop");
                if ($this->o["widget_comments"] == 1) register_widget("gdsrWidgetComments");
            }
        }

        /**
         * Adds Settings link to plugins panel grid
         */
        function plugin_links($links, $file) {
            static $this_plugin;
            if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

            if ($file == $this_plugin){
                $settings_link = '<a href="admin.php?page=gd-star-rating-settings-page">'.__("Settings", "gd-star-rating").'</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

        /**
         * Render update info on the plugins panel if the update is available.
         *
         * @param string $file name of the plugin file
         * @param array $plugin_data plugin info
         * @return bool false if no update available
         */
        function plugin_check_version($file, $plugin_data) {
            static $this_plugin;
            if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

            if ($file == $this_plugin){
                $current = $this->wp_version < 28 ? get_option('update_plugins') : get_transient('update_plugins');
                if (!isset($current->response[$file])) return false;

                $columns = $this->wp_version < 28 ? 5 : 3;
                $url = gdFunctionsGDSR::get_update_url($this->o, get_option('home'));
                $update = wp_remote_fopen($url);
                if ($update != "") {
                    echo '<td colspan="'.$columns.'" class="gdr-plugin-update"><div class="gdr-plugin-update-message">';
                    echo $update;
                    echo '</div></td>';
                }
            }
        }

        /**
         * WordPress rss content filter
         */
        function rss_filter($content) {
            if (is_feed()) {
                if ($this->o["rss_active"] == 1) $content.= "<br />".$this->render_article_rss();
                if ($this->o["integrate_rss_powered"] == 1) $content.= "<br />".$this->powered_by();
                $content.= "<br />";
            }
            return $content;
        }

        /**
         * Renders tag with link and powered by button
         *
         * @return string rendered content
         */
        function powered_by() {
            return '<a target="_blank" href="http://www.gdstarrating.com/"><img src="'.STARRATING_URL.'gfx/powered.png" border="0" width="80" height="15" /></a>';
        }

        function add_dashboard_widget() {
            if (!function_exists('wp_add_dashboard_widget')) {
                wp_register_sidebar_widget("dashboard_gdstarrating_latest", "GD Star Rating ".__("Latest", "gd-star-rating"), array(&$this, 'display_dashboard_widget_latest'), array('all_link' => get_bloginfo('wpurl').'/wp-admin/admin.php?page=gd-star-rating/gd-star-rating.php', 'width' => 'half', 'height' => 'single'));
                wp_register_sidebar_widget("dashboard_gdstarrating_chart", "GD Star Rating ".__("Chart", "gd-star-rating"), array(&$this, 'display_dashboard_widget_chart'), array('all_link' => get_bloginfo('wpurl').'/wp-admin/admin.php?page=gd-star-rating/gd-star-rating.php', 'width' => 'half', 'height' => 'single'));
            } else {
                wp_add_dashboard_widget("dashboard_gdstarrating_latest", "GD Star Rating ".__("Latest", "gd-star-rating"), array(&$this, 'display_dashboard_widget_latest'));
                wp_add_dashboard_widget("dashboard_gdstarrating_chart", "GD Star Rating ".__("Chart", "gd-star-rating"), array(&$this, 'display_dashboard_widget_chart'));
            }
        }

        function add_dashboard_widget_filter($widgets) {
            global $wp_registered_widgets;

            if (!isset($wp_registered_widgets["dashboard_gdstarrating_chart"]) && !isset($wp_registered_widgets["dashboard_gdstarrating_latest"])) return $widgets;

            array_splice($widgets, 2, 0, "dashboard_gdstarrating_latest");
            array_splice($widgets, 2, 0, "dashboard_gdstarrating_chart");
            return $widgets;
        }

        function display_dashboard_widget_chart($sidebar_args) {
            if (!function_exists('wp_add_dashboard_widget')) {
                extract($sidebar_args, EXTR_SKIP);
                echo $before_widget.$before_title.$widget_name.$after_title;
            }
            include($this->plugin_path.'integrate/dash_chart.php');
            if (!function_exists('wp_add_dashboard_widget')) echo $after_widget;
        }

        function display_dashboard_widget_latest($sidebar_args) {
            if (!function_exists('wp_add_dashboard_widget')) {
                extract($sidebar_args, EXTR_SKIP);
                echo $before_widget.$before_title.$widget_name.$after_title;
            }
            $o = $this->o;
            include($this->plugin_path.'integrate/dash_latest.php');
            if (!function_exists('wp_add_dashboard_widget')) echo $after_widget;
        }

        function comment_read_post($comment) {
            $this->post_comment["post_id"] = $_POST["comment_post_ID"];
            $this->post_comment["review"] = isset($_POST["gdsr_cmm_value"]) ? intval($_POST["gdsr_cmm_value"]) : -1;
            $this->post_comment["standard_rating"] = isset($_POST["gdsr_int_value"]) ? intval($_POST["gdsr_int_value"]) : -1;
            $this->post_comment["multi_rating"] = isset($_POST["gdsr_mur_value"]) ? $_POST["gdsr_mur_value"] : "";
            $this->post_comment["multi_id"] = isset($_POST["gdsr_mur_set"]) ? intval($_POST["gdsr_mur_set"]) : 0;
            return $comment;
        }

        function comment_save($comment_id) {
            global $userdata;

            if ($this->post_comment["review"] > -1) {
                $comment_data = GDSRDatabase::get_comment_data($comment_id);
                if (count($comment_data) == 0) GDSRDatabase::add_empty_comment($comment_id, $this->post_comment["post_id"], $this->post_comment["review"]);
                else GDSRDatabase::save_comment_review($comment_id, $this->post_comment["review"]);
            }

            $std_minimum = $this->o["int_comment_std_zero"] == 1 ? -1 : 0;
            $mur_minimum = $this->o["int_comment_mur_zero"] == 1 ?  0 : 1;

            if ($this->post_comment["standard_rating"] > $std_minimum) {
                $ip = $_SERVER["REMOTE_ADDR"];
                $ua = $this->o["save_user_agent"] == 1 ? $_SERVER["HTTP_USER_AGENT"] : "";
                $user = intval($userdata->ID);
                GDSRDatabase::save_vote($this->post_comment["post_id"], $user, $ip, $ua, $this->post_comment["standard_rating"], $comment_id);
            }

            if ($this->post_comment["multi_id"] > 0 && $this->post_comment["multi_rating"] != "") {
                $set = gd_get_multi_set($this->post_comment["multi_id"]);
                $values = explode("X", $this->post_comment["multi_rating"]);
                $allow_vote = true;
                foreach ($values as $v) {
                    if ($v > $set->stars || $v < $mur_minimum) {
                        $allow_vote = false;
                        break;
                    }
                }
                if ($allow_vote) {
                    $ip = $_SERVER["REMOTE_ADDR"];
                    $ua = $this->o["save_user_agent"] == 1 ? $_SERVER["HTTP_USER_AGENT"] : "";
                    $user = intval($userdata->ID);
                    $data = GDSRDatabase::get_post_data($this->post_comment["post_id"]);
                    GDSRDBMulti::save_vote($this->post_comment["post_id"], $set->multi_id, $user, $ip, $ua, $values, $data, $comment_id);
                    GDSRDBMulti::recalculate_multi_averages($this->post_comment["post_id"], $set->multi_id, "", $set, true);
                }
            }
        }

        function comment_delete($comment_id) {
            GDSRDatabase::delete_by_comment($comment_id);
            GDSRDBMulti::delete_by_comment($comment_id);
        }

        function post_delete($post_id) {

        }

        function comment_edit_review($comment_content) {
            if ($_POST['gdsr_comment_edit'] == "edit") {
                $post_id = $_POST["comment_post_ID"];
                $comment_id = $_POST["comment_ID"];
                $value = isset($_POST["gdsr_cmm_review"]) ? $_POST["gdsr_cmm_review"] : -1;
                $comment_data = GDSRDatabase::get_comment_data($comment_id);
                if (count($comment_data) == 0) GDSRDatabase::add_empty_comment($comment_id, $post_id, $value);
                else GDSRDatabase::save_comment_review($comment_id, $value);
            }
            return $comment_content;
        }

        /**
         * Triggers saving GD Star Rating data for post.
         *
         * @param int $post_id ID of the post saving
         */
        function saveedit_post($post_id) {
            $post_id = $_POST["post_ID"];

            if ($_POST['gdsr_post_edit'] == "edit" || $_POST['gdsr_post_edit_mur'] == "edit") {
                if ($this->o["integrate_post_edit"] == 1) {
                    $set_id = $_POST["gdsrmultiactive"];
                    if ($set_id > 0) {
                        $mur = $_POST['gdsrmulti'];
                        $mur = $mur[$post_id][0];
                        $values = explode("X", $mur);
                        $set = gd_get_multi_set($set_id);
                        $record_id = GDSRDBMulti::get_vote($post_id, $set_id, count($set->object));
                        GDSRDBMulti::save_review($record_id, $values);
                        GDSRDBMulti::recalculate_multi_review($record_id, $values, $set);
                        $this->o["mur_review_set"] = $_POST["gdsrmultiset"];
                        update_option('gd-star-rating', $this->o);
                    }
                }

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
            $this->i = get_option('gd-star-rating-import');
            $this->g = get_option('gd-star-rating-gfx');
            $this->wpr8 = get_option('gd-star-rating-wpr8');
            $this->ginc = get_option('gd-star-rating-inc');
            $this->bots = get_option('gd-star-rating-bots');

            if ($this->o["build"] < $this->default_options["build"] || !is_array($this->o)) {
                if (is_object($this->g)) {
                    $this->g = $this->gfx_scan();
                    update_option('gd-star-rating-gfx', $this->g);
                }

                gdDBInstallGDSR::delete_tables(STARRATING_PATH);
                gdDBInstallGDSR::create_tables(STARRATING_PATH);
                gdDBInstallGDSR::upgrade_tables(STARRATING_PATH);
                gdDBInstallGDSR::alter_tables(STARRATING_PATH);
                gdDBInstallGDSR::alter_tables(STARRATING_PATH, "idx.txt");
                $this->o["database_upgrade"] = date("r");

                GDSRDB::install_all_templates();

                $this->o = gdFunctionsGDSR::upgrade_settings($this->o, $this->default_options);

                $this->o["css_last_changed"] = time();
                $this->o["version"] = $this->default_options["version"];
                $this->o["date"] = $this->default_options["date"];
                $this->o["status"] = $this->default_options["status"];
                $this->o["build"] = $this->default_options["build"];
                if ($this->o["css_last_changed"] == 0) $this->o["css_last_changed"] = time();

                $this->is_update = true;
                update_option('gd-star-rating', $this->o);
            }

            if (!is_array($this->o)) {
                update_option('gd-star-rating', $this->default_options);
                $this->o = get_option('gd-star-rating');
                gdDBInstallGDSR::create_tables(STARRATING_PATH);
            }

            if (!is_array($this->i)) {
                update_option('gd-star-rating-import', $this->default_import);
                $this->i = get_option('gd-star-rating-import');
            } else {
                $this->i = gdFunctionsGDSR::upgrade_settings($this->i, $this->default_import);
                update_option('gd-star-rating-import', $this->i);
            }

            if (!is_object($this->g)) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }

            if (!is_array($this->wpr8)) {
                update_option('gd-star-rating-wpr8', $this->default_wpr8);
                $this->wpr8 = get_option('gd-star-rating-wpr8');
            } else {
                $this->wpr8 = gdFunctionsGDSR::upgrade_settings($this->wpr8, $this->default_wpr8);
                update_option('gd-star-rating-wpr8', $this->wpr8);
            }

            if (!is_array($this->bots)) {
                $this->bots = $this->default_spider_bots;
                update_option('gd-star-rating-bots', $this->bots);
            }

            if (!is_array($this->ginc)) {
                $this->ginc = array();
                $this->ginc[] = $this->stars_sizes;
                $this->ginc[] = $this->g->get_list(true);
                $this->ginc[] = $this->thumb_sizes;
                $this->ginc[] = $this->g->get_list(false);
                update_option('gd-star-rating-inc', $this->ginc);
            }

            if (count($this->ginc) == 2) {
                $this->ginc[] = $this->thumb_sizes;
                $this->ginc[] = $this->g->get_list(false);
                update_option('gd-star-rating-inc', $this->ginc);
            }

            $this->use_nonce = $this->o["use_nonce"] == 1;
            define("STARRATING_VERSION", $this->o["version"].'_'.$this->o["build"]);
            define("STARRATING_DEBUG_ACTIVE", $this->o["debug_active"]);
            define("STARRATING_STARS_GENERATOR", $this->o["gfx_generator_auto"] == 0 ? "DIV" : "GFX");
            $this->t = GDSRDB::get_database_tables();
        }

        /**
         * Scans main and additional graphics folders for stars and trends sets.
         *
         * @return GDgfxLib scanned graphics object
         */
        function gfx_scan() {
            $data = new GDgfxLib();

            $stars_folders = gdFunctionsGDSR::get_folders($this->plugin_path."stars/");
            foreach ($stars_folders as $f) {
                $gfx = new GDgfxStar($f);
                if ($gfx->imported)
                    $data->stars[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."stars/")) {
                $stars_folders = gdFunctionsGDSR::get_folders($this->plugin_xtra_path."stars/");
                foreach ($stars_folders as $f) {
                    $gfx = new GDgfxStar($f, false);
                    if ($gfx->imported)
                        $data->stars[] = $gfx;
                }
            }

            $trend_folders = gdFunctionsGDSR::get_folders($this->plugin_path."trends/");
            foreach ($trend_folders as $f) {
                $gfx = new GDgfxTrend($f);
                if ($gfx->imported)
                    $data->trend[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."trends/")) {
                $trend_folders = gdFunctionsGDSR::get_folders($this->plugin_xtra_path."trends/");
                foreach ($trend_folders as $f) {
                    $gfx = new GDgfxTrend($f, false);
                    if ($gfx->imported)
                        $data->trend[] = $gfx;
                }
            }

            $thumbs_folders = gdFunctionsGDSR::get_folders($this->plugin_path."thumbs/");
            foreach ($thumbs_folders as $f) {
                $gfx = new GDgfxThumb($f);
                if ($gfx->imported)
                    $data->thumbs[] = $gfx;
            }
            if (is_dir($this->plugin_xtra_path."thumbs/")) {
                $thumbs_folders = gdFunctionsGDSR::get_folders($this->plugin_xtra_path."thumbs/");
                foreach ($thumbs_folders as $f) {
                    $gfx = new GDgfxThumb($f, false);
                    if ($gfx->imported)
                        $data->thumbs[] = $gfx;
                }
            }

            return $data;
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
            } else {
                $this->plugin_url = WP_PLUGIN_URL.'/gd-star-rating/';
                $this->plugin_ajax = $this->plugin_url.'ajax.php';
                $this->plugin_xtra_url = WP_CONTENT_URL.'/gd-star-rating/';
                $this->plugin_xtra_path = WP_CONTENT_DIR.'/gd-star-rating/';
                $this->plugin_cache_path = $this->plugin_xtra_path."cache/";
            }
            $this->plugin_path = dirname(__FILE__)."/";
            $this->e = $this->plugin_url."gfx/blank.gif";

            $this->plugin_wpr8_path = $this->plugin_path."wpr8/";
            $this->plugin_chart_path = $this->plugin_path."charts/";
            $this->plugin_chart_url = $this->plugin_url."charts/";

            if (is_dir($this->plugin_wpr8_path)) $this->wpr8_available = true;

            define('STARRATING_URL', $this->plugin_url);
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
            $this->init_uninstall();

            if ($this->is_update) {
                GDSRDatabase::init_categories_data();
            }

            define('STARRATING_ENCODING', $this->o["encoding"]);

            if (isset($_GET["page"])) {
                if (substr($_GET["page"], 0, 14) == "gd-star-rating") {
                    $this->admin_plugin = true;
                    $this->admin_plugin_page = substr($_GET["page"], 15);
                }
            }

            $this->init_operations();
            $this->init_templates();
            wp_enqueue_script('jquery');
            if ($this->wp_version >= 28 && is_admin()) {
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-tabs');
            }

            if (!is_admin()) {
                $this->is_bot = GDSRHelper::detect_bot($_SERVER['HTTP_USER_AGENT'], $this->bots);
                $this->is_ban = GDSRHelper::detect_ban();
                $this->render_wait_article();
                if ($this->o["comments_active"] == 1) $this->render_wait_comment();
                if ($this->o["multis_active"] == 1) $this->render_wait_multis();
                if ($this->o["thumbs_active"] == 1) {
                    $this->render_wait_article_thumb();
                    $this->render_wait_comment_thumb();
                }
                if ($this->o["external_javascript"] == 1) {
                    wp_enqueue_script("gdsr_script", plugins_url('gd-star-rating/script.js.php'), array(), $this->o["version"]);
                }
                if ($this->wp_version >= 27) {
                    if ($this->o["external_rating_css"] == 1) {
                        wp_enqueue_style("gdsr_style_main", $this->include_rating_css(true, true), array(), $this->o["version"]);
                    }
                    if ($this->o["external_css"] == 1 && file_exists($this->plugin_xtra_path."css/rating.css")) {
                        wp_enqueue_style("gdsr_style_xtra", $this->plugin_xtra_url."css/rating.css", array(), $this->o["version"]);
                    }
                }
            }
            else $this->cache_cleanup();

            if ($this->admin_plugin_page == "settings-page") {
                $gdsr_options = $this->o;
                include ($this->plugin_path."code/save_settings.php");
                $this->o = $gdsr_options;
            }

            if ($this->admin_plugin_page == "gfx-page") {
                $gdsr_options = $this->o;
                $ginc = $this->ginc;
                $ginc_sizes = $this->ginc[0];
                $ginc_stars = $this->ginc[1];
                $ginc_sizes_thumb = $this->ginc[2];
                $ginc_stars_thumb = $this->ginc[3];
                include ($this->plugin_path."code/save_gfx.php");
                $this->o = $gdsr_options;
                $this->ginc = $ginc;
            }

            if ($this->admin_plugin) {
                if ($this->wp_version >= 26) add_thickbox();
                else wp_enqueue_script("thickbox");
                $this->safe_mode = gdFunctionsGDSR::php_in_safe_mode();
                if (!$this->safe_mode)
                    $this->extra_folders = $this->o["cache_forced"] == 1 || GDSRHelper::create_folders($this->wp_version);
            }

            if (is_admin()) {
                $this->l = get_locale();
                if(!empty($this->l)) {
                    $moFile = dirname(__FILE__)."/languages/gd-star-rating-".$this->l.".mo";
                    if (@file_exists($moFile) && is_readable($moFile)) load_textdomain('gd-star-rating', $moFile);
                }
            }

            $this->is_cached = $this->o["cache_active"];
            $this->is_ie6 = $this->o["disable_ie6_check"] == 1 ? false : is_msie6();
            $this->custom_actions('init');

            if (is_admin() && $this->o["mur_review_set"] == 0) {
                $set = GDSRDBMulti::get_multis(0, 1);
                if (count($set) > 0) {
                    $this->o["mur_review_set"] = $set[0]->multi_id;
                    update_option('gd-star-rating', $this->o);
                }
            }
        }

        /**
         * WordPress action for adding blog header contents
         */
        function wp_head() {
            if (is_feed()) return;

            $include_cmm_review = $this->o["comments_review_active"] == 1;
            $include_mur_rating = $this->o["multis_active"] == 1;

            if ($this->o["external_rating_css"] == 0) $this->include_rating_css(false);
            else if ($this->wp_version < 27) {
                $this->include_rating_css(true);
            }
            if ($this->wp_version < 27 && $this->o["external_css"] == 1 && file_exists($this->plugin_xtra_path."css/rating.css")) {
                echo sprintf('<link rel="stylesheet" href="%s" type="text/css" media="screen" />', $this->plugin_xtra_url."css/rating.css");
            }
            if ($this->o["external_javascript"] == 0) {
                echo("\r\n");
                echo('<script type="text/javascript">');
                $nonce = $this->use_nonce ? wp_create_nonce('gdsr_ajax_r8') : "";
                $button_active = $this->o["mur_button_active"] == 1;
                echo('//<![CDATA[');
                include ($this->plugin_path."code/js/main.php");
                if ($this->o["cmm_integration_replay_hide_review"] == 1)
                    include ($gdsr->plugin_path."code/js/comments.php");
                echo('// ]]>');
                echo('</script>');
                echo("\r\n");
            }

            if ($this->o["debug_wpquery"] == 1) {
                global $wp_query;
                wp_gdsr_dump("WP_QUERY", $wp_query->request);
            }

            $this->custom_actions('wp_head');
            if ($this->o["ie_opacity_fix"] == 1) GDSRHelper::ie_opacity_fix();
        }

        /**
         * Prepare multi sets for rendering.
         */
        function prepare_multiset() {
            $this->rendering_sets = GDSRDBMulti::get_multisets_for_auto_insert();
            if (!is_array($this->rendering_sets)) $this->rendering_sets = array();
        }

        /**
         * Method executing cleanup of the cache files
         */
        function cache_cleanup() {
            if ($this->o["cache_cleanup_auto"] == 1) {
                $clean = false;

                $pdate = strtotime($this->o["cache_cleanup_last"]);
                $next_clean = mktime(date("H", $pdate), date("i", $pdate), date("s", $pdate), date("m", $pdate) + $this->o["cache_cleanup_days"], date("j", $pdate), date("Y", $pdate));
                if (intval($next_clean) < intval(mktime())) $clean = true;

                if ($clean) {
                    GDSRHelper::clean_cache(substr(STARRATING_CACHE_PATH, 0, strlen(STARRATING_CACHE_PATH) - 1));
                    $this->o["cache_cleanup_last"] = date("r");
                    update_option('gd-star-rating', $this->o);
                }
            }
        }

        function init_uninstall() {
            if (isset($_POST["gdsr_full_uninstall"]) && $_POST["gdsr_full_uninstall"] == __("UNINSTALL", "gd-star-rating")) {
                delete_option('gd-star-rating');
                delete_option('widget_gdstarrating');
                delete_option('gd-star-rating-import');
                delete_option('gd-star-rating-gfx');
                delete_option('gd-star-rating-inc');

                gdDBInstallGDSR::drop_tables(STARRATING_PATH);
                GDSRHelper::deactivate_plugin();
                update_option('recently_activated', array("gd-star-rating/gd-star-rating.php" => time()) + (array)get_option('recently_activated'));
                wp_redirect('index.php');
                exit;
            }
        }

        function init_operations() {
            $msg = "";
            if (isset($_POST["gdsr_multi_review_form"]) && $_POST["gdsr_multi_review_form"] == "review") {
                $mur_all = $_POST['gdsrmulti'];
                $set_id = $this->o["mur_review_set"];
                foreach ($mur_all as $post_id => $data) {
                    $mur = $data[0];
                    $values = explode("X", $mur);
                    $set = gd_get_multi_set($set_id);
                    $record_id = GDSRDBMulti::get_vote($post_id, $set_id, count($set->object));
                    GDSRDBMulti::save_review($record_id, $values);
                    GDSRDBMulti::recalculate_multi_review($record_id, $values, $set);
                }
                $this->custom_actions('init_save_review');
                wp_redirect_self();
                exit;
            }

            if (isset($_POST["gdsr_editcss_rating"])) {
                $rating_css = STARRATING_XTRA_PATH."css/rating.css";
                if (is_writeable($rating_css)) {
                    $newcontent = stripslashes($_POST['gdsr_editcss_contents']);
                    $f = fopen($rating_css, 'w+');
                    fwrite($f, $newcontent);
                    fclose($f);
                }
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_debug_clean'])) {
                wp_gdsr_debug_clean();
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_cache_clean'])) {
                GDSRHelper::clean_cache(substr(STARRATING_CACHE_PATH, 0, strlen(STARRATING_CACHE_PATH) - 1));
                $this->o["cache_cleanup_last"] = date("r");
                update_option('gd-star-rating', $this->o);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_preview_scan'])) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_t2_import'])) {
                GDSRDB::insert_extras_templates(STARRATING_XTRA_PATH, false);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_upgrade_tool'])) {
                gdDBInstallGDSR::delete_tables(STARRATING_PATH);
                gdDBInstallGDSR::create_tables(STARRATING_PATH);
                gdDBInstallGDSR::upgrade_tables(STARRATING_PATH);
                gdDBInstallGDSR::alter_tables(STARRATING_PATH);
                gdDBInstallGDSR::alter_tables(STARRATING_PATH, "idx.txt");
                $this->o["database_upgrade"] = date("r");
                update_option('gd-star-rating', $this->o);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_updatemultilog_tool'])) {
                GDSRDBMulti::recalculate_multi_rating_log();
                //wp_redirect_self();
                //exit;
            }

            if (isset($_POST['gdsr_mulitrecalc_tool'])) {
                $set_id = $_POST['gdsr_mulitrecalc_set'];
                if ($set_id > 0) GDSRDBMulti::recalculate_set($set_id);
                else GDSRDBMulti::recalculate_all_sets();
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_cleanup_tool'])) {
                if (isset($_POST['gdsr_tools_clean_invalid_log'])) {
                    $count = GDSRDBTools::clean_invalid_log_articles();
                    if ($count > 0) $msg.= $count." ".__("articles records from log table removed.", "gd-star-rating")." ";
                    $count = GDSRDBTools::clean_invalid_log_comments();
                    if ($count > 0) $msg.= $count." ".__("comments records from log table removed.", "gd-star-rating")." ";
                }
                if (isset($_POST['gdsr_tools_clean_invalid_trend'])) {
                    $count = GDSRDBTools::clean_invalid_trend_articles();
                    if ($count > 0) $msg.= $count." ".__("articles records from trends log table removed.", "gd-star-rating")." ";
                    $count = GDSRDBTools::clean_invalid_trend_comments();
                    if ($count > 0) $msg.= $count." ".__("comments records from trends log table removed.", "gd-star-rating")." ";
                }
                if (isset($_POST['gdsr_tools_clean_old_posts'])) {
                    $count = GDSRDBTools::clean_dead_articles();
                    if ($count > 0) $msg.= $count." ".__("dead articles records from articles table.", "gd-star-rating")." ";
                    $count = GDSRDBTools::clean_revision_articles();
                    if ($count > 0) $msg.= $count." ".__("post revisions records from articles table.", "gd-star-rating")." ";
                    $count = GDSRDBTools::clean_dead_comments();
                    if ($count > 0) $msg.= $count." ".__("dead comments records from comments table.", "gd-star-rating")." ";
                }
                if (isset($_POST['gdsr_tools_clean_old_posts'])) {
                    $count = GDSRDBMulti::clean_dead_articles();
                    if ($count > 0) $msg.= $count." ".__("dead articles records from multi ratings tables.", "gd-star-rating")." ";
                    $count = GDSRDBMulti::clean_revision_articles();
                    if ($count > 0) $msg.= $count." ".__("post revisions records from multi ratings tables.", "gd-star-rating")." ";
                }
                $this->o["database_cleanup"] = date("r");
                $this->o["database_cleanup_msg"] = $msg;
                update_option('gd-star-rating', $this->o);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_post_lock'])) {
                $lock_date = $_POST['gdsr_lock_date'];
                GDSRDatabase::lock_post_massive($lock_date);
                $this->o["mass_lock"] = $lock_date;
                update_option('gd-star-rating', $this->o);
                wp_redirect_self();
                exit;
            }

            if (isset($_POST['gdsr_rules_set'])) {
                GDSRDatabase::update_settings_full($_POST["gdsr_article_moderation"], $_POST["gdsr_article_voterules"], $_POST["gdsr_comments_moderation"], $_POST["gdsr_comments_voterules"]);
                wp_redirect_self();
                exit;
            }
        }

        function init_templates() {
            if (isset($_GET["deltpl"])) {
                $del_id = $_GET["deltpl"];
                gdTemplateDB::delete_template($del_id);
                $url = remove_query_arg("deltpl");
                wp_redirect($url);
                exit;
            }

            if (isset($_POST["gdsr_save_tpl"])) {
                $general = array();
                $general["name"] = stripslashes(htmlentities($_POST['tpl_gen_name'], ENT_QUOTES, STARRATING_ENCODING));
                $general["desc"] = stripslashes(htmlentities($_POST['tpl_gen_desc'], ENT_QUOTES, STARRATING_ENCODING));
                $general["section"] = $_POST["tpl_section"];
                $general["dependencies"] = $_POST["tpl_tpl"];
                $general["id"] = $_POST["tpl_id"];
                $general["preinstalled"] = '0';
                $tpl_input = $_POST["tpl_element"];
                $elements = array();
                foreach ($tpl_input as $key => $value)
                    $elements[$key] = stripslashes(htmlentities($value, ENT_QUOTES, STARRATING_ENCODING));
                if ($general["id"] == 0) gdTemplateDB::add_template($general, $elements);
                else gdTemplateDB::edit_template($general, $elements);
                $url = remove_query_arg("tplid");
                $url = remove_query_arg("mode", $url);
                wp_redirect($url);
                exit;
            }
        }

        function init_post_categories_data($post_id) {
            if (count($this->cats_data_posts[$post_id]) == 0) {
                $cats = wp_get_post_categories($post_id);
                $this->cats_data_posts[$post_id] = GDSRDatabase::get_categories_data($cats);
            }
        }

        function init_cats_categories_data($cat_id) {
            if (count($this->cats_data_cats[$cat_id]) == 0) {
                $this->cats_data_cats[$cat_id] = GDSRDatabase::get_categories_data(array($cat_id));
            }
        }

        function get_taxonomy_multi_ratings($taxonomy = "category", $term = "", $multi_id = 0, $size = 20, $style = "oxygen") {
            $results = GDSRDBTools::taxonomy_multi_ratings($taxonomy, $term, $multi_id);
            $set = wp_gdget_multi_set($multi_id);
            $new_results = array();

            foreach ($results as $row) {
                $row->votes = $row->user_votes + $row->visitor_votes;
                $row->voters = $row->user_voters + $row->visitor_voters;
                $row->rating = $row->voters == 0 ? 0 : @number_format($row->votes / $row->voters, 1);
                $row->review = @number_format($row->review, 1);
                $row->bayesian = $bayesian_calculated ? $gdsr->bayesian_estimate($row->voters, $row->rating) : -1;
                $row->rating_stars = GDSRRender::render_static_stars($style, $size, $set->stars, $row->rating);
                $row->bayesian_stars = GDSRRender::render_static_stars($style, $size, $set->stars, $row->bayesian);
                $row->review_stars = GDSRRender::render_static_stars($style, $size, $set->stars, $row->review);
                $new_results[] = $row;
            }

            if (count($new_results) == 1) return $new_results[0];
            else return $new_results;
        }

        function get_article_rating_simple($post_id) {
            $rating = 0;

            list($votes, $score) = $this->get_article_rating($post_id);
            if ($votes > 0) $rating = $score / $votes;

            $rating = @number_format($rating, 1);
            return $rating;
        }

        function get_post_rule_value($post_id, $rule = "rules_articles", $default = "default_voterules_articles") {
            $this->init_post_categories_data($post_id);

            $prn = 0;
            $value = "";
            foreach ($this->cats_data_posts[$post_id] as $cat) {
                if ($cat->parent > 0 && $prn == 0) $prn = $cat->parent;
                if ($cat->$rule != "" && $value == "") $value = $cat->$rule;
                if ($value != "" || ($value != "" && $prn > 0)) break;
            }

            if ($value != "P") return $value;
            if ($prn > 0) {
                $value = $this->get_post_rule_value_recursion($prn, $rule);
                if ($value != "P" && $value != "") return $value;
            }
            return $this->o[$default];
        }

        function get_post_rule_value_recursion($cat_id, $rule = "rules_articles") {
            $this->init_cats_categories_data($cat_id);

            if (count($this->cats_data_cats[$cat_id]) == 0) return 0;
            $cat = $this->cats_data_cats[$cat_id][0];
            if ($cat->$rule != "P" && $cat->$rule != "") return $cat->$rule;
            if ($cat->parent > 0) return $this->get_post_rule_value_recursion($cat->parent, $rule);
            return "";
        }

        function get_multi_set($post_id) {
            $this->init_post_categories_data($post_id);

            $set = $prn = 0;
            foreach ($this->cats_data_posts[$post_id] as $cat) {
                if ($cat->parent > 0 && $prn == 0) $prn = $cat->parent;
                if ($cat->cmm_integration_set > 0 && $set == 0) $set = $cat->cmm_integration_set;
                if ($set > 0 || ($set > 0 && $prn > 0)) break;
            }

            if ($set > 0) return $set;
            if ($prn > 0) {
                $set = $this->get_multi_set_recursion($prn);
                if ($set > 0) return $set;
                $first = GDSRDBMulti::get_first_multi_set();
                return $first->multi_set;
            } else return 0;
        }

        function get_multi_set_recursion($cat_id) {
            $this->init_cats_categories_data($cat_id);

            if (count($this->cats_data_cats[$cat_id]) == 0) return 0;
            $cat = $this->cats_data_cats[$cat_id][0];
            if ($cat->cmm_integration_set > 0) return $cat->cmm_integration_set;
            if ($cat->parent > 0) return $this->get_multi_set_recursion($cat->parent);
            return 0;
        }

        function include_rating_css_admin() {
            $elements = array();
            $presizes = "m".gdFunctionsGDSR::prefill_zeros(20, 2);
            $sizes = array(20);
            $elements[] = $presizes;
            $elements[] = join("", $sizes);
            $elements[] = join("", $sizes);
            $elements[] = "s1poxygen";
            $elements[] = "t1pstarrating";
            $q = join("#", $elements);
            $t = $this->o["css_cache_active"] == 1 ? $this->o["css_last_changed"] : 0;
            $url = $this->plugin_url.'css/gdsr.css.php?t='.urlencode($t).'&amp;s='.urlencode($q);
            echo('<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen" />');
        }

        function include_rating_css($external = true, $return = false) {
            $elements = array();

            $presizes = "a".gdFunctionsGDSR::prefill_zeros($this->o["stars"], 2);
            $presizes.= "i".gdFunctionsGDSR::prefill_zeros($this->o["stars"], 2);
            $presizes.= "m".gdFunctionsGDSR::prefill_zeros(20, 2);
            $presizes.= "k".gdFunctionsGDSR::prefill_zeros(20, 2);
            $presizes.= "c".gdFunctionsGDSR::prefill_zeros($this->o["cmm_stars"], 2);
            $presizes.= "r".gdFunctionsGDSR::prefill_zeros($this->o["cmm_review_stars"], 2);
            $elements[] = $presizes;

            $star_sizes = array();
            foreach ($this->ginc[0] as $size => $var) {
                if ($var == 1) $star_sizes[] = $size;
            }
            if (count($star_sizes) == 0) $star_sizes[] = 24;
            $elements[] = join("", $star_sizes);

            $thumb_sizes = array();
            foreach ($this->ginc[2] as $size => $var) {
                if ($var == 1) $thumb_sizes[] = $size;
            }
            if (count($thumb_sizes) == 0) $thumb_sizes[] = 24;
            $elements[] = join("", $thumb_sizes);

            if (!is_array($this->ginc[1])) $elements[] = "spstarrating";
            else {
                foreach($this->g->stars as $s) {
                    if (in_array($s->folder, $this->ginc[1]))
                        $elements[] = "s".$s->primary.substr($s->type, 0, 1).$s->folder;
                }
            }

            if (!is_array($this->ginc[3])) $elements[] = "tpstarrating";
            else {
                foreach($this->g->thumbs as $s) {
                    if (in_array($s->folder, $this->ginc[3]))
                        $elements[] = "t".$s->primary.substr($s->type, 0, 1).$s->folder;
                }
            }

            $q = join("#", $elements);
            $t = $this->o["css_cache_active"] == 1 ? $this->o["css_last_changed"] : 0;
            if ($external) {
                $url = $this->plugin_url.'css/gdsr.css.php?t='.urlencode($t).'&amp;s='.urlencode($q);
                if ($return) return $url;
                else echo('<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen" />');
            } else {
                echo('<style type="text/css" media=screen>');
                $inclusion = "internal";
                $base_url_local = $this->plugin_url;
                $base_url_extra = $this->plugin_xtra_url;
                include ($this->plugin_path."css/gdsr.css.php");
                echo('</style>');
            }
        }

        function multi_rating_header($external_css = true) {
            $this->include_rating_css($external_css);
            echo('<script type="text/javascript" src="'.$this->plugin_url.'script.js.php"></script>');
        }
        // install

        // vote
        function vote_thumbs_article($vote, $id, $tpl_id, $unit_width) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $this->o["save_user_agent"] == 1 ? $_SERVER["HTTP_USER_AGENT"] : "";
            $user = intval($userdata->ID);

wp_gdsr_dump("VOTE_THUMB", "[POST: ".$id."] --".$vote."-- [".$user."] ".$unit_width."px");

            $allow_vote = $vote == "up" || $vote == "dw";
            if ($allow_vote) $allow_vote = $this->check_cookie($id, "artthumb");
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'artthumb', $ip, $this->o["logged"] != 1, $this->o["allow_mixed_ip_votes"] == 1);

            $vote_value = $vote == "up" ? 1 : -1;
            if ($allow_vote) {
                GDSRDatabase::save_vote_thumb($id, $user, $ip, $ua, $vote_value);
                $this->save_cookie($id, "artthumb");

                do_action("gdsr_vote_thumb_article", $id, $user, $vote_value);
            }

            $data = GDSRDatabase::get_post_data($id);
            $votes = $score = $votes_plus = $votes_minus = 0;

            if ($data->rules_articles == "A" || $data->rules_articles == "N") {
                $votes = $data->user_recc_plus + $data->user_recc_minus + $data->visitor_recc_plus + $data->visitor_recc_minus;
                $score = $data->user_recc_plus - $data->user_recc_minus + $data->visitor_recc_plus - $data->visitor_recc_minus;
                $votes_plus = $data->user_recc_plus + $data->visitor_recc_plus;
                $votes_minus = $data->user_recc_minus + $data->visitor_recc_minus;
            } else if ($data->rules_articles == "V") {
                $votes = $data->visitor_recc_plus + $data->visitor_recc_minus;
                $score = $data->visitor_recc_plus - $data->visitor_recc_minus;
                $votes_plus = $data->visitor_recc_plus;
                $votes_minus = $data->visitor_recc_minus;
            } else {
                $votes = $data->user_recc_plus + $data->user_recc_minus;
                $score = $data->user_recc_plus - $data->user_recc_minus;
                $votes_plus = $data->user_recc_plus;
                $votes_minus = $data->user_recc_minus;
            }

            include($this->plugin_path.'code/t2/templates.php');

            $template = new gdTemplateRender($tpl_id, "TAB");
            $rt = GDSRRenderT2::render_tat_voted($template->dep["TAT"], $votes, $score, $votes_plus, $votes_minus, $id, $vote_value);

            return "{ status: 'ok', value: ".$score.", rater: '".$rt."' }";
        }

        function vote_thumbs_comment($vote, $id, $tpl_id, $unit_width) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $this->o["save_user_agent"] == 1 ? $_SERVER["HTTP_USER_AGENT"] : "";
            $user = intval($userdata->ID);

wp_gdsr_dump("VOTE THUMB", "[CMM: ".$id."] --".$vote."-- [".$user."] ".$unit_width."px");

            $allow_vote = $vote == "up" || $vote == "dw";
            if ($allow_vote) $allow_vote = $this->check_cookie($id, 'cmmthumb');
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'cmmthumb', $ip, $this->o["cmm_logged"] != 1, $this->o["cmm_allow_mixed_ip_votes"] == 1);

            $vote_value = $vote == "up" ? 1 : -1;
            if ($allow_vote) {
                GDSRDatabase::save_vote_comment_thumb($id, $user, $ip, $ua, $vote_value);
                $this->save_cookie($id, 'cmmthumb');

                do_action("gdsr_vote_thumb_comment", $id, $user, $vote_value);
            }

            $data = GDSRDatabase::get_comment_data($id);
            $post_data = GDSRDatabase::get_post_data($data->post_id);

            $votes = $score = $votes_plus = $votes_minus = 0;

            if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                $votes = $data->user_recc_plus + $data->user_recc_minus + $data->visitor_recc_plus + $data->visitor_recc_minus;
                $score = $data->user_recc_plus - $data->user_recc_minus + $data->visitor_recc_plus - $data->visitor_recc_minus;
                $votes_plus = $data->user_recc_plus + $data->visitor_recc_plus;
                $votes_minus = $data->user_recc_minus + $data->visitor_recc_minus;
            } else if ($post_data->rules_articles == "V") {
                $votes = $data->visitor_recc_plus + $data->visitor_recc_minus;
                $score = $data->visitor_recc_plus - $data->visitor_recc_minus;
                $votes_plus = $data->visitor_recc_plus;
                $votes_minus = $data->visitor_recc_minus;
            } else {
                $votes = $data->user_recc_plus + $data->user_recc_minus;
                $score = $data->user_recc_plus - $data->user_recc_minus;
                $votes_plus = $data->user_recc_plus;
                $votes_minus = $data->user_recc_minus;
            }

            include($this->plugin_path.'code/t2/templates.php');

            $template = new gdTemplateRender($tpl_id, "TCB");
            $rt = GDSRRenderT2::render_tct($template->dep["TCT"], $votes, $score, $votes_plus, $votes_minus, $id, $vote_value);

            return "{ status: 'ok', value: ".$score.", rater: '".$rt."' }";
        }

        function vote_multis($votes, $post_id, $set_id, $tpl_id, $size) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            $user = intval($userdata->ID);
            $data = GDSRDatabase::get_post_data($post_id);
            $set = gd_get_multi_set($set_id);

wp_gdsr_dump("VOTE_MUR", "[POST: ".$post_id."|SET: ".$set_id."] --".$votes."-- [".$user."] ".$unit_width."px");

            $values = explode("X", $votes);
            $allow_vote = true;
            foreach ($values as $v) {
                if ($v > $set->stars) {
                    $allow_vote = false;
                    break;
                }
            }

            $vote_value = GDSRDBMulti::recalculate_multi_vote($values, $set);
            if ($allow_vote) $allow_vote = $this->check_cookie($post_id."#".$set_id, "multis");
            if ($allow_vote) $allow_vote = GDSRDBMulti::check_vote($post_id, $user, $set_id, 'multis', $ip, $this->o["logged"] != 1, $this->o["mur_allow_mixed_ip_votes"] == 1);

            $rating = 0;
            $total_votes = 0;
            $json = array();

            if ($allow_vote) {
                GDSRDBMulti::save_vote($post_id, $set_id, $user, $ip, $ua, $values, $data);
                $summary = GDSRDBMulti::recalculate_multi_averages($post_id, $set_id, $data->rules_articles, $set, true, $size);
                $this->save_cookie($post_id."#".$set_id, "multis");
                $rating = $summary["total"]["rating"];
                $total_votes = $summary["total"]["votes"];
                $json = $summary["json"];

                do_action("gdsr_vote_rating_multis", $post_id, $user, $set_id, $values);
            }

            include($this->plugin_path.'code/t2/templates.php');

            $template = new gdTemplateRender($tpl_id, "MRB");
            $rt = GDSRRenderT2::render_srt_voted($template->dep["MRT"], $rating, $set->stars, $total_votes, $post_id, $vote_value);
            $enc_values = "[".join(",", $json)."]";

            return "{ status: 'ok', values: ".$enc_values.", rater: '".$rt."', average: '".$rating."' }";
        }

        function vote_article($votes, $id, $tpl_id, $unit_width) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $this->o["save_user_agent"] == 1 ? $_SERVER["HTTP_USER_AGENT"] : "";
            $user = intval($userdata->ID);
            $vote_value = $votes;

wp_gdsr_dump("VOTE", "[POST: ".$id."] --".$votes."-- [".$user."] ".$unit_width."px");

            $allow_vote = intval($votes) <= $this->o["stars"] && intval($votes) > 0;
            if ($allow_vote) $allow_vote = $this->check_cookie($id);
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip, $this->o["logged"] != 1, $this->o["allow_mixed_ip_votes"] == 1);

            if ($allow_vote) {
                GDSRDatabase::save_vote($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id);

                do_action("gdsr_vote_rating_article", $id, $user, $votes);
            }

            $data = GDSRDatabase::get_post_data($id);
            $unit_count = $this->o["stars"];
            $votes = $score = 0;

            if ($data->rules_articles == "A" || $data->rules_articles == "N") {
                $votes = $data->user_voters + $data->visitor_voters;
                $score = $data->user_votes + $data->visitor_votes;
            } else if ($data->rules_articles == "V") {
                $votes = $data->visitor_voters;
                $score = $data->visitor_votes;
            } else {
                $votes = $data->user_voters;
                $score = $data->user_votes;
            }

            if ($votes > 0) $rating2 = $score / $votes;
            else $rating2 = 0;
            $rating1 = @number_format($rating2, 1);
            $rating_width = @number_format($rating2 * $unit_width, 0);

            include($this->plugin_path.'code/t2/templates.php');

            $template = new gdTemplateRender($tpl_id, "SRB");
            $rt = GDSRRenderT2::render_srt_voted($template->dep["SRT"], $rating1, $unit_count, $votes, $post_id, $vote_value);

            return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
        }

        function vote_comment($votes, $id, $tpl_id, $unit_width) {
            global $userdata;
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($this->o["save_user_agent"] == 1) $ua = $_SERVER["HTTP_USER_AGENT"];
            else $ua = "";
            $user = intval($userdata->ID);
            $vote_value = $votes;

wp_gdsr_dump("VOTE_CMM", "[CMM: ".$id."] --".$votes."-- [".$user."] ".$unit_width."px");

            $allow_vote = intval($votes) <= $this->o["cmm_stars"] && intval($votes) > 0;
            if ($allow_vote) $allow_vote = $this->check_cookie($id, 'comment');
            if ($allow_vote) $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip, $this->o["cmm_logged"] != 1, $this->o["cmm_allow_mixed_ip_votes"] == 1);

            if ($allow_vote) {
                GDSRDatabase::save_vote_comment($id, $user, $ip, $ua, $votes);
                $this->save_cookie($id, 'comment');

                do_action("gdsr_vote_rating_comment", $id, $user, $votes);
            }

            $data = GDSRDatabase::get_comment_data($id);
            $post_data = GDSRDatabase::get_post_data($data->post_id);
            $unit_count = $this->o["cmm_stars"];
            $votes = $score = 0;

            if ($post_data->rules_comments == "A" || $post_data->rules_comments == "N") {
                $votes = $data->user_voters + $data->visitor_voters;
                $score = $data->user_votes + $data->visitor_votes;
            } else if ($post_data->rules_comments == "V") {
                $votes = $data->visitor_voters;
                $score = $data->visitor_votes;
            } else {
                $votes = $data->user_voters;
                $score = $data->user_votes;
            }

            if ($votes > 0) $rating2 = $score / $votes;
            else $rating2 = 0;
            $rating1 = @number_format($rating2, 1);
            $rating_width = number_format($rating2 * $unit_width, 0);

            include($this->plugin_path.'code/t2/templates.php');

            $template = new gdTemplateRender($tpl_id, "CRB");
            $rt = GDSRRenderT2::render_crt($template->dep["CRT"], $rating1, $unit_count, $votes, $vote_value);

            return "{ status: 'ok', value: ".$rating_width.", rater: '".$rt."' }";
        }
        // vote

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

        function get_ratings_multi($set_id, $post_id) {
            $multis_data = GDSRDBMulti::get_multi_rating_data($set_id, $post_id);
            if (count($multis_data) == 0) return null;
            return new GDSRArticleMultiRating($multis_data, $set_id);
        }

        // menues
        function star_multi_sets() {
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
                $eset->auto_insert = $_POST["gdsr_ms_autoinsert"];
                $eset->auto_categories = $_POST["gdsr_ms_autocategories"];
                $eset->auto_location = $_POST["gdsr_ms_autolocation"];
                $elms = $_POST["gdsr_ms_element"];
                $elwe = $_POST["gdsr_ms_weight"];
                $i = 0;
                foreach ($elms as $el) {
                    if (($el != "" && $eset->multi_id == 0) || $eset->multi_id > 0) {
                        $eset->object[] = stripslashes(htmlentities($el, ENT_QUOTES, STARRATING_ENCODING));
                        $ew = $elwe[$i];
                        if (!is_numeric($ew)) $ew = 1;
                        $eset->weight[] = $ew;
                        $i++;
                    }
                }
                if ($eset->name != "") {
                    if ($eset->multi_id == 0) $set_id = GDSRDBMulti::add_multi_set($eset);
                    else {
                        $set_id = $eset->multi_id;
                        GDSRDBMulti::edit_multi_set($eset);
                    }
                }
            }
            $options = $this->o;
            if (($gdsr_page == "munew" || $gdsr_page == "muedit") && $editor) include($this->plugin_path.'options/multis/editor.php');
            else {
                switch ($gdsr_page) {
                    case "mulist":
                    default:
                        include($this->plugin_path.'options/multis/sets.php');
                        break;
                    case "murpost":
                        include($this->plugin_path.'options/multis/results_post.php');
                        break;
                    case "murset":
                        include($this->plugin_path.'options/multis/results_set.php');
                        break;
                }
            }
        }

        function star_multi_results() {
            $options = $this->o;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/multis/results.php');
        }

        function star_menu_front() {
            $options = $this->o;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/front.php');
        }

        function star_menu_gfx() {
            if (isset($_POST['gdsr_preview_scan'])) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }
            
            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_options = $this->o;
            $gdsr_bots = $this->bots;
            $gdsr_root_url = $this->plugin_url;
            $gdsr_gfx = $this->g;
            $gdsr_wpr8 = $this->wpr8_available;
            $extra_folders = $this->extra_folders;
            $safe_mode = $this->safe_mode;
            $wpv = $this->wp_version;
            $ginc_sizes = $this->ginc[0];
            $ginc_stars = $this->ginc[1];
            $ginc_sizes_thumb = $this->ginc[2];
            $ginc_stars_thumb = $this->ginc[3];
            $wpr8 = $this->wpr8;

            include($this->plugin_path.'options/gfx.php');
        }

        function star_menu_settings() {
            if (isset($_POST['gdsr_preview_scan'])) {
                $this->g = $this->gfx_scan();
                update_option('gd-star-rating-gfx', $this->g);
            }

            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_options = $this->o;
            $gdsr_bots = $this->bots;
            $gdsr_root_url = $this->plugin_url;
            $gdsr_gfx = $this->g;
            $gdsr_wpr8 = $this->wpr8_available;
            $extra_folders = $this->extra_folders;
            $safe_mode = $this->safe_mode;
            $wpv = $this->wp_version;
            $ginc_sizes = $this->ginc[0];
            $ginc_stars = $this->ginc[1];
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

        function star_menu_t2() {
            $options = $this->o;
            $wpv = $this->wp_version;

            include($this->plugin_path.'code/t2/templates.php');

            if (isset($_GET["tplid"])) {
                $id = $_GET["tplid"];
                $mode = $_GET["mode"];
                include($this->plugin_path.'gdt2/form_editor.php');
            }
            else if (isset($_POST["gdsr_defaults"])) {
                include($this->plugin_path.'gdt2/form_defaults.php');
            }
            else if (isset($_POST["gdsr_create"])) {
                $id = 0;
                $mode = "new";
                include($this->plugin_path.'gdt2/form_editor.php');
            }
            else if (isset($_POST["gdsr_setdefaults"])) {
                gdTemplateDB::set_templates_defaults($_POST["gdsr_section"]);
                include($this->plugin_path.'gdt2/form_list.php');
            } else {
                include($this->plugin_path.'gdt2/form_list.php');
            }
        }

        function star_menu_setup() {
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/setup.php');
        }

        function star_menu_ips() {
            $options = $this->o;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/ips.php');
        }

        function star_menu_tools() {
            $msg = "";

            $gdsr_options = $this->o;
            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_gfx = $this->g;
            $wpv = $this->wp_version;

            include($this->plugin_path.'options/tools.php');
        }

        function star_menu_import() {
            $options = $this->o;
            $imports = $this->i;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/import.php');
        }

        function star_menu_export() {
            $options = $this->o;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/export.php');
        }

        function star_menu_stats() {
            $options = $this->o;
            $wpv = $this->wp_version;
            $gdsr_page = isset($_GET["gdsr"]) ? $_GET["gdsr"] : "";
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
            $wpv = $this->wp_version;
            if ($_GET["gdsr"] == "userslog")
                include($this->plugin_path.'options/users_log.php');
            else
                include($this->plugin_path.'options/users.php');
        }

        function star_menu_cats(){
            $options = $this->o;
            $wpv = $this->wp_version;
            include($this->plugin_path.'options/categories.php');
        }

        function star_menu_builder(){
            $options = $this->o;
            $wpv = $this->wp_version;
            $gdsr_styles = $this->g->stars;
            $gdsr_trends = $this->g->trend;
            $gdsr_thumbs = $this->g->thumbs;
            $gdst_multis = GDSRDBMulti::get_multis_tinymce();
            include($this->plugin_path.'options/builder.php');
        }
        // menues

        // cookies
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
                ($type == "artthumb" && $this->o["cookies"] == 1) ||
                ($type == "multis" && $this->o["cookies"] == 1) ||
                ($type == "comment" && $this->o["cmm_cookies"]) ||
                ($type == "cmmthumb" && $this->o["cmm_cookies"] == 1)
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
                ($type == "artthumb" && $this->o["cookies"] == 1) ||
                ($type == "multis" && $this->o["cookies"] == 1) ||
                ($type == "comment" && $this->o["cmm_cookies"] == 1) ||
                ($type == "cmmthumb" && $this->o["cmm_cookies"] == 1)
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

        // comment rating
        /**
        * Renders comment review stars
        *
        * @param int $value initial rating value
        * @param bool $allow_vote render stars to support rendering or not to
        */
        function comment_review($value = 0, $allow_vote = true, $override = array()) {
            $stars = $this->o["cmm_review_stars"];
            $style = $override["style"] == "" ? $this->o["cmm_review_style"] : $override["style"];
            $size = $override["size"] == 0 ? $this->o["cmm_review_size"] : $override["size"];
            return GDSRRender::rating_stars_local($style, $size, $stars, $allow_vote, $value * $size);
        }

        /**
        * Renders result of comment integration of standard rating for specific comment
        *
        * @param int $comment_id initial rating value
        * @param string $stars_set set to use for rendering
        * @param int $stars_size set size to use for rendering
        * @param string $stars_set_ie6 set to use for rendering in ie6
        */
        function comment_integrate_standard_result($comment_id, $post_id, $stars_set = "oxygen", $stars_size = 20, $stars_set_ie6 = "oxygen_gif") {
            if (!$this->is_cached_integration_std) {
                global $gdsr_cache_integation_std;
                $data = GDSRDBCache::get_integration($post_id);
                foreach ($data as $row) {
                    $id = $row->comment_id;
                    $gdsr_cache_integation_std->set($id, $row);
                }

wp_gdsr_dump("CACHE_INT_STD_RESULT", $gdsr_cache_integation_std);

                $this->is_cached_integration_std = true;
            }

            $value = intval(wp_gdget_integration_std($comment_id));
            if ($value > 0 || $this->o["int_comment_std_zero"] == 1) {
                $style = $stars_set == "" ? $this->o["style"] : $stars_set;
                $style = $this->is_ie6 ? ($stars_set_ie6 == "" ? $this->o["style_ie6"] : $stars_set_ie6) : $style;
                return GDSRRender::render_static_stars($style, $stars_size == 0 ? $this->o["size"] : $stars_size, $this->o["stars"], $value);
            } else return "";
        }

        /**
        * Renders comment integration of standard rating
        *
        * @param int $value initial rating value
        * @param string $stars_set set to use for rendering
        * @param int $stars_size set size to use for rendering
        * @param string $stars_set_ie6 set to use for rendering in ie6
        */
        function comment_integrate_standard_rating($value = 0, $stars_set = "oxygen", $stars_size = 20, $stars_set_ie6 = "oxygen_gif") {
            $style = $stars_set == "" ? $this->o["style"] : $stars_set;
            $style = $this->is_ie6 ? ($stars_set_ie6 == "" ? $this->o["style_ie6"] : $stars_set_ie6) : $style;
            $size = $stars_size == 0 ? $this->o["size"] : $stars_size;
            return GDSRRender::rating_stars_local($style, $size, $this->o["stars"], true, $value * $size, "gdsr_int", "rcmmpost");
        }

        /**
        * Renders result of comment integration of multi rating for specific comment
        *
        * @param int $comment_id initial rating value
        * @param object $post_id post id
        * @param int $multi_set_id id of the multi rating set to use
        * @param int $template_id id of the template to use
        * @param string $stars_set set to use for rendering
        * @param int $stars_size set size to use for rendering
        * @param string $stars_set_ie6 set to use for rendering in ie6
        * @param string $avg_stars_set set to use for rendering of average value
        * @param int $avg_stars_size set size to use for rendering of average value
        * @param string $avg_stars_set_ie6 set to use for rendering of average value in ie6
        */
        function comment_integrate_multi_result($comment_id, $post_id, $multi_set_id, $template_id, $stars_set = "oxygen", $stars_size = 20, $stars_set_ie6 = "oxygen_gif", $avg_stars_set = "oxygen", $avg_stars_size = 20, $avg_stars_set_ie6 = "oxygen_gif") {
            if (!$this->is_cached_integration_mur) {
                global $gdsr_cache_integation_mur;
                $data = GDSRDBCache::get_integration($post_id, "multis");
                foreach ($data as $row) {
                    $id = $row->multi_id."_".$row->comment_id;
                    $gdsr_cache_integation_mur->set($id, $row);
                }

wp_gdsr_dump("CACHE_INT_MUR_RESULT", $gdsr_cache_integation_mur);

                $this->is_cached_integration_mur = true;
            }

            $value = wp_gdget_integration_mur($comment_id, $multi_set_id);
            if (is_serialized($value) && !is_null($value)) {
                $value = unserialize($value);
                $set = gd_get_multi_set($multi_set_id);
                $weight_norm = array_sum($set->weight);
                $avg_rating = $i = 0;
                $votes = array();
                foreach ($value as $md) {
                    $single_vote = array();
                    $single_vote["votes"] = 1;
                    $single_vote["score"] = $md;
                    $single_vote["rating"] = $md;
                    $avg_rating += ($md * $set->weight[$i]) / $weight_norm;
                    $votes[] = $single_vote;
                    $i++;
                }
                $avg_rating = @number_format($avg_rating, 1);
                if ($avg_rating > 0) {
                    return GDSRRenderT2::render_rmb($template_id, $votes, $post_id, $set, $avg_rating,
                        $this->is_ie6 ? $stars_set_ie6 : $stars_set, $stars_size,
                        $this->is_ie6 ? $avg_stars_set_ie6 : $avg_stars_set, $avg_stars_size);
                } else return "";
            } else return "";
        }

        /**
        * Renders average result of comment integration of multi rating for specific comment
        *
        * @param int $comment_id initial rating value
        * @param object $post_id post id
        * @param int $multi_set_id id of the multi rating set to use
        * @param int $template_id id of the template to use
        * @param string $avg_stars_set set to use for rendering of average value
        * @param int $avg_stars_size set size to use for rendering of average value
        * @param string $avg_stars_set_ie6 set to use for rendering of average value in ie6
        */
        function comment_integrate_multi_result_average($comment_id, $post_id, $multi_set_id, $template_id, $avg_stars_set = "oxygen", $avg_stars_size = 20, $avg_stars_set_ie6 = "oxygen_gif") {
            $value = GDSRDBMulti::rating_from_comment($comment_id, $multi_set_id);
            if (is_serialized($value)) {
                $value = unserialize($value);
                $set = gd_get_multi_set($multi_set_id);
                $weight_norm = array_sum($set->weight);
                $avg_rating = $i = 0;
                foreach ($value as $md) {
                    $avg_rating += ($md * $set->weight[$i]) / $weight_norm;
                    $i++;
                }
                $avg_rating = @number_format($avg_rating, 1);
                if ($avg_rating > 0) {
                    return GDSRRenderT2::render_mcr($template_id, $post_id, $set, $avg_rating,
                        $this->is_ie6 ? $avg_stars_set_ie6 : $avg_stars_set, $avg_stars_size);
                } else return "";
            } else return "";
        }

        /**
        * Renders comment integration of multi rating
        *
        * @param int $value initial rating value
        * @param object $post_id post id
        * @param int $multi_set_id id of the multi rating set to use
        * @param int $template_id id of the template to use
        * @param string $stars_set set to use for rendering
        * @param int $stars_size set size to use for rendering
        * @param string $stars_set_ie6 set to use for rendering in ie6
        */
        function comment_integrate_multi_rating($value, $post_id, $multi_set_id, $template_id, $stars_set = "oxygen", $stars_size = 20, $stars_set_ie6 = "oxygen_gif") {
            $set = gd_get_multi_set($multi_set_id);
            $votes = array();
            for ($i = 0; $i < count($set->object); $i++) {
                $single_vote = array();
                $single_vote["votes"] = 0;
                $single_vote["score"] = 0;
                $single_vote["rating"] = 0;
                $votes[] = $single_vote;
            }
            return GDSRRenderT2::render_mri($this->is_ie6 ? $stars_set_ie6 : $stars_set, $template_id, $post_id, $set, $stars_size);
        }
        // comment rating

        // rendering
        function render_wait_article_thumb() {
            if ($this->o["wait_loader_artthumb"] != "") {
                $cls = 'loader '.$this->o["wait_loader_artthumb"].' thumb';
                $div = '<div class="'.$cls.'" style="%s"></div>';
                $this->loader_article_thumb = $div;
            }
        }

        function render_wait_comment_thumb() {
            if ($this->o["wait_loader_cmmthumb"] != "") {
                $cls = 'loader thumb '.$this->o["wait_loader_cmmthumb"];
                $div = '<div class="'.$cls.'" style="%s"></div>';
                $this->loader_comment_thumb = $div;
            }
        }

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

        function cache_posts($user_id) {
            $to_get = array();
            foreach ($this->c as $id => $value) {
                if ($value == 0) $to_get[] = $id;
            }
            if (count($to_get) > 0) {
                global $gdsr_cache_posts_std_data, $gdsr_cache_posts_std_log, $gdsr_cache_posts_std_thumbs_log;

                $data = GDSRDBCache::get_posts($to_get);
                foreach ($data as $row) {
                    $id = $row->post_id;
                    $this->c[$id] = 1;
                    $gdsr_cache_posts_std_data->set($id, $row);
                }

wp_gdsr_dump("CACHE_POSTDATA", $gdsr_cache_posts_std_data);

                $logs = GDSRDBCache::get_logs($to_get, $user_id, "article", $_SERVER["REMOTE_ADDR"], $this->o["logged"] != 1, $this->o["allow_mixed_ip_votes"] == 1);
                foreach ($logs as $id => $value) {
                    $gdsr_cache_posts_std_log->set($id, $value == 0);
                }

wp_gdsr_dump("CACHE_POSTLOG", $gdsr_cache_posts_std_log);

                if ($this->o["thumbs_active"] == 1) {
                    $logs_thumb = GDSRDBCache::get_logs($to_get, $user_id, "artthumb", $_SERVER["REMOTE_ADDR"], $this->o["logged"] != 1, $this->o["allow_mixed_ip_votes"] == 1);
                    foreach ($logs_thumb as $id => $value) {
                        $gdsr_cache_posts_std_thumbs_log->set($id, $value == 0);
                    }

wp_gdsr_dump("CACHE_POSTTHUMBLOG", $gdsr_cache_posts_std_thumbs_log);

                }
            }
        }

        function cache_comments($post_id) {
            global $gdsr_cache_posts_cmm_data, $gdsr_cache_posts_cmm_log, $gdsr_cache_posts_cmm_thumbs_log, $userdata;
            $user_id = $userdata->ID;
            $to_get = array();

            $data = GDSRDBCache::get_comments($post_id);
            foreach ($data as $row) {
                $id = $row->comment_id;
                $gdsr_cache_posts_cmm_data->set($id, $row);
                $to_get[] = $id;
            }
            if (count($to_get) > 0) {
                $logs = GDSRDBCache::get_logs($to_get, $user_id, "comment", $_SERVER["REMOTE_ADDR"], $this->o["cmm_logged"] != 1, $this->o["cmm_allow_mixed_ip_votes"] == 1);
                foreach ($logs as $id => $value) {
                    $gdsr_cache_posts_cmm_log->set($id, $value == 0);
                }

wp_gdsr_dump("CACHE_CMMDATA", $gdsr_cache_posts_cmm_log);

                if ($this->o["thumbs_active"] == 1) {
                    $logs_thumb = GDSRDBCache::get_logs($to_get, $user_id, "cmmthumb", $_SERVER["REMOTE_ADDR"], $this->o["cmm_logged"] != 1, $this->o["cmm_allow_mixed_ip_votes"] == 1);
                    foreach ($logs_thumb as $id => $value) {
                        $gdsr_cache_posts_cmm_thumbs_log->set($id, $value == 0);
                    }

wp_gdsr_dump("CACHE_CMMTHUMBLOG", $gdsr_cache_posts_cmm_thumbs_log);

                }
            }
        }

        function display_comment($content) {
            global $post, $comment, $userdata;

            if (is_admin()) return $content;
            if ($comment->comment_type == "pingback" && $this->o["display_trackback"] == 0) return $content;

            if (!is_feed()) {
                if ((is_single() && $this->o["display_comment"] == 1) ||
                    (is_page() && $this->o["display_comment_page"] == 1) ||
                    $this->o["override_display_comment"] == 1
                ) {
                    $rendered = $this->render_comment($post, $comment, $userdata);
                    if ($this->o["auto_display_comment_position"] == "top" || $this->o["auto_display_comment_position"] == "both")
                        $content = $rendered.$content;
                    if ($this->o["auto_display_comment_position"] == "bottom" || $this->o["auto_display_comment_position"] == "both")
                        $content = $content.$rendered;
                }

                if ($this->o["thumbs_active"] == 1) {
                    if ((is_single() && $this->o["thumb_display_comment"] == 1) ||
                        (is_page() && $this->o["thumb_display_comment_page"] == 1) ||
                        $this->o["override_thumb_display_comment"] == 1
                    ) {
                        $rendered = $this->render_thumb_comment($post, $comment, $userdata);
                        if ($this->o["thumb_auto_display_comment_position"] == "top" || $this->o["thumb_auto_display_comment_position"] == "both")
                            $content = $rendered.$content;
                        if ($this->o["thumb_auto_display_comment_position"] == "bottom" || $this->o["thumb_auto_display_comment_position"] == "both")
                            $content = $content.$rendered;
                    }
                }
            }

            return $content;
        }

        function display_article($content) {
            global $post, $userdata;
            $user_id = $userdata->ID;

            if (is_admin()) return $content;
            if (!is_feed()) {
                if (is_single() || is_page()) {
                    GDSRDatabase::add_new_view($post->ID);
                    $this->widget_post_id = $post->ID;
                }

                // standard rating
                if ((is_single() && $this->o["display_posts"] == 1) ||
                    (is_page() && $this->o["display_pages"] == 1) ||
                    (is_home() && $this->o["display_home"] == 1) ||
                    (is_archive() && $this->o["display_archive"] == 1) ||
                    (is_search() && $this->o["display_search"] == 1)
                ) {
                    $this->cache_posts($user_id);
                    $rendered = $this->render_article($post, $userdata);
                    if ($this->o["auto_display_position"] == "top" || $this->o["auto_display_position"] == "both")
                        $content = $rendered.$content;
                    if ($this->o["auto_display_position"] == "bottom" || $this->o["auto_display_position"] == "both")
                        $content = $content.$rendered;
                }

                // thumbs rating
                if ($this->o["thumbs_active"] == 1) {
                    if ((is_single() && $this->o["thumb_display_posts"] == 1) ||
                        (is_page() && $this->o["thumb_display_pages"] == 1) ||
                        (is_home() && $this->o["thumb_display_home"] == 1) ||
                        (is_archive() && $this->o["thumb_display_archive"] == 1) ||
                        (is_search() && $this->o["thumb_display_search"] == 1)
                    ) {
                        $this->cache_posts($user_id);
                        $rendered = $this->render_thumb_article($post, $userdata);
                        if ($this->o["thumb_auto_display_position"] == "top" || $this->o["thumb_auto_display_position"] == "both")
                            $content = $rendered.$content;
                        if ($this->o["thumb_auto_display_position"] == "bottom" || $this->o["thumb_auto_display_position"] == "both")
                            $content = $content.$rendered;
                    }
                }

                // multis rating
                if ($this->o["multis_active"] && (is_single() || is_page())) {
                    $this->prepare_multiset();
                    $this->cache_posts($user_id);
                    $content = $this->display_multi_rating("top", $post, $userdata).$content;
                    $content = $content.$this->display_multi_rating("bottom", $post, $userdata);
                }
            }

            return $content;
        }

        function display_multi_rating($location, $post, $user) {
            $sets = $this->rendering_sets;
            $rendered = "";
            if (is_array($sets) && count($sets) > 0) {
                foreach ($sets as $set) {
                    if ($set->auto_location == $location) {
                        $insert = false;
                        $auto = $set->auto_insert;

                        if (is_single() && ($auto == "apst" || $auto == "allp")) $insert = true;
                        if (!$insert && is_page() && ($auto == "apgs" || $auto == "allp")) $insert = true;
                        if (!$insert && is_single() && in_category(explode(",", $set->auto_categories), $post->ID) && $auto == "cats") $insert = true;

                        if ($insert) {
                            $settings = array('id' => $set->multi_id, 'read_only' => 0);
                            $rendered.= $this->render_multi_rating($post, $user, $settings);
                        }
                    }
                }
            }
            return $rendered;
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
            } else if ($post_data->rules_articles == "V") {
                $votes = $post_data->visitor_voters;
                $score = $post_data->visitor_votes;
            } else {
                $votes = $post_data->user_voters;
                $score = $post_data->user_votes;
            }

            $out = array();
            $out[] = $votes;
            $out[] = $score;
            return $out;
        }

        function render_article_rss() {
            global $post;
            $rd_post_id = intval($post->ID);
            $post_data = GDSRDatabase::get_post_data($rd_post_id);
            $template_id = $this->o["default_ssb_template"];
            $votes = $score = 0;
            $stars = 10;

            if ($this->o["rss_datasource"] == "thumbs") {
                if ($rules_articles == "A" || $rules_articles == "N") {
                    $votes = $post_data->user_recc_plus + $post_data->user_recc_minus + $post_data->visitor_recc_plus + $post_data->visitor_recc_minus;
                    $score = $post_data->user_recc_plus - $post_data->user_recc_minus + $post_data->visitor_recc_plus - $post_data->visitor_recc_minus;
                } else if ($rules_articles == "V") {
                    $votes = $post_data->visitor_recc_plus + $post_data->visitor_recc_minus;
                    $score = $post_data->visitor_recc_plus - $post_data->visitor_recc_minus;
                } else {
                    $votes = $post_data->user_recc_plus + $post_data->user_recc_minus;
                    $score = $post_data->user_recc_plus - $post_data->user_recc_minus;
                }
            } else if ($this->o["rss_datasource"] == "standard") {
                $stars = $this->o["stars"];
                if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                    $votes = $post_data->user_voters + $post_data->visitor_voters;
                    $score = $post_data->user_votes + $post_data->visitor_votes;
                } else if ($post_data->rules_articles == "V") {
                    $votes = $post_data->visitor_voters;
                    $score = $post_data->visitor_votes;
                } else {
                    $votes = $post_data->user_voters;
                    $score = $post_data->user_votes;
                }
            } else {
                $data = GDSRDBMulti::get_rss_multi_data($post_id);
                if (count($row) > 0) {
                    $set = wp_gdget_multi_set($data->multi_id);
                    $stars = $set->stars;
                    if ($post_data->rules_articles == "A" || $post_data->rules_articles == "N") {
                        $sum = $data->average_rating_users * $data->total_votes_users + $data->average_rating_visitors * $data->total_votes_visitors;
                        $votes = $data->total_votes_visitors + $data->total_votes_users;
                        $score = number_format($votes == 0 ? 0 : $sum / $votes, 1);
                    } else if ($post_data->rules_articles == "V") {
                        $votes = $data->total_votes_visitors;
                        $score = $data->average_rating_visitors;
                    } else {
                        $votes = $data->total_votes_users;
                        $score = $data->average_rating_users;
                    }
                }
            }

            $rating_block = GDSRRenderT2::render_ssb($template_id, $rd_post_id, $votes, $score, $stars, $this->o["rss_header_text"], $this->o["rss_datasource"]);
            return $rating_block;
        }

        function render_thumb_comment($post, $comment, $user, $override = array()) {
            $default_settings = array("style" => $this->o["thumb_cmm_style"], "style_ie6" => $this->o["thumb_cmm_style_ie6"], "size" => $this->o["thumb_cmm_size"], "tpl" => 0, "read_only" => 0);
            $override = shortcode_atts($default_settings, $override);
            if ($override["style"] == "") $override["style"] = $this->o["thumb_cmm_style"];
            if ($override["style_ie6"] == "") $override["style_ie6"] = $this->o["thumb_cmm_style_ie6"];
            if ($override["size"] == "") $override["size"] = $this->o["thumb_cmm_size"];

            if ($this->o["comments_active"] != 1) return "";
            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = $override["read_only"] == 0;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_comment_id = intval($comment->comment_ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            $post_data = wp_gdget_post($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = wp_gdget_post($rd_post_id);
                $this->c[$rd_post_id] = 1;
            }

            $rules_comments = $post_data->rules_comments != "I" ? $post_data->rules_comments : $this->get_post_rule_value($rd_post_id, "rules_comments", "default_voterules_comments");

            if ($rules_comments == "H") return "";
            $comment_data = wp_gdget_comment($rd_comment_id);
            if (count($comment_data) == 0) {
                GDSRDatabase::add_empty_comment($rd_comment_id, $rd_post_id);
                $comment_data = wp_gdget_comment($rd_comment_id);
            }

            if ($allow_vote) {
                if ($this->o["cmm_author_vote"] == 1 && $rd_user_id == $comment->user_id && $rd_user_id > 0) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            if ($allow_vote) {
                if (($rules_comments == "") ||
                    ($rules_comments == "A") ||
                    ($rules_comments == "U" && $rd_user_id > 0) ||
                    ($rules_comments == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$rules_comments;
                }
            }

            if ($allow_vote) {
                $allow_vote = wp_gdget_thumb_commentlog($rd_comment_id);
                if (!$allow_vote) $dbg_allow = "D";
            }
            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_comment_id, "cmmthumb");
                if (!$allow_vote) $dbg_allow = "C";
            }

            $votes = $score = $votes_plus = $votes_minus = 0;

            if ($rules_comments == "A" || $rules_comments == "N") {
                $votes = $comment_data->user_recc_plus + $comment_data->user_recc_minus + $comment_data->visitor_recc_plus + $comment_data->visitor_recc_minus;
                $score = $comment_data->user_recc_plus - $comment_data->user_recc_minus + $comment_data->visitor_recc_plus - $comment_data->visitor_recc_minus;
                $votes_plus = $comment_data->user_recc_plus + $comment_data->visitor_recc_plus;
                $votes_minus = $comment_data->user_recc_minus + $comment_data->visitor_recc_minus;
            } else if ($rules_comments == "V") {
                $votes = $comment_data->visitor_recc_plus + $comment_data->visitor_recc_minus;
                $score = $comment_data->visitor_recc_plus - $comment_data->visitor_recc_minus;
                $votes_plus = $comment_data->visitor_recc_plus;
                $votes_minus = $comment_data->visitor_recc_minus;
            } else {
                $votes = $comment_data->user_recc_plus + $comment_data->user_recc_minus;
                $score = $comment_data->user_recc_plus - $comment_data->user_recc_minus;
                $votes_plus = $comment_data->user_recc_plus;
                $votes_minus = $comment_data->user_recc_minus;
            }

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $comment->user_id ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";

            $tags_css = array();
            $tags_css["CMM_CSS_BLOCK"] = $this->o["cmm_class_block"];
            $tags_css["CMM_CSS_HEADER"] = $this->o["srb_class_header"];
            $tags_css["CMM_CSS_STARS"] = $this->o["cmm_class_stars"];
            $tags_css["CMM_CSS_TEXT"] = $this->o["cmm_class_text"];

            if ($override["tpl"] > 0) $template_id = $override["tpl"];
            else $template_id = $this->o["default_tcb_template"];

            $rating_block = GDSRRenderT2::render_tcb($template_id, $rd_comment_id, $votes, $score, $votes_plus, $votes_minus, $rd_unit_style, $rd_unit_width, $allow_vote, $rd_user_id, $tags_css, $this->o["header_text"], $debug, $this->loader_comment_thumb);
            return $rating_block;
        }

        function render_comment($post, $comment, $user, $override = array()) {
            $default_settings = array("style" => $this->o["cmm_style"], "style_ie6" => $this->o["cmm_style_ie6"], "size" => $this->o["cmm_size"], "tpl" => 0, "read_only" => 0);
            $override = shortcode_atts($default_settings, $override);
            if ($override["style"] == "") $override["style"] = $this->o["cmm_style"];
            if ($override["style_ie6"] == "") $override["style_ie6"] = $this->o["cmm_style_ie6"];
            if ($override["size"] == "") $override["size"] = $this->o["cmm_size"];

            if ($this->o["comments_active"] != 1) return "";
            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = $override["read_only"] == 0;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            $rd_unit_count = $this->o["cmm_stars"];
            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_comment_id = intval($comment->comment_ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            $post_data = wp_gdget_post($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = wp_gdget_post($rd_post_id);
                $this->c[$rd_post_id] = 1;
            }

            $rules_comments = $post_data->rules_comments != "I" ? $post_data->rules_comments : $this->get_post_rule_value($rd_post_id, "rules_comments", "default_voterules_comments");

            if ($rules_comments == "H") return "";
            $comment_data = wp_gdget_comment($rd_comment_id);
            if (count($comment_data) == 0) {
                GDSRDatabase::add_empty_comment($rd_comment_id, $rd_post_id);
                $comment_data = wp_gdget_comment($rd_comment_id);
            }

            if ($allow_vote) {
                if ($this->o["cmm_author_vote"] == 1 && $rd_user_id == $comment->user_id && $rd_user_id > 0) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            if ($allow_vote) {
                if (($rules_comments == "") ||
                    ($rules_comments == "A") ||
                    ($rules_comments == "U" && $rd_user_id > 0) ||
                    ($rules_comments == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$rules_comments;
                }
            }

            if ($allow_vote) {
                $allow_vote = wp_gdget_commentlog($rd_comment_id);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_comment_id, "comment");
                if (!$allow_vote) $dbg_allow = "C";
            }

            $votes = 0;
            $score = 0;

            if ($rules_comments == "A" || $rules_comments == "N") {
                $votes = $comment_data->user_voters + $comment_data->visitor_voters;
                $score = $comment_data->user_votes + $comment_data->visitor_votes;
            } else if ($rules_comments == "V") {
                $votes = $comment_data->visitor_voters;
                $score = $comment_data->visitor_votes;
            } else {
                $votes = $comment_data->user_voters;
                $score = $comment_data->user_votes;
            }

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $comment->user_id ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";

            $tags_css = array();
            $tags_css["CMM_CSS_BLOCK"] = $this->o["cmm_class_block"];
            $tags_css["CMM_CSS_HEADER"] = $this->o["srb_class_header"];
            $tags_css["CMM_CSS_STARS"] = $this->o["cmm_class_stars"];
            $tags_css["CMM_CSS_TEXT"] = $this->o["cmm_class_text"];

            if ($override["tpl"] > 0) $template_id = $override["tpl"];
            else $template_id = $this->o["default_crb_template"];

            $rating_block = GDSRRenderT2::render_crb($template_id, $rd_comment_id, "ratecmm", "c", $votes, $score, $rd_unit_style, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "comment", $tags_css, $this->o["cmm_header_text"], $debug, $this->loader_comment);
            return $rating_block;
        }

        function render_thumb_article($post, $user, $override = array()) {
            $default_settings = array("style" => $this->o["thumb_style"], "style_ie6" => $this->o["thumb_style_ie6"], "size" => $this->o["thumb_size"], "tpl" => 0, "read_only" => 0);
            $override = shortcode_atts($default_settings, $override);
            if ($override["style"] == "") $override["style"] = $this->o["thumb_style"];
            if ($override["style_ie6"] == "") $override["style_ie6"] = $this->o["thumb_style_ie6"];
            if ($override["size"] == "") $override["size"] = $this->o["thumb_size"];

            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = $override["read_only"] == 0;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            if ($override["read_only"] == 1) $dbg_allow = "RO";

            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            $post_data = wp_gdget_post($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = wp_gdget_post($rd_post_id);
                $this->c[$rd_post_id] = 1;
            }

            $rules_articles = $post_data->rules_articles != "I" ? $post_data->rules_articles : $this->get_post_rule_value($rd_post_id, "rules_articles", "default_voterules_articles");

            if ($rules_articles == "H") return "";
            if ($allow_vote) {
                if (($rules_articles == "") ||
                    ($rules_articles == "A") ||
                    ($rules_articles == "U" && $rd_user_id > 0) ||
                    ($rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$rules_articles;
                }
            }

            if ($allow_vote) {
                if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            $remaining = 0;
            $deadline = '';
            $expiry_type = 'N';
            if ($allow_vote && ($post_data->expiry_type == 'D' || $post_data->expiry_type == 'T' || $post_data->expiry_type == 'I')) {
                $expiry_type = $post_data->expiry_type != 'I' ? $post_data->expiry_type : $this->get_post_rule_value($rd_post_id, "expiry_type", "default_timer_type");
                $expiry_value = $post_data->expiry_type != 'I' ? $post_data->expiry_value : $this->get_post_rule_value($rd_post_id, "expiry_value", "default_timer_value");
                switch($expiry_type) {
                    case "D":
                        $remaining = GDSRHelper::expiration_date($expiry_value);
                        $deadline = $expiry_value;
                        break;
                    case "T":
                        $remaining = GDSRHelper::expiration_countdown($post->post_date, $expiry_value);
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
                $allow_vote = wp_gdget_thumb_postlog($rd_post_id);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_post_id, "artthumb");
                if (!$allow_vote) $dbg_allow = "C";
            }

            $votes = $score = $votes_plus = $votes_minus = 0;

            if ($rules_articles == "A" || $rules_articles == "N") {
                $votes = $post_data->user_recc_plus + $post_data->user_recc_minus + $post_data->visitor_recc_plus + $post_data->visitor_recc_minus;
                $score = $post_data->user_recc_plus - $post_data->user_recc_minus + $post_data->visitor_recc_plus - $post_data->visitor_recc_minus;
                $votes_plus = $post_data->user_recc_plus + $post_data->visitor_recc_plus;
                $votes_minus = $post_data->user_recc_minus + $post_data->visitor_recc_minus;
            } else if ($rules_articles == "V") {
                $votes = $post_data->visitor_recc_plus + $post_data->visitor_recc_minus;
                $score = $post_data->visitor_recc_plus - $post_data->visitor_recc_minus;
                $votes_plus = $post_data->visitor_recc_plus;
                $votes_minus = $post_data->visitor_recc_minus;
            } else {
                $votes = $post_data->user_recc_plus + $post_data->user_recc_minus;
                $score = $post_data->user_recc_plus - $post_data->user_recc_minus;
                $votes_plus = $post_data->user_recc_plus;
                $votes_minus = $post_data->user_recc_minus;
            }

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $post->post_author ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";

            $tags_css = array();
            $tags_css["CSS_BLOCK"] = $this->o["srb_class_block"];
            $tags_css["CSS_HEADER"] = $this->o["srb_class_header"];
            $tags_css["CSS_STARS"] = $this->o["srb_class_stars"];
            $tags_css["CSS_TEXT"] = $this->o["srb_class_text"];

            if ($override["tpl"] > 0) $template_id = $override["tpl"];
            else $template_id = $this->o["default_tab_template"];

            $rating_block = GDSRRenderT2::render_tab($template_id, $rd_post_id, $votes, $score, $votes_plus, $votes_minus, $rd_unit_style, $rd_unit_width, $allow_vote, $rd_user_id, $tags_css, $this->o["header_text"], $debug, $this->loader_article_thumb, $expiry_type, $remaining, $deadline);
            return $rating_block;
        }

        function render_article($post, $user, $override = array()) {
            $default_settings = array("style" => $this->o["style"], "style_ie6" => $this->o["style_ie6"], "size" => $this->o["size"], "tpl" => 0, "read_only" => 0);
            $override = shortcode_atts($default_settings, $override);
            if ($override["style"] == "") $override["style"] = $this->o["style"];
            if ($override["style_ie6"] == "") $override["style_ie6"] = $this->o["style_ie6"];
            if ($override["size"] == "") $override["size"] = $this->o["size"];

            if ($this->is_bot) return "";

            $dbg_allow = "F";
            $allow_vote = $override["read_only"] == 0;
            if ($this->override_readonly_standard) $allow_vote = false;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            if ($override["read_only"] == 1) $dbg_allow = "RO";

            $rd_unit_count = $this->o["stars"];
            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";

            $post_data = wp_gdget_post($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = wp_gdget_post($rd_post_id);
                $this->c[$rd_post_id] = 1;
            }

            $rules_articles = $post_data->rules_articles != "I" ? $post_data->rules_articles : $this->get_post_rule_value($rd_post_id, "rules_articles", "default_voterules_articles");

            if ($rules_articles == "H") return "";
            if ($allow_vote) {
                if (($rules_articles == "") ||
                    ($rules_articles == "A") ||
                    ($rules_articles == "U" && $rd_user_id > 0) ||
                    ($rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$rules_articles;
                }
            }

            if ($allow_vote) {
                if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            $remaining = 0;
            $deadline = '';
            $expiry_type = 'N';
            if ($allow_vote && ($post_data->expiry_type == 'D' || $post_data->expiry_type == 'T' || $post_data->expiry_type == 'I')) {
                $expiry_type = $post_data->expiry_type != 'I' ? $post_data->expiry_type : $this->get_post_rule_value($rd_post_id, "expiry_type", "default_timer_type");
                $expiry_value = $post_data->expiry_type != 'I' ? $post_data->expiry_value : $this->get_post_rule_value($rd_post_id, "expiry_value", "default_timer_value");
                switch($expiry_type) {
                    case "D":
                        $remaining = GDSRHelper::expiration_date($expiry_value);
                        $deadline = $expiry_value;
                        break;
                    case "T":
                        $remaining = GDSRHelper::expiration_countdown($post->post_date, $expiry_value);
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
                $allow_vote = wp_gdget_postlog($rd_post_id);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_post_id);
                if (!$allow_vote) $dbg_allow = "C";
            }

            $votes = 0;
            $score = 0;

            if ($rules_articles == "A" || $rules_articles == "N") {
                $votes = $post_data->user_voters + $post_data->visitor_voters;
                $score = $post_data->user_votes + $post_data->visitor_votes;
            } else if ($rules_articles == "V") {
                $votes = $post_data->visitor_voters;
                $score = $post_data->visitor_votes;
            } else {
                $votes = $post_data->user_voters;
                $score = $post_data->user_votes;
            }

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $post->post_author ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";

            $tags_css = array();
            $tags_css["CSS_BLOCK"] = $this->o["srb_class_block"];
            $tags_css["CSS_HEADER"] = $this->o["srb_class_header"];
            $tags_css["CSS_STARS"] = $this->o["srb_class_stars"];
            $tags_css["CSS_TEXT"] = $this->o["srb_class_text"];

            if ($override["tpl"] > 0) $template_id = $override["tpl"];
            else $template_id = $this->o["default_srb_template"];

            $rating_block = GDSRRenderT2::render_srb($template_id, $rd_post_id, "ratepost", "a", $votes, $score, $rd_unit_style, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "article", $tags_css, $this->o["header_text"], $debug, $this->loader_article, $expiry_type, $remaining, $deadline);
            return $rating_block;
        }

        function render_multi_rating($post, $user, $override = array()) {
            $default_settings = array("id" => 0, "style" => $this->o["mur_style"], "style_ie6" => $this->o["mur_style_ie6"], "size" => $this->o["mur_size"], "average_stars" => "oxygen", "average_size" => 30, "tpl" => 0, "read_only" => 0);
            $override = shortcode_atts($default_settings, $override);
            if ($override["style"] == "") $override["style"] = $this->o["mur_style"];
            if ($override["style_ie6"] == "") $override["style_ie6"] = $this->o["mur_style_ie6"];
            if ($override["size"] == "") $override["size"] = $this->o["mur_size"];

            if ($this->is_bot) return "";
            if (is_feed()) return "";

            $set = gd_get_multi_set($override["id"]);
            if ($set == null) return "";

            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_unit_width_avg = $override["average_size"];
            $rd_unit_style_avg = $this->is_ie6 ? $override["average_stars_ie6"] : $override["average_stars"];

            $dbg_allow = "F";
            $allow_vote = $override["read_only"] == 0;
            if ($this->override_readonly_multis) $allow_vote = false;
            if ($this->is_ban && $this->o["ip_filtering"] == 1) {
                if ($this->o["ip_filtering_restrictive"] == 1) return "";
                else $allow_vote = false;
                $dbg_allow = "B";
            }

            if ($override["read_only"] == 1) $dbg_allow = "RO";

            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_is_page = $post->post_type == "page" ? "1" : "0";
            $remaining = 0;
            $deadline = "";

            $post_data = wp_gdget_post($rd_post_id);
            if (count($post_data) == 0) {
                GDSRDatabase::add_default_vote($rd_post_id, $rd_is_page);
                $post_data = wp_gdget_post($rd_post_id);
                $this->c[$rd_post_id] = 1;
            }

            $rules_articles = $post_data->rules_articles != "I" ? $post_data->rules_articles : $this->get_post_rule_value($rd_post_id, "rules_articles", "default_voterules_articles");

            if ($rules_articles == "H") return "";
            if ($allow_vote) {
                if ($this->o["author_vote"] == 1 && $rd_user_id == $post->post_author) {
                    $allow_vote = false;
                    $dbg_allow = "A";
                }
            }

            if ($allow_vote) {
                if (($rules_articles == "") ||
                    ($rules_articles == "A") ||
                    ($rules_articles == "U" && $rd_user_id > 0) ||
                    ($rules_articles == "V" && $rd_user_id == 0)
                ) $allow_vote = true;
                else {
                    $allow_vote = false;
                    $dbg_allow = "R_".$rules_articles;
                }
            }

            $remaining = 0;
            $deadline = '';
            $expiry_type = 'N';
            if ($allow_vote && ($post_data->expiry_type == 'D' || $post_data->expiry_type == 'T' || $post_data->expiry_type == 'I')) {
                $expiry_type = $post_data->expiry_type != 'I' ? $post_data->expiry_type : $this->get_post_rule_value($rd_post_id, "expiry_type", "default_timer_type");
                $expiry_value = $post_data->expiry_type != 'I' ? $post_data->expiry_value : $this->get_post_rule_value($rd_post_id, "expiry_value", "default_timer_value");
                switch($expiry_type) {
                    case "D":
                        $remaining = GDSRHelper::expiration_date($expiry_value);
                        $deadline = $expiry_value;
                        break;
                    case "T":
                        $remaining = GDSRHelper::expiration_countdown($post->post_date, $expiry_value);
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
                $allow_vote = GDSRDBMulti::check_vote($rd_post_id, $rd_user_id, $set->multi_id, 'multis', $_SERVER["REMOTE_ADDR"], $this->o["logged"] != 1, $this->o["mur_allow_mixed_ip_votes"] == 1);
                if (!$allow_vote) $dbg_allow = "D";
            }

            if ($allow_vote) {
                $allow_vote = $this->check_cookie($rd_post_id."#".$set->multi_id, "multis");
                if (!$allow_vote) $dbg_allow = "C";
            }

            $multi_record_id = GDSRDBMulti::get_vote($rd_post_id, $set->multi_id, count($set->object));
            $multi_data = GDSRDBMulti::get_values($multi_record_id);

            $votes = array();
            foreach ($multi_data as $md) {
                $single_vote = array();
                $single_vote["votes"] = 0;
                $single_vote["score"] = 0;

                if ($rules_articles == "A" || $rules_articles == "N") {
                    $single_vote["votes"] = $md->user_voters + $md->visitor_voters;
                    $single_vote["score"] = $md->user_votes + $md->visitor_votes;
                } else if ($rules_articles == "V") {
                    $single_vote["votes"] = $md->visitor_voters;
                    $single_vote["score"] = $md->visitor_votes;
                } else {
                    $single_vote["votes"] = $md->user_voters;
                    $single_vote["score"] = $md->user_votes;
                }
                if ($single_vote["votes"] > 0) $rating = $single_vote["score"] / $single_vote["votes"];
                else $rating = 0;
                if ($rating > $set->stars) $rating = $set->stars;
                $single_vote["rating"] = @number_format($rating, 1);

                $votes[] = $single_vote;
            }

            $debug = $rd_user_id == 0 ? "V" : "U";
            $debug.= $rd_user_id == $post->post_author ? "A" : "N";
            $debug.= ":".$dbg_allow." [".STARRATING_VERSION."]";

            $tags_css = array();
            $tags_css["MUR_CSS_BLOCK"] = $this->o["mur_class_block"];
            $tags_css["MUR_CSS_HEADER"] = $this->o["mur_class_header"];
            $tags_css["MUR_CSS_TEXT"] = $this->o["mur_class_text"];
            $tags_css["MUR_CSS_BUTTON"] = $this->o["mur_class_button"];

            if ($override["tpl"] > 0) $template_id = $override["tpl"];
            else $template_id = $this->o["default_mrb_template"];

            $mur_button = $this->o["mur_button_active"] == 1;
            if (!$allow_vote) $mur_button = false;

            return GDSRRenderT2::render_mrb($rd_unit_style, $template_id, $allow_vote, $votes, $rd_post_id, $set, $rd_unit_width, $this->o["mur_header_text"], $tags_css, $rd_unit_style_avg, $rd_unit_width_avg, 1, $expiry_type, $remaining, $deadline, $mur_button, $this->o["mur_button_text"], $debug, $this->loader_multis);
        }

        function render_multi_custom_values($template_id, $multi_set_id, $custom_id, $votes, $header_text = '', $override = array(), $tags_css = array(), $star_factor = 1) {
            $set = gd_get_multi_set($multi_set_id);

            $rd_unit_width = $override["size"];
            $rd_unit_style = $this->is_ie6 ? $override["style_ie6"] : $override["style"];
            $rd_unit_width_avg = $override["average_size"];
            $rd_unit_style_avg = $this->is_ie6 ? $override["average_stars_ie6"] : $override["average_stars"];

            return GDSRRenderT2::render_mrb($rd_unit_style, $template_id, false, $votes, $custom_id, $set, $rd_unit_width, $header_text, $tags_css, $rd_unit_style_avg, $rd_unit_width_avg, $star_factor);
        }
        // rendering
    }

    $gd_debug = new gdDebugGDSR(STARRATING_LOG_PATH);
    $gdsr = new GDStarRating();

    include(STARRATING_PATH."functions_helpers.php");
    include(STARRATING_PATH."functions_integration.php");
}

?>