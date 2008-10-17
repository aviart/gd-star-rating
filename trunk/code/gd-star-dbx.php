<?php

class GDSRX
{
    function get_trend_data($ids, $grouping = "post", $type = "article", $period = "over", $last = 1, $over = 30) {
        global $wpdb, $table_prefix;
        
        if ($period == "over") $where = sprintf("str_to_date(d.vote_date, '%s') BETWEEN DATE_SUB(NOW(), INTERVAL %s DAY) AND DATE_SUB(NOW(), INTERVAL %s DAY)", "%Y-%m-%d", $last + $over, $last);
        else $where = sprintf("str_to_date(d.vote_date, '%s') BETWEEN DATE_SUB(NOW(), INTERVAL %s DAY) AND NOW()", "%Y-%m-%d", $last);
        
        switch ($grouping) {
            case "post":
                $select = "d.id";
                $from = sprintf("%sgdsr_votes_trend d", $table_prefix);
                $join = "";
                break;
            case "user":
                $select = "u.id";
                $from = sprintf("%susers u, %sposts p, %sgdsr_votes_trend d", $table_prefix, $table_prefix, $table_prefix);
                $join = "p.id = d.id and p.post_status = 'publish' and u.id = p.post_author and ";
                break;
            case "category":
                $select = "t.term_id";
                $from = sprintf("%sterm_taxonomy t, %sterm_relationships r, %sterms x, %sposts p, %sgdsr_votes_trend d", $table_prefix, $table_prefix, $table_prefix, $table_prefix, $table_prefix);
                $join = "p.id = d.id and p.post_status = 'publish' and t.term_taxonomy_id = r.term_taxonomy_id and r.object_id = p.id and t.taxonomy = 'category' and t.term_id = x.term_id and ";
                break;
        }
        
        $sql = sprintf("SELECT %s as id, sum(d.user_voters) as user_voters, sum(d.user_votes) as user_votes, sum(d.visitor_voters) as visitor_voters, sum(d.visitor_votes) as visitor_votes FROM %s WHERE %s%s and d.vote_type = '%s' and %s in (%s) group by %s order by %s asc",
            $select, $from, $join, $where, $type, $select, $ids, $select, $select
            );
        return $wpdb->get_results($sql);
    }
    
    function get_trend_calculation($ids, $grouping = "post", $show = "total", $last = 1, $over = 30) {
        global $wpdb, $table_prefix;

        switch ($grouping) {
            case "post":
                $data_last = GDSRX::get_trend_data($ids, $grouping, "article", "last", $last, $over);
                $data_over = GDSRX::get_trend_data($ids, $grouping, "article", "over", $last);
                break;
            case "category":
                $data_last = GDSRX::get_trend_data($ids, $grouping, "article", "last", $last, $over);
                $data_over = GDSRX::get_trend_data($ids, $grouping, "article", "over", $last);
                break;
            case "user":
                $data_last = GDSRX::get_trend_data($ids, $grouping, "article", "last", $last, $over);
                $data_over = GDSRX::get_trend_data($ids, $grouping, "article", "over", $last);
                break;
        }
       
        for ($i = 0; $i < count($data_over); $i++) {
            $row_over = $data_over[$i];

            if ($show == "total") {
                $votes_over[$row_over->id] = $row_over->user_votes + $row_over->visitor_votes;
                $voters_over[$row_over->id] = $row_over->user_voters + $row_over->visitor_voters;
            }                
            if ($show == "visitors") {
                $votes_over[$row_over->id] = $row_over->visitor_votes;
                $voters_over[$row_over->id] = $row_over->visitor_voters;
            }                
            if ($show == "users") {
                $votes_over[$row_over->id] = $row_over->user_votes ;
                $voters_over[$row_over->id] = $row_over->user_voters;
            }
        }
        
        if (count($data_last) == 0) {
            $votes_last = array();
            $voters_last = array();
        }

        if (count($data_over) == 0) {
            $votes_over = array();
            $voters_over = array();
        }
        
        for ($i = 0; $i < count($data_last); $i++) {
            $row_last = $data_last[$i];
            
            if ($show == "total") {
                $votes_last[$row_last->id] = $row_last->user_votes + $row_last->visitor_votes;
                $voters_last[$row_last->id] = $row_last->user_voters + $row_last->visitor_voters;
            }                
            if ($show == "visitors") {
                $votes_last[$row_last->id] = $row_last->visitor_votes;
                $voters_last[$row_last->id] = $row_last->visitor_voters;
            }                
            if ($show == "users") {
                $votes_last[$row_last->id] = $row_last->user_votes ;
                $voters_last[$row_last->id] = $row_last->user_voters;
            }
        }

        foreach ($votes_last as $key => $value) {
            if (!isset($votes_over[$key])) {
                $votes_over[$key] = 0;
                $voters_over[$key] = 0;
            }
        }
        
        foreach ($votes_over as $key => $value) {
            if (!isset($votes_last[$key])) {
                $votes_last[$key] = 0;
                $voters_last[$key] = 0;
            }
        }
        
        foreach ($votes_last as $key => $value) {
            $trends[$key] = new TrendValue($votes_last[$key], $voters_last[$key], $votes_over[$key], $voters_over[$key], $last, $over);
        }
        
        return $trends;
    }
    
