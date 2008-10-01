<?php

class GDSRX
{
    function get_widget_article($widget) {
        global $table_prefix;
        $where = array();
        $sql = "";

        $cats = $widget["category"];
        $where[] = "p.id = d.post_id";
        $where[] = "p.post_status = 'publish'";
        
        if ($cats != "" && $cats != "0"){
            $from = sprintf("%sterm_taxonomy t, %sterm_relationships r, ", $table_prefix, $table_prefix);
            $where[] = "t.term_taxonomy_id = r.term_taxonomy_id";
            $where[] = "r.object_id = p.id";
            $where[] = "t.term_id = ".$cats;
        }
        else $from = "";

        if ($widget["select"] != "" && $widget["select"] != "postpage") 
            $where[] = "post_type = '".$widget["select"]."'";
        
        if ($widget["hide_empty"] == "1") {
            if ($widget["show"] == "total") $where[] = "(d.user_votes + d.visitor_votes) > 0";
            if ($widget["show"] == "visitors") $where[] = "d.visitor_votes > 0";
            if ($widget["show"] == "users") $where[] = "d.user_votes > 0";
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
        
        $sql = sprintf("select p.id as post_id, p.post_title, p.post_type, p.post_date, d.* from %s%sposts p, %sgdsr_data_article d where %s order by %s %s limit 0, %s",
                $from, $table_prefix, $table_prefix, join(" and ", $where), $col, $sort, $widget["rows"]
            );
        return $sql;
    }
    
    function get_widget_user($widget) {
    }
    
    function get_widget_category($widget) {
    }
}