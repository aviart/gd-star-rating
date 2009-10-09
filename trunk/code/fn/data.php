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
    global $post, $gdsr;
    if ($post_id == 0) $post_id = $post->ID;

    $multi_set_id = $multi_set_id == 0 ? wp_gdsr_get_multi_set($post_id) : $multi_set_id;
    return $gdsr->get_ratings_multi($multi_set_id, $post_id);
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
    global $post, $gdsr;
    if ($post_id < 1) $post_id = $post->ID;
    return $gdsr->get_ratings_post($post_id);
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
    global $comment, $gdsr;
    if ($comment_id < 1) $comment_id = $comment->comment_ID;
    return $gdsr->get_ratings_comment($comment_id);
}

?>
