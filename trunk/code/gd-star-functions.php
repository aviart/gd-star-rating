<?php

/**
 * Class with agregated results for a single post.
 * Constructor is givent the post data object, and from that class is filled with data.
 */
class GDSRArticleRating {
    var $post_id;
    var $review;
    var $user_votes;
    var $visitor_votes;
    var $votes;
    var $user_rating = 0;
    var $visitor_rating = 0;
    var $rating = 0;
    var $views;

    /**
     * Class constructor.
     *
     * @param object $post_data input data
     */
    function GDSRArticleRating($post_data) {
        $this->post_id = $post_data->post_id;
        $this->review = $post_data->review;
        $this->views = $post_data->views;
        $this->user_votes = $post_data->user_voters;
        $this->visitor_votes = $post_data->visitor_voters;
        $this->votes = $this->user_votes + $this->visitor_votes;
        if ($post_data->user_voters > 0) $this->user_rating = number_format($post_data->user_votes / $post_data->user_voters, 1);
        if ($post_data->visitor_voters > 0) $this->visitor_rating = number_format($post_data->visitor_votes / $post_data->visitor_voters, 1);
        if ($this->votes > 0) $this->rating = number_format(($post_data->visitor_votes + $post_data->user_votes) / ($post_data->visitor_voters + $post_data->user_voters), 1);
    }
}

/**
 * Class with agregated results for a single comment.
 * Constructor is givent the post comment object, and from that class is filled with data.
 */
class GDSRCommentRating {
    var $comment_id;
    var $post_id;
    var $review;
    var $user_votes;
    var $visitor_votes;
    var $votes;
    var $user_rating;
    var $visitor_rating;
    var $rating;

    /**
     * Class constructor.
     *
     * @param object $comment_data input data
     */
    function GDSRCommentRating($comment_data) {
        $this->comment_id = $comment_data->comment_id;
        $this->post_id = $comment_data->post_id;
        $this->review = $comment_data->review;
        $this->user_votes = $comment_data->user_voters;
        $this->visitor_votes = $comment_data->visitor_voters;
        $this->votes = $this->user_votes + $this->visitor_votes;
        if ($comment_data->user_voters > 0) $this->user_rating = number_format($comment_data->user_votes / $comment_data->user_voters, 1);
        if ($comment_data->visitor_voters > 0) $this->visitor_rating = number_format($comment_data->visitor_votes / $comment_data->visitor_voters, 1);
        if ($this->votes > 0) $this->rating = number_format(($comment_data->visitor_votes + $comment_data->user_votes) / ($comment_data->visitor_voters + $comment_data->user_voters), 1);
    }
}

class GDSRHelper {
    /**
     * Creates extra folders.
     *
     * @return bool cache folder exists and is writeable
     */
    function create_folders() {
        if (is_dir(STARRATING_XTRA_PATH)) {
            if (is_writable(STARRATING_XTRA_PATH)) {
                if (!is_dir(STARRATING_CACHE_PATH)) mkdir(STARRATING_CACHE_PATH, 0755);
                if (!is_dir(STARRATING_XTRA_PATH."stars/")) mkdir(STARRATING_XTRA_PATH."stars/", 0755);
                if (!is_dir(STARRATING_XTRA_PATH."trends/")) mkdir(STARRATING_XTRA_PATH."trends/", 0755);
            }
        }
        else {
            mkdir(STARRATING_XTRA_PATH, 0755);
            GDSRHelper::create_folders();
        }
        return is_dir(STARRATING_CACHE_PATH) && is_writable(STARRATING_CACHE_PATH);
    }
    
    function clean_cache($path) {
        if (!file_exists($path))
            return;
        if (is_file($path)) {
            unlink ($path);
            return;
        }
        foreach(glob($path."/*") as $fn)
            GDSRHelper::clean_cache($fn);
    }

    /**
     * Adding elements for IE PNG fix
     */
    function ie_png_fix() {
        echo('<!--[if lte IE 6]>');
        echo('<style type="text/css">');
        echo('.ratertbl, .outer, .inner, .starsbar a:hover { behavior: url('.STARRATING_URL.'iepngfix/iepngfix.php) }');
        echo('</style>');
        echo('<script type="text/javascript" src="'.STARRATING_URL.'iepngfix/iepngfix_tilebg.js"></script>');
        echo('<![endif]-->');
    }

