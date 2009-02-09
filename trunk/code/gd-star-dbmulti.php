<?php

class GDSRDBMulti {
    function get_stats_count($set_id, $dates = "0", $cats = "0", $search = "") {
        global $table_prefix;
        $where = " and ms.multi_id = ".$set_id;

        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.post_date) = ".substr($dates, 0, 4);
            $where.= " and month(p.post_date) = ".substr($dates, 4, 2);
        }
        if ($search != "")
            $where.= " and p.post_title like '%".$search."%'";

        if ($cats != "" && $cats != "0")
            $sql = sprintf("SELECT p.post_type, count(*) as count FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p, %sgdsr_multis_data ms WHERE p.ID = ms.post_id and t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.ID AND t.term_id = %s AND p.post_status = 'publish'%s GROUP BY p.post_type",
                $table_prefix, $table_prefix, $table_prefix, $table_prefix, $cats, $where
            );
        else
            $sql = sprintf("select p.post_type, count(*) as count from %sposts p inner join %sgdsr_multis_data ms on p.ID = ms.post_id where p.post_status = 'publish'%s group by post_type",
                $table_prefix, $table_prefix, $where
            );
        return $sql;
    }

    function get_stats($set_id, $select = "", $start = 0, $limit = 20, $dates = "0", $cats = "0", $search = "", $sort_column = 'id', $sort_order = 'desc', $additional = '') {
        global $table_prefix;
        $where = " and ms.multi_id = ".$set_id;

        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.post_date) = ".substr($dates, 0, 4);
            $where.= " and month(p.post_date) = ".substr($dates, 4, 2);
        }
        if ($search != "")
            $where.= " and p.post_title like '%".$search."%'";

        if ($select != "" && $select != "postpage")
            $where.= " and post_type = '".$select."'";

        if ($sort_column == 'post_title' || $sort_column == 'id')
            $order = " ORDER BY p.".$sort_column." ".$sort_order;
        else
            $order = " ORDER BY ".$sort_column." ".$sort_order;

        if ($cats != "" && $cats != "0")
            $sql = sprintf("SELECT p.id as pid, p.post_title, p.post_type, ms.* FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p, %sgdsr_multis_data ms WHERE ms.post_id = p.id and t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.id AND t.term_id = %s AND p.post_status = 'publish'%s%s%s LIMIT %s, %s",
                 $table_prefix, $table_prefix, $table_prefix, $table_prefix, $cats, $where, $additional, $order, $start, $limit
            );
        else
            $sql = sprintf("select p.id as pid, p.post_title, p.post_type, ms.* from %sposts p left join %sgdsr_multis_data ms on p.id = ms.post_id WHERE p.post_status = 'publish'%s%s%s limit %s, %s",
                $table_prefix, $table_prefix, $where, $additional, $order, $start, $limit
            );
        return $sql;
    }

    function delete_sets($ids) {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete from %sgdsr_multis where multi_id in %s", $table_prefix, $ids);
        $wpdb->query($sql);
    }

    function save_vote($post_id, $set_id, $user_id, $ip, $ua, $values, $post_data) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        if ($post_data->moderate_articles == "" || $post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDBMulti::add_vote($post_id, $set_id, $user_id, $ip, $ua, $values);
            GDSRDBMulti::add_to_log($post_id, $set_id, $user_id, $ip, $ua, $values);
        }
        else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, multi_id, user_id, object, voted, ip, user_agent) VALUES (%s, 'multis', %s, %s, '%s', '%s', '%s', '%s')",
                $table_prefix, $post_id, $set_id, $user_id, serialize($values), str_replace("'", "''", current_time('mysql')), $ip, $ua);
            wp_gdsr_dump("MOD", $modsql);
            $wpdb->query($modsql);
        }
    }

    function add_to_log($post_id, $set_id, $user_id, $ip, $ua, $values) {
        global $wpdb, $table_prefix;
            $modsql = sprintf("INSERT INTO %sgdsr_votes_log (id, vote_type, multi_id, user_id, object, voted, ip, user_agent) VALUES (%s, 'multis', %s, %s, '%s', '%s', '%s', '%s')",
                $table_prefix, $post_id, $set_id, $user_id, serialize($values), str_replace("'", "''", current_time('mysql')), $ip, $ua);
            wp_gdsr_dump("LOG", $modsql);
            $wpdb->query($modsql);
    }

    function add_vote($post_id, $set_id, $user_id, $ip, $ua, $votes) {
        global $wpdb, $table_prefix;
        $data = $table_prefix.'gdsr_multis_data';
        $trend = $table_prefix.'gdsr_multis_trend';

        $trend_date = date("Y-m-d");

        $sql_trend = sprintf("SELECT id FROM %s WHERE vote_date = '%s' and post_id = %s and multi_id = %s", $trend, $trend_date, $post_id, $set_id);
        $trend_data = intval($wpdb->get_var($sql_trend));
        
        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            $sql = sprintf("INSERT INTO %s (post_id, multi_id, vote_date) VALUES (%s, %s, '%s')", $trend, $post_id, $set_id, $trend_date);
            $wpdb->query($sql);
            $trend_id = $wpdb->insert_id;
        }
        else $trend_id = $trend_data;

        GDSRDBMulti::add_values($trend_id, $user_id, $votes, "trd", $trend_added ? "add" : "edit");

        $data_id = GDSRDBMulti::get_vote($post_id, $set_id);

        GDSRDBMulti::add_values($data_id, $user_id, $votes);
    }

    function add_values($record_id, $user_id, $votes, $source = "dta", $operation = "edit") {
        global $wpdb, $table_prefix;
        $values = $table_prefix.'gdsr_multis_values';
        $cl_voters = $user_id == 0 ? "visitor_voters" : "user_voters";
        $cl_votes = $user_id == 0 ? "visitor_votes" : "user_votes";

        if ($operation == "add")
            $sql = sprintf("INSERT INTO %s (id, source, %s, %s, item_id) VALUES (%s, '%s', 1, %s, %s)", 
                $values, $cl_voters, $cl_votes, $record_id, $source, "%s", "%s");
        else
            $sql = sprintf("UPDATE %s SET %s = %s + 1, %s = %s + %s WHERE id = %s and item_id = %s and source = '%s'", 
                $values, $cl_voters, $cl_voters, $cl_votes, $cl_votes, "%s", $record_id, "%s", $source);

        $i = 0;
        foreach ($votes as $vote) {
            $sql_insert = sprintf($sql, $vote, $i);
            $wpdb->query($sql_insert);
            $i++;
        }
    }
    
    function calculate_set_rating($set, $record_id) {
        $values = GDSRDBMulti::get_values($record_id);
        $weight_norm = array_sum($set->weight);
        $weighted["users"]["rating"] = 0;
        $weighted["visitors"]["rating"] = 0;
        $weighted["total"]["rating"] = 0;
        $weighted["users"]["votes"] = 0;
        $weighted["visitors"]["votes"] = 0;
        $weighted["total"]["votes"] = 0;
        $votes = false;
        foreach ($values as $row) {
            $weighted["users"]["rating"] += ( ( $row->user_votes / $row->user_voters ) * $set->weight[$row->item_id] ) / $weight_norm;
            $weighted["visitors"]["rating"] += ( ( $row->visitor_votes / $row->visitor_voters ) * $set->weight[$row->item_id] ) / $weight_norm;
            $weighted["total"]["rating"] += ( ( ($row->visitor_votes + $row->user_votes) / ($row->visitor_voters + $row->user_voters) ) * $set->weight[$row->item_id] ) / $weight_norm;
            if (!$votes) {
                $votes = true;
                $weighted["users"]["votes"] = $row->user_voters;
                $weighted["visitors"]["votes"] = $row->visitor_voters;
                $weighted["total"]["votes"] = $row->visitor_voters + $row->user_voters;
            }
        }

        $weighted["users"]["rating"] = @number_format($weighted["users"]["rating"], 1);
        $weighted["visitors"]["rating"] = @number_format($weighted["visitors"]["rating"], 1);
        $weighted["total"]["rating"] = @number_format($weighted["total"]["rating"], 1);

        return $weighted;
    }

    function get_values($id, $source = 'dta') {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("SELECT * FROM %sgdsr_multis_values WHERE source = '%s' and id = %s ORDER BY item_id ASC", $table_prefix, $source, $id);
        return $wpdb->get_results($sql);
    }

    function get_values_join($post_id, $set_id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select v.* from %sgdsr_multis_values v inner join %sgdsr_multis_data d on d.id = v.id where v.source = 'dta' and d.post_id = %s and d.multi_id = %s order by v.item_id asc",
            $table_prefix, $table_prefix, $post_id, $set_id);
        return $wpdb->get_results($sql);
    }

    function get_vote($post_id, $set_id, $values = 0) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("select * from %sgdsr_multis_data where post_id = %s and multi_id = %s", $table_prefix, $post_id, $set_id);
        $row = $wpdb->get_row($sql);

        if (count($row) == 0) {
            $sql = sprintf("INSERT INTO %sgdsr_multis_data (post_id, multi_id, review, review_text) VALUES (%s, %s, '', '')", $table_prefix, $post_id, $set_id);
            $wpdb->query($sql);
            $record_id = $wpdb->insert_id;
            for ($i = 0; $i < $values; $i++) {
                $sql = sprintf("INSERT INTO %sgdsr_multis_values (id, source, item_id) VALUES (%s, 'dta', %s)",
                    $table_prefix, $record_id, $i);
                $wpdb->query($sql);
            }
        }
        else $record_id = $row->id;
        return $record_id;
    }

    function check_vote($id, $user, $set, $type, $ip, $mod_only = false) {
        $result = true;

        if (!$mod_only)
            $result = GDSRDBMulti::check_vote_logged($id, $user, $set, $type, $ip);
        if ($result)
            $result = GDSRDBMulti::check_vote_moderated($id, $user, $set, $type, $ip);

        return $result;
    }

    function check_vote_logged($id, $user, $set, $type, $ip) {
        return GDSRDBMulti::check_vote_table('gdsr_votes_log', $id, $user, $set, $type, $ip);
    }

    function check_vote_moderated($id, $user, $set, $type, $ip) {
        return GDSRDBMulti::check_vote_table('gdsr_moderate', $id, $user, $set, $type, $ip);
    }
    
    function check_vote_table($table, $id, $user, $set, $type, $ip) {
        global $wpdb, $table_prefix;

        if ($user > 0)
            $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and multi_id = %s and id = %s and user_id = %s",
                $table_prefix.$table, $type, $set, $id, $user
            );
        else
            $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and multi_id = %s and id = %s and ip = '%s'",
                $table_prefix.$table, $type, $set, $id, $ip
            );

        $vote_data = $wpdb->get_row($votes_sql);

