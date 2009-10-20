<?php

class gdsrAdmDBMulti {
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
}

class gdsrAdmDB {
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
            $sql = sprintf("SELECT p.id as pid, p.post_title, p.post_type, d.*%s FROM %sposts p left join %sgdsr_data_article d on p.id = d.post_id WHERE p.post_status = 'publish'%s%s%s limit %s, %s",
                $extras, $table_prefix, $table_prefix, $where, $additional, $order, $start, $limit
            );
        return $sql;
    }

    // ip
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
        if (!gdsrAdmDB::ban_ip_check($ip, $mode))
            $wpdb->query(sprintf("INSERT INTO %sgdsr_ips (`status`, `mode`, `ip`) VALUES ('B', '%s', '%s')", $table_prefix, $mode, $ip));
    }

    function ban_ip_range($ip_from, $ip_to) {
        global $wpdb, $table_prefix;
        $ip = $ip_from."|".$ip_to;
        gdsrAdmDB::ban_ip($ip, 'R');
    }

    function unban_ips($ips) {
        global $wpdb, $table_prefix;
        $sql = sprintf("delete from %sgdsr_ips where id in %s", $table_prefix, $ips);
        $wpdb->query($sql);
    }
    // ip

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

    // moderation
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

    function get_moderation_count_joined($post_id, $vote_type = 'article', $user = 'all') {
        global $wpdb, $table_prefix;

        if ($user == "all")
            $users = '';
        else if ($user == "users")
            $users = ' and m.user_id > 0';
        else if ($user == "visitors")
            $users = ' and m.user_id = 0';
        else
            $users = ' and m.user_id = '.$user;

        $sql = sprintf("select count(*) from %s c inner join %s m on m.id = c.comment_ID where c.comment_post_ID = %s and m.vote_type = '%s'%s",
            $wpdb->comments, $table_prefix."gdsr_moderate", $post_id, $vote_type, $users);
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
    // moderation

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

    // insert templates
    function install_all_templates() {
        gdsrAdmDB::insert_default_templates(STARRATING_PATH);
        gdsrAdmDB::insert_extras_templates(STARRATING_PATH);
        gdsrAdmDB::insert_extras_templates(STARRATING_XTRA_PATH, false);
        gdsrAdmDB::update_default_templates(STARRATING_PATH);
        gdsrAdmDB::update_extras_templates(STARRATING_PATH);
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

    // conversion
    function convert_multi_row($row) {

    }

    function convert_row($row, $multis = array()) {
        $mur = $row->cmm_integration_mur;
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
        switch ($row->recc_moderate_articles) {
            case 'I':
                $row->recc_moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'A':
                $row->recc_moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->recc_moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->recc_moderate_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
            default:
                $row->recc_moderate_articles = __("articles", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->recc_moderate_comments) {
            case 'I':
                $row->recc_moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'A':
                $row->recc_moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("all", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->recc_moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("visitors", "gd-star-rating").'</span></strong>';
                break;
            case 'U':
                $row->recc_moderate_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("users", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
            default:
                $row->recc_moderate_comments = __("comments", "gd-star-rating").': <strong>'.__("free", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->recc_rules_articles) {
            case 'I':
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'H':
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->recc_rules_articles = __("articles", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->recc_rules_comments) {
            case 'I':
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: blue">'.__("inherited", "gd-star-rating").'</span></strong>';
                break;
            case 'H':
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("hidden", "gd-star-rating").'</span></strong>';
                break;
            case 'N':
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong><span style="color: red">'.__("locked", "gd-star-rating").'</span></strong>';
                break;
            case 'V':
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong>'.__("visitors", "gd-star-rating").'</strong>';
                break;
            case 'U':
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong>'.__("users", "gd-star-rating").'</strong>';
                break;
            default:
                $row->recc_rules_comments = __("comments", "gd-star-rating").': <strong>'.__("everyone", "gd-star-rating").'</strong>';
                break;
        }
        switch ($row->cmm_integration_std) {
            default:
            case 'I':
                $row->cmm_integration_std = '<span style="color: blue">'.__("inherit from category", "gd-star-rating").'</span>';
                break;
            case 'N':
                $row->cmm_integration_std = '<span style="color: red">'.__("force hidden", "gd-star-rating").'</span>';
                break;
            case 'A':
                $row->cmm_integration_mur = ''.__("normal activity", "gd-star-rating");
                break;
        }
        switch ($row->cmm_integration_mur) {
            default:
            case 'I':
                $row->cmm_integration_mur = '<span style="color: blue">'.__("inherit from category", "gd-star-rating").'</span>';
                break;
            case 'N':
                $row->cmm_integration_mur = '<span style="color: red">'.__("force hidden", "gd-star-rating").'</span>';
                break;
            case 'A':
                $row->cmm_integration_mur = ''.__("normal activity", "gd-star-rating");
                break;
        }

        $votes_v = $votes_u = $votes_t = '/';
        $count_v = $count_u = $count_t = '[ 0 ] ';

        if ($row->visitor_voters > 0) {
            $visitor_rating = @number_format($row->visitor_votes / $row->visitor_voters, 1);
            $row->rating_visitors = $visitor_rating;
            $votes_v = '<strong><span style="color: red">'.$visitor_rating.'</span></strong>';
            $count_v = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=article&amp;vg=visitors"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->visitor_voters);
        }

        if ($row->user_voters > 0) {
            $user_rating = @number_format($row->user_votes / $row->user_voters, 1);
            $row->rating_users = $user_rating;
            $votes_u = '<strong><span style="color: red">'.$user_rating.'</span></strong>';
            $count_u = sprintf('[ <a href="./admin.php?page=gd-star-rating-stats&amp;gdsr=voters&amp;pid=%s&amp;vt=article&amp;vg=users"> <strong style="color: red;">%s</strong> </a> ] ', $row->pid, $row->user_voters);
        }

        if ($row->review == -1 || $row->review == '') $row->review = "/";
        $row->review = '[ '.($row->review == "/" ? "-" : "+").' ] '.__("review", "gd-star-rating").': <strong><span style="color: blue">'.$row->review.'</span></strong>';

        $total_votes = $row->visitor_votes + $row->user_votes;
        $total_voters = $row->visitor_voters + $row->user_voters;

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

        $row->total = $count_t.__("rating", "gd-star-rating").': <strong>'.$votes_t.'</strong><br />';
        $row->total.= $row->review.'<br /><div class="gdsr-art-split"></div>';
        $row->total.= $vts_thumb_t.__("rating", "gd-star-rating").': <strong>'.$cnt_thumb_t.'</strong>';
        $row->votes = $count_v.__("visitors", "gd-star-rating").': <strong>'.$votes_v.'</strong><br />'.$count_u.__("users", "gd-star-rating").': <strong>'.$votes_u.'</strong>';
        $row->thumbs = $vts_thumb_v.__("visitors", "gd-star-rating").': <strong>'.$cnt_thumb_v.'</strong><br />'.$vts_thumb_u.__("users", "gd-star-rating").': <strong>'.$cnt_thumb_u.'</strong>';

        if ($mur == "I" || $mu == "") $row->cmm_integration_mur.= "<br /><br />";
        else $row->cmm_integration_mur.= "<br/>".$row->cmm_integration_set == 0 ? __("no multi set assigned") : __("multi set", "gd-star-rating").': <strong>'.$multis["cmm_integration_set"].'</strong>';

        $row->cmm_integration_std.= "<br /><br />";
        $row->title = sprintf('<a href="%s">%s</a>', get_edit_post_link($row->pid), $row->post_title);
        $row->views = intval($row->views);

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

    function get_post_title($post_id) {
        global $wpdb;
        return $wpdb->get_var("select post_title from $wpdb->posts where ID = ".$post_id);
    }

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

        if (count($types) == 0) return array();

        $sql = sprintf("select %s from %s where vote_type in (%s)%s order by voted desc limit 0, %s",
            $select, $from, join(", ", $types), $where, $o["integrate_dashboard_latest_count"]);
        return $wpdb->get_results($sql);
    }

    // moderation
    function moderation_approve($ids, $ids_array) {
        global $wpdb, $table_prefix;

        $sql = sprintf("select * from %s where record_id in %s", $table_prefix."gdsr_moderate", $ids);
        $rows = $wpdb->get_results($sql);
        foreach ($rows as $row) {
            if ($row->vote_type == "article")
                gdsrBlgDB::add_vote($row->id, $row->user_id, $row->ip, $row->user_agent, $row->vote);
            if ($row->vote_type == "comment")
                gdsrBlgDB::add_vote_comment($row->id, $row->user_id, $row->ip, $row->user_agent, $row->vote);
        }

        gdsrAdmDB::moderation_delete($ids);
    }

    function moderation_delete($ids) {
        global $wpdb, $table_prefix;

        $sql = sprintf("delete from %s where record_id in %s", $table_prefix."gdsr_moderate", $ids);
        $wpdb->query($sql);
    }
    // moderation

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

class gdsrTlsDB {
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

?>