    function detect_bot($str) {
        $spiders = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler");
        foreach($spiders as $spider) {
        if (ereg($spider, $str)) 
            return true;
        }
        return false;
    }

    function detect_ban() {
        $ip = $_SERVER["REMOTE_ADDR"];
        $ban = false;
        $ban = GDSRDatabase::check_ip_single($ip);
        if (!$ban)
            $ban = GDSRDatabase::check_ip_range($ip);
        if (!$ban)
            $ban = GDSRDatabase::check_ip_mask($ip);
        return $ban;
    }

    function clean_ip($ip) {
        $parts = explode(".", $ip);
        for ($i = 0; $i < count($parts); $i++)
            $parts[$i] = intval($parts[$i]);

        return join(".", $parts);
    }

    function expiration_countdown($post_date, $value) {
        $period = substr($value, 0, 1);
        $value = substr($value, 1);
        $pdate = strtotime($post_date);
        switch ($period) {
            case 'H':
                $expiry = mktime(date("H", $pdate) + $value, date("i", $pdate), date("s", $pdate), date("m", $pdate),          date("j", $pdate),          date("Y", $pdate));
                break;
            case 'D':
                $expiry = mktime(date("H", $pdate),          date("i", $pdate), date("s", $pdate), date("m", $pdate),          date("j", $pdate) + $value, date("Y", $pdate));
                break;
            case 'M':
                $expiry = mktime(date("H", $pdate),          date("i", $pdate), date("s", $pdate), date("m", $pdate) + $value, date("j", $pdate),          date("Y", $pdate));
                break;
        }
        return $expiry - mktime();
    }

    function expiration_date($value) {
        return strtotime($value) - mktime();
    }
    
    function calculate_deadline($timestamp) {
        $deadline_ts = $timestamp + mktime();
        return date("Y-m-d", $deadline_ts);
    }
    
    function remaining_time_parts($timestamp) {
        $times = array(
                31536000 => 'year', 
                2592000 => 'month',  
                86400 => 'day', 
                3600 => 'hour', 
                60 => 'minute', 
                1 => 'second'
            );
        $secs = $timestamp;
        $parts = array();
        
        foreach ($times AS $key => $value) {
            if ($secs >= $key) {
                $count = floor($secs / $key);
                $parts[$value] = $count;
                $secs = $secs - $count * $key;
            }
            else $parts[$value] = 0;
        }
        
        return $parts;
    }
    
    function remaining_time_total($timestamp) {
        $times = array(
                31536000 => 'year', 
                2592000 => 'month',  
                86400 => 'day', 
                3600 => 'hour', 
                60 => 'minute', 
                1 => 'second'
            );
        $parts = array();

        foreach ($times AS $key => $value) {
            $parts[$value] = floor($secs / $key);
        }
        
        return $parts;
    }
    
    function render_styles_select($styles, $selected = '') { 
        foreach ($styles as $style) {
            if ($selected == $style->folder) $current = ' selected="selected"';
            else $current = '';
            echo "\t<option value='".$style->folder."'".$current.">".$style->name."</option>\r\n";
        }
    }

    function render_class_select($styles) {
        for ($i = 0; $i < count($styles); $i++) {
            $style = $styles[$i];
            echo "\t<option value='".$style["class"]."'>".$style["name"]."</option>\r\n";
        }
    }
	
	function render_stars_select($selected = 10) {
        GDSRHelper::render_stars_select_full($selected);
    }
    
    function render_stars_select_full($selected = 10, $stars = 20, $start = 1, $prefix = '') {
        for ($i = $start; $i < $stars + 1; $i++) {
            if ($selected == $i) $current = ' selected="selected"';
            else $current = '';
            if ($prefix != '') $name = $prefix.': '.$i;
            else $name = $i;
            echo "\t<option value='".$i."'".$current.">".$name."</option>\r\n";
        }
    }
    
    function render_gfx_js($styles) {
        $js = array();
        foreach ($styles as $style)
            $js[] = '"'.$style->folder.'": "'.$style->gfx_url.'"';
        echo join(", ", $js);
    }

