<?php

class GDSRDatabase {
    function truncate_table($table_name) {
        global $wpdb, $table_prefix;
        $sql = sprintf("TRUNCATE TABLE %s%s", $table_prefix, $table_name);
        $wpdb->query($sql);
    }

    function table_exists($table_name) {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("SHOW TABLES LIKE '%s%s'", $table_prefix, $table_name)) == $table_prefix.$table_name;
    }

    // save thumb votes
    function save_vote_comment_thumb($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        $post = $wpdb->get_row("select comment_post_ID from $wpdb->comments where comment_ID = ".$id);
        $post_id = $post->comment_post_ID;
        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id = %s", $table_prefix, $post_id);
        $post_data = $wpdb->get_row($sql);

        if ($post_data->moderate_comments == "" || $post_data->moderate_comments == "N" || ($post_data->moderate_comments == "V" && $user > 0) || ($post_data->moderate_comments == "U" && $user == 0)) {
            GDSRDatabase::add_vote_comment_thumb($id, $user, $ip, $ua, $vote);
        } else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'cmmthumb', %s, %s, '%s', '%s', '%s')",
                $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }

    function save_vote_thumb($id, $user, $ip, $ua, $vote, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id = %s", $table_prefix, $id);
        $post_data = $wpdb->get_row($sql);
        if (count($post_data) == 0) {
            GDSRDatabase::add_default_vote($id);
            $post_data = $wpdb->get_row($sql);
        }

        if ($post_data->moderate_articles == "" || $post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDatabase::add_vote_thumb($id, $user, $ip, $ua, $vote, $comment_id);
        } else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, user_id, vote, voted, ip, user_agent, comment_id) VALUES (%s, 'artthumb', %s, %s, '%s', '%s', '%s', %s)",
                $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua, $comment_id);
            $wpdb->query($modsql);
        }
    }

    function add_vote_comment_thumb($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $trend_date = date("Y-m-d");
        $sql_trend = sprintf("SELECT count(*) FROM %sgdsr_votes_trend WHERE vote_date = '%s' and vote_type = 'cmmthumb' and id = %s", $table_prefix, $trend_date, $id);
        $trend_data = $wpdb->get_var($sql_trend);

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            if ($user > 0) {
                $sql = sprintf("INSERT INTO %sgdsr_votes_trend (id, vote_type, user_voters, user_votes, vote_date) VALUES (%s, 'cmmthumb', 1, %s, '%s')",
                    $table_prefix, $id, $vote, $trend_date);
                $wpdb->query($sql);
            } else {
                $sql = sprintf("INSERT INTO %sgdsr_votes_trend (id, vote_type, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'cmmthumb', 1, %s, '%s')",
                    $table_prefix, $id, $vote, $trend_date);
                $wpdb->query($sql);
            }
        }

        if ($user > 0) {
            $part = $vote == 1 ? "user_recc_plus = user_recc_plus + 1" : "user_recc_minus = user_recc_minus + 1";

            if (!$trend_added) {
                $sql = sprintf("UPDATE %sgdsr_votes_trend SET user_voters = user_voters + 1, user_votes = user_votes + %s WHERE id = %s and vote_type = 'cmmthumb' and vote_date = '%s'",
                    $table_prefix, $vote, $id, $trend_date);
                $wpdb->query($sql);
            }
        } else {
            $part = $vote == 1 ? "visitor_recc_plus = visitor_recc_plus + 1" : "visitor_recc_minus = visitor_recc_minus + 1";

            if (!$trend_added) {
                $sql = sprintf("UPDATE %sgdsr_votes_trend SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s WHERE id = %s and vote_type = 'cmmthumb' and vote_date = '%s'",
                    $table_prefix, $vote, $id, $trend_date);
                $wpdb->query($sql);
            }
        }

        $sql = sprintf("UPDATE %sgdsr_data_comment SET %s, last_voted_recc = CURRENT_TIMESTAMP WHERE comment_id = %s",
            $table_prefix, $part, $id);
        $wpdb->query($sql);

wp_gdsr_dump("SAVE_THUMB_VOTE", $sql);

        $logsql = sprintf("INSERT INTO %sgdsr_votes_log (id, vote_type, user_id, vote, object, voted, ip, user_agent) VALUES (%s, 'cmmthumb', %s, %s, '', '%s', '%s', '%s')",
            $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
        $wpdb->query($logsql);

wp_gdsr_dump("SAVE_THUMB_LOG", $logsql);

    }

    function add_vote_thumb($id, $user, $ip, $ua, $vote, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $trend_date = date("Y-m-d");
        $sql_trend = sprintf("SELECT count(*) FROM %sgdsr_votes_trend WHERE vote_date = '%s' and vote_type = 'artthumb' and id = %s", $table_prefix, $trend_date, $id);
        $trend_data = $wpdb->get_var($sql_trend);

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            if ($user > 0) {
                $sql = sprintf("INSERT INTO %sgdsr_votes_trend (id, vote_type, user_voters, user_votes, vote_date) VALUES (%s, 'artthumb', 1, %s, '%s')",
                        $table_prefix, $id, $vote, $trend_date);
                $wpdb->query($sql);
            } else {
                $sql = sprintf("INSERT INTO %sgdsr_votes_trend (id, vote_type, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'artthumb', 1, %s, '%s')",
                        $table_prefix, $id, $vote, $trend_date);
                $wpdb->query($sql);
            }
        }

        if ($user > 0) {
            $part = $vote == 1 ? "user_recc_plus = user_recc_plus + 1" : "user_recc_minus = user_recc_minus + 1";

            if (!$trend_added) {
                $sql = sprintf("UPDATE %sgdsr_votes_trend SET user_voters = user_voters + 1, user_votes = user_votes + %s WHERE id = %s and vote_type = 'artthumb' and vote_date = '%s'",
                    $table_prefix, $vote, $id, $trend_date);
                $wpdb->query($sql);
            }
        } else {
            $part = $vote == 1 ? "visitor_recc_plus = visitor_recc_plus + 1" : "visitor_recc_minus = visitor_recc_minus + 1";

            if (!$trend_added) {
                $sql = sprintf("UPDATE %sgdsr_votes_trend SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s WHERE id = %s and vote_type = 'artthumb' and vote_date = '%s'",
                    $table_prefix, $vote, $id, $trend_date);
                $wpdb->query($sql);
            }
        }

        $sql = sprintf("UPDATE %sgdsr_data_article SET %s, last_voted_recc = CURRENT_TIMESTAMP WHERE post_id = %s",
            $table_prefix, $part, $id);
        $wpdb->query($sql);

wp_gdsr_dump("SAVE_THUMB_VOTE", $sql);

        $logsql = sprintf("INSERT INTO %sgdsr_votes_log (id, vote_type, user_id, vote, object, voted, ip, user_agent, comment_id) VALUES (%s, 'artthumb', %s, %s, '', '%s', '%s', '%s', %s)",
            $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua, $comment_id);
        $wpdb->query($logsql);

wp_gdsr_dump("SAVE_THUMB_LOG", $logsql);

    }
    // save thumb votes

    // check vote
    function check_vote_table($table, $id, $user, $type, $ip, $mixed = false) {
        global $wpdb, $table_prefix;

        if ($user > 0) {
            $votes_sql = sprintf("SELECT count(*) FROM %s WHERE vote_type = '%s' and id = %s and user_id = %s", $table_prefix.$table, $type, $id, $user);

wp_gdsr_dump("CHECK_VOTE_USER", $votes_sql);

            $votes = $wpdb->get_var($votes_sql);
            return $votes == 0;
        } else {
            $votes_sql = sprintf("SELECT count(*) FROM %s WHERE vote_type = '%s' and id = %s and ip = '%s'", $table_prefix.$table, $type, $id, $ip);

wp_gdsr_dump("CHECK_VOTE", $votes_sql);

            $votes = $wpdb->get_var($votes_sql);
            if ($votes > 0 && $mixed) {
                $votes_sql = sprintf("SELECT count(*) FROM %s WHERE vote_type = '%s' and user_id > 0 and id = %s and ip = '%s'", $table_prefix.$table, $type, $id, $ip);

wp_gdsr_dump("CHECK_VOTE_MIX", $votes_sql);

                $votes_mixed = $wpdb->get_var($votes_sql);
                if ($votes_mixed > 0) $votes = 0;
            }
            return $votes == 0;
        }
    }

    function check_vote($id, $user, $type, $ip, $mod_only = false, $mixed = false) {
        $result = true;

        if (!$mod_only) $result = GDSRDatabase::check_vote_logged($id, $user, $type, $ip, $mixed);
        if ($result) $result = GDSRDatabase::check_vote_moderated($id, $user, $type, $ip, $mixed);

        return $result;
    }

    function check_vote_logged($id, $user, $type, $ip, $mixed = false) {
        return GDSRDatabase::check_vote_table('gdsr_votes_log', $id, $user, $type, $ip, $mixed);
    }

    function check_vote_moderated($id, $user, $type, $ip, $mixed = false) {
        return GDSRDatabase::check_vote_table('gdsr_moderate', $id, $user, $type, $ip, $mixed);
    }
    // check vote

    //users
    function get_valid_users() {
        global $wpdb, $table_prefix;

        $sql = sprintf("SELECT l.user_id, l.vote_type, count(*) as voters, sum(l.vote) as votes, count(distinct ip) as ips, u.display_name, u.user_email FROM %sgdsr_votes_log l left join %s u on u.id = l.user_id group by user_id, vote_type order by user_id, vote_type",
                $table_prefix, $wpdb->users);
        return $wpdb->get_results($sql);
    }

    function get_valid_users_count() {
        global $wpdb, $table_prefix;

        $sql = sprintf("SELECT count(distinct user_id) from %sgdsr_votes_log", $table_prefix);
        return $wpdb->get_var($sql);
    }

    function get_user_log($user_id, $vote_type, $vote_value = 0, $start = 0, $limit = 20, $ip = "") {
        global $wpdb, $table_prefix;

        $join = $select = "";

        $vote_value = $vote_value > 0 ? ' and vote = '.$vote_value : '';
        $range = $limit > 0 ? sprintf("limit %s, %s", $start, $limit) : "";
        $ip = $ip != '' ? ' and l.ip in ('.$ip.')' : "";

        if ($vote_type == "article" || $vote_type == "artthumb") {
            $join = sprintf("%sposts o on o.ID = l.id", $table_prefix); 
            $select = "o.post_title, o.ID as post_id, o.ID as control_id";
        } else if ($vote_type == "comment" || $vote_type == "cmmthumb") {
            $join = sprintf("%scomments o on o.comment_ID = l.id left join %sposts p on p.ID = o.comment_post_ID", $table_prefix, $table_prefix); 
            $select = "o.comment_content, o.comment_author as author, o.comment_ID as control_id, p.post_title, p.ID as post_id";
        }
        $sql = sprintf("SELECT 1 as span, l.*, i.status, %s from %sgdsr_votes_log l left join %s left join %sgdsr_ips i on i.ip = l.ip where l.user_id = %s and l.vote_type = '%s'%s%s order by l.ip asc, l.voted desc %s",
                $select, $table_prefix, $join, $table_prefix, $user_id, $vote_type, $vote_value, $ip, $range);
        return $wpdb->get_results($sql);
    }

    function get_count_user_log($user_id, $vote_type, $vote_value = 0) {
        global $wpdb, $table_prefix;
        if ($vote_value > 0) $vote_value = ' and vote = '.$vote_value;
        else $vote_value = '';
        $sql = sprintf("SELECT count(*) from %sgdsr_votes_log where user_id = %s and vote_type = '%s'%s",
                $table_prefix, $user_id, $vote_type, $vote_value);
        return $wpdb->get_var($sql);
    }
    //users

    // ip
    function check_ip_single($ip) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select count(*) from %sgdsr_ips where `status` = 'B' and `mode` = 'S' and `ip` = '%s'", $table_prefix, $ip);
        return $wpdb->get_var($sql) > 0;
    }

    function check_ip_range($ip) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select count(*) from %sgdsr_ips where `status` = 'B' and `mode` = 'R' and inet_aton(substring_index(ip, '|', 1)) <= inet_aton('%s') and inet_aton(substring_index(ip, '|', -1)) >= inet_aton('%s')", $table_prefix, $ip, $ip);
        return $wpdb->get_var($sql) > 0;
    }

    function check_ip_mask($ip) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select ip from %sgdsr_ips where `status` = 'B' and `mode` = 'M'", $table_prefix);
        $ips = $wpdb->get_results($sql);
        foreach ($ips as $i) {
            $mask = explode('.', $i->ip);
            $ip = explode('.', $ip);
            for ($i = 0; $i < 4; $i++) {
                if (is_numeric($mask[$i])) {
                    if ($ip[$i] != $mask[$i]) return false;
                }
            }
            return true;
        }
        return false;
    }

    function get_all_banned_ips($start = 0, $limit = 0) {
        global $wpdb, $table_prefix;
        if ($limit > 0) $limiter = " LIMIT ".$start.", ".$limit;
        else $limiter = "";
        return $wpdb->get_results(sprintf("select * from %sgdsr_ips where status = 'B'%s", $table_prefix, $limiter));
    }

    function get_all_banned_ips_count() {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("select count(*) from %sgdsr_ips where status = 'B'", $table_prefix));
    }

    function ban_ip_check($ip, $mode = 'S') {
        global $wpdb, $table_prefix;
        $sql = sprintf("select count(*) from %sgdsr_ips where `status` = 'B' and `mode` = '%s' and `ip` = '%s'", $table_prefix, $mode, $ip);
        $result = $wpdb->get_var($sql);
        return !($result == 0);
    }

    function ban_ip($ip, $mode = 'S') {
        global $wpdb, $table_prefix;
        if ($mode == 'S') $ip = GDSRHelper::clean_ip($ip);
        if (!GDSRDatabase::ban_ip_check($ip, $mode))
            $wpdb->query(sprintf("INSERT INTO %sgdsr_ips (`status`, `mode`, `ip`) VALUES ('B', '%s', '%s')", $table_prefix, $mode, $ip));
    }

    function ban_ip_range($ip_from, $ip_to) {
        global $wpdb, $table_prefix;
        $ip = $ip_from."|".$ip_to;
        GDSRDatabase::ban_ip($ip, 'R');
    }

    function unban_ips($ips) {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete from %sgdsr_ips where id in %s", $table_prefix, $ips);
        $wpdb->query($sql);
    }
    // ip

    // categories
    function update_category_settings($ids, $ids_array, $items, $upd_am, $upd_ar, $upd_cm, $upd_cr, $upd_ms) {
        global $wpdb, $table_prefix;
        GDSRDatabase::add_category_defaults($ids, $ids_array, $items);
        $dbt_data_cats = $table_prefix.'gdsr_data_category';

        $update = array();
        if ($upd_ms != '') $update[] = "cmm_integration_set = '".$upd_ms."'";
        if ($upd_am != '') $update[] = "moderate_articles = '".$upd_am."'";
        if ($upd_cm != '') $update[] = "moderate_comments = '".$upd_cm."'";
        if ($upd_ar != '') $update[] = "rules_articles = '".$upd_ar."'";
        if ($upd_cr != '') $update[] = "rules_comments = '".$upd_cr."'";
        if (count($update) > 0) {
            $updstring = join(", ", $update);
            $sql = sprintf("update %s set %s where category_id in %s", $dbt_data_cats, $updstring, $ids);
            $wpdb->query($sql);
        }
    }

    function add_category_defaults($ids, $ids_array, $items) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select category_id from %sgdsr_data_category where category_id in %s", $table_prefix, $ids);

