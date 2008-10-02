<?php

class GDSRX
{
    function get_widget($widget) {
        global $table_prefix;
        $grouping = $widget["grouping"];
        $cats = $widget["category"];
        $where = array();
        $select = "";

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
        
        $sql = sprintf("select %s from %s%sposts p, %sgdsr_data_article d where %s %s order by %s %s limit 0, %s",
                $select, $from, $table_prefix, $table_prefix, join(" and ", $where), $group, $col, $sort, $widget["rows"]
            );
//        echo $sql;
        return $sql;
    }
}