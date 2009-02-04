<?php

class GDSRDBMulti {
    function save_vote($post_id, $set_id, $user_id, $ip, $ua, $values, $post_data) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        if ($post_data->moderate_articles == "" || $post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDBMulti::add_vote($id, $user, $ip, $ua, $vote);
        }
        else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, multi_id, user_id, object, voted, ip, user_agent) VALUES (%s, 'multis', %s, %s, '%s', '%s', '%s', '%s')",
                $table_prefix, $post_id, $set_id, $user, $values, str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }

    function add_vote($post_id, $set_id, $user_id, $ip, $ua, $votes) {
        global $wpdb, $table_prefix;
        $data = $table_prefix.'gdsr_multis_data';
        $trend = $table_prefix.'gdsr_multis_trend';

        $trend_date = date("Y-m-d");

        $sql_trend = sprintf("SELECT record_id FROM %s WHERE vote_date = '%s' and post_id = %s and multi_id = %s", $trend, $trend_date, $post_id, $set_id);
        $trend_data = intval($wpdb->get_var($sql_trend));

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            $sql = sprintf("INSERT INTO %s (post_id, multi_id, vote_date) VALUES (%s, %s, '%s')", $trend, $post_id, $set_id, $trend_date);
            $wpdb->query($sql);
            $trend_id = $wpdb->insert_id;
        }
        else $trend_id = $trend_data;

        GDSRDBMulti::add_values($trend_id, $user_id, $votes, "trend", $trend_added ? "edit" : "add");
        GDSRDBMulti::add_values($trend_id, $user_id, $votes);
    }

    function add_values($record_id, $user_id, $votes, $source = "data", $operation = "edit") {
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
            $sql = sprintf($sql, $vote, $i);
            $wpdb->query($sql);
            $i++;
        }
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

    function get_empty_value() {
        $value = str_repeat("0|", count($this->object));
        return substr($value, 0, strlen($value) - 1);
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