wp_gdsr_dump("CAT_DEFAULTS_INPUT", $ids_array);
wp_gdsr_dump("CAT_DEFAULTS_SQL", $sql);

        $cats = array();
        $rows = $wpdb->get_results($sql, ARRAY_N);
        if (is_array($rows)) foreach ($rows as $row) $cats[] = $row[0];

wp_gdsr_dump("CAT_DEFAULTS_RESULTS", $cats);

        if (is_array($cats)) {
            foreach ($ids_array as $id) {
                if (!in_array($id, $cats)) {
                    GDSRDatabase::add_category_default($id, $items[$id] > 0);
                }
            }
        }
    }

    function add_category_default($id, $parented = false) {
        global $wpdb, $table_prefix;

        $values = $parented ? "'P', 'P', 'P', 'P', 'P'" : "'A', 'A', 'N', 'N', 'N'";

        $sql = sprintf("INSERT INTO %sgdsr_data_category (category_id, rules_articles, rules_comments, moderate_articles, moderate_comments, expiry_type, expiry_value, cmm_integration_set) VALUES (%s, %s, '', 0)",
                $table_prefix, $id, $values);

wp_gdsr_dump("CAT_DEFAULTS_ADD", $cats);

        $wpdb->query($sql);
    }

    function init_categories_data() {
        $all_cats = GDSRDatabase::get_all_categories();
        $categories = GDSRHelper::get_categories_hierarchy($all_cats);

        $ids_array = $items = array();
        foreach ($categories as $cat) {
            $items[$cat->term_id] = $cat->parent;
            $ids_array[] = $cat->term_id;
        }

        GDSRDatabase::add_category_defaults("(".join(", ", $ids_array).")", $ids_array, $items);
    }

    function get_categories_data($cats = array()) {
        global $wpdb, $table_prefix;

        $where = count($cats) > 0 ? sprintf(" and x.term_id in (%s)", join(", ", $cats)) : "";
        $sql = sprintf("SELECT x.parent, d.* FROM %sterm_taxonomy x inner join %sterms t on x.term_id = t.term_id left join %sgdsr_data_category d on d.category_id = x.term_id left join %sgdsr_multis ms on ms.multi_id = d.cmm_integration_set where taxonomy = 'category'%s order by x.parent desc, d.cmm_integration_set desc, x.term_id asc",
                $table_prefix, $table_prefix, $table_prefix, $table_prefix, $where);
        return $wpdb->get_results($sql);
    }

    function get_all_categories() {
        global $wpdb, $table_prefix;

        $sql = sprintf("SELECT 0 as depth, x.term_id, t.name, t.slug, x.parent, x.count, ms.name as multi_set, d.* FROM %sterm_taxonomy x inner join %sterms t on x.term_id = t.term_id left join %sgdsr_data_category d on d.category_id = x.term_id left join %sgdsr_multis ms on ms.multi_id = d.cmm_integration_set where taxonomy = 'category' order by x.parent, t.name asc",
                $table_prefix, $table_prefix, $table_prefix, $table_prefix);
        return $wpdb->get_results($sql);
    }
    // categories

    // save & update
    function delete_votes($ids, $delete, $ids_array) {
        global $wpdb, $table_prefix;
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';
        $dbt_votes_log = $table_prefix.'gdsr_votes_log';
        if ($delete == "") return;

        $delstring = $dellog = "";
        switch (substr($delete, 1, 1)) {
            case "A":
                $delstring = "user_votes = 0, user_voters = 0, visitor_votes = 0, visitor_voters = 0";
                $delstring.= ", user_recc_plus = 0, user_recc_minus = 0, visitor_recc_plus = 0, visitor_recc_minus = 0";
                break;
            case "V":
                $delstring = "visitor_votes = 0, visitor_voters = 0";
                $delstring.= ", visitor_recc_plus = 0, visitor_recc_minus = 0";
                $dellog = " and user_id = 0";
                break;
            case "U":
                $delstring = "user_votes = 0, user_voters = 0";
                $delstring.= ", user_recc_plus = 0, user_recc_minus = 0";
                $dellog = " and user_id > 0";
                break;
            default:
                return;
                break;
        }

        if (substr($delete, 0, 1) == "A") {
            $wpdb->query(sprintf("update %s set %s where post_id in %s", $dbt_data_article, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type in ('article', 'artthumb') and id in %s%s", $dbt_votes_log, $ids, $dellog));
        } else if (substr($delete, 0, 1) == "K") {
            $wpdb->query(sprintf("update %s set %s where comment_id in %s", $dbt_data_comment, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type in ('comment', 'cmmthumb') and id in %s%s", $dbt_votes_log, $ids, $dellog));
        } else if (substr($delete, 0, 1) == "C") {
            $cids = GDSRDatabase::get_commentids_posts($ids);
            $cm = array();
            foreach ($cids as $cid) 
                $cm[] = $cid->comment_id;
            $cms = "(".join(", ", $cm).")";
            $wpdb->query(sprintf("update %s set %s where post_id in %s", $dbt_data_comment, $delstring, $ids));
            $wpdb->query(sprintf("delete from %s where vote_type in ('comment', 'cmmthumb') and id in %s%s", $dbt_votes_log, $cms, $dellog));
        } else return;
    }

    function delete_voters_log($ids) {
        global $wpdb, $table_prefix;

        $sql = sprintf("delete from %sgdsr_votes_log where record_id in %s", $table_prefix, $ids);
        $wpdb->query($sql);
    }

    function delete_voters_main_thumb($id, $value, $article = true, $user = true) {
        global $wpdb, $table_prefix;
        $update = "";

        if ($value > 0) {
            if (!$user) $update = "visitor_recc_plus = visitor_recc_plus - 1";
            else $update = "user_recc_plus = user_recc_plus - 1";
        } else {
            if (!$user) $update = "visitor_recc_minus = visitor_recc_minus - 1";
            else $update = "user_recc_minus = user_recc_minus - 1";
        }

        $sql = sprintf("update %sgdsr_data_%s set %s where %s_id = %s", $table_prefix,
                $article ? "article" : "comment", $mod, $article ? "post" : "comment", $id);
        $wpdb->query($sql);
    }

    function delete_voters_main($id, $value, $article = true, $user = true) {
        global $wpdb, $table_prefix;
        $mod = $user ? "user_voters = user_voters - 1, user_votes = user_votes - 1" :
                "visitor_voters = visitor_voters - 1, visitor_votes = visitor_votes - ".$value;

        $sql = sprintf("update %sgdsr_data_%s set %s where %s_id = %s", $table_prefix,
                $article ? "article" : "comment", $mod, $article ? "post" : "comment", $id);
        $wpdb->query($sql);
    }

    function delete_voters_full($ids, $vote_type, $thumb = false) {
        global $wpdb, $table_prefix;
        if ($vote_type == "artthumb") $vote_type = "article";
        if ($vote_type == "cmmthumb") $vote_type = "comment";
        $delfrom = $table_prefix."gdsr_data_".$vote_type;

        if ($thumb) {
            $sql = sprintf("select id, user_id, vote from %sgdsr_votes_log where record_id in %s", $table_prefix, $ids);
            $del = $wpdb->get_results($sql);

            if (count($del) > 0) {
                foreach ($del as $d) {
                    $update = "";

                    if ($d->vote > 0) {
                        if ($d->user_id == 0) $update = "visitor_recc_plus = visitor_recc_plus - 1";
                        else $update = "user_recc_plus = user_recc_plus - 1";
                    } else {
                        if ($d->user_id == 0) $update = "visitor_recc_minus = visitor_recc_minus - 1";
                        else $update = "user_recc_minus = user_recc_minus - 1";
                    }

                    $sql = sprintf("update %s set %s where post_id = %s", $delfrom, $update, $d->id);
                    $wpdb->query($sql);
                }
            }
        } else {
            $sql = sprintf("select id, user_id = 0 as user, count(*) as count, sum(vote) as votes from %sgdsr_votes_log where record_id in %s group by id, (user_id = 0)", $table_prefix, $ids);
            $del = $wpdb->get_results($sql);

            if (count($del) > 0) {
                foreach ($del as $d) {
                    if ($d->user == 0) $update = sprintf("user_voters = user_voters - %s, user_votes = user_votes - %s", $d->count, $d->votes);
                    else $update = sprintf("visitor_voters = visitor_voters - %s, visitor_votes = visitor_votes - %s", $d->count, $d->votes);

                    $sql = sprintf("update %s set %s where post_id = %s", $delfrom, $update, $d->id);
                    $wpdb->query($sql);
                }
            }
        }

        $sql = sprintf("delete from %sgdsr_votes_log where record_id in %s", $table_prefix, $ids);
        $wpdb->query($sql);
    }

    function update_settings_full($upd_am, $upd_ar, $upd_cm, $upd_cr) {
        global $wpdb, $table_prefix;
        $dbt_data_article = $table_prefix.'gdsr_data_article';

        $update = array();
        if ($upd_am != '') $update[] = "moderate_articles = '".$upd_am."'";
        if ($upd_cm != '') $update[] = "moderate_comments = '".$upd_cm."'";
        if ($upd_ar != '') $update[] = "rules_articles = '".$upd_ar."'";
        if ($upd_cr != '') $update[] = "rules_comments = '".$upd_cr."'";
        if (count($update) > 0) {
            $updstring = join(", ", $update);
            $wpdb->query(sprintf("update %s set %s", $dbt_data_article, $updstring));
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

    function lock_post_massive($date) {
        global $wpdb, $table_prefix;

        $sql = sprintf("update %sgdsr_data_article a inner join %sposts p on a.post_id = p.id set a.rules_articles = 'N', a.rules_comments = 'N' where p.post_date < '%s'",
            $table_prefix, $table_prefix, $date);
        $wpdb->query($sql);
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
        $found = array();
        foreach ($rows as $r) $found[] = $r[0];
        foreach ($ids_array as $id)
            if (!in_array($id, $found)) GDSRDatabase::add_default_vote($id);
    }

    function save_vote($id, $user, $ip, $ua, $vote, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id = %s", $table_prefix, $id);
        $post_data = $wpdb->get_row($sql);
        if (count($post_data) == 0) {
            GDSRDatabase::add_default_vote($id);
            $post_data = $wpdb->get_row($sql);
        }

        if ($post_data->moderate_articles == "" || $post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDatabase::add_vote($id, $user, $ip, $ua, $vote, $comment_id);
        } else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, user_id, vote, voted, ip, user_agent, comment_id) VALUES (%s, 'article', %s, %s, '%s', '%s', '%s', %s)",
                $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua, $comment_id);
            $wpdb->query($modsql);
        }
    }

    function save_vote_comment($id, $user, $ip, $ua, $vote) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        $post = $wpdb->get_row("select comment_post_ID from $wpdb->comments where comment_ID = ".$id);
        $post_id = $post->comment_post_ID;
        $sql = sprintf("SELECT * FROM %sgdsr_data_article WHERE post_id = %s", $table_prefix, $post_id);
        $post_data = $wpdb->get_row($sql);

        if ($post_data->moderate_comments == "" || $post_data->moderate_comments == "N" || ($post_data->moderate_comments == "V" && $user > 0) || ($post_data->moderate_comments == "U" && $user == 0)) {
            GDSRDatabase::add_vote_comment($id, $user, $ip, $ua, $vote);
        } else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'comment', %s, %s, '%s', '%s', '%s')",
                $table_prefix, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }

    function save_comment_review($comment_id, $review) {
        global $wpdb, $table_prefix;
        $comments = $table_prefix.'gdsr_data_comment';
        $sql = sprintf("update %s set review = %s where comment_id = %s", 
            $comments, $review, $comment_id);
        $wpdb->query($sql);
    }

    function save_review($post_id, $rating, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if ($rating < 0) $rating = -1;
        if (!$old) GDSRDatabase::add_default_vote($post_id, '', $rating);
        else $wpdb->query("update ".$articles." set review = ".$rating." where post_id = ".$post_id);
    }

    function save_comment_rules($post_id, $comment_vote, $comment_moderation, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) GDSRDatabase::add_default_vote($post_id);
        $wpdb->query("update ".$articles." set rules_comments = '".$comment_vote."', moderate_comments = '".$comment_moderation."' where post_id = ".$post_id);
    }

    function save_article_rules($post_id, $article_vote, $article_moderation, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) GDSRDatabase::add_default_vote($post_id);
        $wpdb->query("update ".$articles." set rules_articles = '".$article_vote."', moderate_articles = '".$article_moderation."' where post_id = ".$post_id);
    }

    function save_timer_rules($post_id, $timer_type, $timer_value, $old = true) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        if (!$old) GDSRDatabase::add_default_vote($post_id);
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

        $trend_date = date("Y-m-d");

        $sql_trend = sprintf("SELECT count(*) FROM %s WHERE vote_date = '%s' and vote_type = 'comment' and id = %s", $trend, $trend_date, $id);
        $trend_data = $wpdb->get_var($sql_trend);

