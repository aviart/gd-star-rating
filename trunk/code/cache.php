<?php

class GDSRDBCache {
    function get_posts($ids) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id in (%s)", $table_prefix, join(", ", $ids));
        return $wpdb->get_results($sql);
    }

    function get_logs($ids, $user, $type, $ip, $mod_only = false, $mixed = false) {
        global $wpdb, $table_prefix;

        if ($user == 0) {
            $sql = sprintf("select p.ID, count(l.record_id) as counter from %sposts p left join %sgdsr_moderate l on l.id = p.ID and l.vote_type = '%s' and ip = '%s' where p.ID in (%s) group by p.ID",
                $table_prefix, $table_prefix, $type, $ip, join(", ", $ids));
            $res_mod = $wpdb->get_results($sql);

            if (!$mod_only) {
                $sql = sprintf("select p.ID, count(l.record_id) as counter from %sposts p left join %sgdsr_votes_log l on l.id = p.ID and l.vote_type = '%s' and ip = '%s' where p.ID in (%s) group by p.ID",
                    $table_prefix, $table_prefix, $type, $ip, join(", ", $ids));
                $res_log = $wpdb->get_results($sql);
            } else $res_log = array();
        } else {
            $sql = sprintf("select p.ID, count(l.record_id) as counter from %sposts p left join %sgdsr_moderate l on l.id = p.ID and l.vote_type = '%s' and user_id = %s where p.ID in (%s) group by p.ID",
                $table_prefix, $table_prefix, $type, $user, join(", ", $ids));
            $res_mod = $wpdb->get_results($sql);

            if (!$mixed) {
                if (!$mod_only) {
                    $sql = sprintf("select p.ID, count(l.record_id) as counter from %sposts p left join %sgdsr_votes_log l on l.id = p.ID and l.vote_type = '%s' and user_id = %s where p.ID in (%s) group by p.ID",
                        $table_prefix, $table_prefix, $type, $user, join(", ", $ids));
                    $res_log = $wpdb->get_results($sql);
                } else $res_log = array();
            } else $res_log = array();
        }

        $res = array();
        foreach ($res_mod as $r) $res[$r->ID] = $r->counter;
        if (count($res_log) > 0) {
            foreach ($res_log as $r) {
                if ($r->counter != 0) $res[$r->ID] = 1;
            }
        }

        return $res;
    }
}

class gdsrCache {
    var $objects;

    function gdsrCache() {
        $this->objects = array();
    }

    function get($id) {
        if (isset($this->objects[$id])) return $this->objects[$id];
        else return null;
    }

    function set($id, $o) {
        $this->objects[$id] = $o;
    }
}

function wp_gdget_post($post_id) {
    global $gdsr_cache_posts_std_data;

    $post = $gdsr_cache_posts_std_data->get($post_id);
    if (!is_null($post)) return $post;
    else {
        $post = GDSRDatabase::get_post_data($post_id);
        $gdsr_cache_posts_std_data->set($post_id, $post);
        return $post;
    }
}

function wp_gdget_postlog($post_id) {
    global $gdsr_cache_posts_std_log;

    $log = $gdsr_cache_posts_std_log->get($post_id);
    if (!is_null($log)) return $log;
    else return true;
}

$gdsr_cache_templates = new gdsrCache();

$gdsr_cache_posts_std_data = new gdsrCache();
$gdsr_cache_posts_std_log = new gdsrCache();

$gdsr_cache_posts_cmm_data = new gdsrCache();
$gdsr_cache_posts_cmm_log = new gdsrCache();

?>
