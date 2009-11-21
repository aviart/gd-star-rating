<?php

/**
 * Returns calculated data for average blog rating including bayesian estimate mean.
 *
 * @global class $gdsr
 * @param string $select articles to select postpage|post|page
 * @param string $show votes to use: total|users|visitors
 * @return object with average blog rating values
 */
function wp_gdsr_blog_rating($select = "postpage", $show = "total") {
    $widget = array("select" => $select, "show" => $show);
    return GDSRRenderT2::prepare_wbr($widget);
}

/**
 * Returns object with all needed multi rating properties for post or page.
 *
 * @global object $post post data
 * @global GDStarRating $gdsr main rating class instance
 * @param int $set_id id of the multi rating set
 * @param int $post_id post to get rating for, leave 0 to get post from loop
 * @return object rating post properties
 */
function wp_gdsr_rating_multi($multi_set_id = 0, $post_id = 0) {
    if ($post_id == 0) {
        global $post;
        $post_id = $post->ID;
    }

    $multi_set_id = $multi_set_id == 0 ? wp_gdsr_get_multi_set($post_id) : $multi_set_id;
    $multis_data = GDSRDBMulti::get_multi_rating_data($set_id, $post_id);
    if (count($multis_data) == 0) return null;
    return new GDSRArticleMultiRating($multis_data, $set_id);
}

/**
 * Returns object with all needed rating properties for post or page.
 *
 * @global object $post post data
 * @global GDStarRating $gdsr main rating class instance
 * @param int $post_id post to get rating for, leave 0 to get post from loop
 * @return object rating post properties
 */
function wp_gdsr_rating_article($post_id = 0) {
    if ($post_id < 1) {
        global $post;
        $post_id = $post->ID;
    }

    $post_data = GDSRDatabase::get_post_data($post_id);
    if (count($post_data) == 0) return null;
    return new GDSRArticleRating($post_data);
}

/**
 * Returns object with all needed rating properties for comment.
 *
 * @global object $comment comment data
 * @global GDStarRating $gdsr main rating class instance
 * @param int $post_id post to get rating for, leave 0 to get post from loop
 * @return object rating post properties
 */
function wp_gdsr_rating_comment($comment_id = 0) {
    if ($comment_id < 1) {
        global $comment;
        $comment_id = $comment->comment_ID;
    }

    $comment_data = GDSRDatabase::get_comment_data($comment_id);
    if (count($comment_data) == 0) return null;
    return new GDSRCommentRating($comment_data);
}

?>