wp_gdsr_dump("SAVEVOTE_CMM_trend_check_sql", $sql_trend);
wp_gdsr_dump("SAVEVOTE_CMM_trend_check_error", $wpdb->last_error);

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            if ($user > 0) {
                $sql = sprintf("INSERT INTO %s (id, vote_type, user_voters, user_votes, vote_date) VALUES (%s, 'comment', 1, %s, '%s')",
                    $trend, $id, $vote, $trend_date);
                $wpdb->query($sql);
            } else {
                $sql = sprintf("INSERT INTO %s (id, vote_type, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'comment', 1, %s, '%s')",
                    $trend, $id, $vote, $trend_date);
                $wpdb->query($sql);
            }

wp_gdsr_dump("SAVEVOTE_CMM_trend_insert_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_trend_insert_error", $wpdb->last_error);

        }

        if ($user > 0) {
            $sql = sprintf("UPDATE %s SET user_voters = user_voters + 1, user_votes = user_votes + %s, last_voted = CURRENT_TIMESTAMP WHERE comment_id = %s",
                $comments, $vote, $id);
            $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_CMM_trend_update_user_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_trend_update_user_error", $wpdb->last_error);

            if (!$trend_added) {
                $sql = sprintf("UPDATE %s SET user_voters = user_voters + 1, user_votes = user_votes + %s WHERE id = %s and vote_type = 'comment' and vote_date = '%s'",
                    $trend, $vote, $id, $trend_date);
                $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_CMM_trend_update_user_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_trend_update_user_error", $wpdb->last_error);

            }
        } else {
            $sql = sprintf("UPDATE %s SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s, last_voted = CURRENT_TIMESTAMP WHERE comment_id = %s",
                $comments, $vote, $id);
            $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_CMM_trend_update_visitor_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_trend_update_visitor_error", $wpdb->last_error);

            if (!$trend_added) {
                $sql = sprintf("UPDATE %s SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s WHERE id = %s and vote_type = 'comment' and vote_date = '%s'",
                    $trend, $vote, $id, $trend_date);
                $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_CMM_trend_update_visitor_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_trend_update_visitor_error", $wpdb->last_error);

            }
        }

        $logsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, voted, ip, user_agent) VALUES (%s, 'comment', %s, %s, '%s', '%s', '%s')",
            $stats, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua);
        $wpdb->query($logsql);

wp_gdsr_dump("SAVEVOTE_CMM_insert_stats_sql", $sql);
wp_gdsr_dump("SAVEVOTE_CMM_insert_stats_id", $wpdb->insert_id);
wp_gdsr_dump("SAVEVOTE_CMM_insert_stats_error", $wpdb->last_error);

    }

    function add_vote($id, $user, $ip, $ua, $vote, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_data_article';
        $stats = $table_prefix.'gdsr_votes_log';
        $trend = $table_prefix.'gdsr_votes_trend';

        $trend_date = date("Y-m-d");

        $sql_trend = sprintf("SELECT count(*) FROM %s WHERE vote_date = '%s' and vote_type = 'article' and id = %s", $trend, $trend_date, $id);
        $trend_data = $wpdb->get_var($sql_trend);

wp_gdsr_dump("SAVEVOTE_trend_check_sql", $sql_trend);
wp_gdsr_dump("SAVEVOTE_trend_check_error", $wpdb->last_error);

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            if ($user > 0) {
                $sql = sprintf("INSERT INTO %s (id, vote_type, user_voters, user_votes, vote_date) VALUES (%s, 'article', 1, %s, '%s')",
                        $trend, $id, $vote, $trend_date);
                $wpdb->query($sql);
            } else {
                $sql = sprintf("INSERT INTO %s (id, vote_type, visitor_voters, visitor_votes, vote_date) VALUES (%s, 'article', 1, %s, '%s')",
                        $trend, $id, $vote, $trend_date);
                $wpdb->query($sql);
            }

wp_gdsr_dump("SAVEVOTE_trend_insert_sql", $sql);
wp_gdsr_dump("SAVEVOTE_trend_insert_error", $wpdb->last_error);

        }

        if ($user > 0) {
            $sql = sprintf("UPDATE %s SET user_voters = user_voters + 1, user_votes = user_votes + %s, last_voted = CURRENT_TIMESTAMP WHERE post_id = %s",
                $articles, $vote, $id);
            $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_update_user_sql", $sql);
wp_gdsr_dump("SAVEVOTE_update_user", $wpdb->last_error);

            if (!$trend_added) {
                $sql = sprintf("UPDATE %s SET user_voters = user_voters + 1, user_votes = user_votes + %s WHERE id = %s and vote_type = 'article' and vote_date = '%s'",
                    $trend, $vote, $id, $trend_date);
                $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_trend_added_user_sql", $sql);
wp_gdsr_dump("SAVEVOTE_trend_added_user_error", $wpdb->last_error);

            }
        } else {
            $sql = sprintf("UPDATE %s SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s, last_voted = CURRENT_TIMESTAMP WHERE post_id = %s",
                $articles, $vote, $id);
            $wpdb->query($sql);

wp_gdsr_dump("SAVEVOTE_update_visitor_sql", $sql);
wp_gdsr_dump("SAVEVOTE_update_visitor_error", $wpdb->last_error);

            if (!$trend_added) {
                $sql = sprintf("UPDATE %s SET visitor_voters = visitor_voters + 1, visitor_votes = visitor_votes + %s WHERE id = %s and vote_type = 'article' and vote_date = '%s'",
                    $trend, $vote, $id, $trend_date);
                $wpdb->query($sql);
            }

wp_gdsr_dump("SAVEVOTE_trend_added_visitor_sql", $sql);
wp_gdsr_dump("SAVEVOTE_trend_added_visitor_error", $wpdb->last_error);

        }

        $logsql = sprintf("INSERT INTO %s (id, vote_type, user_id, vote, object, voted, ip, user_agent, comment_id) VALUES (%s, 'article', %s, %s, '', '%s', '%s', '%s', %s)",
            $stats, $id, $user, $vote, str_replace("'", "''", current_time('mysql')), $ip, $ua, $comment_id);
        $wpdb->query($logsql);

