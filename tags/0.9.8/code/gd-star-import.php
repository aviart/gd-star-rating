<?php

class GDSRImport
{
    // import post star rating
    function import_psr() {
        GDSRImport::import_psr_article();
        GDSRImport::import_psr_log();
        GDSRImport::import_psr_trend();
    }
    
    function import_psr_article() {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("select distinct id from %spsr_post", $table_prefix);
        $ids = $wpdb->get_results($sql);
        $idx = array();
        foreach ($ids as $id) $idx[] = $id->id;
        $idlist = join(", ", $idx);
        
        $sql = sprintf("UPDATE %sgdsr_data_article a INNER JOIN %spsr_post p ON p.id = a.post_id set a.visitor_voters = a.visitor_voters + p.votes, a.visitor_votes = a.visitor_votes + p.points WHERE a.post_id in (%s)",
            $table_prefix, $table_prefix, $idlist
            );
        $wpdb->query($sql);
        
        $sql = sprintf("select post_id from %sgdsr_data_article where post_id in (%s)", $table_prefix, $idlist);
        $idr = $wpdb->get_results($sql);
        $idm = array();
        foreach ($idr as $id) $idm[] = $id->post_id;
        $idn = array();
        foreach ($ids as $id) {
            if (!in_array($id->id, $idm))
                $idn[] = $id->id;
        }
        if (count($idn) > 0) {
            $inlist = join(", ", $idn);
            $sql = sprintf("INSERT INTO %sgdsr_data_article SELECT p.id, 'A', 'A', 'N', 'N', if (strcmp(w.post_type, 'page'), '0', '1'), 0, 0, p.votes, p.points, -1, '', 0, 0, 0, 0, 0 FROM %spsr_post p INNER JOIN %sposts w ON p.id = w.id WHERE p.id in (%s) ORDER BY p.id",
                $table_prefix, $table_prefix, $table_prefix, $inlist
                );
            $wpdb->query($sql);
        }
    }
    
    function import_psr_log() {
        global $wpdb, $table_prefix;
        $sql = sprintf("INSERT INTO %sgdsr_votes_log SELECT null, post, 'article', 0, points, vote_date, ip, '' FROM %spsr_user ORDER BY vote_date", $table_prefix, $table_prefix);
        $wpdb->query($sql);
    }
    
    function import_psr_trend() {
        global $wpdb, $table_prefix;
        $sql = sprintf("INSERT INTO %sgdsr_votes_trend SELECT post, 'article', 0, 0, count(points), sum(points), DATE_FORMAT(vote_date, '%s') FROM %spsr_user GROUP BY post, DATE_FORMAT(vote_date, '%s') ORDER BY DATE_FORMAT(vote_date, '%s') asc, post asc", $table_prefix, '%Y-%m-%d', $table_prefix, '%Y-%m-%d', '%Y-%m-%d');
        $wpdb->query($sql);
    }
    // import post star rating
}

?>