<?php

class gdsrBlgDB {
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
}

?>