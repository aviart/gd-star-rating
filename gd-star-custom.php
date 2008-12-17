<?php

    /**
    * Writes a object dump into the log file
    *
    * @param string $msg log entry message
    * @param mixed $object object to dump
    * @param string $block adds start or end dump limiters { none | start | end }
    * @param string $mode file open mode
    */
    function wp_gdsr_dump($msg, $obj, $block = "none", $mode = "a+") {
        if (STARRATING_DEBUG_ACTIVE == 1) {
            global $gd_debug;
            $gd_debug->dump($msg, $obj, $block, $mode);
        }
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

    /**
     * Returns calculated data for average blog rating including bayesian estimate mean.
     *
     * @global class $gdsr
     * @param string $select articles to select postpage|post|page
     * @param string $show votes to use: total|users|visitors
     * @return object with average blog rating values
     */
    function wp_gdsr_blog_rating($select = "postpage", $show = "total") {
        global $gdsr;
        return $gdsr->get_blog_rating($select, $show);
    }

    /**
     * Renders blog rating widget element based on the $widget settings array
     *
     * @global GDStarRating $gdsr main rating class instance
     * @param array $widget settings to use for rendering
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_blog_rating_widget($widget = array(), $echo = true) {
        global $gdsr;

        if ($echo) echo $gdsr->render_top_widget($widget);
        else return $gdsr->render_top_widget($widget);
    }

    /**
     * Renders widget-like element based on the $widget settings array
     *
     * @global GDStarRating $gdsr main rating class instance
     * @param array $widget settings to use for rendering
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_widget($widget = array(), $echo = true) {
        global $gdsr;

        if ($echo) echo $gdsr->render_articles_widget($widget);
        else return $gdsr->render_articles_widget($widget);
    }

    /**
     * Renders the rating stars. This function call must be withing the post loop.
     *
     * @global object $post post data
     * @global object $userdata user data
     * @global GDStarRating $gdsr main rating class instance
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_article($echo = true) {
        global $post, $userdata, $gdsr;
        
        if ($echo) echo $gdsr->render_article($post, $userdata);
        else return $gdsr->render_article($post, $userdata);
    }

    /**
     * Renders the rating stars. This function call must be withing the post loop.
     *
     * @global object $post post data
     * @global object $userdata user data
     * @global GDStarRating $gdsr main rating class instance
     * @param array $override paramters for overriding defulat rating block behavior
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_article_custom($override = array(), $echo = true) {
        global $post, $userdata, $gdsr;

        if ($echo) echo $gdsr->render_article($post, $userdata, $override);
        else return $gdsr->render_article($post, $userdata, $override);
    }

    /**
     * Renders only rating text part of the rating block
     *
     * @global object $post post data
     * @global GDStarRating $gdsr main rating class instance
     * @param string $cls CSS class to ad to rendered block
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_rating_text_article($cls = "", $echo = true) {
        global $post, $gdsr;
        if ($echo) echo $gdsr->render_rating_text_article($post, $cls);
        else return $gdsr->render_rating_text_article($post, $cls);
    }

    /**
     * Manual render of comment rating
     *
     * @global object $comment comment data
     * @global object $post post data
     * @global object $userdata user data
     * @global GDStarRating $gdsr main rating class instance
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_comment($echo = true) {
        global $comment, $userdata, $gdsr, $post;
        if ($echo) echo $gdsr->render_comment($post, $comment, $userdata);
        else return $gdsr->render_comment($post, $comment, $userdata);
    }
    
    /**
     *
     *
     * @global GDStarRating $gdsr main rating class instance
     * @param bool $echo echo results or return it as a string
     * @return string html with rendered contents
     */
    function wp_gdsr_render_review($echo = true) {
        global $gdsr;
        if ($echo) echo $gdsr->shortcode_starreview();
        else return $gdsr->shortcode_starreview();
    }
    
    function wp_gdsr_new_comment_review($value = 0, $echo = true) {
        global $gdsr;
        if ($echo) echo $gdsr->comment_review($value);
        else return $gdsr->comment_review($value);
    }

    function wp_gdsr_show_comment_review($comment_id = 0, $zero_render = true, $use_default = true, $size = 20, $style = "oxygen", $echo = true) {
        global $comment, $gdsr;
        if ($comment_id < 1) $comment_id = $comment->comment_ID;
        if ($echo) echo $gdsr->display_comment_review($comment_id, $zero_render, $use_default, $style, $size);
        else return $gdsr->display_comment_review($comment, $zero_render, $use_default, $style, $size);
    }
    
    function wp_gdsr_show_article_review($post_id = 0, $zero_render = true, $use_default = true, $size = 20, $style = "oxygen", $echo = true) {
        global $post, $gdsr;
        if ($post_id < 1) $post_id = $post->ID;
        if ($echo) echo $gdsr->display_article_review($post_id, $zero_render, $use_default, $style, $size);
        else return $gdsr->display_article_review($post_id, $zero_render, $use_default, $style, $size);
    }

    function wp_gdsr_show_article_rating($post_id = 0, $zero_render = true, $use_default = true, $size = 20, $style = "oxygen", $echo = true) {
        global $post, $gdsr;
        if ($post_id < 1) $post_id = $post->ID;
        if ($echo) echo $gdsr->display_article_rating($post_id, $zero_render, $use_default, $style, $size);
        else return $gdsr->display_article_rating($post_id, $zero_render, $use_default, $style, $size);
    }

?>