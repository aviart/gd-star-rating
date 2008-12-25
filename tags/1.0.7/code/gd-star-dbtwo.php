<?php

class GDSRDBTools {
    function clean_invalid_log_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_log l left join %sposts o on o.ID = l.id where l.vote_type = 'article' and o.ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_votes_log", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_log_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_log l left join %scomments o on o.comment_ID = l.id where l.vote_type = 'comment' and o.comment_ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_votes_log", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_trend_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_trend l left join %sposts o on o.ID = l.id where l.vote_type = 'article' and o.ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_votes_trend", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_trend_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_trend l left join %scomments o on o.comment_ID = l.id where l.vote_type = 'comment' and o.comment_ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_votes_trend", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_dead_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_data_article l left join %sposts o on o.ID = l.post_id where o.ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_data_article", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_dead_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_data_comment l left join %scomments o on o.comment_ID = l.comment_id where o.comment_ID is null",
            gd_mysql_pre_4_1() ? sprintf("%sgdsr_data_comment", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }
}

class GDSRDB {
    function get_database_tables() {
        global $table_prefix;
        $tables = array(
            "data_article" => $table_prefix.'gdsr_data_article',
            "data_comment" => $table_prefix.'gdsr_data_comment',
            "votes_log" => $table_prefix.'gdsr_votes_log',
            "votes_trend" => $table_prefix.'gdsr_votes_trend',
            "moderate" => $table_prefix.'gdsr_moderate',
            "multi_sets" => $table_prefix.'gdsr_multis',
            "banned_ips" => $table_prefix.'gdsr_ips'
        );
        return $tables;
    }
    
    function convert_row($row) {
        switch ($row->moderate_articles) {
            case 'I':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'A':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            default:
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->moderate_comments) {
            case 'I':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'A':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            default:
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_articles) {
            case 'I':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'H':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_comments) {
            case 'I':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'H':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        
        if ($row->visitor_voters == 0) {
            $votes_v = '/';
            $count_v = '[ 0 ] ';
        }
        else {
            $visitor_rating = @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $row->rating_visitors = $visitor_rating;
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=article&vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->visitor_voters);
        }
        
        if ($row->user_voters == 0) {
            $votes_u = '/';
            $count_u = '[ 0 ] ';
        }
        else {
            $user_rating = @number_format($row->user_votes / $row->user_voters, 1);
            $row->rating_users = $user_rating;
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=article&vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->user_voters);
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
            $row->rating_total = $total_rating;
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=article&vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $total_voters);
        }
        
        $row->total = $count_t.__("rating", "gd-star-rating").': <strong>'.$votes_t.'</strong><br />[ '.$row->views.' ] '.__("views", "gd-star-rating");
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';
        
        $row->title = sprintf('<a href="./post.php?action=edit&post=%s">%s</a>', $row->pid, $row->post_title);
        
        return $row;
    }

    function convert_category_row($row) {
        switch ($row->moderate_articles) {
            case 'A':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            default:
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->moderate_comments) {
            case 'A':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            default:
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_articles) {
            case 'H':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_comments) {
            case 'H':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->rules_comments = __("comments", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
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
            $visitor_rating = @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $row->rating_visitors = $visitor_rating;
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=comment&vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->visitor_voters);
        }
        
        if ($row->user_voters == 0) {
            $votes_u = '/';
            $count_u = '[ 0 ] ';
        }
        else {
            $user_rating = @number_format($row->user_votes / $row->user_voters, 1);
            $row->rating_users = $user_rating;
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=comment&vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->user_voters);
        }

        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;
        
        if ($total_voters == 0) {
            $votes_t = '/';
            $count_t = '[ 0 ] ';
        }
        else {
            $total_rating = @number_format($total_votes / $total_voters, 1);
            $row->rating_total = $total_rating;
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&gdsr=voters&pid=%s&vt=comment&vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $total_voters);
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