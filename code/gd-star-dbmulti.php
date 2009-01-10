<?php

class GDSRDBMulti {
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

class GDMultiSingle {
    var $id = 0;
    var $name = "";
    var $description = "";
    var $stars = 5;
    var $object = array();
    var $weight = array();
    
    function GDMultiSingle($fill_empty = true, $count = 10) {
        if ($fill_empty) {
            for ($i = 0; $i < $count; $i++) {
                $this->object[] = "";
                $this->weight[] = 1;
            }
        }
    }
}

?>