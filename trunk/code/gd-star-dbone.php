<?php

class GDSRDatabase
{
    function dump($msg, $object, $mode = "a+") {
        $obj = print_r($object, true);
        $f = fopen("c:/db_one.txt", $mode);
        fwrite ($f, sprintf("[%s] : %s\r\n", current_time('mysql'), $msg));
        fwrite ($f, "$obj");
        fwrite ($f, "\r\n");
        fclose($f);
    }

    function truncate_table($table_name) {
        global $wpdb, $table_prefix;
        $sql = sprintf("TRUNCATE TABLE %s%s", $table_prefix, $table_name);
        $wpdb->query($sql);
    }

    function table_exists($table_name) {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("SHOW TABLES LIKE '%s%s'", $table_prefix, $table_name)) == $table_prefix.$table_name;
    }
    
    // check vote
    function check_vote_table($table, $id, $user, $type, $ip) {
        global $wpdb, $table_prefix;
        $stats = $table_prefix.$table;
        
        if ($user > 0)
            $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and id = %s and user_id = %s", 
                $stats, 
                $type, 
                $id, 
                $user
            );
        else
            $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and id = %s and ip = '%s'", 
                $stats, 
                $type, 
                $id, 
                $ip
            );
        
        $vote_data = $wpdb->get_row($votes_sql); 
        
        if (count($vote_data) == 0) 
            return true;
        else 
            return false;
    }
    
    function check_vote($id, $user, $type, $ip) {
        $result = GDSRDatabase::check_vote_logged($id, $user, $type, $ip);
        if ($result) 
            $result = GDSRDatabase::check_vote_moderated($id, $user, $type, $ip);
        return $result;
    }
    
    function check_vote_logged($id, $user, $type, $ip) {
        return GDSRDatabase::check_vote_table('gdsr_votes_log', $id, $user, $type, $ip);
    }
    
    function check_vote_moderated($id, $user, $type, $ip) {
        return GDSRDatabase::check_vote_table('gdsr_moderate', $id, $user, $type, $ip);
    }
    // check vote
    
    // save & update
    function delete_votes($ids, $delete, $ids_array) {
        global $wpdb, $table_prefix;
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';
        $dbt_votes_log = $table_prefix.'gdsr_votes_log';

        if ($delete == "") return;

        switch (substr($delete, 1, 1)) {
            case "A":
                $delstring = "user_votes = 0, user_voters = 0, visitor_votes = 0, visitor_voters = 0";
                $dellog = "";
                break;
            case "V":
                $delstring = "visitor_votes = 0, visitor_voters = 0";
                $dellog = " and user_id = 0";
                break;
            case "U":
                $delstring = "user_votes = 0, user_voters = 0";
                $dellog = " and user_id > 0";
                break;
            default:
                return;
                break;
        }

        if (substr($delete, 0, 1) == "A") {
            $wpdb->query(sprintf("update %s set %s where post_id in %s", $dbt_data_article, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type = 'article' and id in %s%s", $dbt_votes_log, $ids, $dellog));
        }
        else if (substr($delete, 0, 1) == "K") {
            $wpdb->query(sprintf("update %s set %s where comment_id in %s", $dbt_data_comment, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type = 'comment' and id in %s%s", $dbt_votes_log, $ids, $dellog));
        }
        else if (substr($delete, 0, 1) == "C") {
            $cids = GDSRDatabase::get_commentids_posts($ids);
            $cm = array();
            foreach ($cids as $cid) 
                $cm[] = $cid->comment_id;
            $cms = "(".join(", ", $cm).")";
            $wpdb->query(sprintf("update %s set %s where post_id in %s", $dbt_data_comment, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type = 'comment' and id in %s%s", $dbt_votes_log, $cms, $dellog));
        }
        else {
            return;
        }
    }
    
    function update_settings($ids, $upd_am, $upd_ar, $upd_cm, $upd_cr, $ids_array) {
        global $wpdb, $table_prefix;
        GDSRDatabase::add_defaults($ids, $ids_array);
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        
        $update = array();
        if ($upd_am != '') $update[] = "moderate_articles = '".$upd_am."'";
        if ($upd_cm != '') $update[] = "moderate_comments = '".$upd_cm."'";
        if ($upd_ar != '') $update[] = "rules_articles = '".$upd_ar."'";
        if ($upd_cr != '') $update[] = "rules_comments = '".$upd_cr."'";
        if (count($update) > 0) {
            $updstring = join(", ", $update);
            $wpdb->query(sprintf("update %s set %s where post_id in %s", $dbt_data_article, $updstring, $ids));
        }
    }
    
    function update_restrictions($ids, $timer_type, $timer_value) {
        global $wpdb, $table_prefix;
        $wpdb->query(sprintf("update %sgdsr_data_article set expiry_type = '%s', expiry_value = '%s' where post_id in %s", 
            $table_prefix, $timer_type, $timer_value, $ids));
    }
    
    function lock_post($post_id, $rules_articles = "N") {
        global $wpdb, $table_prefix;
        
        $wpdb->query(sprintf("update %sgdsr_data_article set rules_articles = '%s' where post_id = %s",
            $table_prefix, $rules_articles, $post_id));
    }
    
    function update_reviews($ids, $review, $ids_array) {
        global $wpdb, $table_prefix;
        GDSRDatabase::add_defaults($ids, $ids_array);
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        
        $wpdb->query(sprintf("update %s set review = %s where post_id in %s", $dbt_data_article, $review, $ids));
    }
    
    function add_defaults($ids, $ids_array) {
        global $wpdb, $table_prefix;
        $rows = $wpdb->get_results(sprintf("select post_id from %sgdsr_data_article where post_id in %s", $table_prefix, $ids), ARRAY_N);
        if (count($rows) == 0) $rows = array();
        foreach ($ids_array as $id)
            if (!in_array($id, $rows)) GDSRDatabase::add_default_vote($id);
    }
    
    function save_vote($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $moderate = $table_prefix.'gdsr_moderate';
        
        $post_data = $wpdb->get_row(
            sprintf("SELECT * FROM %s WHERE post_id = %s", $articles, $id)
        );
        
        if ($post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDatabase::add_vote($id, $user, $ip, $ua, $vote, $post_data);
        }
        else {
            $modsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'article', %s, %s, '%s', '%s', '%s')",
                $moderate, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }
    
    function save_vote_comment($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $comments = $table_prefix.'gdsr_data_comment';
        $moderate = $table_prefix.'gdsr_moderate';

        $post = $wpdb->get_row("select comment_post_ID from $wpdb->comments where comment_ID = ".$id);
        $post_id = $post->comment_post_ID;

        $post_data = $wpdb->get_row(
            sprintf("SELECT * FROM %s WHERE post_id = %s", 
            $articles, $post_id)
        );

        if ($post_data->moderate_comments == "N" || ($post_data->moderate_comments == "V" && $user > 0) || ($post_data->moderate_comments == "U" && $user == 0)) {
            GDSRDatabase::add_vote_comment($id, $user, $ip, $ua, $vote);
        }
        else {
            $modsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'comment', %s, %s, '%s', '%s', '%s')",
                $moderate, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }
    
    function save_comment_review($comment_id, $rating, $post_id) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
    }
    
    function save_review($post_id, $rating, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) 
            GDSRDatabase::add_default_vote($post_id, '', $rating);
        else 
            $wpdb->query("update ".$articles." set review = ".$rating." where post_id = ".$post_id);
    }
    
    function save_article_rules($post_id, $article_vote, $article_moderation, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) 
            GDSRDatabase::add_default_vote($post_id);
        $wpdb->query("update ".$articles." set rules_articles = '".$article_vote."', moderate_articles = '".$article_moderation."' where post_id = ".$post_id);
    }
    
    function save_timer_rules($post_id, $timer_type, $timer_value, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) 
            GDSRDatabase::add_default_vote($post_id);
        $wpdb->query("update ".$articles." set expiry_type = '".$timer_type."', expiry_value = '".$timer_value."' where post_id = ".$post_id);
    }
    
    function check_post($post_id) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $sql = "select review from ".$articles." WHERE post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        return count($results) > 0;
    }
    
    function add_vote_comment($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
        $stats = $table_prefix.'gdsr_votes_log';
        $trend = $table_prefix.'gdsr_votes_trend';
        $moderate = $table_prefix.'gdsr_moderate';
        
        $trend_date = date("Y-m-d");
        $comment_data = $wpdb->get_row(sprintf("SELECT * FROM %s WHERE comment_id = %s", $comments, $id));
        
        $sql_trend = sprintf("SELECT * FROM %s WHERE vote_date = '%s' and vote_type = 'comment' and id = %s", $trend, $trend_date, $id);
        $trend_data = $wpdb->get_row($sql_trend);
        
        if (count($trend_data) == 0) {
            $wpdb->query(sprintf("INSERT INTO %s (id, vote_type, user_voters, user_votes, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'comment', 0, 0, 0, 0, '%s')", $trend, $id, $trend_date));
            $trend_data = $wpdb->get_row($sql_trend);
        }

        if ($user > 0) {
            $comment_data->user_voters+= 1;
            $comment_data->user_votes+= $vote;
            $trend_data->user_voters+= 1;
            $trend_data->user_votes+= $vote;
            $wpdb->query(
                sprintf("UPDATE %s SET user_voters = %s, user_votes = %s WHERE comment_id = %s",
                $comments, $comment_data->user_voters, $comment_data->user_votes, $id)
            );
            $wpdb->query(
                sprintf("UPDATE %s SET user_voters = %s, user_votes = %s WHERE id = %s and vote_type = 'comment' and vote_date = '%s'",
                $trend, $trend_data->user_voters, $trend_data->user_votes, $id, $trend_date)
            );
        }
        else {
            $comment_data->visitor_voters+= 1;
            $comment_data->visitor_votes+= $vote;
            $trend_data->visitor_voters+= 1;
            $trend_data->visitor_votes+= $vote;
            $wpdb->query(
                sprintf("UPDATE %s SET visitor_voters = %s, visitor_votes = %s WHERE comment_id = %s",
                $comments, $comment_data->visitor_voters, $comment_data->visitor_votes, $id)
            );
            $wpdb->query(
                sprintf("UPDATE %s SET visitor_voters = %s, visitor_votes = %s WHERE id = %s and vote_type = 'comment' and vote_date = '%s'",
                $trend, $trend_data->visitor_voters, $trend_data->visitor_votes, $id, $trend_date)
            );
        }

        $logsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'comment', %s, %s, '%s', '%s', '%s')",
            $stats, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
        $wpdb->query($logsql);
    }
    
    function add_vote($id, $user, $ip, $ua, $vote, $post_data = '') {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $stats = $table_prefix.'gdsr_votes_log';
        $trend = $table_prefix.'gdsr_votes_trend';
        $moderate = $table_prefix.'gdsr_moderate';

        $trend_date = date("Y-m-d");
        if ($post_data == '') {
            $post_data = $wpdb->get_row(
                sprintf("SELECT * FROM %s WHERE post_id = %s", 
                $articles, $id)
            );
        }

        $sql_trend = sprintf("SELECT * FROM %s WHERE vote_date = '%s' and vote_type = 'article' and id = %s", $trend, $trend_date, $id);
        $trend_data = $wpdb->get_row($sql_trend);
        
        if (count($trend_data) == 0) {
            $wpdb->query(sprintf("INSERT INTO %s (id, vote_type, user_voters, user_votes, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'article', 0, 0, 0, 0, '%s')", $trend, $id, $trend_date));
            $trend_data = $wpdb->get_row($sql_trend);
        }
        
        if ($user > 0) {
            $post_data->user_voters+= 1;
            $post_data->user_votes+= $vote;
            $trend_data->user_voters+= 1;
            $trend_data->user_votes+= $vote;
            $wpdb->query(
                sprintf("UPDATE %s SET user_voters = %s, user_votes = %s WHERE post_id = %s",
                $articles, $post_data->user_voters, $post_data->user_votes, $id)
            );
            $wpdb->query(
                sprintf("UPDATE %s SET user_voters = %s, user_votes = %s WHERE id = %s and vote_type = 'article' and vote_date = '%s'",
                $trend, $trend_data->user_voters, $trend_data->user_votes, $id, $trend_date)
            );
        }
        else {
            $post_data->visitor_voters+= 1;
            $post_data->visitor_votes+= $vote;
            $trend_data->visitor_voters+= 1;
            $trend_data->visitor_votes+= $vote;
            $wpdb->query(
                sprintf("UPDATE %s SET visitor_voters = %s, visitor_votes = %s WHERE post_id = %s",
                $articles, $post_data->visitor_voters, $post_data->visitor_votes, $id)
            );
            $wpdb->query(
                sprintf("UPDATE %s SET visitor_voters = %s, visitor_votes = %s WHERE id = %s and vote_type = 'article' and vote_date = '%s'",
                $trend, $trend_data->visitor_voters, $trend_data->visitor_votes, $id, $trend_date)
            );
        }
        
        $logsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'article', %s, %s, '%s', '%s', '%s')",
            $stats, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
        $wpdb->query($logsql);
    }
    
    function add_default_vote($post_id, $is_page = '', $review = -1) {
        global $wpdb, $table_prefix;
        $options = get_option('gd-star-rating');
        if ($is_page == '')
            $is_page = GDSRDatabase::get_post_type($post_id) == 'page' ? '1' : '0';
        
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $sql = sprintf(
                "INSERT INTO %s (post_id, rules_articles, rules_comments, moderate_articles, moderate_comments, is_page, user_voters, user_votes, visitor_voters, visitor_votes, review) VALUES (%s, '%s', '%s', '%s', '%s', '%s', 0, 0, 0, 0, %s)",
                $dbt_data_article, $post_id, $options["default_voterules_articles"], $options["default_voterules_comments"], $options["default_moderation_articles"], $options["default_moderation_comments"], $is_page, $review
                );
        $wpdb->query($sql);
    }
    
    function add_empty_comment($comment_id, $post_id) {
        global $wpdb, $table_prefix;
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';
        $sql = sprintf(
                "INSERT INTO %s (comment_id, post_id, is_locked, user_voters, user_votes, visitor_voters, visitor_votes) VALUES (%s, %s, '0', '0', '0', '0', '0')",
                    $dbt_data_comment, 
                    $comment_id, 
                    $post_id
                );
        $wpdb->query($sql);
    }
    
    function add_empty_comments($post_id) {
        global $wpdb, $table_prefix;
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';

        $sql = sprintf("select c.comment_ID from %s c left join %s g on c.comment_ID = g.comment_ID where (isnull(g.post_id) or g.post_id < 1) and c.comment_approved = 1 and c.comment_type = '' and c.comment_post_id = %s",
            $wpdb->comments,
            $dbt_data_comment,
            $post_id
        );
        $cmms = $wpdb->get_results($sql);
        foreach ($cmms as $c)
            GDSRDatabase::add_empty_comment($c->comment_ID, $post_id);
    }
    
    function add_new_view($post_id) {
        global $wpdb, $table_prefix;
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $sql = sprintf("update %s set views = views + 1 where post_id = %s", $dbt_data_article, $post_id);
        $wpdb->query($sql);
    }
    // save & update

    // get
    function get_post_data($post_id) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $sql = "select * from ".$articles." WHERE post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        return $results;
    }
    
    function get_comment_data($comment_id) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
        $sql = "select * from ".$comments." WHERE comment_id = ".$comment_id;
        $results = $wpdb->get_row($sql, OBJECT);
        return $results;
    }
    
    function get_commentids_posts($post_ids) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
        return $wpdb->get_results("select comment_id from ".$comments." where post_id in ".$post_ids, OBJECT);
    }
    
    function get_comment_review($comment_id) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
        
        $sql = "select review from ".$comments." WHERE comment_id = ".$comment_id;
        $results = $wpdb->get_row($sql, OBJECT);
        if (count($results) == 0) return -1;
        else return $results->review;
    }

    function get_review($post_id) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        
        $sql = "select review from ".$articles." WHERE post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        if (count($results) == 0) return -1;
        else return $results->review;
    }

    function get_post_edit($post_id) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        
        $sql = "select review, rules_articles, moderate_articles, expiry_type, expiry_value, rules_comments, moderate_comments from ".$articles." WHERE post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        return $results;
    }
    
    function get_moderation_count($id, $vote_type = 'article', $user = 'all') {
        global $wpdb, $table_prefix;

        if ($user == "all")
            $users = '';
        else if ($user == "users")
            $users = ' and user_id > 0';
        else if ($user == "visitors")
            $users = ' and user_id = 0';
        else
            $users = ' and user_id = '.$user;
        
        $sql = sprintf("select count(*) from %s where id = %s and vote_type = '%s'%s", 
            $table_prefix."gdsr_moderate", 
            $id, 
            $vote_type, 
            $users
        );
        return $wpdb->get_var($sql);
    }

    function get_moderation_count_joined($post_id, $user = 'all') {
        global $wpdb, $table_prefix;
        
        if ($user == "all")
            $users = '';
        else if ($user == "users")
            $users = ' and m.user_id > 0';
        else if ($user == "visitors")
            $users = ' and m.user_id = 0';
        else
            $users = ' and m.user_id = '.$user;
        
        $sql = sprintf("select count(*) from %s c inner join %s m on m.id = c.comment_ID where c.comment_post_ID = %s and m.vote_type = 'comment'%s",
            $wpdb->comments,
            $table_prefix."gdsr_moderate",
            $post_id,
            $users
        );
        return $wpdb->get_var($sql);
    }

    function get_moderation($post_id, $vote_type = 'article', $start = 0, $limit = 20, $user = 'all') {
        global $wpdb, $table_prefix;

        if ($user == "all")
            $users = '';
        else if ($user == "users")
            $users = ' and user_id > 0';
        else if ($user == "visitors")
            $users = ' and m.user_id = 0';
        else
            $users = ' and m.user_id = '.$user;

        $sql = sprintf("select m.*, u.user_login as username from %s m left join wpdev_users u on u.id = m.user_id where m.id = %s and m.vote_type = '%s'%s order by m.voted desc LIMIT %s, %s",
            $table_prefix."gdsr_moderate",
            $post_id,
            $vote_type,
            $users,
            $start,
            $limit
        );
        return $sql;
    }
    
    function get_moderation_joined($post_id, $start = 0, $limit = 20, $user = 'all') {
        global $wpdb, $table_prefix;

        if ($user == "all")
            $users = '';
        else if ($user == "users")
            $users = ' and m.user_id > 0';
        else if ($user == "visitors")
            $users = ' and m.user_id = 0';
        else
            $users = ' and m.user_id = '.$user;

        $sql = sprintf("select m.*, u.user_login as username from %s c inner join %s m on m.id = c.comment_ID left join wpdev_users u on u.id = m.user_id where c.comment_post_ID = %s and m.vote_type = 'comment'%s order by m.voted desc LIMIT %s, %s",
            $wpdb->comments,
            $table_prefix."gdsr_moderate",
            $post_id,
            $users,
            $start,
            $limit
        );
        return $sql;
    }
    
    function get_comments_count($post_id) {
        global $wpdb, $table_prefix;
        
        $sql = sprintf("select count(*) from %s where comment_approved = 1 and comment_type = '' and comment_post_id = %s",
            $wpdb->comments,
            $post_id
        );
        return $wpdb->get_var($sql);
    }
    
    function get_comments($post_id) {
        global $wpdb, $table_prefix;
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';

        GDSRDatabase::add_empty_comments($post_id);
        $sql = sprintf("select g.*, c.comment_content, c.comment_author, c.comment_author_email, c.comment_author_url, c.comment_date from %s c left join %s g on c.comment_ID = g.comment_ID where c.comment_approved = 1 and c.comment_type = '' and c.comment_post_id = %s order by c.comment_date desc",
            $wpdb->comments,
            $dbt_data_comment,
            $post_id
        );
        return $sql;
    }
        
    function get_post_type($post_id) {
        global $wpdb;
        $sql = "SELECT post_type FROM $wpdb->posts WHERE ID = ".$post_id;
        $r = $wpdb->get_row($sql);
        return $r->post_type;
    }
    
    function get_categories($post_id) {
        global $wpdb;
        
        $sql = "SELECT s.name FROM $wpdb->term_taxonomy t, $wpdb->terms s, $wpdb->term_relationships r WHERE t.taxonomy = 'category' AND t.term_taxonomy_id = r.term_taxonomy_id AND t.term_id = s.term_id AND r.object_id = ".$post_id;
        $cats = $wpdb->get_results($sql);
        $output = '';
        foreach ($cats as $cat)
            $output.= $cat->name.", ";
        if ($output != '')
            $output = substr($output, 0, strlen($output) - 2);
        else $output = '/';
        
        return $output;
    }

    function get_voters_count($post_id, $dates = "", $vote_type = "article") {
        global $table_prefix;
        $where = " where vote_type = '".$vote_type."'";
        $where.= " and id = ".$post_id;
        if ($dates != "" && $dates != "0") {
            $where.= " and year(voted) = ".substr($dates, 0, 4);
            $where.= " and month(voted) = ".substr($dates, 4, 2);
        }
        
        $sql = sprintf("SELECT count(*) as count, user_id = 0 as user FROM %sgdsr_votes_log%s group by (user_id = 0)", 
            $table_prefix, $where
            );

        return $sql;
    }
    
    function get_visitors($post_id, $vote_type = "article", $dates = "", $select = "total", $start = 0, $limit = 20) {
        global $table_prefix;

        $where = " where vote_type = '".$vote_type."'";
        $where.= " and p.id = ".$post_id;
        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.voted) = ".substr($dates, 0, 4);
            $where.= " and month(p.voted) = ".substr($dates, 4, 2);
        }
        if ($select == "users")
            $where.= " and user_id > 0";
        if ($select == "visitors")
            $where.= " and user_id = 0";
        
        $sql = sprintf("SELECT p.*, u.user_nicename FROM %sgdsr_votes_log p LEFT JOIN %susers u ON u.ID = p.user_id%s LIMIT %s, %s",
            $table_prefix, $table_prefix, $where, $start, $limit
            );
        
        return $sql;
    }
    
    function get_stats_count($dates = "0", $cats = "0", $search = "") {
        global $table_prefix;
        $where = "";
        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.post_date) = ".substr($dates, 0, 4);
            $where.= " and month(p.post_date) = ".substr($dates, 4, 2);
        }
        if ($search != "")
            $where.= " and p.post_title like '%".$search."%'";
        
        if ($cats != "" && $cats != "0")
            $sql = sprintf("SELECT p.post_type, count(*) as count FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p WHERE t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.ID AND t.term_id = %s AND p.post_status = 'publish'%s GROUP BY p.post_type",
                $table_prefix, $table_prefix, $table_prefix, $cats, $where
            );
        else
            $sql = sprintf("select p.post_type, count(*) as count from %sposts p where p.post_status = 'publish'%s group by post_type",
                $table_prefix, $where
            );
        return $sql;
    }
    
    function get_stats($select = "", $start = 0, $limit = 20, $dates = "0", $cats = "0", $search = "", $sort_column = 'id', $sort_order = 'desc', $additional = '') {
        global $table_prefix;
        $where = "";
        
        $extras = ", '' as total, '' as votes, '' as title";

        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.post_date) = ".substr($dates, 0, 4);
            $where.= " and month(p.post_date) = ".substr($dates, 4, 2);
        }
        if ($search != "")
            $where.= " and p.post_title like '%".$search."%'";

        if ($select != "" && $select != "postpage") 
            $where.= " and post_type = '".$select."'";
            
        if ($sort_column == 'post_title' || $sort_column == 'id')
            $order = " ORDER BY p.".$sort_column." ".$sort_order;
        else
            $order = " ORDER BY ".$sort_column." ".$sort_order;

        if ($cats != "" && $cats != "0")
            $sql = sprintf("SELECT p.id as pid, p.post_title, p.post_type, d.*%s FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p, %sgdsr_data_article d WHERE d.post_id = p.id and t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.id AND t.term_id = %s AND p.post_status = 'publish'%s%s%s LIMIT %s, %s",
                $extras, $table_prefix, $table_prefix, $table_prefix, $table_prefix, $cats, $where, $additional, $order, $start, $limit
            );
        else
            $sql = sprintf("select p.id as pid, p.post_title, p.post_type, d.*%s from %sposts p left join %sgdsr_data_article d on p.id = d.post_id WHERE p.post_status = 'publish'%s%s%s limit %s, %s",
                $extras, $table_prefix, $table_prefix, $where, $additional, $order, $start, $limit
            );
        return $sql;
    }
    
    function get_combo_months($selected = "0", $name = "gdsr_dates") {
        global $wpdb, $wp_locale;
        $arc_query = "SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = 'post' ORDER BY post_date DESC";
        $arc_result = $wpdb->get_results($arc_query);
        $month_count = count($arc_result);
        if ($month_count && !(1 == $month_count && 0 == $arc_result[0]->mmonth)) { ?>
        <select name="<?php echo $name; ?>">
        <option <?php if ($selected == "0") echo ' selected="selected"'; ?> value='0'><?php _e("Show all dates", "gd-star-rating"); ?></option>
        <?php
        foreach ($arc_result as $arc_row) {
            if ($arc_row->yyear == 0)
                continue;
            $arc_row->mmonth = zeroise( $arc_row->mmonth, 2 );

            if ($arc_row->yyear.$arc_row->mmonth == $selected)
                $default = ' selected="selected"';
            else
                $default = '';

            echo "<option$default value='$arc_row->yyear$arc_row->mmonth'>";
            echo $wp_locale->get_month($arc_row->mmonth)." $arc_row->yyear";
            echo "</option>\n";
        }
        ?>
        </select>
        <?php
        }
    }

    function get_combo_users($selected = "all") {
        global $wpdb;
        $arc_query = "SELECT ID, user_login from $wpdb->users";
        $arc_result = $wpdb->get_results($arc_query);
        ?>        
        <select name='gdsr_users'>
            <option <?php if ($selected == "all") echo ' selected="selected"'; ?> value='all'><?php _e("All users and visitors", "gd-star-rating"); ?></option>
            <option <?php if ($selected == "visitors") echo ' selected="selected"'; ?> value='visitors'><?php _e("Visitors Only", "gd-star-rating"); ?></option>
            <option <?php if ($selected == "users") echo ' selected="selected"'; ?> value='users'><?php _e("All Users", "gd-star-rating"); ?></option>
            <option>---------------</option>
        <?php
        foreach ($arc_result as $arc_row) {
            if ($selected == $arc_row->ID)
                $default = ' selected="selected"';
            else
                $default = '';
            echo sprintf('<option%s value="%s">%s</option>', $default, $arc_row->ID, $arc_row->user_login);
        }
        ?>
        </select>
        <?php
    }
    
    function get_combo_categories($selected = '', $name = 'gdsr_categories') {
        $dropdown_options = array('show_option_all' => __("All categories", "gd-star-rating"), 'hide_empty' => 0, 'hierarchical' => 1,
            'show_count' => 0, 'orderby' => 'ID', 'selected' => $selected, 'name' => $name);
        wp_dropdown_categories($dropdown_options);
        do_action('restrict_manage_posts');
    }
    // get
}

?>