wp_gdsr_dump("SAVEVOTE_insert_stats_sql", $sql);
wp_gdsr_dump("SAVEVOTE_insert_stats_id", $wpdb->insert_id);
wp_gdsr_dump("SAVEVOTE_insert_stats_error", $wpdb->last_error);

    }

    function rating_from_comment($comment_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select vote from %sgdsr_votes_log where vote_type = 'article' and comment_id = %s", $table_prefix, $comment_id);
        return $wpdb->get_var($sql);
    }

    function delete_by_comment($comment_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select * from %sgdsr_votes_log where vote_type = 'article' and comment_id = %s", $table_prefix, $comment_id);
        $row = $wpdb->get_row($sql);
        if (count($row) > 0) {
            if ($row->user_id == 0) $delstring = sprintf("visitor_votes = visitor_votes - %s, visitor_voters = visitor_voters - 1", $row->vote);
            else $delstring = sprintf("user_votes = user_votes - %s, user_voters = user_voters - 1", $row->vote);

            $sql = sprintf("update %sgdsr_data_article set %s where post_id = %s", $table_prefix, $delstring, $row->id);
            $wpdb->query($sql);
            $sql = sprintf("delete from %sgdsr_votes_log where record_id = %s", $table_prefix, $row->record_id);
            $wpdb->query($sql);
        }
    }

    function add_default_vote($post_id, $is_page = '', $review = -1) {
        global $wpdb, $table_prefix;
        $options = get_option('gd-star-rating');
        if ($is_page == '') $is_page = GDSRDatabase::get_post_type($post_id) == 'page' ? '1' : '0';

        $vote_timer_value = $options["default_timer_type"] == "T" || $options["default_timer_type"] == "D" ? $options["default_timer_value"] : "";
        $dbt_data_article = $table_prefix.'gdsr_data_article';
        $sql = sprintf("INSERT INTO %s (post_id, rules_articles, rules_comments, moderate_articles, moderate_comments, is_page, user_voters, user_votes, visitor_voters, visitor_votes, review, expiry_type, expiry_value) VALUES (%s, '%s', '%s', '%s', '%s', '%s', 0, 0, 0, 0, %s, '%s', '%s')",
                $dbt_data_article, $post_id, $options["default_voterules_articles"], $options["default_voterules_comments"], $options["default_moderation_articles"], $options["default_moderation_comments"], $is_page, $review, $options["default_timer_type"], $vote_timer_value);
        $wpdb->query($sql);
    }

    function add_empty_comment($comment_id, $post_id, $review = -1) {
        global $wpdb, $table_prefix;
        $sql = sprintf("INSERT INTO %sgdsr_data_comment (comment_id, post_id, is_locked, user_voters, user_votes, visitor_voters, visitor_votes, review) VALUES (%s, %s, '0', '0', '0', '0', '0', %s)",
                $table_prefix, $comment_id, $post_id, $review);
        $wpdb->query($sql);
    }

    function add_empty_comments($post_id) {
        global $wpdb, $table_prefix;
        $dbt_data_comment = $table_prefix.'gdsr_data_comment';

        $sql = sprintf("select c.comment_ID from %s c left join %s g on c.comment_ID = g.comment_ID where (isnull(g.post_id) or g.post_id < 1) and c.comment_approved = 1 and c.comment_type = '' and c.comment_post_id = %s",
                $wpdb->comments, $dbt_data_comment, $post_id);
        $cmms = $wpdb->get_results($sql);
        foreach ($cmms as $c)
            GDSRDatabase::add_empty_comment($c->comment_ID, $post_id);
    }

    function add_new_view($post_id) {
        if (intval($post_id) > 0) {
            global $wpdb, $table_prefix;
            $dbt_data_article = $table_prefix.'gdsr_data_article';
            $sql = sprintf("update %s set views = views + 1 where post_id = %s", $dbt_data_article, $post_id);
            $wpdb->query($sql);
        }
    }
    // save & update

    // get
    function get_comments_aggregation($post_id, $filter_show = "total") {
        global $wpdb, $table_prefix;

        $where = "";
        switch ($filter_show) {
            default:
            case "total":
                $where = " user_voters + visitor_voters > 0";
                break;
            case "users":
                $where = " user_voters > 0";
                break;
            case "visitors":
                $where = " visitor_voters > 0";
                break;
        }

        $sql = sprintf("SELECT * FROM %sgdsr_data_comment where post_id = %s and %s", $table_prefix, $post_id, $where);
        return $wpdb->get_results($sql);
    }

    function get_post_data($post_id) {
        global $wpdb, $table_prefix;
        $sql = "select * from ".$table_prefix."gdsr_data_article WHERE post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        return $results;
    }

    function get_comment_data($comment_id) {
        global $wpdb, $table_prefix;
        $sql = "select * from ".$table_prefix."gdsr_data_comment WHERE comment_id = ".$comment_id;
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
        if (count($results) == 0) return 0;
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
            $table_prefix."gdsr_moderate", $id, $vote_type, $users);
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
            $wpdb->comments, $table_prefix."gdsr_moderate", $post_id, $users);
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

        $sql = sprintf("select m.*, u.user_login as username from %s m left join %s u on u.id = m.user_id where m.id = %s and m.vote_type = '%s'%s order by m.voted desc LIMIT %s, %s",
            $table_prefix."gdsr_moderate",
            $wpdb->users,
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

        $sql = sprintf("select m.*, u.user_login as username from %s c inner join %s m on m.id = c.comment_ID left join %s u on u.id = m.user_id where c.comment_post_ID = %s and m.vote_type = 'comment'%s order by m.voted desc LIMIT %s, %s",
            $wpdb->comments,
            $table_prefix."gdsr_moderate",
            $wpdb->users,
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

wp_gdsr_dump("GET_POST_TYPE_sql", $sql);
wp_gdsr_dump("GET_POST_TYPE_type", $r);

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

    function get_voters_count($post_id, $dates = "", $vote_type = "article", $vote_value = 0) {
        global $table_prefix;
        $where = " where vote_type = '".$vote_type."'";
        $where.= " and id = ".$post_id;
        if ($dates != "" && $dates != "0") {
            $where.= " and year(voted) = ".substr($dates, 0, 4);
            $where.= " and month(voted) = ".substr($dates, 4, 2);
        }
        if ($vote_value > 0)
            $where.= " and vote = ".$vote_value;
        
        $sql = sprintf("SELECT count(*) as count, user_id = 0 as user FROM %sgdsr_votes_log%s group by (user_id = 0)", 
            $table_prefix, $where
            );

        return $sql;
    }

    function get_visitors($post_id, $vote_type = "article", $dates = "", $vote_value = 0, $select = "total", $start = 0, $limit = 20, $sort_column = '', $sort_order = '') {
        global $wpdb, $table_prefix;

        if ($sort_column == '') $sort_column = 'user_id';
        if ($sort_order == '') $sort_order = 'asc';

        if ($sort_column == 'ip') $sort_column = 'INET_ATON(p.ip)';
        else if ($sort_column == 'user_nicename') $sort_column = "u.user_nicename";
        else $sort_column = "p.".$sort_column;

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
        if ($vote_value > 0)
            $where.= " and vote = ".$vote_value;

        $sql = sprintf("SELECT p.*, u.user_nicename FROM %sgdsr_votes_log p LEFT JOIN %s u ON u.ID = p.user_id%s ORDER BY %s %s LIMIT %s, %s",
                $table_prefix, $wpdb->users, $where, $sort_column, $sort_order, $start, $limit);

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

        $extras = ", '' as total, '' as votes, '' as title, 0 as rating_total, 0 as rating_users, 0 as rating_visitors";

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
    // get

    // combox
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
            if ($selected == $arc_row->ID) $default = ' selected="selected"';
            else $default = '';
            echo sprintf('<option%s value="%s">%s</option>', $default, $arc_row->ID, $arc_row->user_login);
        }
        ?>
        </select>
        <?php
    }

    function get_combo_categories($selected = '', $name = 'gdsr_categories') {
        $dropdown_options = array('show_option_all' => __("All categories", "gd-star-rating"), 'hide_empty' => 0, 'hierarchical' => 1,
            'show_count' => 0, 'echo' => 1, 'orderby' => 'name', 'selected' => $selected, 'name' => $name);
        wp_dropdown_categories($dropdown_options);
    }
    // combos
}

class GDSRDBTools {
    function taxonomy_multi_ratings($taxonomy = "category", $term = "", $multi_id = 0) {
        global $wpdb, $table_prefix, $wp_taxonomies;

        $select = "x.name as title, t.term_id, count(*) as counter, sum(d.average_rating_users * d.total_votes_users) as user_votes, sum(d.average_rating_visitors * d.total_votes_visitors) as visitor_votes, sum(d.total_votes_users) as user_voters, sum(d.total_votes_visitors) as visitor_voters, sum(d.average_review)/count(*) as review, 0 as votes, 0 as voters, 0 as rating, 0 as bayesian, '' as rating_stars, '' as bayesian_stars, '' as review_stars";
        $from = sprintf("%sterm_taxonomy t, %sterm_relationships r, %sterms x, ", $table_prefix, $table_prefix, $table_prefix);
        $where = array("t.term_taxonomy_id = r.term_taxonomy_id", "r.object_id = p.id", "t.term_id = x.term_id", "p.id = d.post_id", "p.post_status = 'publish'", "d.multi_id = ".$multi_id);

        $where[] = sprintf("t.taxonomy = '%s'", $taxonomy);
        if ($term != "") $where[] = sprintf("x.name = '%s'", str_replace("'", "''", $term));

        $sql = sprintf("select distinct %s from %s%sposts p, %sgdsr_multis_data d where %s group by t.term_id",
            $select, $from, $table_prefix, $table_prefix, join(" and ", $where));
        return $wpdb->get_results($sql);
    }

    function clean_invalid_log_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_log l left join %sposts o on o.ID = l.id where l.vote_type = 'article' and o.ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_votes_log", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_log_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_log l left join %scomments o on o.comment_ID = l.id where l.vote_type = 'comment' and o.comment_ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_votes_log", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_trend_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_trend l left join %sposts o on o.ID = l.id where l.vote_type = 'article' and o.ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_votes_trend", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_invalid_trend_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_votes_trend l left join %scomments o on o.comment_ID = l.id where l.vote_type = 'comment' and o.comment_ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_votes_trend", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_dead_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_data_article l left join %sposts o on o.ID = l.post_id where o.ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_data_article", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_revision_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_data_article l inner join %sposts o on o.ID = l.post_id where o.post_type = 'revision'",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_data_article", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }

    function clean_dead_comments() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_data_comment l left join %scomments o on o.comment_ID = l.comment_id where o.comment_ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_data_comment", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        return $wpdb->rows_affected;
    }
}