    function render_ext_gfx_js($styles) {
        $js = array();
        foreach ($styles as $style)
            $js[] = '"'.$style->folder.'": "'.$style->type.'"';
        echo join(", ", $js);
    }

    function render_moderation_combo($name, $selected = "N", $width = 180, $style = '', $row_zero = false, $cat = false) {
        ?>
<select style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value=""<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="N"<?php echo $selected == 'N' ? ' selected="selected"' : ''; ?>><?php _e("No moderation", "gd-star-rating"); ?> </option>
    <option value="V"<?php echo $selected == 'V' ? ' selected="selected"' : ''; ?>><?php _e("Moderate visitors", "gd-star-rating"); ?></option>
    <option value="U"<?php echo $selected == 'U' ? ' selected="selected"' : ''; ?>><?php _e("Moderate users", "gd-star-rating"); ?></option>
    <option value="A"<?php echo $selected == 'A' ? ' selected="selected"' : ''; ?>><?php _e("Moderate all", "gd-star-rating"); ?></option>
    <?php if (!$cat) { ?><option value="I"<?php echo $selected == 'I' ? ' selected="selected"' : ''; ?>><?php _e("Inherit from Category", "gd-star-rating"); ?></option><?php } ?>
</select>
        <?php
    }	

    function render_rules_combo($name, $selected = "A", $width = 180, $style = '', $row_zero = false, $cat = false) {
        ?>
<select style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value=""<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="A"<?php echo $selected == 'A' ? ' selected="selected"' : ''; ?>><?php _e("Everyone can vote", "gd-star-rating"); ?></option>
    <option value="V"<?php echo $selected == 'V' ? ' selected="selected"' : ''; ?>><?php _e("Only visitors", "gd-star-rating"); ?></option>
    <option value="U"<?php echo $selected == 'U' ? ' selected="selected"' : ''; ?>><?php _e("Only users", "gd-star-rating"); ?></option>
    <option value="N"<?php echo $selected == 'N' ? ' selected="selected"' : ''; ?>><?php _e("Locked", "gd-star-rating"); ?></option>
    <option value="H"<?php echo $selected == 'H' ? ' selected="selected"' : ''; ?>><?php _e("Locked and hidden", "gd-star-rating"); ?></option>
    <?php if (!$cat) { ?><option value="I"<?php echo $selected == 'I' ? ' selected="selected"' : ''; ?>><?php _e("Inherit from Category", "gd-star-rating"); ?></option><?php } ?>
</select>
        <?php
    } 
    
    function render_timer_combo($name, $selected = "N", $width = 180, $style = '', $row_zero = false, $onchange = '', $cat = false) {
        if ($onchange != '') $onchange = ' onchange="'.$onchange.'"';
        ?>
<select<?php echo $onchange; ?> style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value="_"<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="N"<?php echo $selected == 'N' ? ' selected="selected"' : ''; ?>><?php _e("No timer", "gd-star-rating"); ?></option>
    <option value="T"<?php echo $selected == 'T' ? ' selected="selected"' : ''; ?>><?php _e("Countdown timer", "gd-star-rating"); ?></option>
    <option value="D"<?php echo $selected == 'D' ? ' selected="selected"' : ''; ?>><?php _e("Date limited", "gd-star-rating"); ?></option>
    <?php if (!$cat) { ?><option value="I"<?php echo $selected == 'I' ? ' selected="selected"' : ''; ?>><?php _e("Inherit from Category", "gd-star-rating"); ?></option><?php } ?>
</select>
        <?php
    } 

    function render_countdown_combo($name, $selected = "H", $width = 180, $style = '', $row_zero = false, $cat = false) {
        ?>
<select style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value=""<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="H"<?php echo $selected == 'H' ? ' selected="selected"' : ''; ?>><?php _e("Hours", "gd-star-rating"); ?></option>
    <option value="D"<?php echo $selected == 'D' ? ' selected="selected"' : ''; ?>><?php _e("Days", "gd-star-rating"); ?></option>
    <option value="M"<?php echo $selected == 'M' ? ' selected="selected"' : ''; ?>><?php _e("Months", "gd-star-rating"); ?></option>
    <?php if (!$cat) { ?><option value="I"<?php echo $selected == 'I' ? ' selected="selected"' : ''; ?>><?php _e("Inherit from Category", "gd-star-rating"); ?></option><?php } ?>
</select>
        <?php
    } 
    
