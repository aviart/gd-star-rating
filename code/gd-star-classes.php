<?php

/**
 * Class with agregated review rsults for a single post with multi rating data.
 */
class GDSRArticleMultiReview {
    var $post_id;
    var $values;
    var $rating;
    var $set;
    var $rendered;

    function GDSRArticleMultiReview($post_id) {
        $this->post_id = $post_id;
    }
}

/**
 * Class with agregated multi rating results for a single post.
 */
class GDSRArticleMultiRating {
    var $post_id;
    var $set;
    
    function GDSRArticleMultiRating($post_data) {
        
    }
}

/**
 * Class with agregated results for a single post.
 */
class GDSRArticleRating {
    var $post_id;
    var $review;
    var $user_votes;
    var $visitor_votes;
    var $votes;
    var $user_rating = 0;
    var $visitor_rating = 0;
    var $rating = 0;
    var $views;

    /**
     * Class constructor.
     *
     * @param object $post_data input data
     */
    function GDSRArticleRating($post_data) {
        $this->post_id = $post_data->post_id;
        $this->review = $post_data->review;
        $this->views = $post_data->views;
        $this->user_votes = $post_data->user_voters;
        $this->visitor_votes = $post_data->visitor_voters;
        $this->votes = $this->user_votes + $this->visitor_votes;
        if ($post_data->user_voters > 0) $this->user_rating = number_format($post_data->user_votes / $post_data->user_voters, 1);
        if ($post_data->visitor_voters > 0) $this->visitor_rating = number_format($post_data->visitor_votes / $post_data->visitor_voters, 1);
        if ($this->votes > 0) $this->rating = number_format(($post_data->visitor_votes + $post_data->user_votes) / ($post_data->visitor_voters + $post_data->user_voters), 1);
    }
}

/**
 * Class with agregated results for a single comment.
 */
class GDSRCommentRating {
    var $comment_id;
    var $post_id;
    var $review;
    var $user_votes;
    var $visitor_votes;
    var $votes;
    var $user_rating;
    var $visitor_rating;
    var $rating;

    /**
     * Class constructor.
     *
     * @param object $comment_data input data
     */
    function GDSRCommentRating($comment_data) {
        $this->comment_id = $comment_data->comment_id;
        $this->post_id = $comment_data->post_id;
        $this->review = $comment_data->review;
        $this->user_votes = $comment_data->user_voters;
        $this->visitor_votes = $comment_data->visitor_voters;
        $this->votes = $this->user_votes + $this->visitor_votes;
        if ($comment_data->user_voters > 0) $this->user_rating = number_format($comment_data->user_votes / $comment_data->user_voters, 1);
        if ($comment_data->visitor_voters > 0) $this->visitor_rating = number_format($comment_data->visitor_votes / $comment_data->visitor_voters, 1);
        if ($this->votes > 0) $this->rating = number_format(($comment_data->visitor_votes + $comment_data->user_votes) / ($comment_data->visitor_voters + $comment_data->user_voters), 1);
    }
}

?>