class GDSRDB {
    function get_user_votes_overview($user) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select vote_type, count(*) as counter from %sgdsr_votes_log where user_id = %s group by vote_type", $table_prefix, $user);
        $results = $wpdb->get_results($sql);

        $data = array();
        foreach ($results as $r) {
            $data[$r->vote_type] = intval($r->counter);
        }
        return $data;
    }

    function filter_votes_by_type($user, $filter = "'multis', 'artthumb', 'article'", $posts = true) {
        global $wpdb, $table_prefix;

        $select = "l.id, l.vote_type, l.voted, l.vote, l.ip, l.user_id, u.display_name, u.user_email";
        $from = sprintf("%sgdsr_votes_log l left join %s u on u.ID = l.user_id", $table_prefix, $wpdb->users);
        if ($posts) {
            $select.= ", l.object, m.stars, m.weight, m.name";
            $from.= sprintf(" left join %sgdsr_multis m on m.multi_id = l.multi_id", $table_prefix);
            $from.= sprintf(" inner join %sposts p on p.ID = l.id", $table_prefix);
            $where = " and p.post_author = ".$user;
        } else {
            $from.= sprintf(" inner join %scomments c on c.comment_ID = l.id", $table_prefix);
            $where = " and c.user_id = ".$user;
        }

        $sql = sprintf("select %s from %s where vote_type in (%s)%s order by l.voted desc limit 0, %s",
            $select, $from, $filter, $where, 100);
        return $wpdb->get_results($sql);
    }

    function filter_latest_votes($o, $user = 0) {
        global $wpdb, $table_prefix;
        $types = array();

        $where = $user == 0 ? "" : " and l.user_id = ".$user;
        $select = "l.id, l.vote_type, l.voted, l.vote, l.ip, l.user_id, u.display_name, u.user_email";
        $from = sprintf("%sgdsr_votes_log l left join %s u on u.ID = l.user_id", $table_prefix, $wpdb->users);

        if ($o["integrate_dashboard_latest_filter_thumb_std"] == 1) $types[] = "'artthumb'";
        if ($o["integrate_dashboard_latest_filter_thumb_cmm"] == 1) $types[] = "'cmmthumb'";
        if ($o["integrate_dashboard_latest_filter_stars_std"] == 1) $types[] = "'article'";
        if ($o["integrate_dashboard_latest_filter_stars_cmm"] == 1) $types[] = "'comment'";
        if ($o["integrate_dashboard_latest_filter_stars_mur"] == 1) {
            $types[] = "'multis'";
            $select.= ", l.object, m.stars, m.weight, m.name";
            $from.= sprintf(" left join %sgdsr_multis m on m.multi_id = l.multi_id", $table_prefix);
        }

        $sql = sprintf("select %s from %s where vote_type in (%s)%s order by voted desc limit 0, %s",
            $select, $from, join(", ", $types), $where, $o["integrate_dashboard_latest_count"]);
        return $wpdb->get_results($sql);
    }

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

    function get_post_title($post_id) {
        global $wpdb;
        return $wpdb->get_var("select post_title from $wpdb->posts where ID = ".$post_id);
    }

    // conversion
    function convert_multi_row($row) {

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
            case 'N':
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
            case 'N':
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

        $votes_v = '/';
        $count_v = '[ 0 ] ';
        if ($row->visitor_voters > 0) {
            $visitor_rating = @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $row->rating_visitors = $visitor_rating;
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=article&amp;vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->visitor_voters);
        }

        $votes_u = '/';
        $count_u = '[ 0 ] ';
        if ($row->user_voters > 0) {
            $user_rating = @number_format($row->user_votes / $row->user_voters, 1);
            $row->rating_users = $user_rating;
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=article&amp;vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->user_voters);
        }

        if ($row->review == -1 || $row->review == '') $row->review = "/";
        $row->review = '<strong><span style="color: blue">'.$row->review.'</span></strong>';

        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;

        $votes_t = '/';
        $count_t = '[ 0 ] ';
        if ($total_voters > 0) {
            $total_rating = @number_format($total_votes / $total_voters, 1);
            $row->rating_total = $total_rating;
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=article&amp;vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $total_voters);
        }

        $cnt_thumb_v = $cnt_thumb_u = $cnt_thumb_t = '0';
        $vts_thumb_v = $vts_thumb_u = $vts_thumb_t = '[ 0 ] ';
        if ($row->user_recc_plus > 0 || $row->user_recc_minus > 0) {
            $score = $row->user_recc_plus - $row->user_recc_minus;
            $votes = $row->user_recc_plus + $row->user_recc_minus;
            $cnt_thumb_u = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=artthumb&amp;vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $votes);
        }

        if ($row->visitor_recc_plus > 0 || $row->visitor_recc_minus > 0) {
            $score = $row->visitor_recc_plus - $row->visitor_recc_minus;
            $votes = $row->visitor_recc_plus + $row->visitor_recc_minus;
            $cnt_thumb_v = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=artthumb&amp;vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $votes);
        }

        if ($row->user_recc_plus > 0 || $row->user_recc_minus > 0 || $row->visitor_recc_plus > 0 || $row->visitor_recc_minus > 0) {
            $score = $row->user_recc_plus - $row->user_recc_minus + $row->visitor_recc_plus - $row->visitor_recc_minus;
            $votes = $row->user_recc_plus + $row->user_recc_minus + $row->visitor_recc_plus + $row->visitor_recc_minus;
            $cnt_thumb_t = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=artthumb&amp;vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $votes);
        }

        $row->total = $count_t.__("rating", "gd-star-rating").': <strong>'.$votes_t.'</strong><br />'.$vts_thumb_t.__("thumbs", "gd-star-rating").': <strong>'.$cnt_thumb_t.'</strong>';
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';
        $row->thumbs = $vts_thumb_v.__("visitors", "gd-star-rating").': <strong>'.$cnt_thumb_v.'</strong><br />'.$vts_thumb_u.__("users", "gd-star-rating").': <strong>'.$cnt_thumb_u.'</strong>';

        $row->title = sprintf('<a href="./post.php?action=edit&amp;post=%s">%s</a>', $row->pid, $row->post_title);

        return $row;
    }

    function convert_category_row($row) {
        switch ($row->moderate_articles) {
            case 'P':
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("parent", "gd-star-rating").'</span></strong>';
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
            case 'N':
            default:
                $row->moderate_articles = __("articles", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->moderate_comments) {
            case 'P':
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("parent", "gd-star-rating").'</span></strong>';
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
            case 'N':
            default:
                $row->moderate_comments = __("comments", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_articles) {
            case 'P':
                $row->rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("parent", "gd-star-rating").'</span></strong>';
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
            case 'A':
            default:
                $row->rules_articles = __("articles", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->rules_comments) {
            case 'P':
                $row->rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("parent", "gd-star-rating").'</span></strong>';
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
            case 'A':
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
        $votes_v = '/';
        $count_v = '[ 0 ] ';
        if ($row->visitor_voters > 0) {
            $visitor_rating = @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $row->rating_visitors = $visitor_rating;
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=comment&amp;vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->visitor_voters);
        }

        $votes_u = '/';
        $count_u = '[ 0 ] ';
        if ($row->user_voters > 0) {
            $user_rating = @number_format($row->user_votes / $row->user_voters, 1);
            $row->rating_users = $user_rating;
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=comment&amp;vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $row->user_voters);
        }

        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;

        $votes_t = '/';
        $count_t = '[ 0 ] ';
        if ($total_voters > 0) {
            $total_rating = @number_format($total_votes / $total_voters, 1);
            $row->rating_total = $total_rating;
            $votes_t = '<strong><span style="color: red">'.$total_rating.'</span></strong>';
            $count_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=comment&amp;vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $total_voters);
        }

        $cnt_thumb_v = $cnt_thumb_u = $cnt_thumb_t = '0';
        $vts_thumb_v = $vts_thumb_u = $vts_thumb_t = '[ 0 ] ';
        if ($row->user_recc_plus > 0 || $row->user_recc_minus > 0) {
            $score = $row->user_recc_plus - $row->user_recc_minus;
            $votes = $row->user_recc_plus + $row->user_recc_minus;
            $cnt_thumb_u = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=cmmthumb&amp;vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $votes);
        }

        if ($row->visitor_recc_plus > 0 || $row->visitor_recc_minus > 0) {
            $score = $row->visitor_recc_plus - $row->visitor_recc_minus;
            $votes = $row->visitor_recc_plus + $row->visitor_recc_minus;
            $cnt_thumb_v = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=cmmthumb&amp;vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $votes);
        }

        if ($row->user_recc_plus > 0 || $row->user_recc_minus > 0 || $row->visitor_recc_plus > 0 || $row->visitor_recc_minus > 0) {
            $score = $row->user_recc_plus - $row->user_recc_minus + $row->visitor_recc_plus - $row->visitor_recc_minus;
            $votes = $row->user_recc_plus + $row->user_recc_minus + $row->visitor_recc_plus + $row->visitor_recc_minus;
            $cnt_thumb_t = '<strong><span style="color: red">'.($score > 0 ? "+" : "").$score.'</span></strong>';
            $vts_thumb_t = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=cmmthumb&amp;vg=total"> <strong style="color: red;">%s</strong> </a> ] ', $row->comment_id, $votes);
        }

        $row->total = $count_t.__("votes", "gd-star-rating").': <strong>'.$votes_t.'</strong><br />'.$vts_thumb_t.__("thumbs", "gd-star-rating").': <strong>'.$cnt_thumb_t.'</strong>';
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';
        $row->thumbs = $vts_thumb_v.__("visitors", "gd-star-rating").': <strong>'.$cnt_thumb_v.'</strong><br />'.$vts_thumb_u.__("users", "gd-star-rating").': <strong>'.$cnt_thumb_u.'</strong>';

        if ($row->review == -1) $row->review = "/";
        $row->review = '<strong><span style="color: blue">'.$row->review.'</span></strong>';

        return $row;
    }
    // conversion

    // moderation
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
    // moderation

    // insert templates
    function install_all_templates() {
        GDSRDB::insert_default_templates(STARRATING_PATH);
        GDSRDB::insert_extras_templates(STARRATING_PATH);
        GDSRDB::insert_extras_templates(STARRATING_XTRA_PATH, false);
        GDSRDB::update_default_templates(STARRATING_PATH);
        GDSRDB::update_extras_templates(STARRATING_PATH);
    }

    function insert_extras_templates($path, $default = true) {
        global $wpdb, $table_prefix;
        $templates = array();

        if ($default) $path.= "install/data/gdsr_templates_xtra.txt";
        else $path.= "data/gdsr_templates_cstm.txt";

        $preinstalled = $default ? "2" : "0";

        if (file_exists($path)) {
            $tpls = file($path);
            foreach ($tpls as $tpl) {
                $pipe = strpos($tpl, "|");
                $tpl_check = substr($tpl, 0, $pipe);
                $tpl_section = substr($tpl, $pipe + 1, 3);
                $tpl_insert = substr($tpl, $pipe + 5);
                $sql = sprintf("select template_id from %s%s where name = '%s' and preinstalled = '%s'", $table_prefix, STARRATING_TPLT2_TABLE, $tpl_check, $preinstalled);
                $tpl_id = intval($wpdb->get_var($sql));
                if ($tpl_id == 0) {
                    $sql = str_replace("%s", $table_prefix, $tpl_insert);
                    $wpdb->query($sql);
                    $tpl_id = $wpdb->insert_id;
                }
                $template["section"] = $tpl_section;
                $template["tpl_id"] = sprintf("%s", $tpl_id);
                $templates[] = $template;
            }
        }
        if (count($templates) > 0) {
            include(STARRATING_PATH.'code/t2/templates.php');
            $depend = array();
            foreach ($tpls->tpls as $tpl) {
                $section = $tpl->code;
                $sql = sprintf("select template_id from %s%s where section = '%s' and preinstalled = '1'", $table_prefix, STARRATING_TPLT2_TABLE, $section);
                $tpl_id = intval($wpdb->get_var($sql));
                $depend[$section] = $tpl_id;
            }
            foreach ($templates as $tpl) {
                $dep = array();
                $t = $tpls->get_list($tpl["section"]);
                foreach ($t->tpls as $tag) {
                    $s = $tag->code;
                    $dep[$s] = sprintf("%s", $depend[$s]);
                }
                if (count($dep) > 0) {
                    $sql = sprintf("update %s%s set dependencies = '%s' where template_id = %s",
                        $table_prefix, STARRATING_TPLT2_TABLE, serialize($dep), $tpl["tpl_id"]);
                    $wpdb->query($sql);
                }
            }
        }
    }

    function insert_default_templates($path) {
        global $wpdb, $table_prefix;
        $templates = array();
        $path.= "install/data/gdsr_templates_main.txt";
        if (file_exists($path)) {
            $tpls = file($path);
            foreach ($tpls as $tpl) {
                $tpl_check = substr($tpl, 0, 3);
                $tpl_insert = substr($tpl, 4);
                $sql = sprintf("select template_id from %s%s where section = '%s' and preinstalled = '1'", $table_prefix, STARRATING_TPLT2_TABLE, $tpl_check);

                $tpl_id = intval($wpdb->get_var($sql));
                if ($tpl_id == 0) {
                    $sql = str_replace("%s", $table_prefix, $tpl_insert);
                    $wpdb->query($sql);
                    $tpl_id = $wpdb->insert_id;
                }
                $templates[$tpl_check] = sprintf("%s", $tpl_id);
            }
        }
        if (count($templates) > 0) {
            include(STARRATING_PATH.'code/t2/templates.php');
            foreach ($tpls->tpls as $tpl) {
                $depend = array();
                foreach ($tpl->elements as $el) {
                    if ($el->tpl > -1) {
                        $section = $tpl->tpls[$el->tpl]->code;
                        $depend[$section] = $templates[$section];
                    }
                }
                if (count($depend) > 0) {
                    $sql = sprintf("update %s%s set dependencies = '%s' where template_id = %s",
                        $table_prefix, STARRATING_TPLT2_TABLE, serialize($depend), $templates[$tpl->code]);
                    $wpdb->query($sql);
                }
            }
        }
    }

    function update_default_templates($path) {
        global $wpdb, $table_prefix;
        $path.= "install/data/gdsr_templates_rplc.txt";
        if (file_exists($path)) {
            $tpls = file($path);
            foreach ($tpls as $tpl) {
                $tpl_check = substr($tpl, 0, 3);
                $tpl_value = substr($tpl, 4);
                $sql = sprintf("update %s%s set elements = '%s' where section = '%s' and preinstalled = '1'", $table_prefix, STARRATING_TPLT2_TABLE, $tpl_value, $tpl_check);
                $wpdb->query($sql);
            }
        }
    }

    function update_extras_templates($path) {
        global $wpdb, $table_prefix;
        $path.= "install/data/gdsr_templates_xtrp.txt";
        if (file_exists($path)) {
            $tpls = file($path);
            foreach ($tpls as $tpl) {
                $parts = explode("|", $tpl, 3);
                $sql = sprintf("update %s%s set elements = '%s' where section = '%s' and name = '%s' and preinstalled = '2'", $table_prefix, STARRATING_TPLT2_TABLE, $parts[2], $parts[1], $parts[0]);
                $wpdb->query($sql);
            }
        }
    }
    // insert templates

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