    function render_star_sizes($name, $selected = 20, $width = 120, $extraSel = "") {
        ?>
<select style="width: <?php echo $width ?>px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $extraSel; ?>>
    <option value="12"<?php echo $selected == 12 ? ' selected="selected"' : ''; ?>><?php _e("Mini", "gd-star-rating"); ?> [12px]</option>
    <option value="20"<?php echo $selected == 20 ? ' selected="selected"' : ''; ?>><?php _e("Small", "gd-star-rating"); ?> [20px]</option>
    <option value="30"<?php echo $selected == 30 ? ' selected="selected"' : ''; ?>><?php _e("Medium", "gd-star-rating"); ?> [30px]</option>
    <option value="46"<?php echo $selected == 46 ? ' selected="selected"' : ''; ?>><?php _e("Big", "gd-star-rating"); ?> [46px]</option>
</select>
        <?php
    }   

    function render_alignment($name, $selected = 'left', $width = 180) {
        ?>
<select style="width: <?php echo $width ?>px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <option value="none"<?php echo $selected == 'none' ? ' selected="selected"' : ''; ?>><?php _e("No alignment", "gd-star-rating"); ?></option>
    <option value="left"<?php echo $selected == 'left' ? ' selected="selected"' : ''; ?>><?php _e("Left", "gd-star-rating"); ?></option>
    <option value="center"<?php echo $selected == 'center' ? ' selected="selected"' : ''; ?>><?php _e("Center", "gd-star-rating"); ?></option>
    <option value="right"<?php echo $selected == 'right' ? ' selected="selected"' : ''; ?>><?php _e("Right", "gd-star-rating"); ?></option>
</select>
        <?php
    }

    function render_loaders($name, $selected = 'bar', $cls = 'jqloaderarticle', $width = 180, $extraSel = "") {
        ?>
<select class="<?php echo $cls ?>" style="width: <?php echo $width ?>px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $extraSel; ?>>
    <option value=""<?php echo $selected == '' ? ' selected="selected"' : ''; ?>><?php _e("No Animation", "gd-star-rating"); ?></option>
    <option value="arrows"<?php echo $selected == 'arrows' ? ' selected="selected"' : ''; ?>>Arrows [16x16]</option>
    <option value="bar"<?php echo $selected == 'bar' ? ' selected="selected"' : ''; ?>>Bar [208x13]</option>
    <option value="circle"<?php echo $selected == 'circle' ? ' selected="selected"' : ''; ?>>Circle [16x16]</option>
    <option value="flower"<?php echo $selected == 'flower' ? ' selected="selected"' : ''; ?>>Flower [15x15]</option>
    <option value="gauge"<?php echo $selected == 'gauge' ? ' selected="selected"' : ''; ?>>Gauge [128x15]</option>
    <option value="squares"<?php echo $selected == 'squares' ? ' selected="selected"' : ''; ?>>Squares [43x11]</option>
</select>
        <?php
    }

    function render_placement($name, $selected = 'bottom', $width = 180) {
        ?>
<select style="width: <?php echo $width ?>px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <option value="hide"<?php echo $selected == 'hide' ? ' selected="selected"' : ''; ?>><?php _e("Always hide", "gd-star-rating"); ?></option>
    <option value="top"<?php echo $selected == 'top' ? ' selected="selected"' : ''; ?>><?php _e("Top", "gd-star-rating"); ?></option>
    <option value="top_hidden"<?php echo $selected == 'top_hidden' ? ' selected="selected"' : ''; ?>><?php _e("Top (hide if empty)", "gd-star-rating"); ?></option>
    <option value="bottom"<?php echo $selected == 'bottom' ? ' selected="selected"' : ''; ?>><?php _e("Bottom", "gd-star-rating"); ?></option>
    <option value="bottom_hidden"<?php echo $selected == 'bottom_hidden' ? ' selected="selected"' : ''; ?>><?php _e("Bottom (hide if empty)", "gd-star-rating"); ?></option>
    <option value="left"<?php echo $selected == 'left' ? ' selected="selected"' : ''; ?>><?php _e("Left", "gd-star-rating"); ?></option>
    <option value="left_hidden"<?php echo $selected == 'left_hidden' ? ' selected="selected"' : ''; ?>><?php _e("Left (hide if empty)", "gd-star-rating"); ?></option>
    <option value="right"<?php echo $selected == 'right' ? ' selected="selected"' : ''; ?>><?php _e("Right", "gd-star-rating"); ?></option>
    <option value="right_hidden"<?php echo $selected == 'right_hidden' ? ' selected="selected"' : ''; ?>><?php _e("Right (hide if empty)", "gd-star-rating"); ?></option>
</select>
        <?php
    }
   