wp_gdsr_dump("CHECKVOTE_MULTI_sql", $votes_sql);
wp_gdsr_dump("CHECKVOTE_MULTI", $vote_data);

        if (count($vote_data) == 0)
            return true;
        else
            return false;
    }

    function get_usage_count_posts($set_id) {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("select count(*) from %sgdsr_multis_data where multi_id = %s", $table_prefix, $set_id));
    }

    function get_usage_count_voters($set_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select count(*) from %sgdsr_votes_log where multi_id = %s and vote_type = 'multis'",
            $table_prefix, $set_id);
        return $wpdb->get_var($sql);
    }

    function get_multis_count() {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("select count(*) from %sgdsr_multis", $table_prefix));
    }

    function get_multis_tinymce() {
        global $wpdb, $table_prefix;
        $sql = sprintf("select multi_id as folder, name from %sgdsr_multis", $table_prefix);
        return $wpdb->get_results($sql);
    }
    
    function get_multis($start = 0, $limit = 20) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select * from %sgdsr_multis limit %s, %s", $table_prefix, $start, $limit);
        return $wpdb->get_results($sql);
    }
    
    function get_multi_set($id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select * from %sgdsr_multis where multi_id = %s", $table_prefix, $id);
        return $wpdb->get_row($sql);
    }
    
    function add_multi_set($set) {
        global $wpdb, $table_prefix;
        $sql = sprintf("insert into %sgdsr_multis (`name`, `description`, `stars`, `object`, `weight`) values ('%s', '%s', %s, '%s', '%s')",
                $table_prefix, $set->name, $set->description, $set->stars, serialize($set->object), serialize($set->weight)
            );
        $wpdb->query($sql);
    }
    
    function edit_multi_set($set) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("update %sgdsr_multis set `name` = '%s', `description` = '%s', `object` = '%s', `weight` = '%s' where multi_id = %s",
                $table_prefix, $set->name, $set->description, serialize($set->object), serialize($set->weight), $set->id
            );
        $wpdb->query($sql);
    }
}

/**
 * Multi Rating Set
 */
class GDMultiSingle {
    var $multi_id = 0;
    var $name = "";
    var $description = "";
    var $stars = 5;
    var $object = array();
    var $weight = array();

    /**
     * Constructor
     *
     * @param bool $fill_empty prefill set with empty elements
     * @param int $count number of elements in the set
     */
    function GDMultiSingle($fill_empty = true, $count = 10) {
        if ($fill_empty) {
            for ($i = 0; $i < $count; $i++) {
                $this->object[] = "";
                $this->weight[] = 1;
            }
        }
    }
}

/**
 * Gets the multi rating set.
 *
 * @param int $id set id
 * @return GDMultiSingle multi rating set
 */
function gd_get_multi_set($id) {
    $set = GDSRDBMulti::get_multi_set($id);
    if (count($set) > 0) {
        $set->object = unserialize($set->object);
        $set->weight = unserialize($set->weight);
        return $set;
    }
    else return null;
}

?>