class GDSRDBMulti {
    function get_review_avg($multi_id, $post_id) {
        global $wpdb, $table_prefix;
        $articles = $table_prefix.'gdsr_multis_data';

        $sql = "select average_review from ".$articles." WHERE multi_id = ".$multi_id." and post_id = ".$post_id;
        $results = $wpdb->get_row($sql, OBJECT);
        if (count($results) == 0) return -1;
        else return $results->average_review;
    }

    function delete_votes($ids, $delete, $multi_id) {
        global $wpdb, $table_prefix;
        if ($delete == "") return;

        $delstring = $dellog = "";
        switch (substr($delete, 1, 1)) {
            case "A":
                $delstring = "md.average_rating_users = 0, md.total_votes_users = 0, mv.user_voters = 0, mv.user_votes = 0";
                $delstring.= ", md.average_rating_visitors = 0, md.total_votes_visitors = 0, mv.visitor_voters = 0, mv.visitor_votes = 0";
                break;
            case "V":
                $delstring = "md.average_rating_visitors = 0, md.total_votes_visitors = 0, mv.visitor_voters = 0, mv.visitor_votes = 0";
                $dellog = " and user_id = 0";
                break;
            case "U":
                $delstring = "md.average_rating_users = 0, md.total_votes_users = 0, mv.user_voters = 0, mv.user_votes = 0";
                $dellog = " and user_id > 0";
                break;
            default:
                return;
                break;
        }

        $sql = sprintf("update %sgdsr_multis_data md inner join %sgdsr_multis_values mv on mv.id = md.id set %s where md.multi_id = %s and md.post_id in %s and mv.source = 'dta'",
            $table_prefix, $table_prefix, $delstring, $multi_id, $ids);
        $wpdb->query($sql);

        $sql = sprintf("delete from %sgdsr_votes_log where id in %s and multi_id = %s%s", $table_prefix, $ids, $multi_id, $dellog);
        $wpdb->query($sql);
    }

    function get_rss_multi_data($post_id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_multis_data where post_id = %s order by (total_votes_users + total_votes_visitors) desc limit 0, 1", $table_prefix, $post_id);
        return $wpdb->get_row($sql);
    }

    function recalculate_multi_rating_log() {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_votes_log where vote_type = 'multis' and vote = 0", $table_prefix);
        $rows = $wpdb->get_results($sql);

        foreach ($rows as $row) {
            $set = wp_gdget_multi_set($row->multi_id);
            if (!is_null($set)) {
                $vote = intval(10 * GDSRDBMulti::get_multi_rating_from_single_object($set, unserialize($row->object)));
                $sql = sprintf("update %sgdsr_votes_log set `vote` = %s where record_id = %s", $table_prefix, $vote, $row->record_id);
                $wpdb->query($sql);
            }
        }
    }

    function get_multi_rating_from_single_object($set, $object) {
        $weighted = $i = 0;

        $weight_norm = array_sum($set->weight);
        $multi_data = $object;
        foreach ($multi_data as $md) {
            $weighted += (intval($md) * $set->weight[$i]) / $weight_norm;
            $i++;
        }
        return number_format($weighted, 1);
    }

    function get_first_multi_set() {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_multis order by multi_id limit 0, 1", $table_prefix);
        return $wpdb->get_row($sql);
    }

    function get_multisets_for_auto_insert() {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_multis where auto_insert != 'none'", $table_prefix);
        return $wpdb->get_results($sql);
    }

    function recalculate_trend_averages($trend_id, $set) {
        global $wpdb, $table_prefix;

        $multi_data = GDSRDBMulti::get_trend_values($trend_id);
        $weight_norm = array_sum($set->weight);
        $total_users = $total_visitors = $total_votes = 0;
        $user_weighted = $visitors_weighted = $weighted = 0;
        $i = 0;
        foreach ($multi_data as $md) {
            $v_user = $v_visitor = $s_user = $s_visitor = $r_user = $r_visitor = 0;

            $v_visitor = $md->visitor_voters;
            $s_visitor = $md->visitor_votes;
            $v_user = $md->user_voters;
            $s_user = $md->user_votes;

            if ($v_visitor > 0) $r_visitor = $s_visitor / $v_visitor;
            if ($v_user > 0) $r_user = $s_user / $v_user;
            if ($r_visitor > $set->stars) $r_visitor = $set->stars;
            if ($r_user > $set->stars) $r_user = $set->stars;

            $r_visitor = @number_format($r_visitor, 1);
            $r_user = @number_format($r_user, 1);
            $visitors_weighted += ($r_visitor * $set->weight[$i]) / $weight_norm;
            $user_weighted += ($r_user * $set->weight[$i]) / $weight_norm;
            $total_visitors += $v_visitor;
            $total_users += $v_user;

            $i++;
        }
        $rating_users = @number_format($user_weighted, 1);
        $rating_visitors = @number_format($visitors_weighted, 1);
        $total_users = @number_format($total_users / $i, 0);
        $total_visitors = @number_format($total_visitors / $i, 0);

        $sql = sprintf("update %sgdsr_multis_trend set average_rating_users = '%s', average_rating_visitors = '%s', total_votes_users = '%s', total_votes_visitors = '%s' where id = %s",
            $table_prefix, $rating_users, $rating_visitors, $total_users, $total_visitors, $trend_id);
        $wpdb->query($sql);
    }

    function recalculate_all_sets() {
        global $wpdb, $table_prefix;
        $set = null;
        $prev_set = 0;

        $sql = sprintf("select id, post_id, multi_id from %sgdsr_multis_data order by multi_id asc", $table_prefix);
        $posts = $wpdb->get_results($sql);
        foreach ($posts as $post) {
            if ($prev_set != $post->multi_id) $set = gd_get_multi_set($post->multi_id);
            GDSRDBMulti::recalculate_multi_averages($post->post_id, $post->multi_id, "", $set);
            GDSRDBMulti::recalculate_multi_review_db($post->post_id, $post->id, $set);
        }

        $prev_set = 0;

        $sql = sprintf("select id, multi_id from %sgdsr_multis_trend order by multi_id asc", $table_prefix);
        $ids = $wpdb->get_results($sql);
        foreach ($ids as $id) {
            if ($prev_set != $id->multi_id) $set = gd_get_multi_set($id->multi_id);
            foreach ($ids as $id) GDSRDBMulti::recalculate_trend_averages($id->id, $set);
        }
    }

    function recalculate_set($set_id) {
        global $wpdb, $table_prefix;
        $set = gd_get_multi_set($set_id);

        $sql = sprintf("select id, post_id from %sgdsr_multis_data where multi_id = %s", $table_prefix, $set_id);
        $posts = $wpdb->get_results($sql);
        foreach ($posts as $post) {
            GDSRDBMulti::recalculate_multi_averages($post->post_id, $set_id, "", $set);
            GDSRDBMulti::recalculate_multi_review_db($post->post_id, $post->id, $set);
        }

        $sql = sprintf("select id from %sgdsr_multis_trend where multi_id = %s", $table_prefix, $set_id);
        $ids = $wpdb->get_results($sql);
        foreach ($ids as $id) GDSRDBMulti::recalculate_trend_averages($id->id, $set);
    }

    function get_multi_rating_data($set_id, $post_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select * from %sgdsr_multis_data where post_id = %s and multi_id = %s", $table_prefix, $post_id, $set_id);
        return $wpdb->get_row($sql);
    }

