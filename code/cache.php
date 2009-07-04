<?php

class GDSRDBCache {
    function get_posts($ids) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id in (%s)", $table_prefix, join(", ", $ids));
        return $wpdb->get_results($sql);
    }

    function get_logs($ids, $user, $type, $ip, $mod_only = false, $mixed = false) {
        global $wpdb, $table_prefix;

        $sql = sprintf();
        return $wpdb->get_results($sql);
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

$gdsr_cache_templates = new gdsrCache();

$gdsr_cache_posts_std_data = new gdsrCache();
$gdsr_cache_posts_std_log = new gdsrCache();

$gdsr_cache_posts_cmm_data = new gdsrCache();
$gdsr_cache_posts_cmm_log = new gdsrCache();

?>
