<?php

class gdsrQuery {
    var $keys_sort = array(
        "rating",
        "review",
        "thumbs",
        "votes",
        "thumbs_votes",
        "last_voted"
    );

    var $keys_order = array(
        "desc",
        "asc"
    );

    function gdsrQuery() { }

    function query_vars($qvar) {
        $qvar[] = "gdsr_sort";
        $qvar[] = "gdsr_order";
        $qvar[] = "gdsr_multi";
        $qvar[] = "gdsr_fsvmin";
        $qvar[] = "gdsr_ftvmin";
        return $qvar;
    }

    function pre_get_posts($wpq) {
        $sort = $wpq->get("gdsr_sort");
        $type = intval($wpq->get("gdsr_multi")) > 0 ? "multis" : "standard";
        if (in_array(strtolower($sort), $this->keys_sort)) {
            add_filter('posts_fields', array(&$this, $type."_fields"));
            add_filter('posts_join', array(&$this, $type."_join"));
            add_filter('posts_orderby', array(&$this, $type."_orderby"));
            add_filter('posts_where', array(&$this, $type."_where"));
        } else {
            remove_filter('posts_fields', array(&$this, $type."_fields"));
            remove_filter('posts_join', array(&$this, $type."_join"));
            remove_filter('posts_orderby', array(&$this, $type."_orderby"));
            remove_filter('posts_where', array(&$this, $type."_where"));
        }
    }

    function standard_fields($c) {
        global $table_prefix;
        $c.= ", (gdsra.user_votes + gdsra.visitor_votes)/(gdsra.user_voters + gdsra.visitor_voters) as gdsr_rating";
        $c.= ", (gdsra.user_recc_plus - gdsra.user_recc_minus + gdsra.visitor_recc_plus - gdsra.visitor_recc_minus) as gdsr_thumb_score";
        $c.= ", (gdsra.user_voters + gdsra.visitor_voters) as gdsr_votes";
        $c.= ", (gdsra.user_recc_plus + gdsra.user_recc_minus + gdsra.visitor_recc_plus + gdsra.visitor_recc_minus) as gdsr_thumb_votes";
        $c.= ", gdsra.review as gdsr_review";
        $c.= ", gdsra.last_voted as gdsr_last_voted";
        return $c;
    }

    function standard_join($c) {
        global $table_prefix;
        $c.= sprintf(" LEFT JOIN %sgdsr_data_article gdsra ON gdsra.post_id = %sposts.ID", $table_prefix, $table_prefix);
        return $c;
    }

    function standard_where($c) {
        $filter_min_votes = intval(trim(addslashes(get_query_var('gdsr_fsvmin'))));
        $filter_min_votes_thumbs = intval(trim(addslashes(get_query_var('gdsr_ftvmin'))));
        if ($filter_min_votes > 0) $c.= " AND (gdsra.user_voters + gdsra.visitor_voters) > ".$filter_min_votes;
        if ($filter_min_votes_thumbs > 0) $c.= " AND (gdsra.user_recc_plus + gdsra.user_recc_minus + gdsra.visitor_recc_plus + gdsra.visitor_recc_minus) > ".$filter_min_votes_thumbs;
        return $c;
    }

    function standard_orderby($default) {
        global $table_prefix;

        $sort = trim(addslashes(get_query_var('gdsr_sort')));
        $order = strtolower(trim(addslashes(get_query_var('gdsr_order'))));
        $order = in_array($order, $this->keys_order) ? $order : "desc";

        $c = "";
        switch ($sort) {
            case "thumbs":
                $c = sprintf(" (gdsra.user_recc_plus - gdsra.user_recc_minus + gdsra.visitor_recc_plus - gdsra.visitor_recc_minus) ".$order);
                $c.= sprintf(", (gdsra.user_recc_plus + gdsra.user_recc_minus + gdsra.visitor_recc_plus + gdsra.visitor_recc_minus) ".$order);
                break;
            case "rating":
                $c = sprintf(" (gdsra.user_votes + gdsra.visitor_votes)/(gdsra.user_voters + gdsra.visitor_voters) ".$order);
                $c.= sprintf(", (gdsra.user_voters + gdsra.visitor_voters) ".$order);
                break;
            case "thumbs_votes":
                $c = sprintf(" (gdsra.user_recc_plus + gdsra.user_recc_minus + gdsra.visitor_recc_plus + gdsra.visitor_recc_minus) ".$order);
                break;
            case "votes":
                $c = sprintf(" (gdsra.user_voters + gdsra.visitor_voters) ".$order);
                break;
            case "review":
                $c = sprintf(" gdsra.review ".$order);
                break;
            case "last_voted":
                $c = sprintf(" gdsra.last_voted ".$order);
                break;
        }

        if ($c != "") $c.= sprintf(", %sposts.post_date desc", $table_prefix);
        return $c != "" ? $c : $default;
    }

    function multis_fields($c) {
        global $table_prefix;
        $c.= ", (gdsrm.average_rating_users * gdsrm.total_votes_users + gdsrm.average_rating_visitors * gdsrm.total_votes_visitors)/(gdsrm.total_votes_users + gdsrm.total_votes_visitors) as gdsr_rating";
        $c.= ", (gdsrm.total_votes_users + gdsrm.total_votes_visitors) as gdsr_votes";
        $c.= ", gdsrm.average_review as gdsr_review";
        $c.= ", gdsrm.last_voted as gdsr_last_voted";
        return $c;
    }

    function multis_join($c) {
        global $table_prefix;
        $c.= sprintf(" LEFT JOIN %sgdsr_multis_data gdsrm ON gdsrm.post_id = %sposts.ID", $table_prefix, $table_prefix);
        return $c;
    }

    function multis_where($c) {
        $set = intval(trim(addslashes(get_query_var('gdsr_multi'))));
        $c.= " AND gdsrm.multi_id = ".$set;
        $filter_min_votes = intval(trim(addslashes(get_query_var('gdsr_fsvmin'))));
        if ($filter_min_votes > 0) $c.= " AND (gdsrm.total_votes_users + gdsrm.total_votes_visitors) > ".$filter_min_votes;
        return $c;
    }

    function multis_orderby($default) {
        global $table_prefix;

        $sort = trim(addslashes(get_query_var('gdsr_sort')));
        $order = strtolower(trim(addslashes(get_query_var('gdsr_order'))));
        $order = in_array($order, $this->keys_order) ? $order : "desc";

        $c = "";
        switch ($sort) {
            case "rating":
                $c = sprintf(" (gdsrm.average_rating_users * gdsrm.total_votes_users + gdsrm.average_rating_visitors * gdsrm.total_votes_visitors)/(gdsrm.total_votes_users + gdsrm.total_votes_visitors) ".$order);
                $c.= sprintf(", (gdsrm.total_votes_users + gdsrm.total_votes_visitors) ".$order);
                break;
            case "votes":
                $c = sprintf(" (gdsrm.total_votes_users + gdsrm.total_votes_visitors) ".$order);
                break;
            case "review":
                $c = sprintf(" gdsrm.average_review ".$order);
                break;
            case "last_voted":
                $c = sprintf(" gdsrm.last_voted ".$order);
                break;
        }

        if ($c != "") $c.= sprintf(", %sposts.post_date desc", $table_prefix);
        return $c != "" ? $c : $default;
    }
}

?>