    function recalculate_multi_review_db($post_id, $record_id, $set) {
        global $wpdb, $table_prefix;
        $multi_data = GDSRDBMulti::get_values($record_id, 'rvw');
        if (count($multi_data) == 0) {
            GDSRDBMulti::add_empty_review_values($record_id, count($set->object));
            $multi_data = GDSRDBMulti::get_values($record_id, 'rvw');
        }
        $review = new GDSRArticleMultiReview($post_id);
        $review->set = $set;
        $i = 0;
        $weighted = 0;
        $weight_norm = array_sum($set->weight);
        foreach ($multi_data as $md) {
            $single_vote = array();
            $single_vote["votes"] = 1;
            $single_vote["score"] = $md->user_votes;
            $single_vote["rating"] = $single_vote["score"];
            $review->values = $single_vote;
            $weighted += ( $single_vote["rating"] * $set->weight[$i] ) / $weight_norm;
            $i++;
        }
        $review->rating = @number_format($weighted, 1);

        $sql = sprintf("update %sgdsr_multis_data set average_review = '%s' where id = %s", $table_prefix, $review->rating, $record_id);
        $wpdb->query($sql);

        return $review;
    }

    function recalculate_multi_review($record_id, $values, $set) {
        global $wpdb, $table_prefix;

        $weight_norm = array_sum($set->weight);
        $overall = 0;
        for ($i = 0; $i < count($values); $i++) {
            $overall += ($values[$i] * $set->weight[$i]) / $weight_norm;
        }
        $overall = @number_format($overall, 1);

        $sql = sprintf("update %sgdsr_multis_data set average_review = '%s' where id = %s", $table_prefix, $overall, $record_id);
        $wpdb->query($sql);

        return $overall;
    }

    function recalculate_multi_vote($values, $set) {
        $weight_norm = array_sum($set->weight);
        $weight = $i = 0;
        foreach ($values as $value) {
            $weight+= ($value * $set->weight[$i]) / $weight_norm;
            $i++;
        }
        $rating = @number_format($weight, 1);
        return $rating;
    }

    function recalculate_multi_averages($post_id, $set_id, $rules = "", $set = null, $last_voted = false, $size = 20) {
        global $wpdb, $table_prefix;

        if ($set == null) $set = gd_get_multi_set($set_id);
        $multi_data = GDSRDBMulti::get_values_join($post_id, $set_id);
        $votes_js = array();
        $weight_norm = array_sum($set->weight);
        $total_users = $total_visitors = $total_votes = 0;
        $user_weighted = $visitors_weighted = $weighted = 0;
        $i = 0;
        foreach ($multi_data as $md) {
            $votes = $score = $rating = $v_user = $v_visitor = $s_user = $s_visitor = $r_user = $r_visitor = 0;

            $v_visitor = $md->visitor_voters;
            $s_visitor = $md->visitor_votes;
            $v_user = $md->user_voters;
            $s_user = $md->user_votes;

            if ($v_visitor > 0) $r_visitor = $s_visitor / $v_visitor;
            if ($v_user > 0) $r_user = $s_user / $v_user;
            if ($r_visitor > $set->stars) $r_visitor = $set->stars;
            if ($r_user > $set->stars) $r_user = $set->stars;

            $r_visitor = @number_format($r_visitor, 1);
            $r_user = @number_format($r_user, 1);
            $visitors_weighted += ($r_visitor * $set->weight[$i]) / $weight_norm;
            $user_weighted += ($r_user * $set->weight[$i]) / $weight_norm;
            $total_visitors += $v_visitor;
            $total_users += $v_user;

            if ($rules != "") {
                if ($rules == "A" || $rules == "N") {
                    $votes = $md->user_voters + $md->visitor_voters;
                    $score = $md->user_votes + $md->visitor_votes;
                }
                else if ($rules == "V") {
                    $votes = $md->visitor_voters;
                    $score = $md->visitor_votes;
                }
                else if ($rules == "U") {
                    $votes = $md->user_voters;
                    $score = $md->user_votes;
                }
                if ($votes > 0) $rating = $score / $votes;
                if ($rating > $set->stars) $rating = $set->stars;
                $rating = @number_format($rating, 1);
                $votes_js[] = $rating * $size;
                $weighted += ($rating * $set->weight[$i]) / $weight_norm;
                $total_votes += $votes;
            }
            $i++;
        }
        $rating_users = @number_format($user_weighted, 1);
        $rating_visitors = @number_format($visitors_weighted, 1);
        $total_users = @number_format($total_users / $i, 0);
        $total_visitors = @number_format($total_visitors / $i, 0);

        if ($rules != "") {
            $rating = @number_format($weighted, 1);
            $total_votes = @number_format($total_votes / $i, 0);
            $output["total"]["rating"] = $rating;
            $output["total"]["votes"] = $total_votes;
            $output["json"] = $votes_js;
        }

        $lastv = $last_voted ? ", last_voted = CURRENT_TIMESTAMP" : "";

        $sql = sprintf("update %sgdsr_multis_data set average_rating_users = '%s', average_rating_visitors = '%s', total_votes_users = '%s', total_votes_visitors = '%s'%s where post_id = %s and multi_id = %s",
            $table_prefix, $rating_users, $rating_visitors, $total_users, $total_visitors, $lastv, $post_id, $set_id);
        $wpdb->query($sql);

        $output["users"]["rating"] = $rating_users;
        $output["users"]["votes"] = $total_users;
        $output["visitors"]["rating"] = $rating_visitors;
        $output["visitors"]["votes"] = $total_visitors;

        return $output;
    }

    function clean_revision_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_multis_data l inner join %sposts o on o.ID = l.post_id where o.post_type = 'revision'",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_multis_data", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        $posts = $wpdb->rows_affected;

        $sql = sprintf("delete %s from %sgdsr_multis_values l left join %sgdsr_multis_data o on o.id = l.id where o.id is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_multis_values", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);