    function get_widget($widget, $min = 0) {
        global $table_prefix;
        $grouping = $widget["grouping"];
        $cats = $widget["category"];
        $where = array();
        $select = "";
        if ($widget["bayesian_calculation"] == "0") $min = 0;

        $where[] = "p.id = d.post_id";
        $where[] = "p.post_status = 'publish'";
        
        if (($cats != "" && $cats != "0") || $grouping == 'category'){
            $from = sprintf("%sterm_taxonomy t, %sterm_relationships r, ", $table_prefix, $table_prefix);
            $where[] = "t.term_taxonomy_id = r.term_taxonomy_id";
            $where[] = "r.object_id = p.id";
        }
        if ($cats != "" && $cats != "0")
            $where[] = "t.term_id = ".$cats;
        if ($grouping == 'category') {
            $from.= sprintf("%sterms x, ", $table_prefix);
            $where[] = "t.taxonomy = 'category'";
            $where[] = "t.term_id = x.term_id";
            $select = "x.name as title, t.term_id, count(*) as counter, sum(d.user_votes) as user_votes, sum(d.visitor_votes) as visitor_votes, sum(d.user_voters) as user_voters, sum(d.visitor_voters) as visitor_voters";
            $group = "group by t.term_id";
        }
        else if ($grouping == 'user') {
            $from.= sprintf("%susers u, ", $table_prefix);
            $where[] = "u.id = p.post_author";
            $select = "u.display_name as title, u.id, count(*) as counter, sum(d.user_votes) as user_votes, sum(d.visitor_votes) as visitor_votes, sum(d.user_voters) as user_voters, sum(d.visitor_voters) as visitor_voters";
            $group = "group by u.id";
        }
        else {
            $select = "p.id as post_id, p.post_title as title, p.post_type, p.post_date, d.*, 1 as counter";
        }

        if ($widget["select"] != "" && $widget["select"] != "postpage") 
            $where[] = "post_type = '".$widget["select"]."'";
        
        if ($widget["hide_empty"] == "1" || $widget["bayesian_calculation"] == "1") {
            if ($widget["show"] == "total") $where[] = "(d.user_votes + d.visitor_votes) > ".$min;
            if ($widget["show"] == "visitors") $where[] = "d.visitor_votes > ".$min;
            if ($widget["show"] == "users") $where[] = "d.user_votes > ".$min;
        }
        if ($widget["hide_noreview"] == "1") $where[] = "d.review > -1";
        
        if ($widget["order"] == "desc" || $widget["order"] == "asc")
            $sort = $widget["order"];
        else
            $sort = "desc";
        
        $col = $widget["column"];
        if ($col == "id" || $col == "post_title") 
            $col = "p.".$col;
        else {
            if ($col == "review") $col = "d.".$col;
            else if ($col == "rating") {
                if ($widget["show"] == "total") $col = "(d.user_votes + d.visitor_votes)/(d.user_voters + d.visitor_voters)";
                if ($widget["show"] == "visitors") $col = "d.visitor_votes/d.visitor_voters";
                if ($widget["show"] == "users") $col = "d.user_votes/d.user_voters";
            }
            else if ($col == "votes") {
                if ($widget["show"] == "total") $col = "d.user_votes + d.visitor_votes";
                if ($widget["show"] == "visitors") $col = "d.visitor_votes";
                if ($widget["show"] == "users") $col = "d.user_votes";
            }
            else $col = "p.id";
        }
        
        if ($widget["publish_date"] == "range") {
            $where[] = "post_date >+ '".$widget["publish_range_from"]."' and post_date <= '".$widget["publish_range_to"]."'";
        }
        else if ($widget["publish_date"] == "month") {
            $month = $widget["publish_month"];
            if ($month != "" && $month != "0") {
                $where[] = "year(p.post_date) = ".substr($month, 0, 4);
                $where[] = "month(p.post_date) = ".substr($month, 4, 2);
            }
        }
        else if ($widget["publish_date"] == "lastd") {
            if ($widget["publish_days"] > 0)
                $where[] = "TO_DAYS(CURDATE()) - ".$widget["publish_days"]." <= TO_DAYS(p.post_date)";
        }
        
        $sql = sprintf("select %s from %s%sposts p, %sgdsr_data_article d where %s %s order by %s %s limit 0, %s",
                $select, $from, $table_prefix, $table_prefix, join(" and ", $where), $group, $col, $sort, $widget["rows"]
            );
        return $sql;
    }
}

class TrendValue
{
    var $votes_last = 0;
    var $voters_last = 0;
    var $rating_last = 0;
    var $votes_over = 0;
    var $voters_over = 0;
    var $rating_over = 0;
    
    var $trend_rating = 0;
    var $trend_voting = 0;
    var $day_rate = 0;
    
    function TrendValue($v_last, $r_last, $v_over, $r_over, $last = 1, $over = 30) {
        $this->votes_last = $v_last;
        $this->voters_last = $r_last;
        $this->votes_over = $v_over;
        $this->voters_over = $r_over;
        
        $day_rate = $last / $over;
        
        $this->Calculate();
    }
    
    function Calculate() {
        if ($this->voters_last > 0) $this->rating_last = @number_format($this->votes_last / $this->voters_last, 1);
        if ($this->voters_over > 0) $this->rating_over = @number_format($this->votes_over / $this->voters_over, 1);
        
        if ($this->rating_last > $this->rating_over) $this->trend_rating = 1;
        else if ($this->rating_last < $this->rating_over) $this->trend_rating = -1;

        if ($this->voters_last > ($this->voters_over * $this->day_rate)) $this->trend_voting = 1;
        else if ($this->voters_last < ($this->voters_over * $this->day_rate)) $this->trend_voting = -1;
    }
}

?>