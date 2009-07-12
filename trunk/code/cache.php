<?php

class GDSRDBCache {
    function get_comments($post_id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("SELECT * FROM %sgdsr_data_comment WHERE post_id = %s", $table_prefix, $post_id);
        return $wpdb->get_results($sql);
    }

    function get_posts($ids) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id in (%s)", $table_prefix, join(", ", $ids));
        return $wpdb->get_results($sql);
    }

    function get_logs($ids, $user, $type, $ip, $mod_only = false, $mixed = false) {
        global $wpdb, $table_prefix;

        $table = ($type == "article" || $type == "artthumb") ? "posts" : "comments";
        $column = ($type == "article" || $type == "artthumb") ? "ID" : "comment_ID";

        if (intval($user) == 0) {
            $sql = sprintf("select p.%s as ID, count(l.record_id) as counter from %s%s p left join %sgdsr_moderate l on l.id = p.%s and l.vote_type = '%s' and ip = '%s' where p.%s in (%s) group by p.%s",
                $column, $table_prefix, $table, $table_prefix, $column, $type, $ip, $column, join(", ", $ids), $column);
            $res_mod = $wpdb->get_results($sql);

            if (!$mod_only) {
                $sql = sprintf("select p.%s as ID, count(l.record_id) as counter from %s%s p left join %sgdsr_votes_log l on l.id = p.%s and l.vote_type = '%s' and ip = '%s' where p.%s in (%s) group by p.%s",
                    $column, $table_prefix, $table, $table_prefix, $column, $type, $ip, $column, join(", ", $ids), $column);
                $res_log = $wpdb->get_results($sql);
            } else $res_log = array();
        } else {
            $sql = sprintf("select p.%s as ID, count(l.record_id) as counter from %s%s p left join %sgdsr_moderate l on l.id = p.%s and l.vote_type = '%s' and l.user_id = %s where p.%s in (%s) group by p.%s",
                $column, $table_prefix, $table, $table_prefix, $column, $type, $user, $column, join(", ", $ids), $column);
            $res_mod = $wpdb->get_results($sql);

            if (!$mixed) {
                if (!$mod_only) {
                    $sql = sprintf("select p.%s as ID, count(l.record_id) as counter from %s%s p left join %sgdsr_votes_log l on l.id = p.%s and l.vote_type = '%s' and l.user_id = %s where p.%s in (%s) group by p.%s",
                        $column, $table_prefix, $table, $table_prefix, $column, $type, $user, $column, join(", ", $ids), $column);
                    $res_log = $wpdb->get_results($sql);
                } else $res_log = array();
            } else $res_log = array();
        }

        $res = array();
        foreach ($res_mod as $r) $res[$r->ID] = $r->counter;
        if (count($res_log) > 0) {
            foreach ($res_log as $r) {
                if (intval($r->counter) != 0) $res[$r->ID] = 1;
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

function wp_gdget_comment($comment_id) {
    global $gdsr_cache_posts_cmm_data;

    $cmm = $gdsr_cache_posts_cmm_data->get($comment_id);
    if (!is_null($cmm)) return $cmm;
    else {
        $cmm = GDSRDatabase::get_comment_data($comment_id);
        $gdsr_cache_posts_cmm_data->set($comment_id, $cmm);
        return $cmm;
    }
}

function wp_gdget_commentlog($comment_id) {
    global $gdsr_cache_posts_cmm_log;

    $log = $gdsr_cache_posts_cmm_log->get($comment_id);
    if (!is_null($log)) return $log;
    else return true;
}

function wp_gdget_thumb_postlog($post_id) {
    global $gdsr_cache_posts_std_thumbs_log;

    $log = $gdsr_cache_posts_std_thumbs_log->get($post_id);
    if (!is_null($log)) return $log;
    else return true;
}

function wp_gdget_thumb_commentlog($comment_id) {
    global $gdsr_cache_posts_cmm_thumbs_log;

    $log = $gdsr_cache_posts_cmm_thumbs_log->get($comment_id);
    if (!is_null($log)) return $log;
    else return true;
}

$gdsr_cache_templates = new gdsrCache();
$gdsr_cache_posts_std_data = new gdsrCache();
$gdsr_cache_posts_std_log = new gdsrCache();
$gdsr_cache_posts_cmm_data = new gdsrCache();
$gdsr_cache_posts_cmm_log = new gdsrCache();
$gdsr_cache_posts_std_thumbs_log = new gdsrCache();
$gdsr_cache_posts_cmm_thumbs_log = new gdsrCache();

?>
