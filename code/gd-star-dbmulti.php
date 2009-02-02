<?php

class GDSRDBMulti {
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
    var $id = 0;
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