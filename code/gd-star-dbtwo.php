<?php

class GDSRDB
{
    function dump($msg, $object, $mode = "a+") {
        $obj = print_r($object, true);
        $f = fopen("c:/db_two.txt", $mode);
        fwrite ($f, sprintf("[%s] : %s\r\n", current_time('mysql'), $msg));
        fwrite ($f, "$obj");
        fwrite ($f, "\r\n");
        fclose($f);
    }

    function get_database_tables() {
        global $table_prefix;
        $tables = array(
            "data_article" => $table_prefix.'gdsr_data_article',
            "data_comment" => $table_prefix.'gdsr_data_comment',
            "votes_log" => $table_prefix.'gdsr_votes_log',
            "votes_trend" => $table_prefix.'gdsr_votes_trend',
            "moderate" => $table_prefix.'gdsr_moderate'
        );
        return $tables;
    }
    
    function upgrade_database() {
        global $wpdb, $table_prefix;
        
        $wpdb->query("ALTER TABLE ".$table_prefix."gdsr_data_article ADD expiry_type VARCHAR(1) NOT NULL DEFAULT 'N'");
        $wpdb->query("ALTER TABLE ".$table_prefix."gdsr_data_article ADD expiry_value VARCHAR(32) NOT NULL");
    }
    
    function uninstall_database() {
        global $wpdb, $table_prefix;

        $dbt_votes_log = $table_prefix.'gdsr_votes_log';
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';
        $dbt_votes_trend = $table_prefix.'gdsr_votes_trend';
        $dbt_moderate = $table_prefix.'gdsr_moderate';

        $wpdb->query("DROP TABLE $dbt_data_article");
        $wpdb->query("DROP TABLE $dbt_votes_log");
        $wpdb->query("DROP TABLE $dbt_data_comment");
        $wpdb->query("DROP TABLE $dbt_votes_trend");
        $wpdb->query("DROP TABLE $dbt_moderate");
    }
    
