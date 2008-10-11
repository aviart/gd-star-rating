<?php
/*
Plugin Name: GD Star Rating
Plugin URI: http://wp.gdragon.info/plugin/gd-star-rating/
Description: Star Rating plugin allows you to set up rating system for pages and/or posts in your blog.
Version: 0.9.7
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

if (!class_exists('GDStarRating')) {
    class GDStarRating {
        var $log_file = "c:/gd_star_rating_log.txt";
        
        var $active_wp_page;
        var $wp_version;
        var $vote_status;
        var $plugin_url;
        var $plugin_path;
        var $styles;
        var $trends;
        
        var $o;
        var $w;
        var $t;
        var $p;
        var $x;

        var $shortcodes = array(
            "starrating",
			"starreview",
			"starrater"
        );
        
        var $default_templates = array(
            "word_votes_singular" => "vote",
            "word_votes_plural" => "votes",
            "article_rating_text" => "Rating: %RATING%/&lt;strong&gt;%MAX_RATING%&lt;/strong&gt; (%VOTES% %WORD_VOTES% cast)",
            "cmm_rating_text" => "Rating: %CMM_RATING%/&lt;strong&gt;%MAX_CMM_RATING%&lt;/strong&gt; (%CMM_VOTES% %WORD_VOTES% cast)"
        );
        
        var $default_options = array(
            "version" => "0.9.7",
            "date" => "2008.10.12.",
            "status" => "Beta",
            "ie_png_fix" => 1,
            "ajax" => 1,
            "widget_articles" => 1,
            "preview_active" => 1,
            "preview_trends_active" => 1,
            "moderation_active" => 1,
            "review_active" => 1,
            "comments_active" => 1,
            "style" => 0,
            "size" => 30,
            "stars" => 10,
            "text" => 'bottom',
            "align" => 'none',
            "header" => 0,
            "header_text" => '',
            "class_block" => '',
            "class_text" => '',
            "cmm_style" => 0,
            "cmm_size" => 12,
            "cmm_stars" => 5,
            "cmm_text" => 'bottom',
            "cmm_align" => 'none',
            "cmm_header" => 0,
            "cmm_header_text" => '',
            "cmm_class_block" => '',
            "cmm_class_text" => '',
            "review_style" => 0,
            "review_size" => 20,
            "review_stars" => 5,
            "review_align" => 'none',
            "review_header" => 0,
            "review_header_text" => '',
            "review_class_block" => '',
            "review_class_text" => '',
            "cmm_review_style" => 0,
            "cmm_review_size" => 12,
            "cmm_review_stars" => 5,
            "cmm_review_align" => 'none',
            "cmm_review_header" => 0,
            "cmm_review_header_text" => '',
            "cmm_review_class_block" => '',
            "cmm_review_class_text" => '',
            "display_comment" => 1,
            "display_posts" => 1,
            "display_pages" => 1,
            "display_home" => 1,
            "display_archive" => 1,
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
            "stats_trend_history" => 30,
            "stats_trend_current" => 3,
            "trend_last" => 1,
            "trend_over" => 30
        );
        
        var $default_widget = array(
            "title" => "Rating",
            "rows" => 10,
            "select" => "postpage",
            "column" => "rating",
            "order" => "desc",
            "category" => 0,
            "show" => "total",
            "display" => "all",
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
            "trends_rating_set" => 0,
            "trends_voting" => 'txt',
            "trends_voting_rise" => '+',
            "trends_voting_same" => '=',
            "trends_voting_fall" => '-',
            "trends_voting_set" => 0
        );
        
        var $default_shortcode = array(
            'rows' => 10, 
            'header' => 1, 
            'links' => 0, 
            'select' => 'postpage', 
            'votes' => 1, 
            'review' => 1, 
            'rating' => 1, 
            'sort' => 'rating', 
            'order' => 'desc', 
            'class' => 'starrating',
            'rating_style' => 'stars',
            'rating_stars' => 0,
            'rating_size' => 20,
            'review_style' => 'stars',
            'review_stars' => 0,
            'review_size' => 20,
            'category' => 0,
            'show' => 'total',
            'hide_empty' => 1
        );
        
        function GDStarRating() {
            include(dirname(__FILE__)."/stars/stars.php");
            include(dirname(__FILE__)."/trends/trends.php");
            $this->styles = $gdsr_styles;
            $this->trends = $gdsr_trends;
            
            $this->tabpage = "front";

            $this->active_wp_page();
            $this->plugin_path_url();
            $this->install_plugin();
            $this->actions_filters();
            
            define('SAVEQUERIES', true);
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
        
		function shortcode_starrater($atts) {
            global $post, $userdata;
            echo $this->render_article($post, $userdata);
		}
        
		function shortcode_starreview($atts) {
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
                
                $star_path = $this->plugin_url."stars/".$this->styles[$this->o['review_style']]['folder']."/stars".$this->o['review_size'].".".$this->styles[$this->o['review_style']]['type'];
                $review = sprintf('<div%s%s>%s<div style="%s"><div style="%s"></div></div></div>',
                    $css_class, $rater_align, $rater_header,
                    sprintf('text-align:left; background: url(%s) left bottom; height: %spx; width: %spx;', $star_path, $this->o['review_size'], $full_width),
                    sprintf('background: url(%s) left center; height: %spx; width: %spx;', $star_path, $this->o['review_size'], $rate_width)
                );
                return $review;
            }
            else
                return '';
		}
		
        function shortcode_starrating($atts) {
            global $wpdb;
            $sett = shortcode_atts($this->default_shortcode, $atts);
            
            if ($sett['show'] == "total") {
                if ($sett['sort'] == "rating") $orderc = "(d.user_votes + d.visitor_votes)/(d.user_voters + d.visitor_voters)";
                else if ($sett['sort'] == "votes") $orderc = "d.user_votes + d.visitor_votes";
                else $orderc = $sett['sort'];
                if ($sett['hide_empty'] == 1) $addt =" and d.user_votes + d.visitor_votes > 0";
            }
            if ($sett['show'] == "visitors") {
                if ($sett['sort'] == "rating") $orderc = "d.visitor_votes/d.visitor_voters";
                else if ($sett['sort'] == "votes") $orderc = "d.visitor_votes";
                else $orderc = $sett['sort'];
                if ($sett['hide_empty'] == 1) $addt =" and d.visitor_votes > 0";
            }
            if ($sett['show'] == "users") {
                if ($sett['sort'] == "rating") $orderc = "d.user_votes/d.user_voters";
                else if ($sett['sort'] == "votes") $orderc = "d.user_votes";
                else $orderc = $sett['sort'];
                if ($sett['hide_empty'] == 1) $addt =" and d.user_votes > 0";
            }
            
            $sql = GDSRDatabase::get_stats($sett['select'], 0, $sett['rows'], '0', $sett['category'], '', $orderc, $sett['order'], $addt);
            $all_rows = $wpdb->get_results($sql);
            
            $rating = '<table class="'.$sett['class'].'">';
            if ($sett['header'] == 1) {
                $rating.= '<thead>';
                $rating.= '<td class="title">Title</td>';
                if ($sett['votes'] == 1) $rating.= '<td class="votes">Votes</td>';
                if ($sett['rating'] == 1) $rating.= '<td class="rating">Rating</td>';
                if ($sett['review'] == 1) $rating.= '<td class="rating">Review</td>';
                $rating.= '</thead>';
            }

            $rating.= '<tbody>';
            $tr_class = "odd";
            foreach ($all_rows as $row) {
                if ($row != null) {
                    $rating.= '<tr class="row '.$tr_class.'">';
                    $rating.= '<td class="title">';
                    if ($sett['links'] == 1)
                        $rating.= '<a href="'.get_permalink($row->pid).'">'.$row->post_title.'</a>';
                    else
                        $rating.= $row->post_title;
                    $rating.= '</td>';

                    if ($sett['show'] == "total") {
                        $votes = $row->user_votes + $row->visitor_votes;
                        $voters = $row->user_voters + $row->visitor_voters;
                    }                
                    if ($sett['show'] == "visitors") {
                        $votes = $row->visitor_votes;
                        $voters = $row->visitor_voters;
                    }                
                    if ($sett['show'] == "users") {
                        $votes = $row->user_votes ;
                        $voters = $row->user_voters;
                    }                
                    $review = $row->review;

                    if ($voters == 0) 
                        $rates = 0;
                    else 
                        $rates = @number_format($votes / $voters, 1);

                    if ($sett['votes'] == 1) $rating.= '<td class="votes">'.$voters.'</td>';
                    if ($sett['rating'] == 1) {
                        if ($sett['rating_style'] == 'number')
                            $rating.= '<td class="rating">'.$rates.'</td>';
                        else {
                            $full_width = $sett['rating_size'] * $this->o["stars"];
                            $rate_width = floor($sett['rating_size'] * $rates);
                            
                            $star_path = $this->plugin_url."stars/".$this->styles[$sett['rating_stars']]['folder']."/stars".$sett['rating_size'].".".$this->styles[$sett['rating_stars']]['type'];
                            $rating.= sprintf('<td class="rating" style="width: %spx"><div style="%s" class="ratertbl"><div style="%s" class="ratertbl"></div></div></td>',
                                            $full_width,
                                            sprintf('text-align:left; background: url(%s); height: %spx; width: %spx;', $star_path, $sett['rating_size'], $full_width),
                                            sprintf('background: url(%s) bottom left; height: %spx; width: %spx;', $star_path, $sett['rating_size'], $rate_width)
                                        );
                        }
                    }
                    if ($sett['review'] == 1) {
                        if ($sett['review_style'] == 'number')
                            $rating.= '<td class="rating">'.$review == -1 ? "/" : $review.'</td>';
                        else {
                            $full_width = $sett['review_size'] * $this->o["review_stars"];
                            if ($review > -1) {
                                $rate_width = $sett['review_size'] * $review;
                                
                                $star_path = $this->plugin_url."stars/".$this->styles[$sett['review_stars']]['folder']."/stars".$sett['review_size'].".".$this->styles[$sett['review_stars']]['type'];
                                $rating.= sprintf('<td class="rating" style="width: %spx"><div style="%s" class="ratertbl"><div style="%s" class="ratertbl"></div></div></td>',
                                                $full_width,
                                                sprintf('text-align:left; background: url(%s); height: %spx; width: %spx;', $star_path, $sett['review_size'], $full_width),
                                                sprintf('background: url(%s) bottom left; height: %spx; width: %spx;', $star_path, $sett['review_size'], $rate_width)
                                            );
                            }
                            else $rating.= sprintf('<td class="rating" style="width: %spx">No Rating</td>', $full_width);
                        }
                    }
                }                

                if ($tr_class == "odd") $tr_class = "even";
                else $tr_class = "odd";
            }
            $rating.= '</tbody>';
            $rating.= '</table>';
            return $rating;
        }
        // shortcode
        
        // install
        function admin_menu() {
            add_menu_page('GD Star Rating', 'GD Star Rating', 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Front Page", "gd-star-rating"), __("Front Page", "gd-star-rating"), 10, __FILE__, array(&$this,"star_menu_front"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Plugin Settings", "gd-star-rating"), __("Plugin Settings", "gd-star-rating"), 10, "gdsr-settings-page", array(&$this,"star_menu_settings"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Templates", "gd-star-rating"), __("Templates", "gd-star-rating"), 10, "gdsr-templates", array(&$this,"star_menu_templates"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Votes Stats", "gd-star-rating"), __("Votes Stats", "gd-star-rating"), 10, "gdsr-stats", array(&$this,"star_menu_stats"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Batch Options", "gd-star-rating"), __("Batch Options", "gd-star-rating"), 10, "gdsr-batch", array(&$this,"star_menu_batch"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Import Data", "gd-star-rating"), __("Import Data", "gd-star-rating"), 10, "gdsr-import", array(&$this,"star_menu_import"));
            add_submenu_page(__FILE__, 'GD Star Rating: '.__("Setup", "gd-star-rating"), __("Setup", "gd-star-rating"), 10, "gdsr-setup", array(&$this,"star_menu_setup"));
        }                                                                

        function admin_head() {
            if (isset($_GET["page"])) {
                $gdsr = substr($_GET["page"], 0, 4);
                if ($gdsr == "gdsr" || substr($_GET["page"], 0, 7) == "gd-star") {
                    wp_admin_css('css/dashboard');
                    wp_print_scripts('jquery-ui-tabs');
                    echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin.css" type="text/css" media="screen" />');
                    echo '<script>jQuery(document).ready(function() { jQuery("#gdsr_tabs > ul").tabs(); });</script>';
                }
            }
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/admin_post.css" type="text/css" media="screen" />');
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
                add_action('save_post', array(&$this, 'savereview_post'));
            }
            add_filter('comment_text', array(&$this, 'display_comment'));
            add_filter('the_content', array(&$this, 'display_article'));
            add_filter("mce_external_plugins", array(&$this, 'add_tinymce_plugin'), 5);
            add_filter('mce_buttons', array(&$this, 'add_tinymce_button'), 5);

            foreach ($this->shortcodes as $code) {
                $this->shortcode_action($code);
            }
        }

        function savereview_post($post_id) {
            if ($_POST['gdsr_review'] != "-1") {
                $review = $_POST['gdsr_review'];
                if ($_POST['gdsr_review_decimal'] != "-1")
                    $review.= ".".$_POST['gdsr_review_decimal'];
                GDSRDatabase::save_review($post_id, $review);
            }
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

            if (!is_array($this->o))
                update_option('gd-star-rating-templates', $this->default_templates);
            else
                $this->x = $this->upgrade_settings($this->x, $this->default_templates);

            $this->t = GDSRDB::get_database_tables();
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
            if ($this->wp_version < 26)
                $this->plugin_url = get_option('home').'/'.PLUGINDIR.'/gd-star-rating/';
            else
                $this->plugin_url = WP_PLUGIN_URL.'/gd-star-rating/';
            $this->plugin_path = dirname(__FILE__);
        }

        function init() {
            wp_enqueue_script('jquery');
            $currentLocale = get_locale();
            if(!empty($currentLocale)) {
                $moFile = dirname(__FILE__) . "/languages/gd-star-rating-" . $currentLocale . ".mo";
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
            
            $article = urlencode($this->styles[$this->o["style"]]["folder"]."|".$this->o["size"]."|".$this->o["stars"]."|".$this->styles[$this->o["style"]]["type"]);
            $comment = urlencode($this->styles[$this->o["cmm_style"]]["folder"]."|".$this->o["cmm_size"]."|".$this->o["cmm_stars"]."|".$this->styles[$this->o["cmm_style"]]["type"]);

            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/stars_article.css.php?stars='.$article.'" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/stars_comment.css.php?stars='.$comment.'" type="text/css" media="screen" />');
            echo('<link rel="stylesheet" href="'.$this->plugin_url.'css/rating.css" type="text/css" media="screen" />');
            echo('<script>');
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

            if ($allow_vote) 
                $allow_vote = GDSRDatabase::check_vote($id, $user, 'article', $ip);
            
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
            
            return "{ value: ".$rating_width.", rater: '".$rt."' }";
        }

        function vote_comment($votes, $id, $user) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if ($user == '') $user = 0;
            
            $allow_vote = $this->check_cookie($id, 'comment');

            if ($allow_vote) 
                $allow_vote = GDSRDatabase::check_vote($id, $user, 'comment', $ip);
                
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
            
            return "{ value: ".$rating_width.", rater: '".$rt."' }";
        }
        // vote

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

            if ($this->wp_version < 27)
                include($this->plugin_path.'/options/edit.php');
            else 
                include($this->plugin_path.'/options/edit27.php');
        }                  

        function star_menu_front() {
            $options = $this->o;
            include($this->plugin_path.'/options/front.php');
        }

        function star_menu_settings() {
            $gdsr_styles = $this->styles;
            $gdsr_trends = $this->trends;
            $gdsr_options = $this->o;
            $gdsr_root_url = $this->plugin_url;
            
            include($this->plugin_path.'/options/settings.php');

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
            include($this->plugin_path.'/templates/templates.php');
        }

        function star_menu_setup() {
            include($this->plugin_path.'/options/setup.php');
        }

        function star_menu_batch() {
            $options = $this->o;
            include($this->plugin_path.'/options/batch.php');
        }

        function star_menu_import() {
            $options = $this->o;
            include($this->plugin_path.'/options/import.php');
        }

        function star_menu_stats() {
            $options = $this->o;
            $gdsr_page = $_GET["gdsr"];
            
            switch ($gdsr_page) {
                case "articles":
                default:
                    include($this->plugin_path.'/options/articles.php');
                    break;
                case "moderation":
                    include($this->plugin_path.'/options/moderation.php');
                    break;
                case "comments":
                    include($this->plugin_path.'/options/comments.php');
                    break;
                case "log":
                    include($this->plugin_path.'/options/voters.php');
                    break;
            }
        }
        // menu

        // widget
        function widget_init() {
            if ($this->o["widget_articles"] == 1) $this->widget_articles_init();
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
            $wptr = $this->trends;
            
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

            $sql = GDSRX::get_widget($widget);
            $all_rows = $wpdb->get_results($sql);
            $template = html_entity_decode($widget["tpl_item"]);

            $t_rate = strpos($template, "%RATE_TREND%") === false;
            $t_vote = strpos($template, "%VOTE_TREND%") === false;
            
            if (!($t_rate || $t_vote)) {
                $idx = array();
                foreach ($all_rows as $row) $idx[] = $row->post_id;
                $trends = GDSRX::get_trend_calculation(join(", ", $idx), $widget["grouping"], $widget['show'], $this->o["trend_last"], $this->o["trend_over"]);
                $trends_calculated = true;
            }
            else {
                $trends = array();
                $trends_calculated = false;
            }
            foreach ($all_rows as $row) {
                if ($widget['show'] == "total") {
                    $votes = $row->user_votes + $row->visitor_votes;
                    $voters = $row->user_voters + $row->visitor_voters;
                }                
                if ($widget['show'] == "visitors") {
                    $votes = $row->visitor_votes;
                    $voters = $row->visitor_voters;
                }                
                if ($widget['show'] == "users") {
                    $votes = $row->user_votes ;
                    $voters = $row->user_voters;
                }

                if ($voters == 0) $rating = 0;
                else $rating = @number_format($votes / $voters, 1);
                
                $title = $row->title;
                if ($widget["tpl_title_length"] > 0)
                    $title = substr($title, 0, $widget["tpl_title_length"])."...";
                
                if ($trends_calculated) {
                    $t = $trends[$row->post_id];
                    switch ($widget["trends_rating"]) {
                        case "img":
                            break;
                        case "txt":
                            switch ($t->trend_rating) {
                                case -1:
                                    $item_trend_rating = $widget["trends_rating_fall"];
                                    break;
                                case 0:
                                    $item_trend_rating = $widget["trends_rating_same"];
                                    break;
                                case 1:
                                    $item_trend_rating = $widget["trends_rating_rise"];
                                    break;
                            }
                            break;
                    }
                    switch ($widget["trends_voting"]) {
                        case "img":
                            break;
                        case "txt":
                            switch ($t->trend_voting) {
                                case -1:
                                    $item_trend_voting = $widget["trends_voting_fall"];
                                    break;
                                case 0:
                                    $item_trend_voting = $widget["trends_voting_same"];
                                    break;
                                case 1:
                                    $item_trend_voting = $widget["trends_voting_rise"];
                                    break;
                            }
                            break;
                    }
                }
                
                switch ($widget["grouping"]) {
                    case "post":
                        $permalink = get_permalink($row->post_id);
                        break;
                    case "category":
                        $permalink = get_category_link($row->term_id);
                        break;
                    case "user":
                        $permalink = get_bloginfo('url')."/index.php?author=".$row->id;
                        break;
                }
                
                if ($voters > 1) $tense = $this->x["word_votes_plural"];
                else $tense = $this->x["word_votes_singular"];
                
                $item = $template;
                
                $item = str_replace('%RATING%', $rating, $item);
                $item = str_replace('%MAX_RATING%', $this->o["stars"], $item);
                $item = str_replace('%VOTES%', $voters, $item);
                $item = str_replace('%REVIEW%', $row->review, $item);
                $item = str_replace('%MAX_REVIEW%', $this->o["review_stars"], $item);
                $item = str_replace('%TITLE%', $title, $item);
                $item = str_replace('%PERMALINK%', $permalink, $item);
                $item = str_replace('%ID%', $row->post_id, $item);
                $item = str_replace('%COUNT%', $row->counter, $item);
                $item = str_replace('%WORD_VOTES%', $tense, $item);
                if ($trends_calculated) {
                    $item = str_replace('%RATE_TREND%', $item_trend_rating, $item);
                    $item = str_replace('%VOTE_TREND%', $item_trend_voting, $item);
                }

                echo $item;
            }
            echo html_entity_decode($widget["tpl_footer"]);
        }
        // widget

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
                    (is_archive() && $this->o["display_archive"] == 1)
                ) {
                    $content .= $this->render_article($post, $userdata);
                }
            }
            else {
            }
            
            return $content;
        }

        function render_comment($post, $comment, $user) {
            global $wpdb;
            if ($this->o["comments_active"] != 1) 
                return "";

            $rd_unit_width = $this->o["cmm_size"];
            $rd_unit_count = $this->o["cmm_stars"];
            $rd_post_id = intval($post->ID);
            $rd_user_id = intval($user->ID);
            $rd_comment_id = intval($comment->comment_ID);

            if ($this->p)
                $post_data = $this->p;
            else if (is_single())
                $this->init_post();
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
            
            return GDSRRender::rating_block($rd_post_id, "ratepost", "a", $votes, $score, $rd_unit_width, $rd_unit_count, $allow_vote, $rd_user_id, "article", $this->o["align"], $this->o["text"], $this->o["header"], $this->o["header_text"], $this->o["class_block"], $this->o["class_text"], $this->o["ajax"]);
        }
        // render
    }

    $gdsr = new GDStarRating();

    define('STARRATING_URL', $gdsr->plugin_url);
    define('STARRATING_PATH', $gdsr->plugin_path);

    function wp_gdsr_render_widget($widget = array()) {
        global $gdsr;
        $gdsr->render_articles_widget($widget);
    }
    
    function wp_gdsr_render_article() {
        global $post, $userdata, $gdsr;
        echo $gdsr->render_article($post, $userdata);
    }

    function wp_gdsr_render_comment() {
        global $comment, $userdata, $gdsr, $post;
        echo $gdsr->render_comment($post, $comment, $userdata);
    }
    
	function wp_gdsr_render_review() {
        global $post, $userdata, $gdsr;
	}
}