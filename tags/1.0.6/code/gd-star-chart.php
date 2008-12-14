<?php

class GDSRChart {
    function votes_counter($vote_type = 'article') {
        global $wpdb, $table_prefix;
        $sql = sprintf("SELECT vote, count(*) as counter FROM %sgdsr_votes_log where vote_type = '%s' group by vote order by vote desc", $table_prefix, $vote_type);
        return $wpdb->get_results($sql);
    }
}

?>