    function install_database() {
        global $wpdb, $table_prefix;

        $dbt_votes_log = $table_prefix.'gdsr_votes_log';
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';
        $dbt_votes_trend = $table_prefix.'gdsr_votes_trend';
        $dbt_moderate = $table_prefix.'gdsr_moderate';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$dbt_data_article'") != $dbt_data_article) {
            $install_sql = "CREATE TABLE $dbt_data_article (";
            $install_sql.= "post_id INTEGER(11) UNSIGNED NOT NULL,";
            $install_sql.= "rules_articles VARCHAR(1) DEFAULT '0',";
            $install_sql.= "rules_comments VARCHAR(1) DEFAULT '0',";
            $install_sql.= "moderate_articles VARCHAR(1) DEFAULT '0',";
            $install_sql.= "moderate_comments VARCHAR(1) DEFAULT '0',";
            $install_sql.= "is_page VARCHAR(1) DEFAULT '0',";
            $install_sql.= "user_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "user_votes DECIMAL(11,1) DEFAULT 0,";
            $install_sql.= "visitor_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_votes DECIMAL(11,1) DEFAULT 0,";
            $install_sql.= "review DECIMAL(3,1) DEFAULT -1,";
            $install_sql.= "review_text VARCHAR(255) DEFAULT NULL,";
            $install_sql.= "views INTEGER(11) DEFAULT -1,";
            $install_sql.= "user_recc_plus INTEGER(11) DEFAULT 0,";
            $install_sql.= "user_recc_minus INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_recc_plus INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_recc_minus INTEGER(11) DEFAULT 0,";
            $install_sql.= "expiry_type VARCHAR(1) NOT NULL DEFAULT 'N',";
            $install_sql.= "expiry_value VARCHAR(32) NOT NULL,";
            $install_sql.= "PRIMARY KEY (post_id))";
            $wpdb->query($install_sql);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$dbt_data_comment'") != $dbt_data_comment) {
            $install_sql = "CREATE TABLE $dbt_data_comment (";
            $install_sql.= "comment_id INTEGER(11) UNSIGNED NOT NULL,";
            $install_sql.= "post_id INTEGER(11) DEFAULT -1,";
            $install_sql.= "is_locked VARCHAR(1) DEFAULT '0',";
            $install_sql.= "user_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "user_votes DECIMAL(11,1) DEFAULT 0,";
            $install_sql.= "visitor_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_votes DECIMAL(11,1) DEFAULT 0,";
            $install_sql.= "review DECIMAL(3,1) DEFAULT -1,";
            $install_sql.= "review_text VARCHAR(255) DEFAULT NULL,";
            $install_sql.= "user_recc_plus INTEGER(11) DEFAULT 0,";
            $install_sql.= "user_recc_minus INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_recc_plus INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_recc_minus INTEGER(11) DEFAULT 0,";
            $install_sql.= "PRIMARY KEY (comment_id))";
            $wpdb->query($install_sql);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$dbt_votes_log'") != $dbt_votes_log) {
            $install_sql = "CREATE TABLE $dbt_votes_log (";
            $install_sql.= "record_id INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT,";
            $install_sql.= "id INTEGER(11) DEFAULT NULL,";
            $install_sql.= "vote_type VARCHAR(10) DEFAULT 'article',";
            $install_sql.= "user_id INTEGER(11) DEFAULT '0',";
            $install_sql.= "vote INTEGER(11) DEFAULT '0',";
            $install_sql.= "voted DATETIME DEFAULT NULL,";
            $install_sql.= "ip VARCHAR(32) DEFAULT NULL,";
            $install_sql.= "user_agent VARCHAR(255) DEFAULT NULL,";
            $install_sql.= "PRIMARY KEY (record_id));";
            $wpdb->query($install_sql);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$dbt_votes_trend'") != $dbt_votes_trend) {
            $install_sql = "CREATE TABLE $dbt_votes_trend (";
            $install_sql.= "id INTEGER(11) DEFAULT 0,";
            $install_sql.= "vote_type VARCHAR(10) DEFAULT 'article',";
            $install_sql.= "user_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "user_votes INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_voters INTEGER(11) DEFAULT 0,";
            $install_sql.= "visitor_votes INTEGER(11) DEFAULT 0,";
            $install_sql.= "vote_date VARCHAR(10) DEFAULT NULL)";
            $wpdb->query($install_sql);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$dbt_moderate'") != $dbt_moderate) {
            $install_sql = "CREATE TABLE $dbt_moderate (";
            $install_sql.= "record_id INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT,";
            $install_sql.= "id INTEGER(11) DEFAULT NULL,";
            $install_sql.= "vote_type VARCHAR(10) DEFAULT 'article',";
            $install_sql.= "user_id INTEGER(11) DEFAULT '0',";
            $install_sql.= "vote INTEGER(11) DEFAULT '0',";
            $install_sql.= "voted DATETIME DEFAULT NULL,";
            $install_sql.= "ip VARCHAR(32) DEFAULT NULL,";
            $install_sql.= "user_agent VARCHAR(255) DEFAULT NULL,";
            $install_sql.= "PRIMARY KEY (record_id));";
            $wpdb->query($install_sql);
        }
    }
    
    function convert_row($row) {
        switch ($row->moderate_articles) {
            case 'A':
                $row->moderate_articles = 'articles: <strong><span style="color: red">all</span></strong>';
                break;
            case 'V':
                $row->moderate_articles = 'articles: <strong><span style="color: red">visitors</span></strong>';
                break;
            case 'U':
                $row->moderate_articles = 'articles: <strong><span style="color: red">users</span></strong>';
                break;
            default:
                $row->moderate_articles = 'articles: <strong>free</strong>';
                break;
        }
        switch ($row->moderate_comments) {
            case 'A':
                $row->moderate_comments = 'comments: <strong><span style="color: red">all</span></strong>';
                break;
            case 'V':
                $row->moderate_comments = 'comments: <strong><span style="color: red">visitors</span></strong>';
                break;
            case 'U':
                $row->moderate_comments = 'comments: <strong><span style="color: red">users</span></strong>';
                break;
            default:
                $row->moderate_comments = 'comments: <strong>free</strong>';
                break;
        }
        switch ($row->rules_articles) {
            case 'H':
                $row->rules_articles = 'articles: <strong><span style="color: red">hidden</span></strong>';
                break;
            case 'N':
                $row->rules_articles = 'articles: <strong><span style="color: red">locked</span></strong>';
                break;
            case 'V':
                $row->rules_articles = 'articles: <strong>visitors</strong>';
                break;
            case 'U':
                $row->rules_articles = 'articles: <strong>users</strong>';
                break;
            default:
                $row->rules_articles = 'articles: <strong>everyone</strong>';
                break;
        }
        switch ($row->rules_comments) {
            case 'H':
                $row->rules_comments = 'articles: <strong><span style="color: red">hidden</span></strong>';
                break;
            case 'N':
                $row->rules_comments = 'comments: <strong><span style="color: red">locked</span></strong>';
                break;
            case 'V':
                $row->rules_comments = 'comments: <strong>visitors</strong>';
                break;
            case 'U':
                $row->rules_comments = 'comments: <strong>users</strong>';
                break;
            default:
                $row->rules_comments = 'comments: <strong>everyone</strong>';
                break;
        }
        
        if ($row->visitor_voters == 0) {
            $votes_v = '/';
            $count_v = '[ 0 ] ';
        }
        else {
            $visitor_rating =  @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=article&vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->visitor_voters);
        }
        
        if ($row->user_voters == 0) {
            $votes_u = '/';
            $count_u = '[ 0 ] ';
        }
        else {
            $user_rating =  @number_format($row->user_votes / $row->user_voters, 1);
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=article&vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->user_voters);
        }
        
        if ($row->review == -1 || $row->review == '') $row->review = "/";
        $row->review = '<strong><span style="color: blue">'.$row->review.'</span></strong>';
        
        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;
        
        if ($total_voters == 0) {
            $votes_t = '/';
            $count_t = '[ 0 ] ';
        }
        else {
            $total_rating =  @number_format($total_votes / $total_voters, 1);
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=article&vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $total_voters);
        }
        
        $row->total = $count_t.__("votes", "gd-star-rating").': <strong>'.$votes_t.'</strong><br />[ '.$row->views.' ] '.__("views", "gd-star-rating");
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';
        
        $row->title = sprintf('<a href="./post.php?action=edit&post=%s">%s</a>', $row->pid, $row->post_title);
        
        return $row;
    }
    
    function convert_moderation_row($row) {
        if ($row->user_id == 0)
            $row->username = '<span style="color: red">visitor</span>';
        else
            $row->username = sprintf('<a href="./user-edit.php?user_id=%s">%s</a>', $row->user_id, $row->username);
        
        return $row;
    }
    
    function convert_comment_row($row) {
        if ($row->visitor_voters == 0) {
            $votes_v = '/';
            $count_v = '[ 0 ] ';
        }
        else {
            $visitor_rating =  @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=comment&vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->visitor_voters);
        }
        
        if ($row->user_voters == 0) {
            $votes_u = '/';
            $count_u = '[ 0 ] ';
        }
        else {
            $user_rating =  @number_format($row->user_votes / $row->user_voters, 1);
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=comment&vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->user_voters);
        }

        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;
        
        if ($total_voters == 0) {
            $votes_t = '/';
            $count_t = '[ 0 ] ';
        }
        else {
            $total_rating =  @number_format($total_votes / $total_voters, 1);
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=log&pid=%s&vt=comment&vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $total_voters);
        }

        $row->total = $count_t.__("votes", "gd-star-rating").': <strong>'.$votes_t.'</strong>';
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';

        if ($row->review == -1) $row->review = "/";
        $row->review = '<strong><span style="color: blue">'.$row->review.'</span></strong>';
        
        return $row;
    }
    
    function moderation_approve($ids, $ids_array) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("select * from %s where record_id in %s", $table_prefix."gdsr_moderate", $ids);
        $rows = $wpdb->get_results($sql);
        foreach ($rows as $row) {
            if ($row->vote_type == "article")
                GDSRDatabase::add_vote($row->id, $row->user_id, $row->ip, $row->user_agent, $row->vote);
            if ($row->vote_type == "comment")
                GDSRDatabase::add_vote_comment($row->id, $row->user_id, $row->ip, $row->user_agent, $row->vote);
        }
        
        GDSRDB::moderation_delete($ids);
    }

    function moderation_delete($ids) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("delete from %s where record_id in %s", $table_prefix."gdsr_moderate", $ids);
        $wpdb->query($sql);
    }
    
    function get_post_title($post_id) {
        global $wpdb;
        return $wpdb->get_var("select post_title from $wpdb->posts where ID = ".$post_id);
    }
    
    // totals
    function front_page_article_totals() {
        global $wpdb, $table_prefix;
        return $wpdb->get_row(sprintf("select sum(visitor_voters) as votersv, sum(visitor_votes) as votesv, sum(user_voters) as votersu, sum(user_votes) as votesu from %s", $table_prefix."gdsr_data_article"));
    }
    
    function front_page_comment_totals() {
        global $wpdb, $table_prefix;
        return $wpdb->get_row(sprintf("select sum(visitor_voters) as votersv, sum(visitor_votes) as votesv, sum(user_voters) as votersu, sum(user_votes) as votesu from %s", $table_prefix."gdsr_data_comment"));
    }
    
    function front_page_moderation_totals() {
        global $wpdb, $table_prefix;
        return $wpdb->get_row(sprintf("select vote_type, count(*) as queue from %s group by vote_type", $table_prefix."gdsr_moderate"));
    }
    // totals
    
    // recalculate
    function recalculate_articles($gdsr_oldstars, $gdsr_newstars) {
        global $wpdb, $table_prefix;
        $rate = $gdsr_newstars / $gdsr_oldstars;
        $sql = "UPDATE ".$table_prefix."gdsr_data_article SET user_votes = user_votes * ".$rate.", visitor_votes = visitor_votes * ".$rate;
        $wpdb->query($sql);
    }

    function recalculate_comments($gdsr_oldstars, $gdsr_newstars) {
        global $wpdb, $table_prefix;
        $rate = $gdsr_newstars / $gdsr_oldstars;
        $sql = "UPDATE ".$table_prefix."gdsr_data_comment SET user_votes = user_votes * ".$rate.", visitor_votes = visitor_votes * ".$rate;
        $wpdb->query($sql);
    }

    function recalculate_reviews($gdsr_oldstars, $gdsr_newstars) {
        global $wpdb, $table_prefix;
        $rate = $gdsr_newstars / $gdsr_oldstars;
        $sql = "UPDATE ".$table_prefix."gdsr_data_article SET review = review * ".$rate." where review > -1";
        $wpdb->query($sql);
    }

    function recalculate_comments_reviews($gdsr_oldstars, $gdsr_newstars) {
        global $wpdb, $table_prefix;
        $rate = $gdsr_newstars / $gdsr_oldstars;
        $sql = "UPDATE ".$table_prefix."gdsr_data_comment SET review = review * ".$rate." where review > -1";
        $wpdb->query($sql);
    }
    // recalculate
}

?>