        return $posts;
    }

    function clean_dead_articles() {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete %s from %sgdsr_multis_data l left join %sposts o on o.ID = l.post_id where o.ID is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_multis_data", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);
        $posts = $wpdb->rows_affected;

        $sql = sprintf("delete %s from %sgdsr_multis_values l left join %sgdsr_multis_data o on o.id = l.id where o.id is null",
            gdFunctionsGDSR::mysql_pre_4_1() ? sprintf("%sgdsr_multis_values", $table_prefix) : "l",
            $table_prefix, $table_prefix);
        $wpdb->query($sql);

        return $posts;
    }

    function get_stats_count($set_id, $dates = "0", $cats = "0", $search = "") {
        global $table_prefix;
        $where = " and ms.multi_id = ".$set_id;

        if ($dates != "" && $dates != "0") {
            $where.= " and year(p.post_date) = ".substr($dates, 0, 4);
            $where.= " and month(p.post_date) = ".substr($dates, 4, 2);
        }
        if ($search != "")
            $where.= " and p.post_title like '%".$search."%'";

        if ($cats != "" && $cats != "0")
            $sql = sprintf("SELECT p.post_type, count(*) as count FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p, %sgdsr_multis_data ms WHERE p.ID = ms.post_id and t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.ID AND t.term_id = %s AND t.taxonomy = 'category' AND p.post_status = 'publish'%s GROUP BY p.post_type",
                $table_prefix, $table_prefix, $table_prefix, $table_prefix, $cats, $where
            );
        else
            $sql = sprintf("select p.post_type, count(*) as count from %sposts p inner join %sgdsr_multis_data ms on p.ID = ms.post_id where p.post_status = 'publish'%s group by post_type",
                $table_prefix, $table_prefix, $where
            );
        return $sql;
    }

    function get_stats($set_id, $select = "", $start = 0, $limit = 20, $dates = "0", $cats = "0", $search = "", $sort_column = 'id', $sort_order = 'desc', $additional = '') {
        global $table_prefix;
        $where = " and ms.multi_id = ".$set_id;

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
            $sql = sprintf("SELECT p.id as pid, p.post_title, p.post_type, ms.* FROM %sterm_taxonomy t, %sterm_relationships r, %sposts p, %sgdsr_multis_data ms WHERE ms.post_id = p.id and t.term_taxonomy_id = r.term_taxonomy_id AND r.object_id = p.id AND t.term_id = %s AND t.taxonomy = 'category' AND p.post_status = 'publish'%s%s%s LIMIT %s, %s",
                 $table_prefix, $table_prefix, $table_prefix, $table_prefix, $cats, $where, $additional, $order, $start, $limit
            );
        else
            $sql = sprintf("select p.id as pid, p.post_title, p.post_type, ms.* from %sposts p left join %sgdsr_multis_data ms on p.id = ms.post_id WHERE p.post_status = 'publish'%s%s%s limit %s, %s",
                $table_prefix, $table_prefix, $where, $additional, $order, $start, $limit
            );
        return $sql;
    }

    function delete_sets($ids) {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete from %sgdsr_multis where multi_id in %s", $table_prefix, $ids);
        $wpdb->query($sql);
    }

    function save_vote($post_id, $set_id, $user_id, $ip, $ua, $values, $post_data, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $ua = str_replace("'", "''", $ua);
        $ua = substr($ua, 0, 250);

        if ($post_data->moderate_articles == "" || $post_data->moderate_articles == "N" || ($post_data->moderate_articles == "V" && $user > 0) || ($post_data->moderate_articles == "U" && $user == 0)) {
            GDSRDBMulti::add_vote($post_id, $set_id, $user_id, $ip, $ua, $values);
            GDSRDBMulti::add_to_log($post_id, $set_id, $user_id, $ip, $ua, $values, $comment_id);
        }
        else {
            $modsql = sprintf("INSERT INTO %sgdsr_moderate (id, vote_type, multi_id, user_id, object, voted, ip, user_agent) VALUES (%s, 'multis', %s, %s, '%s', '%s', '%s', '%s')",
                $table_prefix, $post_id, $set_id, $user_id, serialize($values), str_replace("'", "''", current_time('mysql')), $ip, $ua);
            $wpdb->query($modsql);
        }
    }

    function add_to_log($post_id, $set_id, $user_id, $ip, $ua, $values, $comment_id = 0) {
        global $wpdb, $table_prefix;
        $set = wp_gdget_multi_set($set_id);
        $vote = intval(GDSRDBMulti::get_multi_rating_from_single_object($set, $values) * 10);

        $modsql = sprintf("INSERT INTO %sgdsr_votes_log (id, vote_type, multi_id, user_id, vote, object, voted, ip, user_agent, comment_id) VALUES (%s, 'multis', %s, %s, %s, '%s', '%s', '%s', '%s', %s)",
            $table_prefix, $post_id, $set_id, $user_id, $vote, serialize($values), str_replace("'", "''", current_time('mysql')), $ip, $ua, $comment_id);
        wp_gdsr_dump("LOG", $modsql);
        $wpdb->query($modsql);
    }

    function rating_from_comment($comment_id, $multi_set_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select object from %sgdsr_votes_log where vote_type = 'multis' and comment_id = %s and multi_id = %s", $table_prefix, $comment_id, $multi_set_id);
        return $wpdb->get_var($sql);
    }

    function delete_by_comment($comment_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select * from %sgdsr_votes_log where vote_type = 'multis' and comment_id = %s", $table_prefix, $comment_id);
        $row = $wpdb->get_row($sql);
        if (count($row) > 0) {
            $votes = unserialize($row->object);
            $sql = sprintf("select id from %sgdsr_multis_data where post_id = %s and multi_id = %s", $table_prefix, $row->id, $row->multi_id);
            $id = $wpdb->get_var($sql);

            if ($row->user_id == 0) $delstring = "visitor_votes = visitor_votes - %s, visitor_voters = visitor_voters - 1";
            else $delstring = "user_votes = user_votes - %s, user_voters = user_voters - 1";

            $i = 0;
            foreach ($votes as $vote) {
                $sql_set = sprintf($delstring, $vote);
                $sql = sprintf("update %sgdsr_multis_values set %s where source = 'dta' and id = %s and item_id = %s", $table_prefix, $sql_set, $id, $i);
                $wpdb->query($sql);
                $i++;
            }
            $set = gd_get_multi_set($row->multi_id);
            GDSRDBMulti::recalculate_multi_averages($row->id, $row->multi_id, "", $set, true);

            $sql = sprintf("delete from %sgdsr_votes_log where record_id = %s", $table_prefix, $row->record_id);
            $wpdb->query($sql);
        }
    }

    function add_vote($post_id, $set_id, $user_id, $ip, $ua, $votes) {
        global $wpdb, $table_prefix;
        $set = gd_get_multi_set($set_id);
        $data = $table_prefix.'gdsr_multis_data';
        $trend = $table_prefix.'gdsr_multis_trend';

        $trend_date = date("Y-m-d");

        $sql_trend = sprintf("SELECT id FROM %s WHERE vote_date = '%s' and post_id = %s and multi_id = %s", $trend, $trend_date, $post_id, $set_id);
        wp_gdsr_dump("TREND_CHECK", $sql_trend);
        $trend_data = intval($wpdb->get_var($sql_trend));
        wp_gdsr_dump("TREND_ID", $trend_data);

        $trend_added = false;
        if ($trend_data == 0) {
            $trend_added = true;
            $sql = sprintf("INSERT INTO %s (post_id, multi_id, vote_date) VALUES (%s, %s, '%s')", $trend, $post_id, $set_id, $trend_date);
            $wpdb->query($sql);
            $trend_id = $wpdb->insert_id;
        }
        else $trend_id = $trend_data;

        GDSRDBMulti::add_values($trend_id, $user_id, $votes, "trd", $trend_added ? "add" : "edit");
        GDSRDBMulti::recalculate_trend_averages($trend_id, $set);

        $data_id = GDSRDBMulti::get_vote($post_id, $set_id, count($set->object));

        GDSRDBMulti::add_values($data_id, $user_id, $votes);
    }

    function add_values($record_id, $user_id, $votes, $source = "dta", $operation = "edit") {
        global $wpdb, $table_prefix;
        $values = $table_prefix.'gdsr_multis_values';
        $cl_voters = $user_id == 0 ? "visitor_voters" : "user_voters";
        $cl_votes = $user_id == 0 ? "visitor_votes" : "user_votes";

        if ($operation == "add")
            $sql = sprintf("INSERT INTO %s (id, source, %s, %s, item_id) VALUES (%s, '%s', 1, %s, %s)",
                $values, $cl_voters, $cl_votes, $record_id, $source, "%s", "%s");
        else
            $sql = sprintf("UPDATE %s SET %s = %s + 1, %s = %s + %s WHERE id = %s and item_id = %s and source = '%s'",
                $values, $cl_voters, $cl_voters, $cl_votes, $cl_votes, "%s", $record_id, "%s", $source);

        $i = 0;
        foreach ($votes as $vote) {
            $sql_insert = sprintf($sql, $vote, $i);
            wp_gdsr_dump("SAVE_VOTE", $sql_insert);
            $wpdb->query($sql_insert);
            $i++;
        }
    }

    function get_values($id, $source = 'dta') {
        global $wpdb, $table_prefix;

        $sql = sprintf("SELECT * FROM %sgdsr_multis_values WHERE source = '%s' and id = %s ORDER BY item_id ASC", $table_prefix, $source, $id);
        return $wpdb->get_results($sql);
    }

    function add_empty_review_values($id, $values) {
        global $wpdb, $table_prefix;

        $values = intval($values);
        for ($i = 0; $i < $values; $i++) {
            $sql = sprintf("INSERT INTO %sgdsr_multis_values (id, source, item_id) VALUES (%s, 'rvw', %s)",
                $table_prefix, $id, $i);
            $wpdb->query($sql);
        }
    }

    function get_trend_values($id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_multis_values where source = 'trd' and id = %s order by item_id asc",
            $table_prefix, $id);
        return $wpdb->get_results($sql);
    }

    function get_values_join($post_id, $set_id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select v.* from %sgdsr_multis_values v inner join %sgdsr_multis_data d on d.id = v.id where v.source = 'dta' and d.post_id = %s and d.multi_id = %s order by v.item_id asc",
            $table_prefix, $table_prefix, $post_id, $set_id);
        return $wpdb->get_results($sql);
    }

    function get_averages($post_id, $set_id) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %sgdsr_multis_data where post_id = %s and multi_id = %s", $table_prefix, $post_id, $set_id);
        return $wpdb->get_row($sql);
    }

    function get_vote($post_id, $set_id, $values) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select id from %sgdsr_multis_data where post_id = %s and multi_id = %s", $table_prefix, $post_id, $set_id);
        $record_id = intval($wpdb->get_var($sql));

        if ($record_id == 0) {
            $sql = sprintf("INSERT INTO %sgdsr_multis_data (post_id, multi_id) VALUES (%s, %s)", $table_prefix, $post_id, $set_id);
            $wpdb->query($sql);
            $record_id = $wpdb->insert_id;
            for ($i = 0; $i < $values; $i++) {
                $sql = sprintf("INSERT INTO %sgdsr_multis_values (id, source, item_id) VALUES (%s, 'dta', %s)", $table_prefix, $record_id, $i);
                $wpdb->query($sql);
            }
        } else {
            $sql = sprintf("SELECT count(*) FROM %sgdsr_multis_values WHERE id = %s AND source = 'dta'", $table_prefix, $record_id);
            $counter = $wpdb->get_var($sql);
            if ($counter == 0) {
                for ($i = 0; $i < $values; $i++) {
                    $sql = sprintf("INSERT INTO %sgdsr_multis_values (id, source, item_id) VALUES (%s, 'dta', %s)", $table_prefix, $record_id, $i);
                    $wpdb->query($sql);
                }
            }
        }

        return $record_id;
    }

    function get_multi_review_average($record_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select average_review from %sgdsr_multis_data where id = %s", $table_prefix, $record_id);
        return $wpdb->get_var($sql);
    }

    function save_review($record_id, $values = array()) {
        global $wpdb, $table_prefix;

        if (!is_array($values)) return;
        $sql = sprintf("DELETE FROM %sgdsr_multis_values where id = %s and source = 'rvw'", $table_prefix, $record_id);
        $wpdb->query($sql);
        for ($i = 0; $i < count($values); $i++) {
            $sql = sprintf("INSERT INTO %sgdsr_multis_values (id, source, item_id, user_voters, user_votes) VALUES (%s, 'rvw', %s, 1, '%s')",
                $table_prefix, $record_id, $i, $values[$i]);
            wp_gdsr_dump("INSERT", $sql);
            $wpdb->query($sql);
        }
    }

    function check_vote($id, $user, $set, $type, $ip, $mod_only = false, $mixed = false) {
        $result = true;

        if (!$mod_only)
            $result = GDSRDBMulti::check_vote_logged($id, $user, $set, $type, $ip, $mixed);
        if ($result)
            $result = GDSRDBMulti::check_vote_moderated($id, $user, $set, $type, $ip, $mixed);

        return $result;
    }

    function check_vote_logged($id, $user, $set, $type, $ip, $mixed = false) {
        return GDSRDBMulti::check_vote_table('gdsr_votes_log', $id, $user, $set, $type, $ip, $mixed);
    }

    function check_vote_moderated($id, $user, $set, $type, $ip, $mixed = false) {
        return GDSRDBMulti::check_vote_table('gdsr_moderate', $id, $user, $set, $type, $ip, $mixed);
    }

    function check_vote_table($table, $id, $user, $set, $type, $ip, $mixed = false) {
        global $wpdb, $table_prefix;

        if ($user > 0) {
            $votes_sql = sprintf("SELECT count(*) FROM %s WHERE vote_type = '%s' and multi_id = %s and id = %s and user_id = %s", $table_prefix.$table, $type, $set, $id, $user);
            $votes = $wpdb->get_var($votes_sql);
            return $votes == 0;
        }
        else {
            $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and multi_id = %s and id = %s and ip = '%s'", $table_prefix.$table, $type, $set, $id, $ip);
            $votes = $wpdb->get_var($votes_sql);
            if ($votes > 0 && $mixed) {
                $votes_sql = sprintf("SELECT * FROM %s WHERE vote_type = '%s' and user_id > 0 and multi_id = %s and id = %s and ip = '%s'", $table_prefix.$table, $type, $set, $id, $ip);
                $votes_mixed = $wpdb->get_var($votes_sql);
                if ($votes_mixed > 0) $votes = 0;
            }
            return $votes == 0;
        }
    }

    function get_usage_count_posts($set_id) {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("select count(*) from %sgdsr_multis_data where (average_rating_visitors > 0 or average_rating_users > 0) and multi_id = %s", $table_prefix, $set_id));
    }

    function get_usage_count_post_reviews($set_id) {
        global $wpdb, $table_prefix;
        return $wpdb->get_var(sprintf("select count(*) from %sgdsr_multis_data where average_review > 0 and multi_id = %s", $table_prefix, $set_id));
    }

    function get_usage_count_voters($set_id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("select count(*) from %sgdsr_votes_log where multi_id = %s and vote_type = 'multis'",
            $table_prefix, $set_id);
        return $wpdb->get_var($sql);
    }

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
        $sql = sprintf("select * from %sgdsr_multis order by multi_id desc limit %s, %s", $table_prefix, $start, $limit);
        return $wpdb->get_results($sql);
    }

    function get_multi_set($id = 0) {
        global $wpdb, $table_prefix;
        $where = $id > 0 ? " where multi_id = ".$id : "";
        $sql = sprintf("select * from %sgdsr_multis%s limit 0, 1", $table_prefix, $where);
        return $wpdb->get_row($sql);
    }

    function add_multi_set($set) {
        global $wpdb, $table_prefix;
        $sql = sprintf("insert into %sgdsr_multis (`name`, `description`, `stars`, `object`, `weight`, `auto_insert`, `auto_location`, `auto_categories`) values ('%s', '%s', %s, '%s', '%s', '%s', '%s', '%s')",
                $table_prefix, $set->name, $set->description, $set->stars, serialize($set->object), serialize($set->weight), $set->auto_insert, $set->auto_location, $set->auto_categories
            );
        $wpdb->query($sql);
        return $wpdb->insert_id;
    }

    function edit_multi_set($set) {
        global $wpdb, $table_prefix;

        $sql = sprintf("update %sgdsr_multis set `name` = '%s', `description` = '%s', `object` = '%s', `weight` = '%s', `auto_insert` = '%s', `auto_location` = '%s', `auto_categories` = '%s' where multi_id = %s",
                $table_prefix, $set->name, $set->description, serialize($set->object), serialize($set->weight), $set->auto_insert, $set->auto_location, $set->auto_categories, $set->multi_id
            );
        $wpdb->query($sql);
    }
}

?>