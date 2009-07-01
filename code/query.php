<?php

class GDSRQuery {
    var $keys_sort = array(
        "rating",
        "review",
        "votes"
    );

    var $keys_order = array(
        "desc",
        "asc"
    );

    function GDSRQuery() { }

    function query_vars($qvar) {
        $qvar[] = "gdsr_sort";
        $qvar[] = "gdsr_order";
        $qvar[] = "gdsr_multi";
        return $qvar;
    }

    function pre_get_posts($wpq) {
        $sort = $wpq->get("gdsr_sort");
        $type = intval($wpq->get("gdsr_sort")) > 0 ? "multis" : "standard";
        if (in_array(strtolower($sort), $keys_sort)) {
            add_filter('posts_fields', array(&$this, $type."_fields"));
            add_filter('posts_join', array(&$this, $type."_join"));
            add_filter('posts_orderby', array(&$this, $type."_orderby"));
            //add_filter('posts_where', array(&$this, $type."_where"));
        } else {
            remove_filter('posts_fields', array(&$this, $type."_fields"));
            remove_filter('posts_join', array(&$this, $type."_join"));
            remove_filter('posts_orderby', array(&$this, $type."_orderby"));
            //remove_filter('posts_where', array(&$this, $type."_where"));
        }
    }

    function standard_fields($c) {
        global $table_prefix;
        $c.= sprintf(", gdsra.review as gdsr_review");
        return $c;
    }

    function standard_join($c) {
        global $table_prefix;
        $c.= sprintf(" LEFT JOIN %sgdsr_data_article gdsra ON gdsra.post_id = %sposts.ID", $table_prefix, $table_prefix);
        return $c;
    }

    function standard_where($c) {
        global $table_prefix;
        return $c;
    }

    function standard_orderby($default) {
        global $table_prefix;

        $sort = trim(addslashes(get_query_var('gdsr_sort')));
        $order = strtolowerr(trim(addslashes(get_query_var('gdsr_sort'))));
        $order = in_array($order, $this->keys_order) ? $order : "desc";

        switch ($sort) {
            case "rating":
                $c = sprintf(" (gdsra.user_votes + gdsra.visitor_votes)/(gdsra.user_voters + gdsra.visitor_voters) ".$order);
                break;
            case "votes":
                $c = sprintf(" (gdsra.user_voters + gdsra.visitor_voters) ".$order);
                break;
            case "review":
                $c = sprintf(" gdsra.review ".$order);
                break;
            default:
                $c = "";
                break;
        }
        return $c != "" ? $c : $default;
    }

    function multis_fields($c) {
        global $table_prefix;
        $c.= sprintf(", gdsrm.average_review as gdsr_review");
        return $c;
    }

    function multis_join($c) {
        global $table_prefix;
        $c.= sprintf(" LEFT JOIN %sgdsr_multis_data gdsrm ON gdsrm.post_id = %sposts.ID", $table_prefix, $table_prefix);
        return $c;
    }

    function multis_where($c) {
        global $table_prefix;
        return $c;
    }

    function multis_orderby($default) {
        global $table_prefix;

        $sort = trim(addslashes(get_query_var('gdsr_sort')));
        $order = strtolowerr(trim(addslashes(get_query_var('gdsr_sort'))));
        $order = in_array($order, $this->keys_order) ? $order : "desc";

        switch ($sort) {
            case "rating":
                $c = sprintf(" (gdsrm.average_rating_users * gdsrm.total_votes_users + gdsrm.average_rating_visitors * gdsrm.total_votes_visitors)/(gdsrm.total_votes_users + gdsrm.total_votes_visitors) ".$order);
                break;
            case "votes":
                $c = sprintf(" (gdsrm.total_votes_users + gdsrm.total_votes_visitors) ".$order);
                break;
            case "review":
                $c = sprintf(" gdsrm.average_review ".$order);
                break;
            default:
                $c = "";
                break;
        }
        return $c != "" ? $c : $default;
    }
}

?>
