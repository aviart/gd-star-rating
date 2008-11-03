<?php

class GDSRImport
{
    function import_check($import_exists) {
        if ($import_exists) {
            _e("Data not imported.", "gd-star-rating");
            return true;
        }
        else {
            _e("Plugin not available for import.", "gd-star-rating");
            return false;
        }
    }

    // import post star rating
    function import_psr() {
        GDSRImport::import_psr_article();
        GDSRImport::import_psr_log();
        GDSRImport::import_psr_trend();
    }

    function import_psr_check($import_status) {
        if ($import_status == 0) {
            return GDSRImport::import_check(GDSRDatabase::table_exists("psr_post") && GDSRDatabase::table_exists("psr_user"));
        }
        else {
            _e("Data imported.", "gd-star-rating");
            return false;
        }
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
    
    // improt wp post rating
    function import_wpr() {
        GDSRImport::import_wpr_log();
        GDSRImport::import_wpr_trend_articles();
    }
    
    function import_wpr_check($import_status) {
        if ($import_status == 0) {
            return GDSRImport::import_check(GDSRDatabase::table_exists("ratings"));
        }
        else {
            _e("Data imported.", "gd-star-rating");
            return false;
        }
    }
    
    function import_wpr_log() {
        global $wpdb, $table_prefix;
        $sql = sprintf("INSERT INTO %sgdsr_votes_log SELECT null, rating_postid, 'article', rating_userid, rating_rating, FROM_UNIXTIME(rating_timestamp), rating_ip, '' FROM %sratings ORDER BY rating_timestamp asc", $table_prefix, $table_prefix);
        $wpdb->query($sql);
    }
    
    function import_wpr_trend_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("select distinct rating_postid from %sratings", $table_prefix);
        $ids = $wpdb->get_results($sql);
        $idx = array();
        foreach ($ids as $id) $idx[] = $id->rating_postid;
        $idlist = join(", ", $idx);
        $sql = sprintf("select post_id from %sgdsr_data_article where post_id in (%s)", $table_prefix, $idlist);
        $idr = $wpdb->get_results($sql);
        $idm = array();
        foreach ($idr as $id) $idm[] = $id->post_id;
        $idn = array();
        foreach ($ids as $id) {
            if (!in_array($id->rating_postid, $idm))
                $idn[] = $id->id;
        }
        $sqlFull = sprintf("CREATE TABLE %sgdsrtemp (id INTEGER(11) DEFAULT NULL, vote_type VARCHAR(10) DEFAULT NULL, user_voters INTEGER(11) DEFAULT NULL, user_votes INTEGER(11) DEFAULT NULL, visitor_voters INTEGER(11) DEFAULT NULL, visitor_votes INTEGER(11) DEFAULT NULL, vote_date VARCHAR(10) DEFAULT NULL)", $table_prefix);
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("CREATE TABLE %sgdsrjoin (id INTEGER(11) DEFAULT NULL, vote_type VARCHAR(10) DEFAULT NULL, user_voters INTEGER(11) DEFAULT NULL, user_votes INTEGER(11) DEFAULT NULL, visitor_voters INTEGER(11) DEFAULT NULL, visitor_votes INTEGER(11) DEFAULT NULL, vote_date VARCHAR(10) DEFAULT NULL)", $table_prefix);
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("INSERT INTO %sgdsrtemp SELECT rating_postid, 'article', 0, 0, count(rating_rating), sum(rating_rating), DATE_FORMAT(FROM_UNIXTIME(rating_timestamp), '%s') FROM %sratings where rating_userid = 0 group by rating_postid, DATE_FORMAT(FROM_UNIXTIME(rating_timestamp), '%s') ORDER BY rating_timestamp asc", $table_prefix, "%Y-%m-%d", $table_prefix, "%Y-%m-%d");
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("INSERT INTO %sgdsrtemp SELECT rating_postid, 'article', count(rating_rating), sum(rating_rating), 0, 0, DATE_FORMAT(FROM_UNIXTIME(rating_timestamp), '%s') FROM %sratings where rating_userid > 0 group by rating_postid, DATE_FORMAT(FROM_UNIXTIME(rating_timestamp), '%s') ORDER BY rating_timestamp asc", $table_prefix, "%Y-%m-%d", $table_prefix, "%Y-%m-%d");
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("INSERT INTO %sgdsrjoin SELECT id, 'article', sum(user_voters), sum(user_votes), sum(visitor_voters), sum(visitor_votes), vote_date FROM %sgdsrtemp group by id", $table_prefix, $table_prefix);
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("INSERT INTO %sgdsr_votes_trend SELECT * FROM %sgdsrjoin; ", $table_prefix, $table_prefix);
        $wpdb->query($sqlFull);
        $sqlFull = sprintf("UPDATE %sgdsr_data_article a INNER JOIN %sgdsrjoin p ON p.id = a.post_id set a.visitor_voters = a.visitor_voters + p.visitor_voters, a.visitor_votes = a.visitor_votes + p.visitor_votes, a.user_voters = a.user_voters + p.user_voters, a.user_votes = a.user_votes + p.user_votes WHERE a.post_id in (%s)",
            $table_prefix, $table_prefix, $idlist
            );
        $wpdb->query($sqlFull);

        if (count($idn) > 0) {
            $inlist = join(", ", $idn);
            $sqlFull = sprintf("INSERT INTO %sgdsr_data_article SELECT p.id, 'A', 'A', 'N', 'N', if (strcmp(w.post_type, 'page'), '0', '1'), p.user_voters, p.user_votes, p.visitor_voters, p.visitor_votes, -1, '', 0, 0, 0, 0, 0 FROM %sgdsrjoin p INNER JOIN %sposts w ON p.id = w.id WHERE p.id in (%s) ORDER BY p.id",
                $table_prefix, $table_prefix, $table_prefix, $inlist
                );
            $wpdb->query($sqlFull);
        }

        $wpdb->query(sprintf("DROP TABLE %sgdsrtemp", $table_prefix));
        $wpdb->query(sprintf("DROP TABLE %sgdsrjoin", $table_prefix));
    }
    // improt wp post rating
}

?>