    function draw_pager($total_pages, $current_page, $url, $query = "page") {
        $pages = array();
        $break_first = -1;
        $break_last = -1;
        if ($total_pages < 10) for ($i = 0; $i < $total_pages; $i++) $pages[] = $i + 1;
        else {
            
            $island_start = $current_page - 1;
            $island_end = $current_page + 1;
            
            if ($current_page == 1) $island_end = 3;
            if ($current_page == $total_pages) $island_start = $island_start - 1;
            
            if ($island_start > 4) {
                for ($i = 0; $i < 3; $i++) $pages[] = $i + 1;
                $break_first = 3;
            }
            else {
                for ($i = 0; $i < $island_end; $i++) $pages[] = $i + 1;
            }
            
            if ($island_end < $total_pages - 4) {
                for ($i = 0; $i < 3; $i++) $pages[] = $i + $total_pages - 2;
                $break_last = $total_pages - 2;
            }
            else {
                for ($i = 0; $i < $total_pages - $island_start + 1; $i++) $pages[] = $island_start + $i;
            }
            
            if ($island_start > 4 && $island_end < $total_pages - 4) {
                for ($i = 0; $i < 3; $i++) $pages[] = $island_start + $i;
            }
        }
        sort($pages, SORT_NUMERIC);
        $render = '';
        foreach ($pages as $page) {
            if ($page == $break_last)
                $render.= "... ";
            if ($page == $current_page) 
                $render.= sprintf('<span class="page-numbers current">%s</span>', $page);
            else
                $render.= sprintf('<a class="page-numbers" href="%s&%s=%s">%s</a>', $url, $query, $page, $page);
            if ($page == $break_first)
                $render.= "... ";
        }
        
        if ($current_page > 1) $render.= sprintf('<a class="next page-numbers" href="%s&%s=%s">Previous</a>', $url, $query, $current_page - 1);
        if ($current_page < $total_pages) $render.= sprintf('<a class="next page-numbers" href="%s&%s=%s">Next</a>', $url, $query, $current_page + 1);
        
        return $render;
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
    
    function deactivate_plugin() {
        $current = get_option('active_plugins');
        if(in_array("gd-star-rating/gd-star-rating.php", $current))
            array_splice($current, array_search("gd-star-rating/gd-star-rating.php", $current), 1);
        update_option('active_plugins', $current);
    }
   
    function get_categories_hierarchy($cats, $depth = 0, $level = 0) {
        $h = array();
        foreach ($cats as $cat) {
            if($cat->parent == $level) {
                $cat->depth = $depth;
                $h[] = $cat;
                $recats = GDSRHelper::get_categories_hierarchy($cats, $depth + 1, $cat->term_id);
                $h = array_merge($h, $recats);
            }
        }
        return $h;
    }
}

/**
 * Array sort method, ascending bayesian value.
 *
 * @param object $a first object
 * @param object $b second object
 * @return bool order
 */
function gd_sort_bayesian_asc($a, $b) {
    if ($a->bayesian == $b->bayesian) return 0;
    return ($a->bayesian < $b->bayesian) ? -1 : 1;
}

/**
 * Array sort method, descending bayesian value.
 *
 * @param object $a first object
 * @param object $b second object
 * @return bool order
 */
function gd_sort_bayesian_desc($a, $b) {
    if ($a->bayesian == $b->bayesian) return 0;
    return ($a->bayesian > $b->bayesian) ? -1 : 1;
}
