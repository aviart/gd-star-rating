<?php

class GDSRHelper
{
    function detect_bot($str) {
        $spiders = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler");
        foreach($spiders as $spider) {
        if (ereg($spider, $str)) 
            return true;
        }
        return false;
    }
    
    function render_styles_select($styles, $selected = 0) {
        for ($i = 0; $i < count($styles); $i++) {
            $style = $styles[$i];
            if ($selected == $i) $current = ' selected="selected"';
            else $current = '';
            echo "\t<option value='".$i."'".$current.">".$style["name"]."</option>\r\n";
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
    
    function render_stars_select_full($selected = 10, $stars = 20, $start = 1) {
        for ($i = $start; $i < $stars + 1; $i++) {
            if ($selected == $i) $current = ' selected="selected"';
            else $current = '';
            echo "\t<option value='".$i."'".$current.">".$i."</option>\r\n";
        }
    }
    
    function render_styles_js($styles) {
        $js = "";
        for ($i = 0; $i < count($styles); $i++) {
            $js.="'".$styles[$i]["folder"]."'";
            if ($i < count($styles)-1) $js.=",";
        }
        echo $js;
    }

    function render_styles_types_js($styles) {
        $js = "";
        for ($i = 0; $i < count($styles); $i++) {
            $js.="'".$styles[$i]["type"]."'";
            if ($i < count($styles)-1) $js.=",";
        }
        echo $js;
    }

    function render_moderation_combo($name, $selected = "N", $width = 180, $style = '', $row_zero = false) {
        ?>
<select style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value=""<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="N"<?php echo $selected == 'N' ? ' selected="selected"' : ''; ?>><?php _e("No moderation", "gd-star-rating"); ?> </option>
    <option value="V"<?php echo $selected == 'V' ? ' selected="selected"' : ''; ?>><?php _e("Moderate visitors", "gd-star-rating"); ?></option>
    <option value="U"<?php echo $selected == 'U' ? ' selected="selected"' : ''; ?>><?php _e("Moderate users", "gd-star-rating"); ?></option>
    <option value="A"<?php echo $selected == 'A' ? ' selected="selected"' : ''; ?>><?php _e("Moderate all", "gd-star-rating"); ?></option>
</select>
        <?php
    }	

    function render_rules_combo($name, $selected = "A", $width = 180, $style = '', $row_zero = false) {
        ?>
<select style="width: <?php echo $width ?>px; <?php echo $style; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
    <?php if ($row_zero) { ?> <option value=""<?php echo $selected == '/' ? ' selected="selected"' : ''; ?>>/</option> <?php } ?>
    <option value="A"<?php echo $selected == 'A' ? ' selected="selected"' : ''; ?>><?php _e("Everyone can vote", "gd-star-rating"); ?></option>
    <option value="V"<?php echo $selected == 'V' ? ' selected="selected"' : ''; ?>><?php _e("Only visitors", "gd-star-rating"); ?></option>
    <option value="U"<?php echo $selected == 'U' ? ' selected="selected"' : ''; ?>><?php _e("Only users", "gd-star-rating"); ?></option>
    <option value="N"<?php echo $selected == 'N' ? ' selected="selected"' : ''; ?>><?php _e("Locked", "gd-star-rating"); ?></option>
    <option value="H"<?php echo $selected == 'H' ? ' selected="selected"' : ''; ?>><?php _e("Locked and hidden", "gd-star-rating"); ?></option>
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
   
    function create_posts_query($select, $sort_column, $sort_order = 'desc') {
        global $table_prefix;
        
        if ($sort_column == 'id' || $sort_column == 'post_title') $prefix = "p.";
        else $prefix = "d.";
        
        if ($sort_column == "votes") {
            $sort_column = "count(*)";
            $prefix = "";
        }

        if ($sort_column == "users") {
            $sort_column = "count(distinct s.user_id)";
            $prefix = "";
        }
        
        if ($select == "post") $where.= " and p.post_type = 'post";
        else if ($select == "page") $where.= " and p.post_type = 'page'";
        
        $sql = "select p.id, p.post_title, count(distinct s.user_id) as users, count(*) as votes from ".$table_prefix."gdstarrating_stats s left join ".$table_prefix."posts p on p.id = s.post_id ".$where." group by p.id order by ".$prefix.$sort_column." ".$sort_order;
        return $sql;
    }
    
    function create_posts_query_count($select) {
        global $table_prefix;
        $where = "";
        if ($select == "post") $where = "where d.is_page = 0";
        else if ($select == "page") $where = "where d.is_page = 1";
        
        $sql = "select count(distinct p.post_id) as count from ".$table_prefix."gdstarrating_stats p inner join ".$table_prefix."gdstarrating_data d on p.post_id = d.post_id ".$where;
        return $sql;
    }

    function create_users_query($sort_column, $sort_order = 'desc') {
        global $table_prefix;

        if ($sort_column == 'user_login') $prefix = "p.";
        else $prefix = "s.";
        
        if ($sort_column == "votes") {
            $sort_column = "count(*)";
            $prefix = "";
        }
        
        $sql = "SELECT s.user_id, p.user_login, count(*) as votes FROM ".$table_prefix."gdstarrating_stats s left join ".$table_prefix."users p on s.user_id = p.id group by s.user_id order by ".$prefix.$sort_column." ".$sort_order;
        return $sql;
    }
    
    function create_users_query_count() {
        global $table_prefix;

        $sql = "select count(distinct user_id) as count from ".$table_prefix."gdstarrating_stats";
        return $sql;
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
}

function gd_addslashes($input) {
    if (get_magic_quotes_gpc()) return $input;
    else return addslashes($input);
}